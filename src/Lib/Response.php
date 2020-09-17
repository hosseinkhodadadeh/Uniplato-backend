<?php

namespace App\Lib;


class Response
{
    public const HTTP_STATUS_OK = 200;
    public const HTTP_STATUS_NOT_Found = 404;
    public const HTTP_STATUS_INTERNAL = 501;
    public const STATUS_DESCRIPTION = [
        self::HTTP_STATUS_OK => 'OK',
        self::HTTP_STATUS_NOT_Found => 'Not Found',
        self::HTTP_STATUS_INTERNAL => 'Internal Server Error',

    ];

    protected array $content;
    protected int $statusCode;
    protected array $headers = [];

    public function __construct(array $content = [], int $statusCode = self::HTTP_STATUS_OK, $headers = [])
    {
        $this->content = $content;
        $this->statusCode = $statusCode;
        $this->headers = $headers;
        $this->headers[] = 'Content-Type: Application/json';
    }

    public function send(): void
    {
        $this->sendHeaders();
        echo json_encode($this->content);

    }

    protected function sendHeaders(): void
    {
        header('HTTP/1.0 ' . $this->statusCode . ' ' . self::STATUS_DESCRIPTION[$this->statusCode]);
        foreach ($this->headers as $header) {
            header($header);
        }
    }

    public static function createNotFound(): self
    {
        return new self(
            ['status' => self::HTTP_STATUS_NOT_Found],
            self::HTTP_STATUS_NOT_Found
        );
    }
}