<?php

namespace App\Actions;

use App\Http\Requests\Step1Request;
use OpenAI\Laravel\Facades\OpenAI;
use OpenAI\Responses\Responses\CreateResponse;

/**
 * @class ExtractProductDataAction
 * @package App\Actions
 * @see https://platform.openai.com/chat/edit?prompt=pmpt_68ca927bdc6c8194ba32bf9c048b77b90ff4ead9a78a2586
 */
class ExtractProductDataAction
{
    /**
     * @param \App\Http\Requests\Step1Request $data
     * @return array<string, mixed>
     */
    public function execute(Step1Request $data): array
    {
        $response = OpenAI::responses()->create([
            'prompt' => [
                'id'      => 'pmpt_68ca927bdc6c8194ba32bf9c048b77b90ff4ead9a78a2586',
            ],
            'input' => [
                [
                    'role' => 'user',
                    'content' => "Analyze {$data->url}",
                ],
            ],
        ]);

        $response = OpenAI::responses()->create([
            'prompt' => [
                'id'      => 'pmpt_68ca996d74048194a07e276f6d96dc4200a5e885e95ac01c',
            ],
            'input' => [
                [
                    'role' => 'user',
                    'content' => "Convert this to json: " . $response->outputText,
                ],
            ],
        ]);

        return json_decode($response->outputText, true);
    }
}
