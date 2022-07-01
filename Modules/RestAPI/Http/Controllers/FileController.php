<?php

namespace Modules\RestAPI\Http\Controllers;

use Froiden\RestAPI\ApiResponse;
use App\Helper\Files;
use Froiden\RestAPI\Exceptions\ApiException;
use Modules\RestAPI\Http\Requests\File\FileShowRequest;
use Modules\RestAPI\Http\Requests\File\FileStoreRequest;

class FileController extends ApiBaseController
{

    public function upload(FileStoreRequest $request)
    {
        $uploadedFile = $request->file;
        $folder = $request->type;
        try {
            $newName = Files::uploadLocalOrS3($uploadedFile, $folder);
        } catch (\Exception $e) {
            ApiResponse::make(null, [
                'name' => $e,
            ]);
        }

        return ApiResponse::make(null, [
            'name' => $newName,
            'url' => asset_url($folder . '/' . $newName),
            'download_url' => route('file.show.v1', ['name' => $newName])
        ]);
    }

    public function download(FileShowRequest $request, $name)
    {
        $folder = request()->folder;
        if (config('filesystems.default') == 's3') {
            $size = \File::size(asset_url_local_s3($folder.'/'.$name));
            $mime = \File::mimeType(asset_url_local_s3($folder.'/'.$name));
        } else {
            $size = \File::size('user-uploads/'.$folder.'/'.$name);
            $mime = \File::mimeType('user-uploads/'.$folder.'/'.$name);
        }

        $fs = \Storage::disk(config('filesystems.default'))->getDriver();
        $stream = $fs->readStream($folder . '/' . $name);

        return \Response::stream(function () use ($stream) {
            fpassthru($stream);
        }, 200, [
            'Content-Type' => $mime,
            'Content-Length' => $size,
            'Content-Disposition' => 'attachment; filename="' . $name . '"',
        ]);
    }

    /**
     * Generate a new unique file name
     * @param $currentFileName
     * @return string
     */
    public static function generateNewFileName($currentFileName)
    {
        $ext = strtolower(\File::extension($currentFileName));

        $newName = md5(microtime());

        if ($ext === '') {
            return $newName;
        } else {
            return $newName . '.' . $ext;
        }
    }
}
