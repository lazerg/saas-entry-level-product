<?php

namespace App\Http\Controllers;

use App\Actions\BuildVideoAction;
use App\Http\Requests\Step1Response;
use Illuminate\Http\JsonResponse;

/**
 * @class Step2Controller
 */
class Step2Controller extends Controller
{
    public function __invoke(
        Step1Response $request,
        BuildVideoAction $action
    ): JsonResponse {
        $video = $action->execute($request);

        return response()->json([
            'video' => $video,
        ]);
    }
}
