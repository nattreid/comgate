<?php

declare(strict_types=1);

namespace NAttreid\Comgate\Hooks;

use Nette\SmartObject;

/**
 * Class ComgateConfig
 *
 * @property string|null $password
 * @property int|null $merchant
 *
 * @author Attreid <attreid@gmail.com>
 */
class ComgateConfig
{
	use SmartObject;

	/** @var string|null */
	private $password;

	/** @var string|null */
	private $merchant;

	protected function getPassword(): ?string
	{
		return $this->password;
	}

	protected function setPassword(?string $password): void
	{
		$this->password = $password;
	}

	protected function getMerchant(): ?int
	{
		return $this->merchant;
	}

	protected function setMerchant(?int $merchant): void
	{
		$this->merchant = $merchant;
	}
}