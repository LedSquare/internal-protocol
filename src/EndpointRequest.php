<?php

namespace InternalProtocol;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use InternalProtocol\Exceptions\InternalProtocolException;

/**
 * @method Response send(string $baseDomain, array $headers)
 */
abstract class EndpointRequest
{
    protected VersionAPI $version;
    protected MethodName $method;
    protected string $route;
    protected array $data = [];
    protected array $headers = [];

    /**
     * @param string $baseDomain
     * @param array $headers
     * @throws InternalProtocolException
     * @return mixed<\Illuminate\Http\Client\Response|null>
     */
    public function send(string $baseDomain, array $headers): mixed
    {
        $headers = array_merge($headers, $this->headers);

        $this->route = $this->checkSlash($this->route);

        $url = $baseDomain . $this->version->value . $this->route;

        $method = Str::lower($this->method->name);

        return Http::withHeaders($headers)->{$method}($url, $this->data);
    }

    final protected function checkSlash(string $str): string
    {
        if (substr($str, 0) !== '/') {
            return $str = '/' . $str;
        }
        return $str;
    }
}
