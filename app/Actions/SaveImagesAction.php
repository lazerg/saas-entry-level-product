<?php

namespace App\Actions;

/**
 * @class SaveImagesAction
 * @package App\Actions
 */
class SaveImagesAction
{
    /**
     * @param array $ofImages
     * @return array
     * @throws \Spatie\TemporaryDirectory\Exceptions\PathAlreadyExists
     */
    public function execute(array $ofImages): array
    {
        $images = [];

        foreach ($ofImages as $key => $image) {
            $content = file_get_contents($image);
            $path = temp_path('.jpg');

            file_put_contents($path, $content);
            $images[] = $path;
        }

        return $images;
    }
}
