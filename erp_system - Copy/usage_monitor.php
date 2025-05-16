<?php
require_once 'config.php';
require 'vendor/autoload.php';

use Aws\S3\S3Client;
use Aws\CloudWatch\CloudWatchClient;

class UsageMonitor {
    private $s3;
    private static $usageFile = 'aws_usage.json';
    
    public function __construct() {
        $this->s3 = new S3Client([
            'version' => 'latest',
            'region'  => AWS_REGION,
            'credentials' => [
                'key'    => AWS_ACCESS_KEY_ID,
                'secret' => AWS_SECRET_ACCESS_KEY,
            ]
        ]);
    }
    
    public function checkStorageLimit($fileSize) {
        // Get current S3 usage
        $usage = $this->getCurrentUsage();
        
        // Check if adding this file would exceed free tier
        if (($usage['storage'] + $fileSize) > S3_FREE_TIER_LIMIT) {
            return false;
        }
        
        // Check monthly PUT requests
        if ($usage['puts'] >= MONTHLY_PUT_LIMIT) {
            return false;
        }
        
        return true;
    }
    
    public function trackUpload($fileSize) {
        $usage = $this->getCurrentUsage();
        $usage['storage'] += $fileSize;
        $usage['puts']++;
        $this->saveUsage($usage);
    }
    
    public function trackDownload() {
        $usage = $this->getCurrentUsage();
        $usage['gets']++;
        $this->saveUsage($usage);
    }
    
    private function getCurrentUsage() {
        if (file_exists(self::$usageFile)) {
            $data = json_decode(file_get_contents(self::$usageFile), true);
            
            // Reset monthly counters if it's a new month
            $lastUpdate = new DateTime($data['last_update']);
            $now = new DateTime();
            if ($lastUpdate->format('Y-m') != $now->format('Y-m')) {
                $data['puts'] = 0;
                $data['gets'] = 0;
            }
            
            return $data;
        }
        
        // Initial usage data
        return [
            'storage' => 0,
            'puts' => 0,
            'gets' => 0,
            'last_update' => date('Y-m-d H:i:s')
        ];
    }
    
    private function saveUsage($usage) {
        $usage['last_update'] = date('Y-m-d H:i:s');
        file_put_contents(self::$usageFile, json_encode($usage));
    }
    
    public function getUsageStats() {
        $usage = $this->getCurrentUsage();
        return [
            'storage_used_gb' => round($usage['storage'] / (1024 * 1024 * 1024), 2),
            'storage_limit_gb' => 5,
            'puts_used' => $usage['puts'],
            'puts_limit' => MONTHLY_PUT_LIMIT,
            'gets_used' => $usage['gets'],
            'gets_limit' => MONTHLY_GET_LIMIT,
            'last_update' => $usage['last_update']
        ];
    }
}
?> 