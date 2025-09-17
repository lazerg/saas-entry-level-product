<?php

namespace App\Actions;

use App\Http\Requests\Step1Response;
use Exception;
use Illuminate\Support\Facades\Process;
use Illuminate\Support\Str;

/**
 * @class BuildVideoAction
 * @package App\Actions
 */
class BuildVideoAction
{
    /**
     * @param \App\Actions\ConvertTextToSpeechAction $textToSpeechAction
     * @param \App\Actions\SaveImagesAction $saveImagesAction
     */
    public function __construct(
        protected ConvertTextToSpeechAction $textToSpeechAction,
        protected SaveImagesAction $saveImagesAction
    ) {
        //
    }

    /**
     * @param \App\Http\Requests\Step1Response $data
     * @return string
     * @throws \Spatie\TemporaryDirectory\Exceptions\PathAlreadyExists
     * @throws \Exception
     */
    public function execute(Step1Response $data): string
    {
        $speech = $this->textToSpeechAction->execute($data->speech);
        $images = $this->saveImagesAction->execute($data->images);

        $outputFilename = Str::random(32) . '.mp4';
        $outputPath = public_path('storage/' . $outputFilename);
        $imageCount = count($images);

        $imageDuration = 3;
        $transitionDuration = 1;
        $totalDuration = ($imageCount * $imageDuration) - (($imageCount - 1) * $transitionDuration);

        $imageInputs = '';
        for ($i = 0; $i < $imageCount; $i++) {
            $imageInputs .= "-loop 1 -t {$totalDuration} -i {$images[$i]} ";
        }

        $scaleFilters = '';
        for ($i = 0; $i < $imageCount; $i++) {
            $scaleFilters .= "[{$i}:v]scale=1280:720,setsar=1[v{$i}];";
        }

        $xfadeFilters = '';
        if ($imageCount > 1) {
            $currentLabel = 'v0';
            for ($i = 1; $i < $imageCount; $i++) {
                $nextLabel = "v{$i}";
                $outputLabel = ($i === $imageCount - 1) ? 'vout' : "v0{$i}";
                $offset = (($i - 1) * $imageDuration) + ($imageDuration - $transitionDuration);
                $xfadeFilters .= "[{$currentLabel}][{$nextLabel}]xfade=transition=fade:duration=1:offset={$offset}[{$outputLabel}];";
                $currentLabel = $outputLabel;
            }
        } else {
            $xfadeFilters = "[v0]copy[vout];";
        }

        $filterComplex = $scaleFilters . $xfadeFilters;
        $filterComplex = rtrim($filterComplex, ';');

        $command = "ffmpeg {$imageInputs}-i {$speech} -filter_complex \"{$filterComplex}\" -map \"[vout]\" -map {$imageCount}:a -c:v libx264 -crf 18 -preset veryfast -c:a aac -shortest {$outputPath}";

        $result = Process::run($command);

        if (!$result->successful()) {
            throw new Exception('FFmpeg failed: ' . $result->errorOutput());
        }

        return 'storage/' . $outputFilename;
    }
}
