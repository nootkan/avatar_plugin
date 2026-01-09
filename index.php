<?php
/*
Plugin Name: Avatar Plugin
Plugin URI: http://www.osclass.org
Description: Avatar plugins helps to upload profile picture while register.
Version: 2.0.0
Author: Van Isle Web Solutions
Author Uri: https://www.vanislebc.com/
Original Author: Media.Dmj
Original Author URI: http://www.vithudu.com/
Short name: Avatar Plugin
Plugin update URI: avatar-plugin
*/

include "ModelAvatar.php";

/* Install Plugin */
function avatar_install(): void {
    ModelAvatar::newInstance()->import('avatar_plugin/struct.sql');
    $avatar_path = osc_content_path() . "/plugins/avatar_plugin/avatar/";
    
    if (!file_exists($avatar_path)) {
        // Create directory with secure permissions (755 instead of 777)
        mkdir($avatar_path, 0755, true);
        // Create index.php to prevent directory listing
        file_put_contents($avatar_path . "index.php", "<?php\n// Silence is golden\n");
    }
}

/* Uninstall Plugin */
function avatar_uninstall(): void {
    ModelAvatar::newInstance()->uninstall();
}

/**
 * Validate uploaded image file
 * 
 * @param array $file The $_FILES array element
 * @return array Returns ['success' => bool, 'error' => string]
 */
function avatar_validate_upload(array $file): array {
    // Check if file was uploaded
    if (!isset($file['error']) || is_array($file['error'])) {
        return ['success' => false, 'error' => 'Invalid file upload'];
    }
    
    // Check for upload errors
    switch ($file['error']) {
        case UPLOAD_ERR_OK:
            break;
        case UPLOAD_ERR_INI_SIZE:
        case UPLOAD_ERR_FORM_SIZE:
            return ['success' => false, 'error' => 'File size exceeds maximum allowed (3MB)'];
        case UPLOAD_ERR_NO_FILE:
            return ['success' => false, 'error' => 'No file uploaded'];
        default:
            return ['success' => false, 'error' => 'Unknown upload error'];
    }
    
    // Validate file size (3MB max)
    if ($file['size'] > 3145728) {
        return ['success' => false, 'error' => 'File size must be less than 3MB'];
    }
    
    // Validate file is an actual image using getimagesize
    $image_info = @getimagesize($file['tmp_name']);
    if ($image_info === false) {
        return ['success' => false, 'error' => 'File is not a valid image'];
    }
    
    // Validate MIME type (only allow JPEG, PNG, GIF)
    $allowed_mime_types = ['image/jpeg', 'image/png', 'image/gif'];
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime_type = finfo_file($finfo, $file['tmp_name']);
    finfo_close($finfo);
    
    if (!in_array($mime_type, $allowed_mime_types, true)) {
        return ['success' => false, 'error' => 'Only JPG, PNG, and GIF images are allowed'];
    }
    
    // Validate file extension matches MIME type
    $extension_map = [
        'image/jpeg' => ['jpg', 'jpeg'],
        'image/png' => ['png'],
        'image/gif' => ['gif']
    ];
    
    $file_extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    $valid_extensions = [];
    foreach ($extension_map as $extensions) {
        $valid_extensions = array_merge($valid_extensions, $extensions);
    }
    
    if (!in_array($file_extension, $valid_extensions, true)) {
        return ['success' => false, 'error' => 'Invalid file extension'];
    }
    
    return ['success' => true, 'error' => ''];
}

/**
 * Insert or update user avatar
 * 
 * @param int $userId The user ID
 * @return void
 */
