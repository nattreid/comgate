<?php

declare(strict_types=1);

namespace NAttreid\Comgate\DI;

use NAttreid\Comgate\ComgateClient;
use NAttreid\Comgate\Hooks\ComgateConfig;
use Nette\DI\CompilerExtension;

/**
 * Class AbstractComgateExtension
 *
 * @author Attreid <attreid@gmail.com>
 */
abstract class AbstractComgateExtension extends CompilerExtension
{

	private $defaults = [
		'url' => 'https://payments.comgate.cz/v1.0/',
		'merchant' => null,
		'debug' => false,
		'password' => null
	];

	public function loadConfiguration(): void
	{
		$config = $this->validateConfig($this->defaults, $this->getConfig());
		$builder = $this->getContainerBuilder();

		$comgate = $this->prepareConfig($config);

		$builder->addDefinition($this->prefix('client'))
			->setType(ComgateClient::class)
			->setArguments([
				$config['debug'],
				$comgate,
				$config['url'],
			]);
	}

	protected function prepareConfig(array $config)
	{
		$builder = $this->getContainerBuilder();
		return $builder->addDefinition($this->prefix('config'))
			->setFactory(ComgateConfig::class)
			->addSetup('$merchant', [$config['merchant']])
			->addSetup('$password', [$config['password']]);
	}
}