<?php

namespace Utils;

use Error;

/**
 * Files Handler Class
 *
 * Save files in your server.
 *
 * 
 */
class Filemanager
{

    /**
     * Save a image on your server files
     *
     * @param string $dir File save path
     * @param mixed $image File you want store
     * @return mixed
     */
    public function saveImage(string $dir, mixed $image): string | Error
    {
        try {
            $target = $dir . uniqid() . '.jpg';
            $imageType = exif_imagetype($image['tmp_name']);
            $allowedTypes = array(
                IMAGETYPE_GIF,
                IMAGETYPE_JPEG,
                IMAGETYPE_PNG,
                IMAGETYPE_BMP
            );
            if (!in_array($imageType, $allowedTypes)) {
                throw new Error('Invalid file type');
            }

            move_uploaded_file($image['tmp_name'], $target);
            return $target;
        } catch (\Throwable $th) {
            $this->saveFile('public/files/unknown/', $image);
            return throw new Error('Invalid file saved in unknown files');
        }
    }

    /**
     * Save a zip file on your server files
     *
     * @param string $dir File save path
     * @param mixed $file File you want store
     * @return mixed
     */
    public function saveZip(string $dir, mixed $file): string | Error
    {
        try {
            $target = $dir . uniqid() . '.zip';
            $fileType = mime_content_type($file['tmp_name']);
            $allowedTypes = array(
                'application/zip',
                'application/x-zip-compressed',
                'application/octet-stream',
                'application/x-rar-compressed',
                'application/x-rar'
            );
    
            if (!in_array($fileType, $allowedTypes)) {
                throw new Error('Invalid file type');
            }

            move_uploaded_file($file['tmp_name'], $target);
            return $target;
        } catch (\Throwable $th) {
            $this->saveFile('public/files/unknown/', $file);
            return throw new Error('Invalid file saved in unknown files');
        }
    }

    /**
     * Delete an specific file from your server 
     *
     * @param string $dir Where file is locted
     * @return bool
     */
    public function removeFile(string $dir): bool | Error
    {
        try {
            if (file_exists($dir) && unlink($dir)) {
                return true;
            } else {
                throw new Error('Invalid path or error trying to remove file');
            }
        } catch (\Throwable $th) {
            return throw new Error('Invalid path or error trying to remove file');
        }
    }

    /**
     * Save a file you can't recognize on secret path
     *
     * @param string $dir File save path
     * @param mixed $file File you want store
     * @return bool
     */
    public function saveFile(string $dir, mixed $file): bool
    {
        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $target = $dir . uniqid() . "." . $extension;
        return move_uploaded_file($file['tmp_name'], $target);
    }
}
