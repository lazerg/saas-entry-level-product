<?php

namespace App\Http\Requests;

use Spatie\LaravelData\Attributes\Validation\Required;
use Spatie\LaravelData\Attributes\Validation\Url;
use Spatie\LaravelData\Data;

/**
 * @class Step1Request
 */
class Step1Request extends Data
{
    public function __construct(
        #[Required]
        #[Url]
        public string $url
    ) {
        //
    }
}
