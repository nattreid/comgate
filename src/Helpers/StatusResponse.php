<?php

declare(strict_types=1);

namespace NAttreid\Comgate\Helpers;

use Exception;
use Nette\SmartObject;

/**
 * Class StatusResponse
 *
 * @property-read string|null $transactionId
 * @property-read string|null $status
 * @property-read ComgateResponse $reponse
 * @property-read string|null $error
 *
 * @author Attreid <attreid@gmail.com>
 */
class StatusResponse
{
	use SmartObject;

	/** @var string */
	private $transactionId;

	/** @var string */
	private $status;

	private $error;

	private $response;

	public function __construct(?string $transactionId, ?string $status, ?Exception $exception)
	{
		$this->transactionId = $transactionId;
		$this->status = $status;
		if ($exception !== null) {
			$this->error = $exception->getMessage();
			$this->response = new ComgateResponse(500, 'code=1&message=ERROR');
		} else {
			$this->response = new ComgateResponse(200, 'code=0&message=OK');
		}
	}

	public function isOk(): bool
	{
		return $this->error === null;
	}

	protected function getTransactionId(): ?string
	{
		return $this->transactionId;
	}

	protected function getStatus(): ?string
	{
		return $this->status;
	}

	protected function getResponse(): ComgateResponse
	{
		return $this->response;
	}
}