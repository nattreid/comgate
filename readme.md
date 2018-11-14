# Comgate pro Nette Framework
Nastavení v **config.neon**
```neon
extensions:
    comgate: NAttreid\Comgate\DI\ComgateExtension

comgate:
    paymentsUrl: https://payments.comgate.cz/v1.0/create
    temp: %tempDir%/comgate/
    merchant: 123456
    test: true
    password: password
```

### Použití
```php
/** @var \NAttreid\Comgate\ComgateClient @inject */
public $comgateClient;

private function actionProcess(): void {
    $comgateClient = $this->comgateClient;
    
    $comgateClient->setCountry($this->order->customer->country->code);
    $comgateClient->setCurrency($this->order->currency->code);
    $comgateClient->setPrice($this->order->price);

    $response = $comgateClient->createTransaction($this->order->id);

    $this->order->setComgateTransactionId($response->transactionId);

    $this->sendResponse($response->response);
}

public function actionComgateStatus(): void
	{
		$response = $this->comgateClient->checkTransactionStatus();
		if ($response->isOk()) {
			if ($response->status === 'PAID') {
				// paid code
			}
		} else {
			// error code
		}

		$this->sendResponse($response->reponse);
	}
```