<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Patient;
use App\Models\TreatmentPlan;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

use Spatie\Permission\Models\Role;

class TreatmentPlanTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_are_redirected_to_login(): void
    {
        $response = $this->get('/treatment-plans');

        $response->assertRedirect('/login');
    }

    public function test_dentist_can_view_treatment_plans_page(): void
    {
        $user = User::factory()->create();
        Role::create(['name' => 'dentist']);
        $user->assignRole('dentist');

        $response = $this
            ->actingAs($user)
            ->get('/treatment-plans');

        $response->assertOk();
    }

    public function test_administrator_can_view_treatment_plans_page(): void
    {
        $user = User::factory()->create();
        Role::create(['name' => 'administrator']);
        $user->assignRole('administrator');

        $response = $this
            ->actingAs($user)
            ->get('/treatment-plans');

        $response->assertOk();
    }

    public function test_treatment_plan_status_is_displayed_with_correct_color(): void
    {
        $user = User::factory()->create();
        Role::create(['name' => 'dentist']);
        $user->assignRole('dentist');
        $patient = Patient::factory()->create();

        $plan = TreatmentPlan::factory()->create([
            'status' => 'proposed',
            'patient_id' => $patient->id,
            'dentist_id' => $user->id,
        ]);

        $response = $this->actingAs($user)->get('/treatment-plans');

        $response->assertSee('<span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">', false);
    }
}
