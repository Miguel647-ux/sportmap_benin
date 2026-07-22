<?php

namespace App\Services;

use Cloudinary\Cloudinary;

class CloudinaryService
{
    protected $cloudinary;

    public function __construct()
    {
        $this->cloudinary = new Cloudinary([
            'cloud' => [
                'cloud_name' => env('CLOUDINARY_CLOUD_NAME'),
                'api_key'    => env('CLOUDINARY_API_KEY'),
                'api_secret' => env('CLOUDINARY_API_SECRET'),
            ],
        ]);
    }

    public function upload($file, $folder = 'sportmap')
    {
        $uploaded = $this->cloudinary->uploadApi()->upload($file, [
            'folder' => $folder,
        ]);

        return $uploaded['secure_url'];
    }

    public function uploadVideo($file, $folder = 'sportmap/videos')
    {
        $uploaded = $this->cloudinary->uploadApi()->upload($file, [
            'folder' => $folder,
            'resource_type' => 'video',
        ]);

        return $uploaded['secure_url'];
    }
}