<?php

namespace App\Http\Requests;

use Spatie\LaravelData\Data;

class Step1Response extends Data
{
    public function __construct(
        public string $product_name,
        public array $images,
        public string $product_type,
        public string $speech,
    ) {}
}
