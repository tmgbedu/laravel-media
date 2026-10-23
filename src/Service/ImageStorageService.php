<?php

namespace Aammui\LaravelMedia\Service;

use Aammui\LaravelMedia\Enum\Responsive;
use Aammui\LaravelMedia\Service\Interfaces\FileStorageInterface;
use Intervention\Image\ImageManager;
use Illuminate\Http\File;
use Illuminate\Http\UploadedFile;

/**
 * Class ImageStorageService
 *
 * @package Aammui\LaravelMedia\Service
 */
class ImageStorageService extends BaseStorageService implements FileStorageInterface
{
    /**
     * @param File|UploadedFile $file
     * @param string            $disk
     *
     * @return mixed
     * @todo implement type check with minimum PHP 8
     */
    public function store($file, string $disk): array
    {
        return [
            "url" => $this->prepareURL($file, $disk),
        ];
    }

    /**
     * @param File|UploadedFile $file
     * @param                   $key
     * @param                   $path
     * @param                   $disk
     */
    private function resizeImage($file, $key, $path, $disk)
    {
        $image = ImageManager::gd()->read($file->getRealPath());

        $image->scale(Responsive::getWidthFromConfig($key), Responsive::getHeightFromConfig($key));

        parent::upload($disk, $path, (string) $image->encode());
    }

    /**
     * @param File|UploadedFile $file
     * @param string            $disk
     *
     * @return array
     */
    private function prepareURL($file, string $disk): array
    {
        $sizes = Responsive::getConfig();

        return collect($sizes)->map(
            function ($value, $key) use ($file, $disk) {

                $path = $key."/".$file->hashName();;

                $this->resizeImage($file, $key, $path, $disk);

                return "/".$path;
            }
        )->toArray();
    }
}
