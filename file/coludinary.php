<?php
require __DIR__ . '/../vendor/autoload.php';

    
use Cloudinary\Configuration\Configuration;
use Cloudinary\Api\Upload\UploadApi;
use Cloudinary\Api\Admin\AdminApi;
use Cloudinary\Tag\ImageTag;
use Cloudinary\Transformation\Background;
use Cloudinary\Transformation\Resize;

Configuration::instance('cloudinary://795322159246418:ZXuOcmrzKYLbX9XL4jxPG-zopeQ@derf19wfk?secure=true');

 function uploadToCloudinary($filePath, $publicId, $folder)
{
    $uploadApi = new UploadApi();
    try {
        $uploadResponse = $uploadApi->upload($filePath, [
            'public_id' => $publicId,
            'overwrite' => true,
            'folder' => $folder
        ]);
        echo print_r($uploadResponse['public_id']);
        return $uploadResponse['secure_url'] ?? null;
    } catch (Exception $e) {
        error_log("Cloudinary upload error: " . $e->getMessage());
        return null;
    }
}

function deleteFromCloudinary($publicId)
{
    $uploadApi = new UploadApi();
    try {
        $deleteResponse = $uploadApi->destroy($publicId);
        return $deleteResponse['result'] === 'ok';
    } catch (Exception $e) {
        error_log("Cloudinary delete error: " . $e->getMessage());
        return false;
    }
}