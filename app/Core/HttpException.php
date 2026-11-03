<?php

namespace App\Core;

use RuntimeException;

class HttpException extends RuntimeException
{
    private int $statusCode;

    public function __construct(int $statusCode, string $message = '')
    {
        parent::__construct($message ?: "HTTP Error {$statusCode}", $statusCode);
        $this->statusCode = $statusCode;
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }
}
