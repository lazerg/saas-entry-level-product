<?php

namespace App\Actions;

use Illuminate\Support\Facades\File;
use OpenAI\Laravel\Facades\OpenAI;

/**
 * @class ConvertTextToSpeechAction
 * @package App\Actions
 */
class ConvertTextToSpeechAction
{
    /**
     * @param string $text
     * @return string
     * @throws \Spatie\TemporaryDirectory\Exceptions\PathAlreadyExists
     */
    public function execute(string $text): string
    {
        $response = OpenAI::audio()->speech([
            'model'           => 'tts-1',
            'voice'           => 'alloy',
            'input'           => $text,
            'response_format' => 'mp3',
            'speed'           => 1.0,
        ]);

        return tap(
            temp_path('mp3'),
            fn(string $path) => File::put($path, $response)
        );
    }
}
