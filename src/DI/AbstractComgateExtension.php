<?php

declare(strict_types=1);

namespace NAttreid\Comgate\DI;

use NAttreid\Comgate\ComgateClient;
use NAttreid\Comgate\Hooks\ComgateConfig;
use Nette\DI\CompilerExtension;
use Nette\DI\Helpers;

/**
 * Class AbstractComgateExtension
 *
 * @author Attreid <attreid@gmail.com>
 */
abstract class AbstractComgateExtension extends CompilerExtension
{

	private $defaults = [
		'paymentsUrl' => 'https://payments.comgate.cz/v1.0/create',
		'temp' => '%tempDir%/comgate/',
		'merchant' => null,
		'test' => false,
		'password' => null
	];

	public function loadConfiguration(): void
	{
		$config = $this->validateConfig($this->defaults, $this->getConfig());
		$builder = $this->getContainerBuilder();

		$config['temp'] = Helpers::expand($config['temp'], $builder->parameters);

		$comgate = $this->prepareConfig($config);

		$builder->addDefinition($this->prefix('client'))
			->setType(ComgateClient::class)
			->setArguments([
				$comgate,
				$config['temp'],
				$config['paymentsUrl'],
				$config['test'],
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