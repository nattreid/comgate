<?php

declare(strict_types=1);

namespace NAttreid\Comgate\Hooks;

use NAttreid\Form\Form;
use NAttreid\WebManager\Services\Hooks\HookFactory;
use Nette\ComponentModel\Component;
use Nette\Utils\ArrayHash;

/**
 * Class ComgateHook
 *
 * @author Attreid <attreid@gmail.com>
 */
class ComgateHook extends HookFactory
{

	/** @var IConfigurator */
	protected $configurator;

	public function init(): void
	{
		if (!$this->configurator->comgate) {
			$this->configurator->comgate = new ComgateConfig;
		}
	}

	/** @return Component */
	public function create(): Component
	{
		$form = $this->formFactory->create();
		$form->setAjaxRequest();

		$form->addText('password', 'webManager.web.hooks.comgate.password')
			->setDefaultValue($this->configurator->comgate->password);

		$form->addSubmit('save', 'form.save');

		$form->onSuccess[] = [$this, 'comgateFormSucceeded'];

		return $form;
	}

	public function comgateFormSucceeded(Form $form, ArrayHash $values): void
	{
		$config = $this->configurator->comgate;

		$config->password = $values->password ?: null;

		$this->configurator->comgate = $config;

		$this->flashNotifier->success('default.dataSaved');
	}
}