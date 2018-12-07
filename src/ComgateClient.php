<?php

declare(strict_types=1);

namespace NAttreid\Comgate;

use GuzzleHttp\Client;
use NAttreid\Comgate\Helpers\ComgateException;
use NAttreid\Comgate\Helpers\CredentialsNotSetException;
use NAttreid\Comgate\Helpers\StatusResponse;
use NAttreid\Comgate\Helpers\TransactionResponse;
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
	/** @var string */
	private $country;

	/** @var float */
	private $price;

	/** @var string */
	private $currency;

	/** @var string */
	private $email;

	/** @var string */
	private $language;

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

	public function setCountry(string $country): void
	{
		$this->country = $country;
	}

	public function setPrice(float $price): void
	{
		$this->price = $price;
	}

	public function setCurrency(string $currency): void
	{
		$this->currency = $currency;
	}

	public function setEmail(string $email): void
	{
		$this->email = $email;
	}

	protected function setLanguage(string $language): void
	{
		$this->language = $language;
	}

	public function setPreAuth(bool $preAuth = true): void
	{
		$this->preAuth = $preAuth;
	}

	/**
	 * @throws ComgateException
	 */
	private function checkState(): void
	{
		if ($this->country === null) {
			throw new ComgateException('Country is not set.');
		}
		if ($this->currency === null) {
			throw new ComgateException('Currency is not set.');
		}
		if ($this->price === null) {
			throw new ComgateException('Price is not set.');
		}
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
	 * @param int $refId
	 * @return TransactionResponse
	 * @throws ComgateException
	 * @throws CredentialsNotSetException
	 */
	public function transaction(int $refId): TransactionResponse
	{
		$this->checkState();
		$response = $this->request('create', [
			'merchant' => $this->config->merchant,
			'test' => ($this->debug ? 'true' : 'false'),
			'country' => $this->country,
			'price' => round($this->price * 100),
			'curr' => $this->currency,
			'label' => 'Payment',
			'refId' => $refId,
			'payerId' => null,
			'vatPL' => 'STANDARD',
			'cat' => 'PHYSICAL',
			'method' => 'ALL',
			'account' => '',
			'email' => $this->email,
			'phone' => '',
			'name' => '',
			'lang' => $this->language,
			'prepareOnly' => 'true',
			'secret' => $this->config->password,
			'preauth' => $this->preAuth ? 'true' : 'false',
			'initRecurring' => 'false',
			'eetReport' => false,
			'eetData' => null
		]);

		return new TransactionResponse($response);
	}

	public function getStatus(): StatusResponse
	{
		return new StatusResponse($this->request, $this->config, $this->debug);
	}
}
