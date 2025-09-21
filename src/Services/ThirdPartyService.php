<?php

namespace App\Services;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class ThirdPartyService
{
    public function __construct(
        private HttpClientInterface $client
    ) {}

    public function fetchData($url, $headers = [])
    {
        try {
            $request = $this->client->request('GET', $url, [
                'headers' => array_merge([
                    'Accept' => 'application/json',
                ], $headers),
                'verify_peer' => false,
                'verify_host' => false,
            ]);

            $statusCode = $request->getStatusCode();
            $content = $request->getContent();
            $data = $request->toArray();
            return [
                'success' => true,
                'status_code' => $statusCode,
                'content' => $content,
                'data' => $data,
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => 'HTTP request failed: ' . $e->getMessage(),
            ];
        }
    }

    public function postData($url, $body = [], $headers = [])
    {
        try {
            $request = $this->client->request('POST', $url, [
                'headers' => array_merge([
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                ], $headers),
                'json' => $body,
                'verify_peer' => false,
                'verify_host' => false,
            ]);

            $statusCode = $request->getStatusCode();
            $content = $request->getContent();
            $data = $request->toArray();

            return [
                'success' => true,
                'status_code' => $statusCode,
                'content' => $content,
                'data' => $data,
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => 'HTTP request failed: ' . $e->getMessage(),
            ];
        }
    }

    public function putData($url, $body = [], $headers = [])
    {
        try {
            $request = $this->client->request('PUT', $url, [
                'headers' => array_merge([
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                ], $headers),
                'json' => $body,
                'verify_peer' => false,
                'verify_host' => false,
            ]);

            $statusCode = $request->getStatusCode();
            $content = $request->getContent();
            $data = $request->toArray();

            return [
                'success' => true,
                'status_code' => $statusCode,
                'content' => $content,
                'data' => $data,
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => 'HTTP request failed: ' . $e->getMessage(),
            ];
        }
    }

    public function deleteData($url, $headers = [])
    {
        try {
            $request = $this->client->request('DELETE', $url, [
                'headers' => array_merge([
                    'Accept' => 'application/json',
                ], $headers),
                'verify_peer' => false,
                'verify_host' => false,
            ]);

            $statusCode = $request->getStatusCode();
            $content = $request->getContent();
            $data = $request->toArray();

            return [
                'success' => true,
                'status_code' => $statusCode,
                'content' => $content,
                'data' => $data,
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => 'HTTP request failed: ' . $e->getMessage(),
            ];
        }
    }
}
