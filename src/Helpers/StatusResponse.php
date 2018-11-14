<?php

declare(strict_types=1);

namespace NAttreid\Comgate\Helpers;

use Nette\Application\Responses\RedirectResponse;
use Nette\SmartObject;

/**
 * Class StatusResponse
 *
 * @property-read string $transactionId
 * @property-read string $status
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

	public function __construct(string $transactionId, string $status)
	{
		$this->transactionId = $transactionId;
		$this->status = $status;
	}

	protected function getTransactionId(): string
	{
		return $this->transactionId;
	}

	protected function getStatus(): string
	{
		return $this->status;
	}
}