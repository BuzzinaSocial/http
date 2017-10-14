# HTTP Client

### Installing
```
composer require BuzzinaSocial/http
```

### Using

```php
require __DIR__.'/vendor/autoload.php';

class MoreClass extends \BuzzinaSocial\Http\Client
{
    public function __construct()
    {
        $this->setHeaders( [
            'accept' => 'application/json',
            'Content-Type' => 'application/json'
        ]);

        $this->setBaseURI('https://httpbin.org/');

        parent::__construct();
    }

    public function test()
    {
        return $this->get('01001000/json/');
    }
}

try {
    $more_class = new MoreClass();
    $more_class->test();
} catch (\Exception $e) {}

print_r($more_class->test()->getBody());
```
