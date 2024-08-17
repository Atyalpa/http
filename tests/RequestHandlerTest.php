<?php

use Atyalpa\Http\RequestHandler;
use http\Encoding\Stream;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\Test;

use PHPUnit\Framework\MockObject\MockObject;
use Psr\Http\Message\ServerRequestInterface;

use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\UriInterface;
use function PHPUnit\Framework\assertEquals;

#[\PHPUnit\Framework\Attributes\CoversClass(RequestHandler::class)]
class RequestHandlerTest extends TestCase
{
    private MockObject $serverRequestMock;
    private RequestHandler $requestHandler;

    /**
     * @throws \PHPUnit\Framework\MockObject\Exception
     */
    protected function setUp(): void
    {
        $this->serverRequestMock = $this->createMock(ServerRequestInterface::class);
        $this->requestHandler = new RequestHandler($this->serverRequestMock);
    }

    #[Test]
    public function it_returns_protocol_version(): void
    {
        $this->serverRequestMock->expects($this->once())
            ->method('getProtocolVersion')
            ->willReturn('1.0');

        $this->assertEquals('1.0', $this->requestHandler->getProtocolVersion());
    }

    #[Test]
    public function it_adds_protocol_version_header(): void
    {
        $this->serverRequestMock->expects($this->once())
            ->method('withProtocolVersion')
            ->with('1.0')
            ->willReturnSelf();

        $this->requestHandler->withProtocolVersion('1.0');
    }

    #[Test]
    public function it_returns_headers(): void
    {
        $headers = [
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'Version' => '1.0',
        ];

        $this->serverRequestMock->expects($this->once())
            ->method('getHeaders')
            ->willReturn($headers);

        $this->assertEquals($headers, $this->requestHandler->getHeaders());
    }

    #[Test]
    public function it_returns_true_if_the_header_exists(): void
    {
        $this->serverRequestMock->expects($this->once())
            ->method('hasHeader')
            ->with('Accept')
            ->willReturn(true);

        $this->assertTrue($this->requestHandler->hasHeader('Accept'));
    }

    #[Test]
    public function it_returns_false_if_the_header_does_not_exists(): void
    {
        $this->serverRequestMock->expects($this->once())
            ->method('hasHeader')
            ->with('Accept')
            ->willReturn(false);

        $this->assertFalse($this->requestHandler->hasHeader('Accept'));
    }

    #[Test]
    public function it_returns_header_passed_via_method_argument(): void
    {
        $this->serverRequestMock->expects($this->once())
            ->method('getHeader')
            ->with('Accept')
            ->willReturn(['application/json']);

        $this->assertEquals(['application/json'], $this->requestHandler->getHeader('Accept'));
    }

    #[Test]
    public function it_returns_header_line_passed_via_method_argument(): void
    {
        $this->serverRequestMock->expects($this->once())
            ->method('getHeaderLine')
            ->with('Accept')
            ->willReturn('application/json');

        $this->assertEquals('application/json', $this->requestHandler->getHeaderLine('Accept'));
    }

    #[Test]
    public function it_adds_header_to_the_request(): void
    {
        $this->serverRequestMock->expects($this->once())
            ->method('withHeader')
            ->with('Accept', 'application/json')
            ->willReturnSelf();

        $this->requestHandler->withHeader('Accept', 'application/json');
    }

    #[Test]
    public function it_appends_header_to_the_request(): void
    {
        $this->serverRequestMock->expects($this->once())
            ->method('withAddedHeader')
            ->with('Accept', 'application/json')
            ->willReturnSelf();

        $this->requestHandler->withAddedHeader('Accept', 'application/json');
    }

    #[Test]
    public function it_removes_header_from_the_request(): void
    {
        $this->serverRequestMock->expects($this->once())
            ->method('withoutHeader')
            ->with('Accept')
            ->willReturnSelf();

        $this->requestHandler->withoutHeader('Accept');
    }

    /**
     * @throws \PHPUnit\Framework\MockObject\Exception
     */
    #[Test]
    public function it_returns_request_body_of_stream_interface(): void
    {
        $this->serverRequestMock->expects($this->once())
            ->method('getBody')
            ->willReturn($this->createMock(StreamInterface::class));

        $this->assertInstanceOf(StreamInterface::class, $this->requestHandler->getBody());
    }

    /**
     * @throws \PHPUnit\Framework\MockObject\Exception
     */
    #[Test]
    public function it_adds_body_to_the_request(): void
    {
        $this->serverRequestMock->expects($this->once())
            ->method('withBody')
            ->with($this->createMock(StreamInterface::class))
            ->willReturnSelf();

        $this->requestHandler->withBody($this->createMock(StreamInterface::class));
    }

    #[Test]
    public function it_returns_request_target(): void
    {
        $this->serverRequestMock->expects($this->once())
            ->method('getRequestTarget')
            ->willReturn('https://example.com');

        $this->assertEquals('https://example.com', $this->requestHandler->getRequestTarget());
    }

    #[Test]
    public function it_adds_request_target_to_the_request(): void
    {
        $this->serverRequestMock->expects($this->once())
            ->method('withRequestTarget')
            ->with($this->equalTo('https://example.com'))
            ->willReturnSelf();

        $this->requestHandler->withRequestTarget('https://example.com');
    }

