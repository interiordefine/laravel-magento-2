<?php

namespace Grayloon\Magento\Api;

use Exception;
use Grayloon\Magento\Magento;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

abstract class AbstractApi
{
    /**
     * The Magento Client instance.
     *
     * @var Magento
     */
    public $magento;

    /**
     * The API request Uri builder.
     *
     * @var string
     */
    public $apiRequest;

    public function __construct(Magento $magento)
    {
        $this->magento = $magento;
        $this->apiRequest = $this->constructRequest();
    }

    /**
     * The initial API request before the builder.
     *
     * @return string
     */
    protected function constructRequest(): string
    {
        $request = $this->magento->baseUrl;
        $request .= '/'.$this->magento->basePath;
        $request .= '/'.$this->magento->storeCode;

        if ($this->magento->versionIncluded) {
            $request .= '/'.$this->magento->version;
        }

        return $request;
    }

    /**
     * Send a GET request with query parameters.
     *
     * @param string $path
     * @param string $parameters
     * @return Response
     * @throws Exception
     */
    protected function get(string $path, $parameters = []): Response
    {
        return $this->call('get', $path, $parameters);
    }

    /**
     * Send a POST request with query parameters.
     *
     * @param string $path
     * @param string $parameters
     * @return Response
     * @throws Exception
     */
    protected function post(string $path, $parameters = []): Response
    {
        return $this->call('post', $path, $parameters);
    }

    /**
     * Send a PUT request.
     *
     * @param $path
     * @param array $parameters
     * @return Response
     * @throws Exception
     */
    protected function put($path, array $parameters = []): Response
    {
        return $this->call('put', $path, $parameters);
    }

    /**
     * Send a DELETE request.
     *
     * @param $path
     * @param array $parameters
     * @return Response
     * @throws Exception
     */
    protected function delete($path, array $parameters = []): Response
    {
        return $this->call('delete', $path, $parameters);
    }

    /**
     * Check for any type of invalid API Responses.
     *
     * @param Response $response
     * @param $endpoint
     * @param $parameters
     * @return Response
     *
     * @throws Exception
     */
    protected function checkExceptions(Response $response, $endpoint, $parameters): Response
    {
        if ($response->serverError()) {
            throw new Exception($response['message'] ?? $response);
        }

        if (! $response->successful()) {
            if (config('magento.log_failed_requests')) {
                Log::info('[MAGENTO API][STATUS] '.$response->status().' [ENDPOINT] '.$endpoint.' [PARAMETERS]  '.json_encode($parameters).' [RESPONSE] '.json_encode($response->json()));
            }
        }

        return $response;
    }

    /**
     * Validates the usage of the store code as needed.
     *
     * @return AbstractApi
     *
     * @throws Exception
     */
    protected function validateSingleStoreCode(): AbstractApi
    {
        if ($this->magento->storeCode === 'all') {
            throw new Exception(__('You must pass a single store code. "all" cannot be used.'));
        }

        return $this;
    }

    /**
     * @param string $method
     * @param string $path
     * @param array $parameters
     * @return Response
     * @throws Exception
     */
    protected function call(string $method, string $path, array $parameters): Response
    {
        return $this->checkExceptions(
            HTTP::withOptions([
                'debug' => env('APP_DEBUG'),
                //for non-production environments, don't worry about verifying ssl
                'verify' => (bool)('production' === config('app.env')),
            ])
                ->withToken($this->magento->token)
                ->$method($this->apiRequest.$path, $parameters),
            $this->apiRequest.$path, $parameters
        );
    }
}
