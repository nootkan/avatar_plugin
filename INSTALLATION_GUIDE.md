# Avatar Plugin v2.0.0 - Installation & Upgrade Guide

**Updated:** January 8, 2025  
**Plugin Version:** 2.0.0  
**Minimum PHP Version:** 8.0  
**Osclass Compatibility:** 3.7+

---

## 📋 Table of Contents

1. [What You Received](#what-you-received)
2. [Files to Replace](#files-to-replace)
3. [Fresh Installation](#fresh-installation)
4. [Upgrading from v1.0.0](#upgrading-from-v100)
5. [Post-Installation Steps](#post-installation-steps)
6. [Testing Checklist](#testing-checklist)
7. [Troubleshooting](#troubleshooting)

---

## 🎁 What You Received

You received **4 updated core files** that replace your existing plugin files:

### Updated Files:
1. ✅ **index.php** - Main plugin file with security fixes and PHP 8+ compatibility
2. ✅ **ModelAvatar.php** - Database operations with type declarations
3. ✅ **help.php** - Admin help page with new documentation
4. ✅ **en_US.po** - Translation file with new text strings
5. ✅ **README.md** - Complete documentation

### Files You Already Have (No Changes Needed):
- **struct.sql** - Database structure (unchanged, compatible as-is)
- **additional-methods_min.js** - jQuery validation (unchanged)
- **no-avatar.png** - Default avatar image (unchanged)

---

## 📁 Files to Replace

Here's exactly what to replace in your plugin directory:

```
oc-content/plugins/avatar_plugin/
├── index.php              ← REPLACE THIS
├── ModelAvatar.php        ← REPLACE THIS
├── help.php               ← REPLACE THIS (or admin/help.php)
├── en_US.po               ← REPLACE THIS
├── README.md              ← REPLACE THIS
├── struct.sql             ← KEEP EXISTING (no changes needed)
├── additional-methods_min.js  ← KEEP EXISTING
├── no-avatar.png          ← KEEP EXISTING
└── avatar/                ← KEEP EXISTING (contains user avatars)
```

---

## 🆕 Fresh Installation

If you're installing the plugin for the first time:

### Step 1: Upload Files
1. Create directory: `oc-content/plugins/avatar_plugin/`
2. Upload all 5 updated files to this directory
3. Upload the remaining files (struct.sql, additional-methods_min.js, no-avatar.png)
4. Create `js` subdirectory and place `additional-methods_min.js` inside it

### Step 2: Set Permissions
```bash
chmod 755 oc-content/plugins/avatar_plugin/
chmod 644 oc-content/plugins/avatar_plugin/*.php
chmod 644 oc-content/plugins/avatar_plugin/*.po
chmod 644 oc-content/plugins/avatar_plugin/*.sql
```

### Step 3: Activate Plugin
1. Log in to Osclass admin panel
2. Go to **Plugins**
3. Find **Avatar Plugin**
4. Click **Install**

The plugin will automatically:
- Create the database table
- Create the `avatar` directory with secure permissions (755)
- Create an index.php file in the avatar directory

### Step 4: Verify Installation
1. Go to **Plugins > Avatar Help** to see the help page
2. Edit your profile to test avatar upload
3. Check that the avatar directory was created: `oc-content/plugins/avatar_plugin/avatar/`

---

## 🔄 Upgrading from v1.0.0

### IMPORTANT: Backup First! ⚠️

**Before upgrading, backup:**
1. Your entire `avatar_plugin` directory
2. Your database (specifically the `t_avatar` table)
3. All uploaded avatar images in the `avatar` directory

### Upgrade Steps:

#### Step 1: Deactivate Plugin (Recommended)
1. Go to Osclass admin > Plugins
2. Deactivate (don't uninstall!) Avatar Plugin
3. This prevents conflicts during file replacement

#### Step 2: Backup Avatar Images
```bash
# Create a backup of avatar images
cp -r oc-content/plugins/avatar_plugin/avatar/ /path/to/backup/avatar_backup/
```

Or via FTP: Download the entire `avatar` folder to your computer.

#### Step 3: Replace Files
Replace these 5 files in your `avatar_plugin` directory:
1. index.php
2. ModelAvatar.php
3. help.php (might be in `admin/help.php`)
4. en_US.po
5. README.md

**DO NOT delete or replace:**
- The `avatar` directory (contains user images!)
- struct.sql
- additional-methods_min.js

#### Step 4: Verify File Permissions
Ensure proper permissions after upload:
```bash
chmod 755 oc-content/plugins/avatar_plugin/
chmod 755 oc-content/plugins/avatar_plugin/avatar/
chmod 644 oc-content/plugins/avatar_plugin/avatar/*.jpg
chmod 644 oc-content/plugins/avatar_plugin/avatar/*.png
chmod 644 oc-content/plugins/avatar_plugin/avatar/*.gif
```

#### Step 5: Reactivate Plugin
1. Go to Osclass admin > Plugins
2. Activate Avatar Plugin
3. All existing avatars should still work

#### Step 6: Clear Cache (if applicable)
If your Osclass installation uses caching:
```bash
# Clear Osclass cache
rm -rf oc-content/cache/*
```

---

## ✅ Post-Installation Steps

### 1. Test Avatar Upload

**Test on Registration:**
1. Log out of your account
2. Go to the registration page
3. You should see an avatar upload field
4. Try uploading a test image (JPG, PNG, or GIF under 3MB)

**Test on Profile Edit:**
1. Log in to your account
2. Go to your profile page
3. You should see your current avatar (or "No Avatar" placeholder)
4. Try uploading a new avatar
5. Verify old avatar is deleted and new one appears

### 2. Test Security Features

**Test File Validation:**
1. Try uploading a .txt file renamed as .jpg (should fail)
2. Try uploading a file over 3MB (should fail)
3. Try uploading a .pdf file (should fail)
4. Verify error messages appear correctly

### 3. Verify Permissions

Check that directories have correct permissions:
```bash
# Plugin directory: 755
ls -ld oc-content/plugins/avatar_plugin/

# Avatar directory: 755
ls -ld oc-content/plugins/avatar_plugin/avatar/

# Avatar files: 644
ls -l oc-content/plugins/avatar_plugin/avatar/
```

### 4. Check Admin Help Page
1. Go to **Plugins > Avatar Help**
2. Verify the help page displays correctly
3. Review the security features section

### 5. Test Display Functions

Add this code to a theme template to test:
```php
<?php 
// Test display functions
if (osc_is_web_user_logged_in()) {
    echo "<h3>Your Avatar:</h3>";
    show_avatar(osc_logged_user_id());
}
?>
```

---

## 🧪 Testing Checklist

Use this checklist to verify everything works:

### Basic Functionality
- [ ] Plugin activates without errors
- [ ] Database table exists (`{prefix}_t_avatar`)
- [ ] Avatar directory created with correct permissions
- [ ] Help page displays correctly

### Upload Testing
- [ ] Can upload JPG images successfully
- [ ] Can upload PNG images successfully
- [ ] Can upload GIF images successfully
- [ ] Old avatar is automatically deleted when uploading new one
- [ ] Error message shown for invalid file types
- [ ] Error message shown for files over 3MB
- [ ] Success message shown after successful upload

### Security Testing
- [ ] Cannot upload .txt files renamed as images
- [ ] Cannot upload .php files
- [ ] Cannot upload files without proper MIME types
- [ ] CSRF protection works (try uploading without refreshing page)

### Display Testing
- [ ] Avatar displays on profile page
- [ ] "No Avatar" placeholder shows when no avatar uploaded
- [ ] Avatar displays correct size (130px width)
- [ ] Avatar URL is properly escaped (no XSS vulnerabilities)

### Existing Data (Upgrade Only)
- [ ] Old avatars still display correctly
- [ ] User avatar associations preserved in database
- [ ] No broken images from previous uploads

---

## 🔧 Troubleshooting

### Problem: Plugin won't activate

**Solutions:**
1. Check PHP version: Must be 8.0 or higher
   ```bash
   php -v
   ```
2. Check file permissions on all PHP files
3. Check Osclass error logs: `oc-content/debug.log`
4. Verify all required files are present

### Problem: "Failed to save avatar"

**Solutions:**
1. Check avatar directory exists and is writable:
   ```bash
   ls -ld oc-content/plugins/avatar_plugin/avatar/
   chmod 755 oc-content/plugins/avatar_plugin/avatar/
   ```
2. Check PHP upload settings in `php.ini`:
   ```ini
   upload_max_filesize = 3M
   post_max_size = 4M
   file_uploads = On
   ```
3. Check disk space on server
4. Review server error logs for permission issues

### Problem: "Security verification failed"

**Solutions:**
1. Clear browser cache and cookies
2. Refresh the profile/registration page
3. Check if sessions are working properly
4. Verify the form has the nonce field (view page source, look for `avatar_nonce`)

### Problem: Old avatars not displaying after upgrade

**Solutions:**
1. Verify avatar files still exist in `avatar` directory
2. Check file permissions: `chmod 644 oc-content/plugins/avatar_plugin/avatar/*`
3. Check database: Query `{prefix}_t_avatar` table to verify records exist
4. Clear browser cache

### Problem: Images upload but don't display

**Solutions:**
1. Check that `no-avatar.png` exists in plugin directory
2. Verify file paths in database are correct (just filename, not full path)
3. Check browser console for 404 errors on image URLs
4. Verify web server can serve images from the avatar directory

### Problem: "Only JPG, PNG, GIF allowed" error for valid images

**Solutions:**
1. Verify PHP fileinfo extension is installed:
   ```bash
   php -m | grep fileinfo
   ```
2. If missing, install it (example for Ubuntu):
   ```bash
   sudo apt-get install php-fileinfo
   sudo systemctl restart apache2
   ```
3. Check image file isn't corrupted (open in image viewer)

### Problem: Database table not created

**Solutions:**
1. Check database user has CREATE TABLE privileges
2. Manually import struct.sql:
   - Go to phpMyAdmin
   - Select your Osclass database
   - Go to SQL tab
   - Copy contents of struct.sql
   - Replace `/*TABLE_PREFIX*/` with your actual table prefix
   - Execute the query

---

## 📞 Getting Additional Help

### Check These First:
1. **README.md** - Complete documentation and usage examples
2. **Help Page** - In Osclass admin: Plugins > Avatar Help
3. **Error Logs** - Check `oc-content/debug.log` for errors

### Common Error Log Locations:
- Osclass: `oc-content/debug.log`
- Apache: `/var/log/apache2/error.log`
- Nginx: `/var/log/nginx/error.log`
- PHP-FPM: `/var/log/php-fpm/error.log`

### Information to Gather for Support:
- PHP version (`php -v`)
- Osclass version
- Plugin version
- Error messages (exact text)
- Steps to reproduce the problem
- Browser console errors (F12 > Console tab)

---

## 🎉 Success! What's Next?

Once everything is working:

### Optional Enhancements:

1. **Make Avatar Required:**
   - Edit `index.php`
   - Find the `avatar_form()` function
   - Uncomment the two lines marked with `// required`

2. **Customize Avatar Size:**
   - Edit `index.php`
   - Find `width="130"` in the `show_avatar()` function
   - Change to your preferred size

3. **Change Maximum File Size:**
   - Edit `index.php`
   - Find `3145728` (3MB in bytes)
   - Change to your preferred size
   - Update in two places: validation function and JavaScript

4. **Add Custom Styling:**
   - Target class `.avatar` in your theme CSS
   - Example:
     ```css
     .avatar {
         border-radius: 50% !important;
         border: 3px solid #0073aa !important;
     }
     ```

---

## 📝 Final Notes

### What Changed in v2.0.0:
- ✅ Full PHP 8+ compatibility
- ✅ Enhanced security (CSRF, XSS, file validation)
- ✅ Better error handling and user feedback
- ✅ Automatic old avatar cleanup
- ✅ Improved code organization
- ✅ Comprehensive documentation

### Backward Compatibility:
- ✅ Database structure unchanged
- ✅ Existing avatars continue to work
- ✅ Display functions unchanged
- ✅ Template integration remains the same

### Security Improvements:
- ✅ Server-side file validation
- ✅ MIME type checking
- ✅ CSRF protection
- ✅ XSS prevention
- ✅ Secure file permissions
- ✅ Path traversal protection

---

**Congratulations! Your Avatar Plugin is now updated to v2.0.0 with enhanced security and PHP 8+ compatibility!** 🎊

If you followed all the steps and completed the testing checklist, your plugin should be working perfectly.

---

**Questions or Issues?**  
Refer to the Troubleshooting section above or review the README.md for detailed usage information.