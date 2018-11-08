<?php

declare(strict_types=1);

namespace NAttreid\Comgate\Hooks;

use Nette\SmartObject;

/**
 * Class ComgateConfig
 *
 * @property string|null $password
 *
 * @author Attreid <attreid@gmail.com>
 */
class ComgateConfig
{
	use SmartObject;

	/** @var string|null */
	private $password;

	protected function getPassword(): ?string
	{
		return $this->password;
	}

	protected function setPassword(?string $password): void
	{
		$this->password = $password;
	}
}