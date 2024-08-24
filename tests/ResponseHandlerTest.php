<?php


use Atyalpa\Http\ResponseHandler;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

#[\PHPUnit\Framework\Attributes\CoversClass(ResponseHandler::class)]
class ResponseHandlerTest extends TestCase
{
    private ResponseHandler $responseHandler;
    protected function setUp(): void
    {
        $this->responseHandler = new ResponseHandler();
    }

    #[Test]
    public function it_returns_response_status_code(): void
    {
        $response = new ResponseHandler(200);

        $this->assertSame(200, $response->getStatusCode());
    }

    #[Test]
    public function it_sets_response_status_code(): void
    {
        $this->responseHandler->withStatus(404);

        $this->assertSame(404, $this->responseHandler->getStatusCode());
    }

    #[Test]
    public function it_returns_status_code_reason_phrase(): void
    {
        $this->responseHandler->withStatus(404, 'Some reason phrase');

        $this->assertSame('Some reason phrase', $this->responseHandler->getReasonPhrase());
    }

    #[Test]
    public function it_sets_protocol_version(): void
    {
        $this->responseHandler->withProtocolVersion('1.0');

        $this->assertSame('1.0', $this->responseHandler->getProtocolVersion());
    }

    #[Test]
    public function it_sets_header(): void
    {
        $this->responseHandler->withHeader('Content-Type', 'text/html');

        $this->assertSame(['text/html'], $this->responseHandler->getHeader('Content-Type'));
        $this->assertSame('text/html', $this->responseHandler->getHeaderLine('Content-Type'));
    }

    #[Test]
    public function it_sets_headers(): void
    {
        $this->responseHandler->withHeaders([
            'Content-Type' => 'text/html',
            'Content-Length' => '100',
        ]);

        $this->assertSame(
            ['Content-Type' => ['text/html'], 'Content-Length' => ['100']],
            $this->responseHandler->getHeaders()
        );
    }

    #[Test]
    public function it_returns_true_if_the_header_is_set(): void
    {
        $this->responseHandler->withHeader('Content-Type', 'text/html');

        $this->assertTrue($this->responseHandler->hasHeader('Content-Type'));
    }

    #[Test]
    public function it_returns_false_if_the_header_is_not_set(): void
    {
        $this->responseHandler->withHeader('Content-Type', 'text/html');

        $this->assertFalse($this->responseHandler->hasHeader('Some-Random-Header'));
    }

    #[Test]
    public function it_append_header(): void
    {
        $this->responseHandler->withHeader('Content-Type', 'text/html');
        $this->responseHandler->withAddedHeader('Content-Type', 'text/plain');

        $this->assertSame(
            ['text/html', 'text/plain'],
            $this->responseHandler->getHeader('Content-Type')
        );
    }

    #[Test]
    public function it_removes_header(): void
    {
        $this->responseHandler->withHeader('Content-Type', 'text/html');
        $this->assertSame(['text/html'], $this->responseHandler->getHeader('Content-Type'));

        $this->responseHandler->withoutHeader('Content-Type');
        $this->assertSame([], $this->responseHandler->getHeader('Content-Type'));
    }

    /**
     * @throws \PHPUnit\Framework\MockObject\Exception
     */
    #[Test]
    public function it_sets_body(): void
    {
        $bodyMock = $this->createMock(StreamInterface::class);

        $this->responseHandler->withBody($bodyMock);

        $this->assertSame($bodyMock, $this->responseHandler->getBody());
    }

    #[Test]
    public function it_sets_body_with_json_content(): void
    {
        $body = ['foo' => 'bar'];
        $responseHandler = $this->responseHandler->json($body);

        $this->assertSame(json_decode($responseHandler->getBody()->getContents(), true), $body);
    }

    #[Test]
    public function it_returns_response(): void
    {
        $this->assertInstanceOf(ResponseInterface::class, $this->responseHandler->send());
    }
}
