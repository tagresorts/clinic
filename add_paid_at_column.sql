-- Add paid_at column to invoices table
-- Run this SQL query in your database to fix the revenue module

ALTER TABLE invoices ADD COLUMN paid_at TIMESTAMP NULL AFTER status;

-- Update existing paid invoices to have a paid_at timestamp
-- This assumes invoices with status = 'paid' should have paid_at set to their updated_at time
UPDATE invoices 
SET paid_at = updated_at 
WHERE status = 'paid' AND paid_at IS NULL;