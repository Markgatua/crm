<?php

namespace App\Actions\Utils;

use Illuminate\Http\JsonResponse;
use Lorisleiva\Actions\Concerns\AsAction;
use Response;

class RespondWithError
{
    use AsAction;

    public static string $ERROR_CODE_UNAUTHORIZED = "ERROR_CODE_UNAUTHORIZED";
    public static string $INTERNAL_SERVER_ERROR = "INTERNAL_SERVER_ERROR";
    public static string $ERROR_CODE_ACCESS_DENIED = "ERROR_CODE_ACCESS_DENIED";
    public static string $ERROR_CODE_NOT_FOUND = "ERROR_CODE_NOT_FOUND";
    public static string $ERROR_CODE_BAD_REQUEST = "ERROR_CODE_BAD_REQUEST";
    public static string $ERROR_CODE_VALIDATION = "ERROR_CODE_VALIDATION";


    public function handle(
        string $code = null,
        string $message = "Internal Server Error",
        array $payload = [],
        string $recommendation = "",
        bool $error = true,
        int $status = 500
    ): JsonResponse {
        if (!$code) {
            $code = self::$INTERNAL_SERVER_ERROR;
        }
        if ($code && $status === 500) {
            $status = match ($code) {
                self::$ERROR_CODE_ACCESS_DENIED => 403,
                self::$ERROR_CODE_UNAUTHORIZED => 401,
                self::$ERROR_CODE_NOT_FOUND => 404,
                self::$ERROR_CODE_BAD_REQUEST => 400,
                self::$ERROR_CODE_VALIDATION => 422,
                default => 500
            };
        }

        return Response::json([
            'error' => $error,
            'code' => $code,
            'message' => $message,
            'recommendation' => $recommendation,
            'payload' => $payload
        ], $status);
    }
}
