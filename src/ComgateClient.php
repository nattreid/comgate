<?php

declare(strict_types=1);

namespace NAttreid\Comgate;

use AgmoPaymentsSimpleDatabase;
use AgmoPaymentsSimpleProtocol;
use Nette\Application\Responses\RedirectResponse;
use Nette\InvalidStateException;

/**
 * Class ComgateClient
 *
 * @author Attreid <attreid@gmail.com>
 */
class ComgateClient
{
	/** @var AgmoPaymentsSimpleDatabase */
	private $paymentsDatabase;

	/** @var AgmoPaymentsSimpleProtocol */
	private $paymentsProtocol;

	/** @var string */
	private $country;

	/** @var float */
	private $price;

	/** @var string */
	private $currency;

	/** @var string */
	private $email;

	/** @var string */
	private $language;

	/** @var bool */
	private $preAuth = false;

	public function __construct(AgmoPaymentsSimpleDatabase $paymentsDatabase, AgmoPaymentsSimpleProtocol $paymentsProtocol)
	{
		$this->paymentsDatabase = $paymentsDatabase;
		$this->paymentsProtocol = $paymentsProtocol;
	}

	public function setCountry(string $country): void
	{
		$this->country = $country;
	}

	public function setPrice(float $price): void
	{
		$this->price = $price;
	}

	public function setCurrency(string $currency): void
	{
		$this->currency = $currency;
	}

	public function setEmail(string $email): void
	{
		$this->email = $email;
	}

	protected function setLanguage(string $language): void
	{
		$this->language = $language;
	}

	public function setPreAuth(bool $preAuth = true): void
	{
		$this->preAuth = $preAuth;
	}

	private function checkState(): void
	{
		if ($this->country === null) {
			throw new InvalidStateException('Country is not set.');
		}
		if ($this->currency === null) {
			throw new InvalidStateException('Currency is not set.');
		}
		if ($this->price === null) {
			throw new InvalidStateException('Price is not set.');
		}
	}

	/**
	 * @return RedirectResponse
	 * @throws ComgateException
	 */
	public function createTransaction(): RedirectResponse
	{
		$this->checkState();

		// prepare payment parameters
		try {
			$refId = $this->paymentsDatabase->createNextRefId();
		} catch (\Exception $ex) {
			throw new ComgateException($ex->getMessage());
		}

		// create new payment transaction
		try {
			$this->paymentsProtocol->createTransaction(
				$this->country,
				$this->price,
				$this->currency,
				'Payment',
				$refId,
				null,
				'STANDARD',
				'PHYSICAL',
				'ALL',
				'',
				$this->email,
				'',
				'',
				$this->language,
				$this->preAuth
			);
			$transId = $this->paymentsProtocol->getTransactionId();
		} catch (\Exception $ex) {
			throw new ComgateException($ex->getMessage());
		}

		// save transaction data
		try {
			$this->paymentsDatabase->saveTransaction(
				$transId,
				$refId,
				$this->price,
				$this->currency,
				'PENDING'
			);
		} catch (\Exception $ex) {
			throw new ComgateException($ex->getMessage());
		}

		// redirect to agmo payments system
		return new RedirectResponse($this->paymentsProtocol->getRedirectUrl());
	}

	public function checkTransactionStatus()
	{
		try {
			// get transaction status parameters and check them in my configuration
			$this->paymentsProtocol->checkTransactionStatus($_POST);

			// check transaction parameters in my database
			$this->paymentsDatabase->checkTransaction(
				$this->paymentsProtocol->getTransactionStatusTransId(),
				$this->paymentsProtocol->getTransactionStatusRefId(),
				$this->paymentsProtocol->getTransactionStatusPrice(),
				$this->paymentsProtocol->getTransactionStatusCurrency()
			);

			// save new transaction status to my database
			$this->paymentsDatabase->saveTransaction(
				$this->paymentsProtocol->getTransactionStatusTransId(),
				$this->paymentsProtocol->getTransactionStatusRefId(),
				$this->paymentsProtocol->getTransactionStatusPrice(),
				$this->paymentsProtocol->getTransactionStatusCurrency(),
				$this->paymentsProtocol->getTransactionStatus(),
				$this->paymentsProtocol->getTransactionFee()
			);
			return $this->paymentsProtocol->getTransactionStatusTransId();
		} catch (\Exception $ex) {
			return false;
		}
	}
}

interface IComgateClientFactory
{
	public function create(): ComgateClient;
}
