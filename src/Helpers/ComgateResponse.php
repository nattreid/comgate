<?php

declare(strict_types=1);

namespace NAttreid\Comgate\Helpers;

use Nette\Application\IResponse;
use Nette\SmartObject;

/**
 * Class ComgateResponse
 *
 * @author Attreid <attreid@gmail.com>
 */
class ComgateResponse implements IResponse
{
	use SmartObject;

	/** @var int */
	private $code;

	/** @var string */
	private $message;

	public function __construct(int $code, string $message)
	{
		$this->code = $code;
		$this->message = $message;
	}

	public function success(): void
	{
		$this->response = 'code=0&message=OK';
	}

	public function error(): void
	{
		$this->response = 'code=1&message=ERROR';
	}

	/**
	 * Sends response to output.
	 * @return void
	 */
	public function send(\Nette\Http\IRequest $httpRequest, \Nette\Http\IResponse $httpResponse)
	{
		$httpResponse->setCode($this->code);
		echo $this->message;
	}
}