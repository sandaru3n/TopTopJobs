<?php

namespace App\Models;

use CodeIgniter\Model;

class SavedJobModel extends Model
{
    protected $table            = 'saved_jobs';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields   = true;
    protected $allowedFields    = [
        'user_id',
        'job_id',
        'created_at'
    ];

    // Dates - Handle created_at manually, no updated_at
    protected $useTimestamps = false; // Disable automatic timestamps
    protected $dateFormat    = 'datetime';

    // Validation
    protected $validationRules = [
        'user_id' => 'required|integer',
        'job_id' => 'required|integer',
    ];

    protected $validationMessages = [];

    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    /**
     * Check if job is saved by user
     */
    public function isSaved(int $userId, int $jobId): bool
    {
        return $this->where('user_id', $userId)
                    ->where('job_id', $jobId)
                    ->first() !== null;
    }

    /**
     * Save a job for a user
     */
    public function saveJob(int $userId, int $jobId): bool
    {
        // Check if already saved
        if ($this->isSaved($userId, $jobId)) {
            return true; // Already saved
        }

        $data = [
            'user_id' => $userId,
            'job_id' => $jobId,
            'created_at' => date('Y-m-d H:i:s') // Manually set created_at
        ];

        try {
            // Temporarily skip validation to avoid issues
            $this->skipValidation(true);
            $result = $this->insert($data);
            $this->skipValidation(false);
            
            if ($result === false) {
                $errors = $this->errors();
                if (!empty($errors)) {
                    log_message('error', 'Save job validation errors: ' . json_encode($errors));
                }
                return false;
            }
            
            return true;
        } catch (\Exception $e) {
            log_message('error', 'Save job error: ' . $e->getMessage());
            log_message('error', 'Save job stack trace: ' . $e->getTraceAsString());
            return false;
        }
    }

    /**
     * Unsave a job for a user
     */
    public function unsaveJob(int $userId, int $jobId): bool
    {
        try {
            $result = $this->where('user_id', $userId)
                        ->where('job_id', $jobId)
                        ->delete();
            return $result !== false;
        } catch (\Exception $e) {
            log_message('error', 'Unsave job error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Get all saved jobs for a user
     */
    public function getSavedJobs(int $userId): array
    {
        return $this->where('user_id', $userId)
                    ->orderBy('created_at', 'DESC')
                    ->findAll();
    }
}

