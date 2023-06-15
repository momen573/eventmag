<?php

namespace App\Traits;

use Illuminate\Support\Facades\Storage;

trait UploadMedia
{
    protected function singleUpload($file, $folder): string
    {
        $full_path = config('uploadMedia.storage_upload_path').'/'.$folder.'/';
        $filename = makeToken(50);
        Storage::putFileAs($full_path, $file, $filename.'.'.$file->getClientOriginalExtension());

        return $folder.'/'.$filename.'.'.$file->getClientOriginalExtension();
    }

    protected function multipleUpload($files, $folder): array
    {
        $original_path = null;

        $full_path = config('uploadMedia.storage_upload_path').'/'.$folder.'/';

        foreach ($files as $file) {
            $filename = makeToken(50);
            Storage::putFileAs($full_path, $file, $filename.'.'.$file->getClientOriginalExtension());
            $original_path[] = $folder.'/'.$filename.'.'.$file->getClientOriginalExtension();
        }

        return $original_path;
    }

    protected function deleteFile($file): void
    {
        if (Storage::exists(config('uploadMedia.storage_upload_path').'/'.$file)) {
            Storage::delete(config('uploadMedia.storage_upload_path').'/'.$file);
        }
    }
}
