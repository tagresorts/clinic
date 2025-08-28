# Log Viewer & Maintenance System

## Overview

Your Laravel application now includes a comprehensive log viewing and maintenance system with a modern web interface and powerful command-line tools.

## Features

### 🖥️ **Web Interface (Frontend)**
- **Multi-file Support**: View any `.log` file in the `storage/logs` directory
- **Real-time Filtering**: Filter by log level, search text, and line count
- **Statistics Dashboard**: File size, line count, modification time, and log level distribution
- **Color-coded Logs**: Different colors for different log levels (Error, Warning, Info, Debug)
- **Maintenance Actions**: Download, clear, or delete log files directly from the interface
- **Auto-refresh**: Automatically refreshes logs every 30 seconds (when no filters applied)

### 🛠️ **Command Line Tools**
- **Log Cleanup**: Remove old logs and optionally compress them
- **Log Monitoring**: Track file sizes and disk usage with alerts
- **Automated Maintenance**: Schedule regular cleanup tasks

## Access

### Web Interface
- **URL**: `/logs`
- **Access**: Administrator role required
- **Route**: `logs.index`

### Command Line
```bash
# Navigate to project directory
cd /path/to/dental-clinic-pms

# List available commands
php artisan list logs
```

## Usage

### 1. Web Interface

#### **Log File Selection**
- Choose from available log files in the dropdown
- Shows file size and last modification time
- Automatically refreshes when switching files

#### **Filtering Options**
- **Log Level**: Filter by specific log levels (Emergency, Alert, Critical, Error, Warning, Notice, Info, Debug)
- **Search**: Search within log messages (case-insensitive)
- **Max Lines**: Limit the number of log entries displayed (100, 500, 1000, 5000)
- **Apply Filters**: Click the "Apply Filters" button to update results

#### **Statistics Panel**
- **File Size**: Current size of the selected log file
- **Total Lines**: Number of lines in the log file
- **Last Modified**: When the file was last updated
- **Filtered Results**: Number of entries matching current filters
- **Log Level Distribution**: Breakdown of log entries by level

#### **Maintenance Actions**
- **Download**: Download the current log file
- **Clear**: Empty the log file (keeps the file but removes all content)
- **Delete**: Permanently remove the log file

### 2. Command Line Tools

#### **Log Cleanup Command**
```bash
# Basic cleanup (keep logs for 14 days)
php artisan logs:cleanup

# Custom retention period
php artisan logs:cleanup --days=7

# Compress old logs instead of deleting
php artisan logs:cleanup --compress

# Force cleanup without confirmation
php artisan logs:cleanup --force

# Dry run (show what would be cleaned)
php artisan logs:cleanup --dry-run

# Combine options
php artisan logs:cleanup --days=7 --compress --force
```

#### **Log Monitoring Command**
```bash
# Basic monitoring
php artisan logs:monitor

# Custom alert thresholds (in MB)
php artisan logs:monitor --alert-size=50 --alert-total=200

# Send email alerts
php artisan logs:monitor --email=admin@example.com

# Full monitoring with alerts
php artisan logs:monitor --alert-size=100 --alert-total=500 --email=admin@example.com
```

## Configuration

### Log Rotation
To enable automatic log rotation, update your `.env` file:

```env
LOG_CHANNEL=daily
LOG_DAILY_DAYS=14
LOG_STACK=daily
```

### Log Level
Control the verbosity of logging:

```env
LOG_LEVEL=debug
```

Available levels: `emergency`, `alert`, `critical`, `error`, `warning`, `notice`, `info`, `debug`

### Custom Log Channels
The system includes a custom `log_viewer` channel for admin-specific logging:

```php
Log::channel('log_viewer')->info("User action performed");
```

## Scheduling

### Automated Cleanup
Add to your `app/Console/Kernel.php`:

```php
protected function schedule(Schedule $schedule)
{
    // Daily log cleanup at 2 AM
    $schedule->command('logs:cleanup --days=7 --compress')
             ->daily()
             ->at('02:00');
    
    // Monitor logs every 6 hours
    $schedule->command('logs:monitor --alert-size=100 --alert-total=500')
             ->everyFourHours();
}
```

### Cron Job Setup
Ensure your server has the cron job running:

```bash
* * * * * cd /path/to/dental-clinic-pms && php artisan schedule:run >> /dev/null 2>&1
```

## Security

### Access Control
- **Web Interface**: Protected by `role:administrator` middleware
- **Command Line**: Requires server access and proper permissions
- **File Access**: Only reads from `storage/logs` directory

### Data Protection
- **No PHI Logging**: Patient data is not logged to prevent privacy violations
- **Audit Trail**: User actions are logged for security purposes
- **File Permissions**: Log files should have restricted read/write permissions

## Troubleshooting

### Common Issues

#### **"No logs found" Message**
- Check if log files exist in `storage/logs/`
- Verify file permissions
- Ensure log files have `.log` extension

#### **Large Log Files**
- Run cleanup: `php artisan logs:cleanup --days=7`
- Enable compression: `php artisan logs:cleanup --compress`
- Check for infinite loops or excessive logging

#### **Permission Errors**
- Ensure web server has read access to `storage/logs/`
- Check file ownership and permissions
- Verify storage directory is writable

#### **Memory Issues**
- Reduce max lines in web interface
- Use command line tools for large files
- Consider log rotation configuration

### Performance Tips

1. **Regular Cleanup**: Schedule daily cleanup to prevent log accumulation
2. **Compression**: Use compression for old logs to save disk space
3. **Monitoring**: Set up monitoring to catch issues early
4. **Rotation**: Configure proper log rotation in your environment

## File Structure

```
storage/logs/
├── laravel.log          # Main application logs
├── log_viewer.log       # Admin-specific logs
├── laravel-2024-01-01.log  # Daily rotated logs (if enabled)
└── *.log.gz            # Compressed log files
```

## API Endpoints

| Method | Endpoint | Description | Access |
|--------|----------|-------------|---------|
| GET | `/logs` | View logs interface | Admin |
| GET | `/logs/download/{filename}` | Download log file | Admin |
| POST | `/logs/clear/{filename}` | Clear log file | Admin |
| DELETE | `/logs/delete/{filename}` | Delete log file | Admin |

## Support

For issues or questions:
1. Check the troubleshooting section above
2. Review Laravel logging documentation
3. Check server error logs
4. Verify file permissions and disk space

## Future Enhancements

- [ ] Real-time log streaming
- [ ] Advanced search with regex support
- [ ] Log export in multiple formats (JSON, CSV)
- [ ] Email notifications for specific log patterns
- [ ] Integration with external log aggregation services
- [ ] Custom log parsing rules
- [ ] Performance metrics and analytics