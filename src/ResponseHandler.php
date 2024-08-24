<?php

declare(strict_types=1);

namespace Atyalpa\Http;

use Fig\Http\Message\StatusCodeInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;
use React\Http\Message\Response;

class ResponseHandler implements ResponseInterface
{
    private Response $response;

    public function __construct(
        int $statusCode = StatusCodeInterface::STATUS_OK,
        array $headers = [],
        string $body = ''
    ) {
        $this->response = new Response($statusCode, $headers, $body);
    }

    public function getStatusCode(): int
    {
        return $this->response->getStatusCode();
    }

    public function withStatus(int $code, string $reasonPhrase = ''): static
    {
        $this->response = $this->response->withStatus($code, $reasonPhrase);

        return $this;
    }

    public function getReasonPhrase(): string
    {
        return $this->response->getReasonPhrase();
    }

    public function getProtocolVersion(): string
    {
        return $this->response->getProtocolVersion();
    }

    public function withProtocolVersion(string $version): static
    {
        $this->response = $this->response->withProtocolVersion($version);

        return $this;
    }

    public function getHeaders(): array
    {
        return $this->response->getHeaders();
    }

    public function hasHeader(string $name): bool
    {
        return $this->response->hasHeader($name);
    }

    public function getHeader(string $name): array
    {
        return $this->response->getHeader($name);
    }

    public function getHeaderLine(string $name): string
    {
        return $this->response->getHeaderLine($name);
    }

    public function withHeader(string $name, $value): static
    {
        $this->response = $this->response->withHeader($name, $value);

        return $this;
    }

    public function withAddedHeader(string $name, $value): static
    {
        $this->response = $this->response->withAddedHeader($name, $value);

        return $this;
    }

    public function withoutHeader(string $name): static
    {
        $this->response = $this->response->withoutHeader($name);

        return $this;
    }

    public function getBody(): StreamInterface
    {
        return $this->response->getBody();
    }

    public function withBody(StreamInterface $body): static
    {
        $this->response = $this->response->withBody($body);

        return $this;
    }

    public function withHeaders(array $headers): static
    {
        foreach ($headers as $name => $value) {
            $this->withHeader($name, $value);
        }

        return $this;
    }

    public function json(array $body): static
    {
        $this->response = $this->response->json($body);

        return $this;
    }

    public function send(): ResponseInterface
    {
        return $this->response;
    }
}
