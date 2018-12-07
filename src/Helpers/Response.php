<?php

declare(strict_types=1);

namespace NAttreid\Comgate\Helpers;

use Nette\SmartObject;
use Psr\Http\Message\ResponseInterface;

/**
 * Class Response
 *
 * @author Attreid <attreid@gmail.com>
 */
abstract class Response
{
	use SmartObject;

	/** @var ResponseInterface */
	protected $response;

	/** @var array */
	private $params;

	public function __construct(ResponseInterface $response)
	{
		$this->response = $response;
	}

	private function parseResponse(): array
	{
		$result = [];
		$params = explode('&', $this->response->getBody()->getContents());

		foreach ($params as $param) {
			$arr = explode('=', $param);
			$name = urlencode($arr[0]);
			$value = (count($arr) == 2 ? urldecode($arr[1]) : '');
			$params[$name] = $value;
		}
		return $result;
	}

	/**
	 * @param string $param
	 * @return string
	 * @throws ComgateException
	 */
	public function getParam(string $param): string
	{
		if ($this->params === null) {
			$this->params = $this->parseResponse();
		}

		if (!isset($this->params[$param])) {
			throw new ComgateException('Missing response parameter: ' . $param);
		}
		return $this->params[$param];
	}
}