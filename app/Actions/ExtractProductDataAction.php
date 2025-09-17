<?php

namespace App\Actions;

use App\Http\Requests\Step1Request;
use OpenAI\Laravel\Facades\OpenAI;

/**
 * @class ExtractProductDataAction
 * @package App\Actions
 *
 * @see https://platform.openai.com/chat/edit?prompt=pmpt_68ca927bdc6c8194ba32bf9c048b77b90ff4ead9a78a2586
 * @see https://platform.openai.com/chat/edit?prompt=pmpt_68ca996d74048194a07e276f6d96dc4200a5e885e95ac01c
 */
class ExtractProductDataAction
{
    /**
     * @return array<string, mixed>
     */
    public function execute(Step1Request $data): array
    {
        $analysisResult = $this->ask(
            'pmpt_68ca927bdc6c8194ba32bf9c048b77b90ff4ead9a78a2586',
            'Extract Product Data: ' . $data->url,
        );

        $jsonResult = $this->ask(
            'pmpt_68ca996d74048194a07e276f6d96dc4200a5e885e95ac01c',
            'Convert this to json: ' . $analysisResult
        );

        return json_decode($jsonResult, true);
    }

    /**
     * @param string $promptId
     * @param string $content
     * @return string
     */
    private function ask(string $promptId, string $content): string
    {
        return OpenAI::responses()->create([
            'prompt' => [
                'id' => $promptId,
            ],
            'input' => [
                [
                    'role'    => 'user',
                    'content' => $content,
                ],
            ],
        ])->outputText;
    }
}
