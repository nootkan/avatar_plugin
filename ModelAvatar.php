<?php
/**
 * ModelAvatar Class
 * 
 * Handles database operations for avatar plugin
 * 
 * @package AvatarPlugin
 * @version 2.0.0
 */
class ModelAvatar extends DAO
{
    /**
     * Singleton instance
     * 
     * @var ModelAvatar|null
     */
    private static ?ModelAvatar $instance = null;

    /**
     * Get singleton instance
     * 
     * @return ModelAvatar
     */
    public static function newInstance(): ModelAvatar
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Import SQL file to create database tables
     * 
     * @param string $file Path to SQL file
     * @return void
     * @throws Exception If import fails
     */
    public function import(string $file): void
    {
        $path = osc_plugin_resource($file);
        
        if (!file_exists($path)) {
            throw new Exception("SQL file not found: " . $file);
        }
        
        $sql = file_get_contents($path);
        
        if ($sql === false) {
            throw new Exception("Failed to read SQL file: " . $file);
        }

        if (!$this->dao->importSQL($sql)) {
            throw new Exception("Error importSQL::ModelAvatar - " . $file);
        }
    }

    /**
     * Get the avatar table name
     * 
     * @return string
     */
    public function getTable_Avatar(): string
    {
        return DB_TABLE_PREFIX . 't_avatar';
    }

    /**
     * Uninstall plugin - drops the avatar table
     * 
     * @return void
     */
    public function uninstall(): void
    {
        // First, get all avatars and delete the image files
        try {
            $this->dao->select('avatar');
            $this->dao->from($this->getTable_Avatar());
            $result = $this->dao->get();
            
            if ($result !== false) {
                $avatars = $result->result();
                $upload_directory = osc_content_path() . '/plugins/avatar_plugin/avatar/';
                
                foreach ($avatars as $avatar_row) {
                    if (!empty($avatar_row['avatar'])) {
                        $file_path = $upload_directory . $avatar_row['avatar'];
                        if (file_exists($file_path)) {
                            @unlink($file_path);
                        }
                    }
                }
            }
        } catch (Exception $e) {
            // Continue with uninstall even if file deletion fails
        }
        
        // Drop the table
        $this->dao->query(sprintf('DROP TABLE IF EXISTS %s', $this->getTable_Avatar()));
    }

    /**
     * Get avatar filename for a user
     * 
     * @param int $user User ID
     * @return string|null Avatar filename or null if not found
     */
    public function getAvatar(int $user): ?string
    {
        $this->dao->select('avatar');
        $this->dao->from($this->getTable_Avatar());
        $this->dao->where('fk_i_user_id', $user);
        
        $result = $this->dao->get();
        
        if ($result === false || $result->numRows() === 0) {
            return null;
        }
        
        $avatar = $result->row();
        
        return !empty($avatar['avatar']) ? $avatar['avatar'] : null;
    }

    /**
     * Insert new avatar record
     * 
     * @param string $avatar Avatar filename
     * @param int $user User ID
     * @return bool True on success, false on failure
     */
    public function insertAvatar(string $avatar, int $user): bool
    {
        $data = [
            'avatar' => $avatar,
            'fk_i_user_id' => $user
        ];
        
        return $this->dao->insert($this->getTable_Avatar(), $data);
    }

    /**
     * Update existing avatar record
     * 
     * @param string $avatar Avatar filename
     * @param int $user User ID
     * @return bool True on success, false on failure
     */
    public function updateAvatar(string $avatar, int $user): bool
    {
        $aSet = ['avatar' => $avatar];
        $aWhere = ['fk_i_user_id' => $user];
        
        return $this->_update($this->getTable_Avatar(), $aSet, $aWhere);
    }

    /**
     * Delete avatar record for a user
     * 
     * @param int $user User ID
     * @return bool True on success, false on failure
     */
    public function deleteAvatar(int $user): bool
    {
        $this->dao->from($this->getTable_Avatar());
        $this->dao->where('fk_i_user_id', $user);
        
        return $this->dao->delete();
    }

    /**
     * Check if user has an avatar
     * 
     * @param int $user User ID
     * @return bool True if user has avatar, false otherwise
     */
    public function hasAvatar(int $user): bool
    {
        $avatar = $this->getAvatar($user);
        return $avatar !== null;
    }

    /**
     * Update database record
     * 
     * @param string $table Table name
     * @param array $values Values to update
     * @param array $where Where conditions
     * @return bool True on success, false on failure
     */
    private function _update(string $table, array $values, array $where): bool
    {
        $this->dao->from($table);
        $this->dao->set($values);
        $this->dao->where($where);
        
        return $this->dao->update();
    }
}