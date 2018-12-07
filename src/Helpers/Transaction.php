<?php

declare(strict_types=1);

namespace NAttreid\Comgate\Helpers;

use Nette\SmartObject;

/**
 * Class Transaction
 *
 * @property int $refId
 * @property string $country
 * @property float $price
 * @property string $currency
 * @property string|null $email
 * @property string|null $language
 *
 * @author Attreid <attreid@gmail.com>
 */
class Transaction
{
	use SmartObject;

	/** @var int */
	private $refId;

	/** @var string */
	private $country;

	/** @var float */
	private $price;

	/** @var string */
	private $currency;

	/** @var string|null */
	private $email;

	/** @var string|null */
	private $language;

	protected function getRefId(): int
	{
		return $this->refId;
	}

	protected function setRefId(int $refId): void
	{
		$this->refId = $refId;
	}

	protected function getCountry(): string
	{
		return $this->country;
	}

	protected function setCountry(string $country): void
	{
		$this->country = $country;
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

	protected function getEmail(): ?string
	{
		return $this->email;
	}

	protected function setEmail(?string $email): void
	{
		$this->email = $email;
	}

	protected function getLanguage(): ?string
	{
		return $this->language;
	}

	protected function setLanguage(?string $language): void
	{
		$this->language = $language;
	}
}