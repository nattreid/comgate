<?php

declare(strict_types=1);

namespace NAttreid\Comgate\Helpers;

use Nette\Application\Responses\RedirectResponse;
use Nette\SmartObject;

/**
 * Class TransactionResponse
 *
 * @property-read string $transactionId
 * @property-read string $redirectUrl
 * @property-read RedirectResponse $response
 *
 * @author Attreid <attreid@gmail.com>
 */
class TransactionResponse
{
	use SmartObject;

	/** @var string */
	private $transactionId;

	/** @var string */
	private $redirectUrl;

	public function __construct(string $transactionId, string $redirectUrl)
	{
		$this->transactionId = $transactionId;
		$this->redirectUrl = $redirectUrl;
	}

	protected function getTransactionId(): string
	{
		return $this->transactionId;
	}

	protected function getRedirectUrl(): string
	{
		return $this->redirectUrl;
	}

	protected function getResponse(): RedirectResponse
	{
		return new RedirectResponse($this->redirectUrl);
	}
}