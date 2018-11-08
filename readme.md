# Comgate pro Nette Framework
Nastavení v **config.neon**
```neon
extensions:
    comgate: NAttreid\Comgate\DI\ComgateExtension

comgate:
    paymentsUrl: https://payments.comgate.cz/v1.0/create
    temp: %tempDir%/comgate/
    merchant: eshop
    test: true
    password: password
```

### Použití
```php
/** @var \NAttreid\Comgate\IComgateClientFactory @inject */
public $comgateFactory;

private function process() {
    $comgate = $this->comgateFactory->create();
    
    
}
```