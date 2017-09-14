<?php

namespace Lefamed\LaravelBillwerk\Billwerk;

use GuzzleHttp\Psr7\Response;

/**
 * Class ApiResponse
 *
 * @package App\Api
 */
class ApiResponse
{
	/**
	 * @var Response
	 */
	protected $response;

	protected $body;

	/**
	 * ApiResponse constructor.
	 *
	 * @param Response $response
	 * @throws \Exception
	 */
	public function __construct(Response $response)
	{
		if ($response->getStatusCode() < 200 || $response->getStatusCode() >= 300) {
			throw new \Exception('API request failed with status code ' . $response->getStatusCode() . '.');
		}

		$this->response = $response;
		$this->body = json_decode((string)$response->getBody());
	}

	/**
	 * Returns the amount of results from the API.
	 *
	 * @return int
	 */
	public function count()
	{
		return $this->body->total;
	}

	/**
	 * Gets all records.
	 *
	 * @return \Illuminate\Support\Collection
	 */
	public function data()
	{
		//hack for collection access to findOne
		if (!is_array($this->body)) {
			$this->body = [$this->body];
			return collect($this->body)->first();
		}

		return collect($this->body);
	}

}
