<?php

namespace InternalProtocol\Core;

use Illuminate\Http\Client\Response;
use InternalProtocol\EndpointRequest;
use InternalProtocol\Exceptions\InternalException;

/**
 * @template TEndpoint of EndpointRequest
 */
abstract class InternalApiService
{
    public function __construct(
        protected string $baseDomain,
        protected array $headers = [],
        protected bool $adminRoute = false,
    ) {
    }

    /**
     * 
     * @param TEndpoint $endpointRequest
     * @return array
     */
    final public function callEndpoint(EndpointRequest $endpointRequest): array
    {
        $this->before();

        $response = $endpointRequest->send($this->baseDomain, $this->headers);

        $this->after($response);

        return $response->json();
    }

    abstract protected function before(): void;

    /**
     * @param \Illuminate\Http\Client\Response $response
     * @return void
     */
    abstract protected function after(Response $response): void;
}
