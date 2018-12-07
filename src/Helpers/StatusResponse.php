<?php

declare(strict_types=1);

namespace NAttreid\Comgate\Helpers;

use NAttreid\Comgate\Hooks\ComgateConfig;
use Nette\Http\Request;
use Nette\SmartObject;

/**
 * Class StatusResponse
 *
 * @property-read string|null $transactionId
 * @property-read string|null $status
 * @property-read ComgateResponse $response
 * @property-read string|null $error
 *
 * @author Attreid <attreid@gmail.com>
 */
class StatusResponse
{
	use SmartObject;

	/** @var string */
	private $transactionId;

	/** @var string */
	private $status;

	/** @var string */
	private $error;


	public function __construct(Request $request, ComgateConfig $config, bool $debug)
	{
		if (
			$request->getPost('merchant') === null ||
			$request->getPost('test') === null ||
			$request->getPost('price') === null ||
			$request->getPost('curr') === null ||
			$request->getPost('refId') === null ||
			$request->getPost('transId') === null ||
			$request->getPost('secret') === null ||
			$request->getPost('status') === null
		) {
			$this->error = 'Missing parameters';
		} elseif (
			$request->getPost('merchant') !== $config->merchant ||
			$request->getPost('test') !== ($debug ? 'true' : 'false') ||
			$request->getPost('secret') !== $config->password
		) {
			$this->error = 'Invalid merchant identification';
		}

		$this->status = $request->getPost('status');
		$this->transactionId = $request->getPost('transId');
	}

	public function isOk(): bool
	{
		return $this->error === null;
	}

	protected function getTransactionId(): ?string
	{
		return $this->transactionId;
	}

	protected function getStatus(): ?string
	{
		return $this->status;
	}

	protected function getError(): ?string
	{
		return $this->error;
	}

	protected function getResponse(): ComgateResponse
	{
		if ($this->isOk()) {
			return new ComgateResponse(200, 'code=0&message=OK');
		} else {
			return new ComgateResponse(200, 'code=1&message=ERROR');
		}
	}
}