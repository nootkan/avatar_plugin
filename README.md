# Avatar Plugin for Osclass

**Version:** 2.0.0
**Author:**Van Isle Web Solutions  
**Original Author:** Media.Dmj  
**Updated for:** PHP 8+ Compatibility & Enhanced Security  
**License:** MIT

## Overview

The Avatar Plugin allows users to upload profile pictures during registration and when editing their profiles in Osclass. This updated version includes critical security fixes and full PHP 8+ compatibility.

---

## 🆕 What's New in Version 2.0.0

### Critical Security Improvements
- ✅ **Server-side file validation** - Validates MIME type and verifies files are actual images
- ✅ **CSRF protection** - Nonce verification prevents unauthorized uploads
- ✅ **Secure file permissions** - Directories (755) and files (644) use proper permissions
- ✅ **XSS prevention** - All outputs properly escaped
- ✅ **Path traversal protection** - Prevents malicious file path manipulation
- ✅ **Old avatar cleanup** - Automatically deletes old avatars when uploading new ones

### PHP 8+ Compatibility
- ✅ Full type declarations (parameters and return types)
- ✅ Proper null handling
- ✅ Strict comparisons throughout
- ✅ Modern error handling with try-catch blocks
- ✅ No deprecated functions or patterns

### Enhanced Functionality
- ✅ Better error messages and user feedback
- ✅ File existence verification before display
- ✅ Automatic file cleanup on plugin uninstall
- ✅ Help text showing allowed formats and size limits
- ✅ Improved code organization and documentation

---

## Requirements

- **PHP:** 8.0 or higher
- **Osclass:** 3.7 or higher (recommended)
- **PHP Extensions:**
  - GD Library or Imagick (for image processing)
  - Fileinfo (for MIME type detection)
- **Server Permissions:** Write access to `oc-content/plugins/avatar_plugin/avatar/` directory

---

## Installation

### Step 1: Upload Files
1. Download the plugin files
2. Upload the `avatar_plugin` folder to your Osclass installation at:
   ```
   oc-content/plugins/avatar_plugin/
   ```

### Step 2: Set Permissions
Ensure the avatar directory has the correct permissions:
```bash
chmod 755 oc-content/plugins/avatar_plugin/avatar/
```

### Step 3: Activate Plugin
1. Log in to your Osclass admin panel
2. Go to **Plugins** section
3. Find **Avatar Plugin** in the list
4. Click **Install** or **Activate**

### Step 4: Verify Installation
1. The plugin will automatically create the database table
2. The `avatar` directory will be created with secure permissions
3. Go to **Plugins > Avatar Help** to view the help page

---

## Usage

### Display User Avatar

Use these functions in your theme templates:

#### Show Avatar of Current Logged User
```php
<?php echo show_avatar(osc_logged_user_id()); ?>
```

#### Show Avatar of Item Owner
```php
<?php echo show_avatar(osc_item_user_id()); ?>
```

#### Show Avatar on Public Profile Page
```php
<?php echo show_avatar(osc_user_id()); ?>
```

### Making Avatar Required (Optional)

To make avatar uploads mandatory:

1. Open `avatar_plugin/index.php`
2. Find the `avatar_form()` function
3. Locate these two commented lines in the JavaScript section:
   ```javascript
   // required: true,
   // required: "Please upload an avatar image",
   ```
4. Remove the `//` at the beginning of both lines
5. Save the file

---

## Supported File Formats

- **JPG/JPEG** - Recommended for photos
- **PNG** - Recommended for graphics with transparency
- **GIF** - Animated images supported

### File Size Limits
- **Maximum:** 3MB per image
- **Recommended:** 500KB or less for best performance

---

## Security Features

### File Upload Security
- Server-side MIME type validation
- Actual image content verification using `getimagesize()`
- File extension whitelist (only jpg, png, gif allowed)
- Maximum file size enforcement (3MB)

### Access Control
- CSRF token verification on all uploads
- Nonce validation prevents unauthorized submissions
- Direct file access prevention

### File System Security
- Secure directory permissions (755)
- Secure file permissions (644)
- Automatic cleanup of old files
- Index files prevent directory listing

### Code Security
- All outputs properly escaped (XSS prevention)
- Type-safe operations throughout
- Prepared statements for database queries (via Osclass DAO)
- Input validation and sanitization

