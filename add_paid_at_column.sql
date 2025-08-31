-- Add paid_at column to invoices table
ALTER TABLE invoices ADD COLUMN paid_at TIMESTAMP NULL AFTER status;

-- Update existing paid invoices to have paid_at set to created_at
UPDATE invoices SET paid_at = created_at WHERE status = 'paid';