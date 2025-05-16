<?php
require 'vendor/autoload.php';
require_once 'config.php';

use Aws\S3\S3Client;
use Aws\Exception\AwsException;

class S3Helper {
    private $s3;
    
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
    
    public function uploadFile($filePath, $fileName) {
        try {
            $result = $this->s3->putObject([
                'Bucket' => S3_BUCKET,
                'Key'    => 'uploads/' . $fileName,
                'SourceFile' => $filePath,
                'ACL'    => 'private'
            ]);
            return $result['ObjectURL'];
        } catch (AwsException $e) {
            error_log($e->getMessage());
            return false;
        }
    }
    
    public function deleteFile($s3Key) {
        try {
            $this->s3->deleteObject([
                'Bucket' => S3_BUCKET,
                'Key'    => $s3Key
            ]);
            return true;
        } catch (AwsException $e) {
            error_log($e->getMessage());
            return false;
        }
    }
    
    public function getFileUrl($s3Key) {
        try {
            $cmd = $this->s3->getCommand('GetObject', [
                'Bucket' => S3_BUCKET,
                'Key'    => $s3Key
            ]);
            
            $request = $this->s3->createPresignedRequest($cmd, '+15 minutes');
            return (string) $request->getUri();
        } catch (AwsException $e) {
            error_log($e->getMessage());
            return false;
        }
    }
}
?> 