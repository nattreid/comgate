<?php

declare(strict_types=1);

namespace NAttreid\Comgate\Helpers;

use Nette\Application\Responses\RedirectResponse;
use Psr\Http\Message\ResponseInterface;

/**
 * Class TransactionResponse
 *
 * @property-read string $transactionId
 * @property-read string $redirectUrl
 * @property-read RedirectResponse $response
 *
 * @author Attreid <attreid@gmail.com>
 */
class TransactionResponse extends Response
{
	/**
	 * TransactionResponse constructor.
	 * @param ResponseInterface $response
	 * @throws ComgateException
	 */
	public function __construct(ResponseInterface $response)
	{
		parent::__construct($response);

		$code = $this->getParam('code');
		$message = $this->getParam('message');

		if ($code !== '0' || $message !== 'OK') {
			throw new ComgateException('Transaction creation error ' . $code . ': ' . $message);
		}
	}

	/**
	 * @return string
	 * @throws ComgateException
	 */
	protected function getTransactionId(): string
	{
		return $this->getParam('transId');
	}

	/**
	 * @return string
	 * @throws ComgateException
	 */
	protected function getRedirectUrl(): string
	{
		return $this->getParam('redirect');
	}

	protected function getResponse(): RedirectResponse
	{
		return new RedirectResponse($this->redirectUrl);
	}
}