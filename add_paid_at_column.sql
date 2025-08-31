-- Add paid_at column to invoices table
ALTER TABLE invoices ADD COLUMN paid_at TIMESTAMP NULL AFTER status;