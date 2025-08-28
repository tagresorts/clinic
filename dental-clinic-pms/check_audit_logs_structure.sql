-- Check current audit_logs table structure
DESCRIBE audit_logs;

-- Check what columns exist
SHOW COLUMNS FROM audit_logs;

-- Check if specific columns exist
SELECT 
    COLUMN_NAME,
    DATA_TYPE,
    IS_NULLABLE,
    COLUMN_DEFAULT
FROM INFORMATION_SCHEMA.COLUMNS 
WHERE TABLE_SCHEMA = DATABASE() 
AND TABLE_NAME = 'audit_logs'
ORDER BY ORDINAL_POSITION;