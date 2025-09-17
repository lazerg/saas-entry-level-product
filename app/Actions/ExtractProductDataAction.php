<?php

namespace App\Actions;

use App\Http\Requests\Step1Request;
use App\Http\Requests\Step1Response;
use OpenAI\Laravel\Facades\OpenAI;

/**
 * @class ExtractProductDataAction
 *
 * @see https://platform.openai.com/chat/edit?prompt=pmpt_68ca927bdc6c8194ba32bf9c048b77b90ff4ead9a78a2586
 * @see https://platform.openai.com/chat/edit?prompt=pmpt_68ca996d74048194a07e276f6d96dc4200a5e885e95ac01c
 */
class ExtractProductDataAction
{
    public function execute(Step1Request $data): Step1Response
    {
        if ($data->url === 'https://www.brompton.com/p/817/c-line-explore-23') {
            return $this->cacheResponse();
        }

        $analysisResult = $this->ask(
            'pmpt_68ca927bdc6c8194ba32bf9c048b77b90ff4ead9a78a2586',
            'Extract Product Data: ' . $data->url,
        );

        $jsonResult = $this->ask(
            'pmpt_68ca996d74048194a07e276f6d96dc4200a5e885e95ac01c',
            'Convert this to json: ' . $analysisResult
        );

        return Step1Response::from(json_decode($jsonResult, true));
    }

    private function ask(string $promptId, string $content): string
    {
        return OpenAI::responses()->create([
            'prompt' => [
                'id' => $promptId,
            ],
            'input'  => [
                [
                    'role'    => 'user',
                    'content' => $content,
                ],
            ],
        ])->outputText;
    }

    /**
     * @return \App\Http\Requests\Step1Response
     */
    private function cacheResponse(): Step1Response
    {
        return Step1Response::from([
            'product_name' => 'C Line - 6-speed',
            'images'       => [
                'https://cdn11.bigcommerce.com/s-y6rxtt0m81/products/817/images/59916/S6L0AAB06B000000B0050125AAAA00_1__90853__27505.1756900523.1280.1280.jpg?c=1',
                'https://cdn11.bigcommerce.com/s-y6rxtt0m81/products/817/images/42602/S6L0AAB06B000000B0050125AAAA00_2__02382.1756900522.1280.1280.jpg?c=1',
                'https://cdn11.bigcommerce.com/s-y6rxtt0m81/products/817/images/42591/S6L0AAB06B000000B0050125AAAA00_3__38484.1756900522.1280.1280.jpg?c=1',
                'https://cdn11.bigcommerce.com/s-y6rxtt0m81/products/817/images/42600/S6L0AAB06B000000B0050125AAAA00_4__87494.1756900522.1280.1280.jpg?c=1',
                'https://cdn11.bigcommerce.com/s-y6rxtt0m81/products/817/images/42590/S6L0AAB06B000000B0050125AAAA00_5__76877.1756900522.1280.1280.jpg?c=1',
                'https://cdn11.bigcommerce.com/s-y6rxtt0m81/products/817/images/42589/S6L0AAB06B000000B0050125AAAA00_6__38412.1756900522.1280.1280.jpg?c=1',
                'https://cdn11.bigcommerce.com/s-y6rxtt0m81/products/817/images/42601/S6L0AAB06B000000B0050125AAAA00_7__34740.1756900522.1280.1280.jpg?c=1',
                'https://cdn11.bigcommerce.com/s-y6rxtt0m81/products/817/images/42582/S6L0AAB06B000000B0050125AAAA00_8__46372.1756900522.1280.1280.jpg?c=1',
                'https://cdn11.bigcommerce.com/s-y6rxtt0m81/products/817/images/43991/S6L0AAB06B000000B0050125AAAA00_9__20186.1756900522.1280.1280.jpg?c=1',
            ],
            'product_type' => 'folding bicycle',
            'speech'       => 'Meet the Brompton C Line 6-speed—your compact, go-anywhere companion for city commutes and weekend adventures. Fast to fold, smooth to ride, and built to last.',
        ]);
    }
}
