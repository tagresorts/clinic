# Invoice and Payment Seeders

This document describes the InvoiceSeeder and PaymentSeeder that have been created to populate the database with sample invoice and payment data for testing purposes.

## Seeders Created

### 1. InvoiceSeeder (`database/seeders/InvoiceSeeder.php`)

**Purpose**: Creates sample invoices with realistic data for testing the invoice management system.

**Features**:
- Creates 20 sample invoices
- Generates realistic invoice items (dental procedures)
- Assigns random patients, users, appointments, and treatment plans
- Sets various invoice statuses (draft, sent, paid)
- Sets various payment statuses (unpaid, partial, paid)
- Calculates outstanding balances based on payment status
- Generates unique invoice numbers
- Sets appropriate dates (due dates, sent dates, paid dates)

**Sample Invoice Items**:
- Dental Cleaning ($150)
- Cavity Filling ($200)
- Root Canal ($800)
- Crown ($1,200)
- X-Ray ($75 each)

**Dependencies**:
- PatientSeeder (must be run first)
- UserSeeder (must be run first)
- AppointmentSeeder (optional)
- TreatmentPlanSeeder (optional)
- ProcedureSeeder (optional)

### 2. PaymentSeeder (`database/seeders/PaymentSeeder.php`)

**Purpose**: Creates sample payments for invoices that have partial or paid status.

**Features**:
- Creates up to 30 sample payments
- Supports multiple payment methods:
  - Cash
  - Credit Card
  - Debit Card
  - Check
  - Bank Transfer
- Generates realistic payment references and receipt numbers
- Updates invoice payment status and outstanding balances automatically
- Creates method-specific data (card numbers, check numbers, bank references)
- Handles multiple payments per invoice for partial payments

**Payment Methods and References**:
- Cash: `CASH-YYYYMMDD-XXX`
- Credit Card: `CC-YYYYMMDD-XXX`
- Debit Card: `DC-YYYYMMDD-XXX`
- Check: `CHK-YYYYMMDD-XXX`
- Bank Transfer: `BT-YYYYMMDD-XXX`

**Dependencies**:
- InvoiceSeeder (must be run first)
- UserSeeder (must be run first)

## Usage

### Option 1: Using Laravel Artisan (Recommended)

```bash
# Run all seeders in the correct order
php artisan db:seed

# Run specific seeders
php artisan db:seed --class=InvoiceSeeder
php artisan db:seed --class=PaymentSeeder
```

### Option 2: Using the Custom Script

If PHP is not available in your environment, you can use the custom script:

```bash
php run_seeders.php
```

### Option 3: Manual Execution

You can also run the seeders manually by including them in your DatabaseSeeder:

```php
// In database/seeders/DatabaseSeeder.php
public function run(): void
{
    $this->call([
        // ... other seeders
        InvoiceSeeder::class,
        PaymentSeeder::class,
    ]);
}
```

## Database Updates

The seeders will automatically:

1. **Create invoices** with realistic data and relationships
2. **Create payments** for invoices with partial/paid status
3. **Update invoice payment status** based on payment amounts
4. **Calculate outstanding balances** for invoices
5. **Generate receipt numbers** for completed payments

## Sample Data Generated

### Invoices
- 20 invoices with various statuses
- Realistic invoice items and amounts
- Proper relationships with patients, appointments, and treatment plans
- Calculated totals and outstanding balances

### Payments
- Up to 30 payments across different methods
- Realistic payment references and transaction IDs
- Method-specific data (card numbers, check numbers, etc.)
- Automatic invoice status updates

## Notes

- The seeders include error handling and will warn if dependencies are missing
- Payment amounts are calculated based on invoice status and outstanding balances
- All dates are set relative to the current date for realistic testing
- The seeders use database transactions to ensure data consistency
- Invoice numbers are automatically generated in the format: `INV-YYYY-XXXXXX`

## Troubleshooting

If you encounter issues:

1. **Missing dependencies**: Ensure PatientSeeder and UserSeeder are run first
2. **Database errors**: Check that all required migrations have been run
3. **Permission issues**: Ensure the database user has proper permissions
4. **Memory issues**: The seeders create a moderate amount of data; increase PHP memory limit if needed

## Customization

You can modify the seeders to:
- Change the number of records created
- Adjust the sample data (procedures, amounts, etc.)
- Add new payment methods
- Modify the date ranges
- Add additional fields or relationships