-- Fix Revenue Module Database Schema
-- Run these SQL queries in your MySQL database to add missing columns

-- Add missing columns to invoices table
ALTER TABLE invoices ADD COLUMN paid_at TIMESTAMP NULL AFTER status;

-- Add missing columns to payments table (in case they don't exist)
-- These columns are referenced in the Payment model but might be missing from the database
ALTER TABLE payments ADD COLUMN IF NOT EXISTS payment_reference VARCHAR(255) NULL AFTER received_by;
ALTER TABLE payments ADD COLUMN IF NOT EXISTS transaction_id VARCHAR(255) NULL AFTER payment_method;
ALTER TABLE payments ADD COLUMN IF NOT EXISTS check_number VARCHAR(50) NULL AFTER transaction_id;
ALTER TABLE payments ADD COLUMN IF NOT EXISTS bank_reference VARCHAR(255) NULL AFTER check_number;
ALTER TABLE payments ADD COLUMN IF NOT EXISTS card_last_four VARCHAR(4) NULL AFTER bank_reference;
ALTER TABLE payments ADD COLUMN IF NOT EXISTS receipt_generated BOOLEAN DEFAULT FALSE AFTER status;
ALTER TABLE payments ADD COLUMN IF NOT EXISTS receipt_number VARCHAR(255) NULL AFTER receipt_generated;
ALTER TABLE payments ADD COLUMN IF NOT EXISTS refund_of_payment_id BIGINT UNSIGNED NULL AFTER receipt_number;
ALTER TABLE payments ADD COLUMN IF NOT EXISTS refund_amount DECIMAL(10,2) NULL AFTER refund_of_payment_id;
ALTER TABLE payments ADD COLUMN IF NOT EXISTS refund_reason TEXT NULL AFTER refund_amount;

-- Add foreign key constraint for refund_of_payment_id if it doesn't exist
-- This creates a self-referencing foreign key for refunds
ALTER TABLE payments ADD CONSTRAINT IF NOT EXISTS payments_refund_of_payment_id_foreign 
FOREIGN KEY (refund_of_payment_id) REFERENCES payments(id) ON DELETE SET NULL;

-- Update existing paid invoices to have a paid_at timestamp
UPDATE invoices SET paid_at = updated_at WHERE status = 'paid' AND paid_at IS NULL;

-- Set default values for receipt_generated
UPDATE payments SET receipt_generated = FALSE WHERE receipt_generated IS NULL;