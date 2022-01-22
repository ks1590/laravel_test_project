<?php

namespace App\Utils;

use GuzzleHttp\Client;

class ClientBuilder
{
    /**
     * @var Client
     */
    private $client;
    private $method;
    private $url;
    private $urls;

    static function make(): self {
        return new static();
    }

    function __construct() {
        $this->client = new Client();
    }

    function post(): self {
        $this->method = "POST";
        return $this;
    }

    function put(): self {
        $this->method = "PUT";
        return $this;
    }

    function get(): self {
        $this->method = "GET";
        return $this;
    }

    function to(string $url): self {
        $this->url = $url;
        return $this;
    }

    function at(string $url): self {
        $this->url = $url;
        return $this;
    }

    function ats(array $urls): self {
        $this->urls = $urls;
        return $this;
    }

    function header(array $headers): self {
        $this->headers = $headers;
        return $this;
    }

    function noErrors(): self {
        $this->errors = false;
        return $this;
    }

    function withCertOptions(array $paths): self {
        $this->cert = $paths["cert"];
        $this->ssl_key = $paths["ssl_key"];
        return $this;
    }

    function with($datas): self {
        $this->datas = $datas;
        return $this;
    }

    function asyncFire() {
        $promises = [];

        foreach ($this->urls as $url) {
            $promises[] = $this->client->requestAsync(
                $this->method,
                $url,
                $this->createRequestOption()
            );
        }
        $responses = \GuzzleHttp\Promise\all($promises)->wait();

        $result = [];
        foreach ($responses as $response) {
            $result[] = $response->getBody()->getContents();
        }

        return $result;
    }

    function getHeaders() {
        return $this->client->request(
            $this->method,
            $this->url,
            $this->createRequestOption()
        )->getHeaders();
    }

    function getStatusCode() {
        return $this->client->request(
            $this->method,
            $this->url,
            $this->createRequestOption()
        )->getStatusCode();
    }

    function fire() {
        return (string) $this->client->request(
            $this->method,
            $this->url,
            $this->createRequestOption()
        )->getBody();
    }


    private function createRequestOption() {
        $option = [];

        $option['verify'] = false;

        if (isset($this->headers)) {
            $option["headers"] = $this->headers;
        }

        if (isset($this->errors)) {
            $option["http_errors"] = $this->errors;
        }

        if (isset($this->cert) && isset($this->ssl_key)) {
            $option["cert"] = $this->cert;
            $option["ssl_key"] = $this->ssl_key;
            $option['verify'] = true;
        }

        if (isset($this->datas)) {
            if ($this->method == "POST" || $this->method == "PUT") {
                if (is_array($this->datas)) {
                    $option["form_params"] = $this->datas;
                } else {
                    $option["body"] = $this->datas;
                }
            } else {
                $option["query"] = $this->datas;
            }
        }
        return $option;
    }
}
