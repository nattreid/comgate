<?php

declare(strict_types=1);

namespace NAttreid\Comgate\Helpers;

use Nette\Application\Responses\RedirectResponse;

/**
 * Class Response
 *
 * @author Attreid <attreid@gmail.com>
 */
class Response
{
	/** @var string */
	private $transactionId;

	/** @var string */
	private $redirectUrl;

	public function __construct(string $transactionId, string $redirectUrl)
	{
		$this->transactionId = $transactionId;
		$this->paymentUrl = $redirectUrl;
	}

	public function getTransactionId(): string
	{
		return $this->transactionId;
	}

	public function getRedirectUrl(): string
	{
		return $this->redirectUrl;
	}

	public function getResponse(): RedirectResponse
	{
		return new RedirectResponse($this->redirectUrl);
	}
}