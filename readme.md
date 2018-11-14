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
public $comgate;

private function process() {
    $comgate = $this->comgate;
}
```