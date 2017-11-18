<?php

namespace Lefamed\LaravelBillwerk\Billwerk;

use GuzzleHttp\Client;

/**
 * Class BaseClient
 * Base client for communication with the midoffice API.
 *
 * @package App\Api
 */
abstract class BaseClient
{
    /**
     * @var \GuzzleHttp\Client
     */
    protected $httpClient;

    /**
     * Base URL for all RESTful API resources
     *
     * @var string
     */
    protected $baseUrl;
    protected $authUrl;

    /**
     * Points to the HTTP Resource
     *
     * @var string
     */
    protected $resource = '';

    protected $accessToken = null;

    /**
     * Client constructor.
     */
    public function __construct()
    {
        $this->httpClient = new Client();

        $this->baseUrl = config('laravel-billwerk.api.baseUrl');
        $this->authUrl = config('laravel-billwerk.api.authUrl');

        if (\Cache::has(config('laravel-billwerk.auth.token_cache_key'))) {
            $this->accessToken = \Cache::get(config('laravel-billwerk.auth.token_cache_key'));
        }
        if (is_null($this->accessToken)) {
            $this->requestAccessToken();
        }
    }

    /**
     * Request an access token on billwerk api
     *
     * @throws \Exception
     */
    protected function requestAccessToken()
    {
        //prepare the oAuth2 call
        $res = $this->httpClient->post($this->authUrl, [
            'form_params' => [
                'grant_type'    => 'client_credentials',
                'client_id'     => config('laravel-billwerk.auth.client_id'),
                'client_secret' => config('laravel-billwerk.auth.client_secret'),
            ],
        ]);

        if ($res->getStatusCode() === 200) {
            //store access token on cache
            $body = json_decode($res->getBody());
            \Cache::put(config('laravel-billwerk.auth.token_cache_key'), $body->access_token, 60 * 24);
            $this->accessToken = $body->access_token;
        } else {
            \Log::error($res->getBody());
            throw new \Exception('Billwerk auth error - ' . $res->getStatusCode());
        }
    }

    /**
     * @param $data
     *
     * @return array
     */
    //abstract public function mutateModel($data);

    /**
     * Builds the request options, including e.g. auth information.
     *
     * @return array
     */
    protected function buildOptions()
    {
        return [
            'headers' => [
                'Authorization' => 'Bearer ' . $this->accessToken,
            ],
        ];
    }

    /**
     * @param null $id Resource ID
     * @param null $action
     *
     * @return ApiResponse
     */
    public function get($id = null, $action = null)
    {
        if (null !== $id) {
            $route = $this->baseUrl . $this->resource . '/' . $id . ($action !== null ? '/' . $action : '');
        } else {
            $route = $this->baseUrl . $this->resource;
        }

        /** @noinspection PhpParamsInspection */
        return new ApiResponse($this->httpClient->get($route, $this->buildOptions()));
    }

    /**
     * @param      $payload
     * @param null $resource
     *
     * @return ApiResponse
     */
    public function post($payload, $resource = null)
    {
        $route   = $this->baseUrl . ($resource ?? $this->resource);
        $options = $this->buildOptions();

        $options['json'] = $payload;

        /** @noinspection PhpParamsInspection */
        return new ApiResponse($this->httpClient->post($route, $options));

    }

    /**
     * @param $id
     * @param $payload
     *
     * @return ApiResponse
     */
    public function put($id, $payload)
    {
        $route   = $this->baseUrl . $this->resource . '/' . $id;
        $options = $this->buildOptions();

        $options['json'] = $payload;

        /** @noinspection PhpParamsInspection */
        return new ApiResponse($this->httpClient->put($route, $options));
    }

    /**
     * @param $id
     *
     * @return ApiResponse
     */
    public function delete($id)
    {
        $route   = $this->baseUrl . $this->resource . '/' . $id;
        $options = $this->buildOptions();

        /** @noinspection PhpParamsInspection */
        return new ApiResponse($this->httpClient->delete($route, $options));
    }
}
