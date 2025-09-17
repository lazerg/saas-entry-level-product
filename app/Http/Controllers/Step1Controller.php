<?php

namespace App\Http\Controllers;

use App\Http\Requests\Step1Request;
use Illuminate\Http\JsonResponse;

/**
 * @class Step1Controller
 */
class Step1Controller extends Controller
{
    public function __invoke(Step1Request $request): JsonResponse
    {
        return response()->json([
            'name'   => 'Example Product',
            'type'   => 'Bike',
            'images' => [
                'https://example.com/image1.jpg',
                'https://example.com/image2.jpg',
            ],
            'speech' => implode(' ', [
                'lorem ipsum dolor sit amet consectetur adipiscing elit sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.',
                'ut enim ad minim veniam quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.',
                'duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.',
                'excepteur sint occaecat cupidatat non proident sunt in culpa qui officia deserunt mollit anim id est laborum.',
            ]),
        ]);
    }
}
