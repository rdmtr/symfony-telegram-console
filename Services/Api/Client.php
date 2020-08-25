<?php

declare(strict_types=1);

namespace Rdmtr\TelegramConsole\Services\Api;

use Psr\Http\Client\ClientExceptionInterface;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Rdmtr\TelegramConsole\Api\MethodInterface;
use RuntimeException;
use function json_encode;

/**
 * Class Client
 */
final class Client
{
    private const BASE_URL = 'https://api.telegram.org';

    /**
     * @var ClientInterface|null
     */
    private $client;

    /**
     * @var RequestFactoryInterface|null
     */
    private $requestFactory;

    /**
     * @var StreamFactoryInterface|null
     */
    private $streamFactory;

    /**
     * @var string
     */
    private $token;

    /**
     * Client constructor.
     *
     * @param string                       $token
     * @param ClientInterface|null         $client
     * @param RequestFactoryInterface|null $requestFactory
     * @param StreamFactoryInterface|null  $streamFactory
     */
    public function __construct(
        string $token,
        ClientInterface $client,
        RequestFactoryInterface $requestFactory,
        StreamFactoryInterface $streamFactory
    ) {
        $this->token = $token;
        $this->client = $client;
        $this->requestFactory = $requestFactory;
        $this->streamFactory = $streamFactory;
    }

    /**
     * @param MethodInterface $method
     *
     * @return ResponseInterface
     * @throws ClientExceptionInterface
     */
    public function call(MethodInterface $method): ResponseInterface
    {
        $request = $this->requestFactory
            ->createRequest('POST', self::BASE_URL.'/bot'.$this->token.'/'.$method->getName())
            ->withAddedHeader('Content-Type', 'application/json')
            ->withBody($this->streamFactory->createStream($requestContent = json_encode($method)));

        $response = $this->client->sendRequest($request);
        $responseData = json_decode($responseContent = $response->getBody()->getContents(), true);

        if (!$responseData['ok']) {
            throw new RuntimeException(
                sprintf('Telegram API error: "%s". Request: "%s".', $responseContent, $requestContent)
            );
        }

        return $response;
    }
}
