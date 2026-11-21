<?php

namespace Tests\Feature;

use App\Models\Appointment;
use App\Models\InventoryItem;
use App\Models\Invoice;
use App\Models\Patient;
use App\Models\Payment;
use App\Models\StockMovement;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class CriticalPathTest extends TestCase
{
    use RefreshDatabase;

    private function makeRole(string $name): void
    {
        Role::findOrCreate($name, 'web');
    }

    private function userWithRole(string $role): User
    {
        $this->makeRole($role);
        $user = User::factory()->create();
        $user->assignRole($role);

        return $user;
    }

    // ---------------------------------------------------------------------
    // Appointment authorization (AppointmentPolicy)
    // ---------------------------------------------------------------------

    public function test_dentist_cannot_view_another_dentists_appointment(): void
    {
        $dentistA = $this->userWithRole('dentist');
        $dentistB = $this->userWithRole('dentist');
        $patient = Patient::factory()->create();

        $appointment = Appointment::create([
            'patient_id' => $patient->id,
            'dentist_id' => $dentistB->id,
            'appointment_datetime' => now()->addDay(),
            'duration_minutes' => 30,
            'appointment_type' => 'Cleaning',
            'status' => Appointment::STATUS_SCHEDULED,
        ]);

        $this->actingAs($dentistA)
            ->get(route('appointments.show', $appointment))
            ->assertForbidden();
    }

    public function test_assigned_dentist_can_view_their_own_appointment(): void
    {
        $dentist = $this->userWithRole('dentist');
        $patient = Patient::factory()->create();

        $appointment = Appointment::create([
            'patient_id' => $patient->id,
            'dentist_id' => $dentist->id,
            'appointment_datetime' => now()->addDay(),
            'duration_minutes' => 30,
            'appointment_type' => 'Cleaning',
            'status' => Appointment::STATUS_SCHEDULED,
        ]);

        $this->actingAs($dentist)
            ->get(route('appointments.show', $appointment))
            ->assertOk();
    }

    public function test_receptionist_cannot_delete_appointment(): void
    {
        $receptionist = $this->userWithRole('receptionist');
        $dentist = $this->userWithRole('dentist');
        $patient = Patient::factory()->create();

        $appointment = Appointment::create([
            'patient_id' => $patient->id,
            'dentist_id' => $dentist->id,
            'appointment_datetime' => now()->addDay(),
            'duration_minutes' => 30,
            'appointment_type' => 'Cleaning',
            'status' => Appointment::STATUS_SCHEDULED,
        ]);

        $this->actingAs($receptionist)
            ->delete(route('appointments.destroy', $appointment))
            ->assertForbidden();

        $this->assertDatabaseHas('appointments', ['id' => $appointment->id]);
    }

    public function test_malformed_appointment_time_is_rejected(): void
    {
        $receptionist = $this->userWithRole('receptionist');
        $dentist = $this->userWithRole('dentist');
        $patient = Patient::factory()->create();

        $response = $this->actingAs($receptionist)->post(route('appointments.store'), [
            'patient_id' => $patient->id,
            'dentist_id' => $dentist->id,
            'appointment_date' => now()->addDay()->toDateString(),
            'appointment_time' => 'not-a-time',
            'duration_minutes' => 30,
            'appointment_type' => 'Cleaning',
        ]);

        $response->assertSessionHasErrors('appointment_time');
        $this->assertDatabaseCount('appointments', 0);
    }

    public function test_conflicting_appointment_is_not_created(): void
    {
        $receptionist = $this->userWithRole('receptionist');
        $dentist = $this->userWithRole('dentist');
        $patient = Patient::factory()->create();

        $slot = now()->addDays(2)->setTime(10, 0);

        Appointment::create([
            'patient_id' => $patient->id,
            'dentist_id' => $dentist->id,
            'appointment_datetime' => $slot,
            'duration_minutes' => 60,
            'appointment_type' => 'Filling',
            'status' => Appointment::STATUS_SCHEDULED,
        ]);

        $response = $this->actingAs($receptionist)->post(route('appointments.store'), [
            'patient_id' => $patient->id,
            'dentist_id' => $dentist->id,
            'appointment_date' => $slot->toDateString(),
            'appointment_time' => $slot->format('H:i'),
            'duration_minutes' => 30,
            'appointment_type' => 'Cleaning',
        ]);

        $response->assertSessionHas('error');
        $this->assertDatabaseCount('appointments', 1);
    }

    // ---------------------------------------------------------------------
    // Payments: invoice status updates
    // ---------------------------------------------------------------------

    private function makeInvoice(float $total): Invoice
    {
        $user = User::factory()->create();
        $patient = Patient::factory()->create();

        return Invoice::create([
            'patient_id' => $patient->id,
            'total_amount' => $total,
            'outstanding_balance' => $total,
            'status' => 'pending',
            'payment_status' => 'unpaid',
            'due_date' => now()->addDays(30),
            'user_id' => $user->id,
        ]);
    }

    public function test_full_payment_marks_invoice_as_paid(): void
    {
        $receptionist = $this->userWithRole('receptionist');
        $invoice = $this->makeInvoice(200.00);

        $response = $this->actingAs($receptionist)->post(route('payments.store'), [
            'invoice_id' => $invoice->id,
            'patient_id' => $invoice->patient_id,
            'amount' => 200.00,
            'payment_method' => 'cash',
            'payment_date' => now()->toDateString(),
        ]);

        $response->assertRedirect(route('payments.index'));

        $invoice->refresh();
        $this->assertSame('paid', $invoice->payment_status);
        $this->assertEquals(0, (float) $invoice->outstanding_balance);
        $this->assertNotNull($invoice->paid_at);
        $this->assertDatabaseCount('payments', 1);
    }

    public function test_partial_payment_leaves_remaining_balance(): void
    {
        $receptionist = $this->userWithRole('receptionist');
        $invoice = $this->makeInvoice(200.00);

        $this->actingAs($receptionist)->post(route('payments.store'), [
            'invoice_id' => $invoice->id,
            'patient_id' => $invoice->patient_id,
            'amount' => 50.00,
            'payment_method' => 'cash',
            'payment_date' => now()->toDateString(),
        ]);

        $invoice->refresh();
        $this->assertSame('partial', $invoice->payment_status);
        $this->assertEquals(150.00, (float) $invoice->outstanding_balance);
        $this->assertNull($invoice->paid_at);
    }

    // ---------------------------------------------------------------------
    // Inventory: atomic quantity update + stock movement
    // ---------------------------------------------------------------------

    public function test_inventory_update_records_stock_movement(): void
    {
        $admin = $this->userWithRole('administrator');
        $item = InventoryItem::create([
            'item_name' => 'Nitrile Gloves',
            'quantity_in_stock' => 100,
            'reorder_level' => 10,
        ]);

        $this->actingAs($admin)->put(route('inventory.update', $item), [
            'item_name' => 'Nitrile Gloves',
            'quantity_in_stock' => 80,
            'reorder_level' => 10,
        ]);

        $item->refresh();
        $this->assertSame(80, (int) $item->quantity_in_stock);

        $movement = StockMovement::where('inventory_item_id', $item->id)->latest('id')->first();
        $this->assertNotNull($movement, 'Stock movement should be recorded.');
        $this->assertSame('out', $movement->type);
        $this->assertSame(20, (int) $movement->quantity);
    }

    public function test_inventory_update_without_quantity_change_creates_no_movement(): void
    {
        $admin = $this->userWithRole('administrator');
        $item = InventoryItem::create([
            'item_name' => 'Anesthetic Cartridges',
            'quantity_in_stock' => 50,
            'reorder_level' => 5,
        ]);

        $this->actingAs($admin)->put(route('inventory.update', $item), [
            'item_name' => 'Anesthetic Cartridges (renamed)',
            'quantity_in_stock' => 50,
            'reorder_level' => 5,
        ]);

        $this->assertDatabaseCount('stock_movements', 0);
    }
}
