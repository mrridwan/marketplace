<?php

namespace App\Services;

use Illuminate\Support\Str;
use App\Services\UploadService;

class VideoService
{
    public function create(object $request)
    {
        $data           = $request->all();
        $data['slug']   = Str::slug($request->title, '-');

        if ($request->hasFile('file_name')) {
            $uploadService = new UploadService();
            $data['file_name'] = $uploadService->uploadToPublic($request, 'file_name', 'category_video');
        }

        return $data;
    }


    public function update(object $request)
    {
        $data           = $request->all();
        $data['slug']   = Str::slug($request->title, '-');
        return $data;
    }
}
