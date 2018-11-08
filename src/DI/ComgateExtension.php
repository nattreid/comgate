<?php
declare(strict_types=1);

namespace NAttreid\Comgate\DI;

use NAttreid\Comgate\Hooks\ComgateConfig;
use NAttreid\Comgate\Hooks\ComgateHook;
use NAttreid\Cms\Configurator\Configurator;
use NAttreid\Cms\DI\ExtensionTranslatorTrait;
use NAttreid\WebManager\Services\Hooks\HookService;
use Nette\DI\Statement;

if (trait_exists('NAttreid\Cms\DI\ExtensionTranslatorTrait')) {
	class ComgateExtension extends AbstractComgateExtension
	{
		use ExtensionTranslatorTrait;

		protected function prepareConfig(array $comgate)
		{
			$builder = $this->getContainerBuilder();
			$hook = $builder->getByType(HookService::class);
			if ($hook) {
				$builder->addDefinition($this->prefix('hook'))
					->setType(ComgateHook::class);

				$this->setTranslation(__DIR__ . '/../lang/', [
					'webManager'
				]);

				return new Statement('?->comgate \?: new ' . ComgateConfig::class, ['@' . Configurator::class]);
			} else {
				return parent::prepareConfig($comgate);
			}
		}
	}
} else {
	class ComgateExtension extends AbstractComgateExtension
	{
	}
}