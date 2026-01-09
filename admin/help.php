<?php
/**
 * Avatar Plugin Help Page
 * 
 * @package AvatarPlugin
 * @version 2.0.0
 */

// Prevent direct access
if (!defined('ABS_PATH')) {
    exit('Direct access is not allowed.');
}
?>

<div class="avatar-plugin-help">
    <h1><?php echo __('Avatar Plugin', 'avatar_plugin'); ?></h1>
    
    <div class="help-section">
        <h3><?php echo __('What the plugin does?', 'avatar_plugin'); ?></h3>
        <p><?php echo __('The avatar plugin shows the profile picture upload button on Register page, Profile page, and admin user page. Users can upload their picture while registering or editing their profile.', 'avatar_plugin'); ?></p>
    </div>
    
    <div class="help-section">
        <h3><?php echo __('Security Features', 'avatar_plugin'); ?></h3>
        <ul>
            <li><?php echo __('Server-side file validation (MIME type and actual image verification)', 'avatar_plugin'); ?></li>
            <li><?php echo __('CSRF protection with nonce verification', 'avatar_plugin'); ?></li>
            <li><?php echo __('Secure file permissions (755 for directories, 644 for files)', 'avatar_plugin'); ?></li>
            <li><?php echo __('Maximum file size limit: 3MB', 'avatar_plugin'); ?></li>
            <li><?php echo __('Allowed formats: JPG, PNG, GIF only', 'avatar_plugin'); ?></li>
            <li><?php echo __('Automatic cleanup of old avatars when uploading new ones', 'avatar_plugin'); ?></li>
        </ul>
    </div>
    
    <div class="help-section">
        <h3><?php echo __('How to use', 'avatar_plugin'); ?></h3>
        <p><?php echo __('Use this code to show the picture of user:', 'avatar_plugin'); ?></p>
        
        <div class="code-example">
            <p><strong><?php echo __('Get picture of item user:', 'avatar_plugin'); ?></strong></p>
            <code>&lt;?php echo show_avatar(osc_item_user_id()); ?&gt;</code>
        </div>
        
        <div class="code-example">
            <p><strong><?php echo __('Get picture of logged user:', 'avatar_plugin'); ?></strong></p>
            <code>&lt;?php echo show_avatar(osc_logged_user_id()); ?&gt;</code>
        </div>
        
        <div class="code-example">
            <p><strong><?php echo __('Get picture of public profile user:', 'avatar_plugin'); ?></strong></p>
            <code>&lt;?php echo show_avatar(osc_user_id()); ?&gt;</code>
        </div>
    </div>
    
    <div class="help-section">
        <h3><?php echo __('Want to make avatar required?', 'avatar_plugin'); ?></h3>
        <p><?php echo __('Edit avatar_plugin/index.php and find the avatar_form() function. Look for the JavaScript validation section and uncomment the two lines that say:', 'avatar_plugin'); ?></p>
        <div class="code-example">
            <code>// required: true,</code><br />
            <code>// required: "Please upload an avatar image",</code>
        </div>
        <p><?php echo __('Remove the // at the beginning of these lines to make avatar uploads required.', 'avatar_plugin'); ?></p>
    </div>
    
    <div class="help-section">
        <h3><?php echo __('Installation Requirements', 'avatar_plugin'); ?></h3>
        <ul>
            <li><?php echo __('PHP 8.0 or higher', 'avatar_plugin'); ?></li>
            <li><?php echo __('GD Library or Imagick extension for image processing', 'avatar_plugin'); ?></li>
            <li><?php echo __('Write permissions on oc-content/plugins/avatar_plugin/avatar/ directory', 'avatar_plugin'); ?></li>
        </ul>
    </div>
    
    <div class="help-section">
        <h3><?php echo __('Troubleshooting', 'avatar_plugin'); ?></h3>
        <ul>
            <li><strong><?php echo __('Avatar not uploading?', 'avatar_plugin'); ?></strong> - <?php echo __('Check that the avatar directory has write permissions (755)', 'avatar_plugin'); ?></li>
            <li><strong><?php echo __('File too large error?', 'avatar_plugin'); ?></strong> - <?php echo __('Maximum file size is 3MB. Resize your image before uploading', 'avatar_plugin'); ?></li>
            <li><strong><?php echo __('Invalid file format?', 'avatar_plugin'); ?></strong> - <?php echo __('Only JPG, PNG, and GIF images are allowed', 'avatar_plugin'); ?></li>
            <li><strong><?php echo __('Security verification failed?', 'avatar_plugin'); ?></strong> - <?php echo __('Try refreshing the page and uploading again', 'avatar_plugin'); ?></li>
        </ul>
    </div>
    
    <div class="help-section">
        <h3><?php echo __('About', 'avatar_plugin'); ?></h3>
        <table class="about-table">
            <tr>
                <td><strong><?php echo __('Plugin Name:', 'avatar_plugin'); ?></strong></td>
                <td>Avatar Plugin</td>
            </tr>
            <tr>
                <td><strong><?php echo __('Version:', 'avatar_plugin'); ?></strong></td>
                <td>2.0.0</td>
            </tr>
            <tr>
                <td><strong><?php echo __('Original Author:', 'avatar_plugin'); ?></strong></td>
                <td>Media.Dmj</td>
            </tr>
            <tr>
                <td><strong><?php echo __('Website:', 'avatar_plugin'); ?></strong></td>
                <td>vithudu.com</td>
            </tr>
            <tr>
                <td><strong><?php echo __('Updated for:', 'avatar_plugin'); ?></strong></td>
                <td>PHP 8+ Compatibility & Enhanced Security</td>
            </tr>
        </table>
    </div>
    
    <div class="help-section">
        <h3><?php echo __('Changelog - Version 2.0.0', 'avatar_plugin'); ?></h3>
        <ul>
            <li><?php echo __('Added server-side file validation and MIME type checking', 'avatar_plugin'); ?></li>
            <li><?php echo __('Implemented CSRF protection with nonce verification', 'avatar_plugin'); ?></li>
            <li><?php echo __('Improved file permissions (755 for directories, 644 for files)', 'avatar_plugin'); ?></li>
            <li><?php echo __('Added automatic cleanup of old avatars', 'avatar_plugin'); ?></li>
            <li><?php echo __('Full PHP 8+ compatibility with type declarations', 'avatar_plugin'); ?></li>
            <li><?php echo __('Enhanced error handling and user feedback', 'avatar_plugin'); ?></li>
            <li><?php echo __('Added XSS protection with proper output escaping', 'avatar_plugin'); ?></li>
            <li><?php echo __('Improved code organization and documentation', 'avatar_plugin'); ?></li>
        </ul>
    </div>
