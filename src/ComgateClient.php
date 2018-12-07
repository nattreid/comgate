<?php

declare(strict_types=1);

namespace NAttreid\Comgate;

use GuzzleHttp\Client;
use NAttreid\Comgate\Helpers\Exceptions\ComgateException;
use NAttreid\Comgate\Helpers\Exceptions\CredentialsNotSetException;
use NAttreid\Comgate\Helpers\Refund;
use NAttreid\Comgate\Helpers\Response\RefundResponse;
use NAttreid\Comgate\Helpers\Response\StatusResponse;
use NAttreid\Comgate\Helpers\Response\TransactionResponse;
use NAttreid\Comgate\Helpers\Transaction;
use NAttreid\Comgate\Hooks\ComgateConfig;
use Nette\Http\Request;
use Psr\Http\Message\ResponseInterface;

/**
 * Class ComgateClient
 *
 * @author Attreid <attreid@gmail.com>
 */
class ComgateClient
{


	/** @var bool */
	private $preAuth = false;

	/** @var ComgateConfig */
	private $config;

	/** @var Client */
	private $client;

	/** @var bool */
	private $debug;

	/** @var Request */
	private $request;

	public function __construct(bool $debug, ComgateConfig $config, string $url, Request $request)
	{
		$this->config = $config;
		$this->client = new Client(['base_uri' => $url]);
		$this->debug = $debug;
		$this->request = $request;
	}


	public function setPreAuth(bool $preAuth = true): void
	{
		$this->preAuth = $preAuth;
	}


	/**
	 * @param string $url
	 * @param array $args
	 * @return ResponseInterface
	 * @throws CredentialsNotSetException
	 */
	private function request(string $url, array $args = []): ResponseInterface
	{
		if (empty($this->config->merchant)) {
			throw new CredentialsNotSetException('Merchant must be set');
		}

		return $this->client->post($url, [
			'form_params' => $args
		]);

	}

	/**
	 * @param Transaction $transaction
	 * @return TransactionResponse
	 * @throws ComgateException
	 * @throws CredentialsNotSetException
	 */
	public function transaction(Transaction $transaction): TransactionResponse
	{
		if ($transaction->refId === null) {
			throw new ComgateException('ReferenceId is not set.');
		}
		if ($transaction->country === null) {
			throw new ComgateException('Country is not set.');
		}
		if ($transaction->currency === null) {
			throw new ComgateException('Currency is not set.');
		}
		if ($transaction->price === null) {
			throw new ComgateException('Price is not set.');
		}

		$response = $this->request('create', [
			'merchant' => $this->config->merchant,
			'test' => ($this->debug ? 'true' : 'false'),
			'country' => $transaction->country,
			'price' => round($transaction->price * 100),
			'curr' => $transaction->currency,
			'label' => 'Payment',
			'refId' => $transaction->refId,
			'payerId' => null,
			'vatPL' => 'STANDARD',
			'cat' => 'PHYSICAL',
			'method' => 'ALL',
			'account' => '',
			'email' => $transaction->email,
			'phone' => '',
			'name' => '',
			'lang' => $transaction->language,
			'prepareOnly' => 'true',
			'secret' => $this->config->password,
			'preauth' => $this->preAuth ? 'true' : 'false',
			'initRecurring' => 'false',
			'eetReport' => '',
			'eetData' => null
		]);

		return new TransactionResponse($response);
	}

	public function getStatus(): StatusResponse
	{
		return new StatusResponse($this->request, $this->config, $this->debug);
	}

	public function refund(Refund $refund): RefundResponse
	{
		if ($refund->transactionId === null) {
			throw new ComgateException('TransactionId is not set.');
		}
		if ($refund->currency === null) {
			throw new ComgateException('Currency is not set.');
		}
		if ($refund->price === null) {
			throw new ComgateException('Price is not set.');
		}

		$response = $this->request('refund', [
			'merchant' => $this->config->merchant,
			'transId' => $refund->transactionId,
			'secret' => $this->config->password,
			'amount' => round($refund->price * 100),
			'curr' => $refund->currency,
			'test' => ($this->debug ? 'true' : 'false')
		]);

		return new RefundResponse($response);
	}
}
