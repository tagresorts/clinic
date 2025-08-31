# Dashboard Default Layout System

## Overview

The dashboard now includes an improved default layout system that allows users to save their preferred layout as the default for all users, eliminating the need to repeatedly organize widgets after each reset.

## Features

### 1. Save as Default Layout
- **Button**: "Save as Default" (Green button with checkmark icon)
- **Function**: Saves the current user's layout preferences as the default layout for all users
- **Storage**: Uses `user_id = 0` in the `user_dashboard_preferences` table to store the default layout

### 2. Reset to Saved Default
- **Button**: "Reset to Saved Default" (Blue button with refresh icon)
- **Function**: Resets the current user's layout to the saved default layout
- **Fallback**: If no saved default exists, falls back to config defaults

### 3. Reset to Config Default
- **Button**: "Reset to Config Default" (Red button with trash icon)
- **Function**: Resets to the original config-based default layout
- **Use Case**: When you want to completely start over with the system defaults

## How It Works

### Automatic Default Loading
When a user has no saved preferences, the system automatically:
1. Checks if a saved default layout exists (`user_id = 0`)
2. If found, copies the default layout to the user's preferences
3. If not found, uses the config-based defaults

### Layout Hierarchy
1. **User Preferences** (highest priority) - Individual user's saved layout
2. **Saved Default Layout** (medium priority) - Layout saved as default by admin
3. **Config Defaults** (lowest priority) - Hard-coded defaults in `config/dashboard.php`

## Usage Instructions

### For Administrators
1. **Organize the dashboard** to your preferred layout
2. **Click "Save as Default"** to save this layout as the default for all users
3. **New users** will automatically get this organized layout
4. **Existing users** can use "Reset to Saved Default" to get the organized layout

### For Regular Users
1. **Organize your dashboard** as needed
2. **Use "Save Layout"** to save your personal preferences
3. **Use "Reset to Saved Default"** to get back to the organized default layout
4. **Use "Reset to Config Default"** only if you want the original unorganized layout

## Technical Implementation

### Database Structure
- Uses existing `user_dashboard_preferences` table
- `user_id = 0` represents the default layout
- `user_id = actual_user_id` represents individual user preferences

### Controller Methods
- `saveAsDefaultLayout()` - Saves current layout as default
- `resetToDefaultLayout()` - Resets to saved default
- `resetLayout()` - Resets to config defaults

### Routes
- `POST /dashboard/save-as-default` - Save as default
- `POST /dashboard/reset-to-default` - Reset to saved default
- `POST /dashboard/reset-layout` - Reset to config default

## Benefits

1. **Reduced Setup Time**: New users get an organized layout automatically
2. **Consistent Experience**: All users can easily access the same organized layout
3. **Flexibility**: Users can still customize their personal layout
4. **Easy Recovery**: Quick reset to organized layout when needed
5. **Admin Control**: Administrators can set the standard layout for the organization

## Configuration

The default layout is defined in `config/dashboard.php` and includes:
- Organized widget positioning in a 12-column grid
- Logical grouping of related widgets
- Proper spacing and sizing for optimal viewing
- Role-based widget visibility

## Troubleshooting

### If the default layout isn't loading:
1. Check if a saved default exists: `SELECT * FROM user_dashboard_preferences WHERE user_id = 0`
2. Verify the user has no existing preferences: `SELECT * FROM user_dashboard_preferences WHERE user_id = [user_id]`
3. Check the application logs for any errors

### If widgets are overlapping:
1. Ensure the grid coordinates don't conflict
2. Check that widget widths don't exceed the 12-column limit
3. Verify the layout coordinates in the saved preferences