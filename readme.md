# Comgate pro Nette Framework
Nastavení v **config.neon**
```neon
extensions:
    comgate: NAttreid\Comgate\DI\ComgateExtension

comgate:
    paymentsUrl: https://payments.comgate.cz/v1.0/
    merchant: 123456
    debug: true
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

    $response = $comgateClient->transaction($this->order->id);

    $this->order->setComgateTransactionId($response->transactionId);

    $this->sendResponse($response->response);
}

public function actionComgateStatus(): void
	{
		$response = $this->comgateClient->getStatus();
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