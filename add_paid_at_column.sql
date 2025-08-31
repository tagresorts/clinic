-- Add paid_at column to invoices table
-- Run this SQL query in your MySQL database

ALTER TABLE invoices ADD COLUMN paid_at TIMESTAMP NULL AFTER status;

-- Update existing paid invoices to have a paid_at timestamp
-- This sets paid_at to the updated_at timestamp for invoices with status = 'paid'
UPDATE invoices 
SET paid_at = updated_at 
WHERE status = 'paid' AND paid_at IS NULL;