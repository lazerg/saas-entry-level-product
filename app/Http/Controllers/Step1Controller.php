<?php

namespace App\Http\Controllers;

use App\Actions\ExtractProductDataAction;
use App\Http\Requests\Step1Request;
use Illuminate\Http\JsonResponse;

/**
 * @class Step1Controller
 */
class Step1Controller extends Controller
{
    public function __invoke(Step1Request $request, ExtractProductDataAction $action): JsonResponse
    {
        return response()->json(
            $action->execute($request)
        );
    }
}