---

## File Structure

```
avatar_plugin/
├── index.php              # Main plugin file with hooks and functions
├── ModelAvatar.php        # Database operations class
├── help.php               # Admin help page
├── README.md              # This file
├── struct.sql             # Database structure
├── no-avatar.png          # Default placeholder image
├── js/
│   └── additional-methods.min.js  # jQuery validation extensions
└── avatar/                # Upload directory (created on install)
    └── index.php          # Prevents directory listing
```

---

## Database Structure

The plugin creates one table:

### `{prefix}_t_avatar`
| Column | Type | Description |
|--------|------|-------------|
| `fk_i_user_id` | INT(10) UNSIGNED | User ID (Primary Key, Foreign Key) |
| `avatar` | VARCHAR(255) | Avatar filename |

**Indexes:**
- Primary Key on `fk_i_user_id`
- Foreign Key references `t_user(pk_i_id)`

---

## Troubleshooting

### Avatar Not Uploading?
- Check that `oc-content/plugins/avatar_plugin/avatar/` directory exists
- Verify directory has write permissions (755)
- Check PHP upload limits in `php.ini`:
  ```ini
  upload_max_filesize = 3M
  post_max_size = 4M
  ```

### "File Too Large" Error?
- Maximum file size is 3MB
- Resize your image before uploading
- Check server PHP limits (see above)

### "Invalid File Format" Error?
- Only JPG, PNG, and GIF images are allowed
- File must be an actual image, not renamed file
- MIME type must match file extension

### "Security Verification Failed" Error?
- Clear your browser cache
- Refresh the page and try again
- Check that your session is still active

### Avatar Not Displaying?
- Check that the image file exists in the `avatar` directory
- Verify file permissions are correct (644)
- Look for JavaScript errors in browser console

---

## Upgrading from Version 1.0.0

### Important Notes
1. **Backup your data** before upgrading
2. **Backup existing avatars** from the `avatar` directory
3. **Database structure** remains the same (no migration needed)

### Upgrade Steps
1. Deactivate the old plugin (if possible)
2. Backup the `avatar` directory
3. Replace all plugin files with new versions
4. Reactivate the plugin
5. Test avatar upload on a test account

### Breaking Changes
- Old avatars will continue to work
- New uploads will use enhanced security features
- No changes to database structure

---

## Customization

### Changing Maximum File Size

Edit `index.php` in the `avatar_validate_upload()` function:

```php
// Change this line (size in bytes, 3145728 = 3MB)
if ($file['size'] > 3145728) {
```

Also update the validation in `avatar_form()` function:

```javascript
filesize: 3145728  // Change this value
```

### Changing Avatar Display Size

Edit the `show_avatar()` function in `index.php`:

```php
width="130"  // Change this value
```

### Adding More Allowed Formats

Edit `avatar_validate_upload()` in `index.php`:

```php
$allowed_mime_types = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
```

---

## Support

### Documentation
- **Admin Help Page:** Plugins > Avatar Help
- **This README:** Complete installation and usage guide

### Common Issues
- Review the Troubleshooting section above
- Check file and directory permissions
- Verify PHP version is 8.0 or higher

### Reporting Issues
When reporting issues, please include:
- Osclass version
- PHP version
- Error messages (if any)
- Steps to reproduce the problem

---

## Credits

- **Original Plugin:** Media.Dmj (vithudu.com)
- **Version 2.0.0 Updates:** Security & PHP 8+ compatibility improvements

---

## License

This plugin is released under the MIT License. You are free to use, modify, and distribute this plugin.

---

## Changelog

### Version 2.0.0 (2025)
- ✅ Added server-side file validation and MIME type checking
- ✅ Implemented CSRF protection with nonce verification
- ✅ Improved file permissions (755 for directories, 644 for files)
- ✅ Added automatic cleanup of old avatars
- ✅ Full PHP 8+ compatibility with type declarations
- ✅ Enhanced error handling and user feedback
- ✅ Added XSS protection with proper output escaping
- ✅ Improved code organization and documentation
- ✅ Added file existence verification
- ✅ Updated jQuery validation methods
- ✅ Added comprehensive help documentation

### Version 1.0.0 (Original)
- Initial release by Media.Dmj
- Basic avatar upload functionality
- Register and profile page integration