</div>

<style>
.avatar-plugin-help {
    max-width: 900px;
    margin: 20px 0;
    font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Oxygen-Sans, Ubuntu, Cantarell, sans-serif;
}

.avatar-plugin-help h1 {
    color: #23282d;
    font-size: 28px;
    margin-bottom: 20px;
    border-bottom: 2px solid #0073aa;
    padding-bottom: 10px;
}

.avatar-plugin-help .help-section {
    background: #fff;
    border: 1px solid #ccd0d4;
    border-radius: 4px;
    padding: 20px;
    margin-bottom: 20px;
}

.avatar-plugin-help h3 {
    color: #0073aa;
    font-size: 20px;
    margin-top: 0;
    margin-bottom: 15px;
}

.avatar-plugin-help ul {
    margin-left: 20px;
    line-height: 1.8;
}

.avatar-plugin-help .code-example {
    background: #f6f7f7;
    border-left: 4px solid #0073aa;
    padding: 15px;
    margin: 10px 0;
    font-family: Consolas, Monaco, monospace;
}

.avatar-plugin-help .code-example p {
    margin: 0 0 10px 0;
    font-weight: 600;
}

.avatar-plugin-help code {
    background: #f6f7f7;
    padding: 2px 6px;
    border-radius: 3px;
    font-family: Consolas, Monaco, monospace;
    font-size: 13px;
}

.avatar-plugin-help .about-table {
    width: 100%;
    border-collapse: collapse;
}

.avatar-plugin-help .about-table td {
    padding: 10px;
    border-bottom: 1px solid #e5e5e5;
}

.avatar-plugin-help .about-table tr:last-child td {
    border-bottom: none;
}

.avatar-plugin-help .about-table td:first-child {
    width: 200px;
}
</style>
