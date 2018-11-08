<?php

declare(strict_types=1);

namespace NAttreid\Comgate\DI;

use NAttreid\Comgate\ComgateClient;
use NAttreid\Comgate\Hooks\ComgateConfig;
use NAttreid\Comgate\IComgateClientFactory;
use Nette\DI\CompilerExtension;
use Nette\DI\Helpers;
use Nette\InvalidStateException;

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

		if ($config['merchant'] === null) {
			throw new InvalidStateException("Comgate: 'merchant' does not set in config.neon");
		}
		if ($config['password'] === null) {
			throw new InvalidStateException("Comgate: 'password' does not set in config.neon");
		}
		$config['temp'] = Helpers::expand($config['temp'], $builder->parameters);

		$comgate = $this->prepareConfig($config);

		$builder->addDefinition($this->prefix('database'))
			->setType(\AgmoPaymentsSimpleDatabase::class)
			->setArguments([
				$config['temp'],
				$config['merchant'],
				$config['test']
			]);

		$builder->addDefinition($this->prefix('protocol'))
			->setType(\AgmoPaymentsSimpleProtocol::class)
			->setArguments([
				$config['paymentsUrl'],
				$config['merchant'],
				$config['test'],
				$comgate['password']
			]);

		$builder->addDefinition($this->prefix('client'))
			->setImplement(IComgateClientFactory::class)
			->setFactory(ComgateClient::class);
	}

	protected function prepareConfig(array $config)
	{
		$builder = $this->getContainerBuilder();
		return $builder->addDefinition($this->prefix('config'))
			->setFactory(ComgateConfig::class)
			->addSetup('$password', [$config['password']]);
	}
}