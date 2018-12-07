<?php

declare(strict_types=1);

namespace NAttreid\Comgate\Helpers;

use Nette\SmartObject;

/**
 * Class Refund
 *
 * @property string $transactionId
 * @property float $price
 * @property string $currency
 *
 * @author Attreid <attreid@gmail.com>
 */
class Refund
{
	use SmartObject;

	/** @var string */
	private $transactionId;

	/** @var float */
	private $price;

	/** @var string */
	private $currency;

	protected function getTransactionId(): string
	{
		return $this->transactionId;
	}

	protected function setTransactionId(string $transactionId): void
	{
		$this->transactionId = $transactionId;
	}

	protected function getPrice(): float
	{
		return $this->price;
	}

	protected function setPrice(float $price): void
	{
		$this->price = $price;
	}

	protected function getCurrency(): string
	{
		return $this->currency;
	}

	protected function setCurrency(string $currency): void
	{
		$this->currency = $currency;
	}
}