function insertAvatar(int $userId): void {
    // Check if file was uploaded
    if (!isset($_FILES['avatar']) || $_FILES['avatar']['error'] === UPLOAD_ERR_NO_FILE) {
        // No file uploaded, just return (not an error)
        return;
    }
    
    // Validate the upload
    $validation = avatar_validate_upload($_FILES['avatar']);
    if (!$validation['success']) {
        osc_add_flash_error_message($validation['error']);
        return;
    }
    
    $upload_directory = osc_content_path() . '/plugins/avatar_plugin/avatar/';
    
    // Get MIME type to determine correct extension
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime_type = finfo_file($finfo, $_FILES['avatar']['tmp_name']);
    finfo_close($finfo);
    
    // Map MIME type to extension
    $extension_map = [
        'image/jpeg' => 'jpg',
        'image/png' => 'png',
        'image/gif' => 'gif'
    ];
    $ext = $extension_map[$mime_type] ?? 'jpg';
    
    // Delete old avatar if exists
    $old_avatar = ModelAvatar::newInstance()->getAvatar($userId);
    if (!empty($old_avatar)) {
        $old_file = $upload_directory . $old_avatar;
        if (file_exists($old_file)) {
            @unlink($old_file);
        }
    }
    
    // Generate secure filename
    $new_filename = $userId . '_avatar.' . $ext;
    $destination = $upload_directory . $new_filename;
    
    // Move uploaded file
    if (!move_uploaded_file($_FILES['avatar']['tmp_name'], $destination)) {
        osc_add_flash_error_message(__('Failed to save avatar. Please try again.', 'avatar_plugin'));
        return;
    }
    
    // Set secure file permissions
    chmod($destination, 0644);
    
    // Update database
    try {
        if (!empty($old_avatar)) {
            ModelAvatar::newInstance()->updateAvatar($new_filename, $userId);
        } else {
            ModelAvatar::newInstance()->insertAvatar($new_filename, $userId);
        }
        osc_add_flash_ok_message(__('Avatar uploaded successfully!', 'avatar_plugin'));
    } catch (Exception $e) {
        // If database update fails, delete the uploaded file
        @unlink($destination);
        osc_add_flash_error_message(__('Failed to update avatar. Please try again.', 'avatar_plugin'));
    }
}

/**
 * Display user avatar
 * 
 * @param int|null $user The user ID
 * @return void
 */
function show_avatar(?int $user): void {
    if ($user === null) {
        echo avatar_get_no_avatar_html();
        return;
    }
    
    $avatar = ModelAvatar::newInstance()->getAvatar($user);
    
    if (!empty($avatar)) {
        // Build the avatar URL
        $avatar_url = osc_base_url() . "oc-content/plugins/avatar_plugin/avatar/" . urlencode($avatar);
        $avatar_path = osc_content_path() . '/plugins/avatar_plugin/avatar/' . $avatar;
        
        // Verify file exists before displaying
        if (file_exists($avatar_path)) {
            echo '<img class="avatar" style="border: 1px solid rgb(221, 221, 221); background: rgb(255, 255, 255) none repeat scroll 0% 0%; padding: 5px; border-radius: 4px; margin-bottom: 5px;" width="130" class="img-thumbnail" src="' . $avatar_url . '" alt="' . __('User Avatar', 'avatar_plugin') . '" />';
            return;
        }
    }
    
    echo avatar_get_no_avatar_html();
}

/**
 * Get HTML for no avatar placeholder
 * 
 * @return string
 */
function avatar_get_no_avatar_html(): string {
    $no_avatar_url = osc_base_url() . "oc-content/plugins/avatar_plugin/no-avatar.png";
    return '<img class="avatar no-avatar" style="border: 1px solid rgb(221, 221, 221); background: rgb(255, 255, 255) none repeat scroll 0% 0%; padding: 5px; border-radius: 4px; margin-bottom: 5px;" width="130" class="img-thumbnail" src="' . $no_avatar_url . '" alt="' . __('No Avatar', 'avatar_plugin') . '" />';
}

/**
 * Display avatar upload form
 * 
 * @return void
 */
