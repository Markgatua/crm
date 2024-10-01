<?php

namespace App\Actions\Utils;

use Illuminate\Http\JsonResponse;
use Lorisleiva\Actions\Concerns\AsAction;
use Response;

class RespondWithSuccess
{
    use AsAction;

    public function handle(mixed $body = [], int $status = 200): JsonResponse
    {

        return Response::json($body, $status);
        // ...
    }
}