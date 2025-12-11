<?php

namespace App\Models;

use CodeIgniter\Model;

class SiteSettingsModel extends Model
{
    protected $table            = 'site_settings';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'setting_key',
        'setting_value',
        'setting_type'
    ];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    protected $validationRules = [
        'setting_key' => 'required|max_length[100]',
        'setting_type' => 'permit_empty|in_list[text,number,boolean,file,json]',
    ];

    protected $validationMessages = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    /**
     * Get setting value by key
     */
    public function getSetting(string $key, $default = null)
    {
        $setting = $this->where('setting_key', $key)->first();
        return $setting ? $setting['setting_value'] : $default;
    }

    /**
     * Set setting value by key
     */
    public function setSetting(string $key, $value, string $type = 'text'): bool
    {
        $existing = $this->where('setting_key', $key)->first();
        
        if ($existing) {
            return $this->update($existing['id'], [
                'setting_value' => $value,
                'setting_type' => $type
            ]);
        } else {
            return $this->insert([
                'setting_key' => $key,
                'setting_value' => $value,
                'setting_type' => $type
            ]);
        }
    }

    /**
     * Get all settings as key-value array
     */
    public function getAllSettings(): array
    {
        $settings = $this->findAll();
        $result = [];
        foreach ($settings as $setting) {
            $result[$setting['setting_key']] = $setting['setting_value'];
        }
        return $result;
    }
}