function avatar_form(): void {
    // Prevent duplicate display on same page
    static $already_displayed = false;
    if ($already_displayed) {
        return;
    }
    $already_displayed = true;
    
    $user_id = osc_user_id();
    ?>
    <div class="control-group">
        <label class="control-label" for="avatar"><?php _e('Avatar', 'avatar_plugin'); ?></label>
        <div class="controls">
            <?php show_avatar($user_id); ?><br />
        </div>
        <div class="controls">
            <div id="avatar-upload-container">
                <input id="pAvatar" name="avatar" type="file" accept="image/jpeg,image/png,image/gif" />
                <span id="lblError" style="color: red;"></span>
                <p class="help-block"><?php _e('Allowed formats: JPG, PNG, GIF. Maximum size: 3MB', 'avatar_plugin'); ?></p>
            </div>
        </div>
    </div>
    
    <script type="text/javascript">
        (function($) {
            $(document).ready(function() {
                // Add enctype to forms
                $(".user-profile form").attr("enctype", "multipart/form-data");
                $("form[name='register']").attr("enctype", "multipart/form-data");
            });
        })(jQuery);
    </script>
    
    <?php if (osc_get_osclass_section() === "profile") { ?>
        <script type="text/javascript" src="<?php echo osc_base_url() . 'oc-includes/osclass/assets/js/jquery.validate.min.js'; ?>"></script>
    <?php } ?>
    
    <script type="text/javascript" src="<?php echo osc_base_url() . 'oc-content/plugins/avatar_plugin/js/additional-methods.min.js'; ?>"></script>
    
    <script type="text/javascript">
        (function($) {
            // Add custom file size validation
            $.validator.addMethod('filesize', function(value, element, param) {
                return this.optional(element) || (element.files[0].size <= param);
            }, '<?php echo osc_esc_js(__('File size must be less than 3MB', 'avatar_plugin')); ?>');

            // Validate forms
            $("form[name='register'], form[name='profile']").validate({
                rules: {
                    'avatar': {
                        <?php if (!OC_ADMIN) { ?>
                        // Uncomment the line below to make avatar required
                        // required: true,
                        <?php } ?>
                        extension: "png|jpe?g|gif",
                        filesize: 3145728
                    }
                },
                messages: {
                    'avatar': {
                        <?php if (!OC_ADMIN) { ?>
                        // Uncomment the line below to make avatar required
                        // required: "<?php echo osc_esc_js(__('Please upload an avatar image', 'avatar_plugin')); ?>",
                        <?php } ?>
                        extension: "<?php echo osc_esc_js(__('Only JPG, PNG, and GIF formats are allowed', 'avatar_plugin')); ?>",
                        filesize: "<?php echo osc_esc_js(__('File size must be less than 3MB', 'avatar_plugin')); ?>"
                    }
                }
            });
        })(jQuery);
    </script>

    <style type="text/css">
        label.error {
            color: #ff0000;
            display: block;
        }
    </style>
<?php 
}

/**
 * Add plugin menu to admin
 * 
 * @return void
 */
function avatar_user_menu(): void {
    echo '<li style="background:#e7e7e7;"><a href="' . osc_admin_render_plugin_url(osc_plugin_folder(__FILE__) . 'admin/help.php') . '" >' . __('Avatar Help', 'avatar_plugin') . '</a></li>';
}

/**
 * Override gravatar with plugin avatar
 * Automatically replaces gravatar images with uploaded avatars
 * 
 * @param int|null $user_id The user ID
 * @return string Avatar HTML or empty string
 */
function avatar_override_gravatar(?int $user_id = null): string {
    if ($user_id === null) {
        $user_id = osc_user_id();
    }
    
    if ($user_id === null || $user_id === 0) {
        return '';
    }
    
    $avatar = ModelAvatar::newInstance()->getAvatar($user_id);
    
    if (!empty($avatar)) {
        $avatar_url = osc_base_url() . "oc-content/plugins/avatar_plugin/avatar/" . urlencode($avatar);
        $avatar_path = osc_content_path() . '/plugins/avatar_plugin/avatar/' . $avatar;
        
        // Verify file exists
        if (file_exists($avatar_path)) {
            return $avatar_url;
        }
    }
    
    return '';
}

/**
 * Filter to replace gravatar URL with plugin avatar
 * 
 * @param string $url The original gravatar URL
 * @param int|null $user_id The user ID
 * @return string Modified URL or original URL
 */
function avatar_filter_gravatar_url(string $url, ?int $user_id = null): string {
    $custom_avatar = avatar_override_gravatar($user_id);
    if (!empty($custom_avatar)) {
        return $custom_avatar;
    }
    return $url;
}

// Register hooks
osc_add_hook('admin_menu', 'avatar_user_menu');
osc_add_hook('user_register_form', 'avatar_form');
osc_add_hook('user_profile_form', 'avatar_form');
osc_add_hook('user_register_completed', 'insertAvatar');
osc_add_hook('user_edit_completed', 'insertAvatar');

// Hook into gravatar to automatically replace it
osc_add_filter('gravatar_url', 'avatar_filter_gravatar_url');
osc_add_hook(osc_plugin_path(__FILE__) . '_uninstall', 'avatar_uninstall');
osc_register_plugin(osc_plugin_path(__FILE__), 'avatar_install');