    #[Test]
    public function it_returns_request_http_method(): void
    {
        $this->serverRequestMock->expects($this->once())
            ->method('getMethod')
            ->willReturn('GET');

        $this->assertEquals('GET', $this->requestHandler->getMethod());
    }

    #[Test]
    public function it_adds_http_method_to_the_request(): void
    {
        $this->serverRequestMock->expects($this->once())
            ->method('withMethod')
            ->with($this->equalTo('GET'))
            ->willReturnSelf();

        $this->requestHandler->withMethod('GET');
    }

    /**
     * @throws \PHPUnit\Framework\MockObject\Exception
     */
    #[Test]
    public function it_returns_uri_from_the_request(): void
    {
        $this->serverRequestMock->expects($this->once())
            ->method('getUri')
            ->willReturn($this->createMock(UriInterface::class));

        $this->assertInstanceOf(UriInterface::class, $this->requestHandler->getUri());
    }

    #[Test]
    public function it_adds_uri_to_the_request(): void
    {
        $this->serverRequestMock->expects($this->once())
            ->method('withUri')
            ->with($this->createMock(UriInterface::class), true)
            ->willReturnSelf();

        $this->requestHandler->withUri($this->createMock(UriInterface::class), true);
    }

    #[Test]
    public function it_returns_server_params(): void
    {
        $this->serverRequestMock->expects($this->once())
            ->method('getServerParams')
            ->willReturn(['foo' => 'bar']);

        $this->assertEquals(['foo' => 'bar'], $this->requestHandler->getServerParams());
    }

    #[Test]
    public function it_returns_cookie_params(): void
    {
        $this->serverRequestMock->expects($this->once())
            ->method('getCookieParams')
            ->willReturn(['foo' => 'bar']);

        $this->assertEquals(['foo' => 'bar'], $this->requestHandler->getCookieParams());
    }

    #[Test]
    public function it_adds_cookies_to_the_request(): void
    {
        $this->serverRequestMock->expects($this->once())
            ->method('withCookieParams')
            ->with($this->equalTo(['foo' => 'bar']))
            ->willReturnSelf();

        $this->requestHandler->withCookieParams(['foo' => 'bar']);
    }

    #[Test]
    public function it_returns_query_params(): void
    {
        $this->serverRequestMock->expects($this->once())
            ->method('getQueryParams')
            ->willReturn(['foo' => 'bar']);

        $this->assertEquals(['foo' => 'bar'], $this->requestHandler->getQueryParams());
    }

    #[Test]
    public function it_adds_query_params_to_the_request(): void
    {
        $this->serverRequestMock->expects($this->once())
            ->method('withQueryParams')
            ->with($this->equalTo(['foo' => 'bar']))
            ->willReturnSelf();

        $this->requestHandler->withQueryParams(['foo' => 'bar']);
    }

    #[Test]
    public function it_returns_uploaded_files(): void
    {
        $this->serverRequestMock->expects($this->once())
            ->method('getUploadedFiles')
            ->willReturn(['foo' => 'bar']);

        $this->assertEquals(['foo' => 'bar'], $this->requestHandler->getUploadedFiles());
    }

    #[Test]
    public function it_adds_uploaded_files_to_the_request(): void
    {
        $this->serverRequestMock->expects($this->once())
            ->method('withUploadedFiles')
            ->with($this->equalTo(['foo' => 'bar']))
            ->willReturnSelf();

        $this->requestHandler->withUploadedFiles(['foo' => 'bar']);
    }

    #[Test]
    public function it_returns_parse_body(): void
    {
        $this->serverRequestMock->expects($this->once())
            ->method('getParsedBody')
            ->willReturn(['foo' => 'bar']);

        $this->assertEquals(['foo' => 'bar'], $this->requestHandler->getParsedBody());
    }

    #[Test]
    public function it_adds_parse_body_to_the_request(): void
    {
        $this->serverRequestMock->expects($this->once())
            ->method('withParsedBody')
            ->with($this->equalTo(['foo' => 'bar']))
            ->willReturnSelf();

        $this->requestHandler->withParsedBody(['foo' => 'bar']);
    }

    #[Test]
    public function it_returns_request_attributes(): void
    {
        $this->serverRequestMock->expects($this->once())
            ->method('getAttributes')
            ->willReturn(['foo' => 'bar']);

        $this->assertEquals(['foo' => 'bar'], $this->requestHandler->getAttributes());
    }

    #[Test]
    public function it_returns_request_attribute(): void
    {
        $this->serverRequestMock->expects($this->once())
            ->method('getAttribute')
            ->with($this->equalTo('foo'))
            ->willReturn('bar');

        $this->assertEquals('bar', $this->requestHandler->getAttribute('foo'));
    }

    #[Test]
    public function it_adds_attribute_to_the_request(): void
    {
        $this->serverRequestMock->expects($this->once())
            ->method('withAttribute')
            ->with($this->equalTo('foo'))
            ->willReturnSelf();

        $this->requestHandler->withAttribute('foo', 'bar');
    }

    #[Test]
    public function it_removes_attribute_from_the_request(): void
    {
        $this->serverRequestMock->expects($this->once())
            ->method('withoutAttribute')
            ->with($this->equalTo('foo'))
            ->willReturnSelf();

        $this->requestHandler->withoutAttribute('foo');
    }
}
