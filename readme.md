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
    
    $transaction= new \NAttreid\Comgate\Helpers\Transaction;
    $transaction->refId=$this->order->id;
    $transaction->country=$this->order->customer->country->code;
    $transaction->currency=$this->order->currency->code;
    $transaction->price=$this->order->price;

    $response = $comgate->transaction($transaction);

    $this->order->setComgateTransactionId($response->transactionId);

    $this->sendResponse($response->response);
}

private function actionRefund(float $price): void {
    $comgateClient = $this->comgateClient;

    $refund = new \NAttreid\Comgate\Helpers\Refund;
    $refund->transactionId = $this->order->comgateTransactionId;
    $refund->price = $price;
    $refund->currency = $this->order->currency->code;

    $response = $comgateClient->refund($refund);

    return $response->isOk();
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