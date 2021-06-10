![tests](https://github.com/creatortsv/omnipay-manager-bundle/actions/workflows/php.yml/badge.svg?branch=main)

# Omnipay Manager Bundle

This bundle provides you some logic to make your own payment service with different omnipay based gateways.

## 1. Installation
Install this bundle via composer
```bash
composer require creatortsv/omnipay-manager-bundle
```
Make sure that you have installed all the **Omnipay** gateway packages that you're going to use
```bash
composer require <vendor>/omnipay-<gateway>
```
For example, if you are going to use **Kuberaco.com** gateway you must install it
```bash
composer require creatortsv/omnipay-kuberaco
```

## 2. Configuration
### Default configuration
The first thing that you should is supposed to create your ```Adapter``` classes for each of the **Omnipay** gateway extended with ```AbstractGatewayAdapter``` class
```php
use Creatortsv\OmnipayManagerBundle\Adapter\AbstractGatewayAdapter;
// ...
class MyAdapter extends AbstractGatewayAdapter
{
    // ...
}
```
This classes will be automatically registered with the ```Creatortsv\OmnipayManagerBundle\GatewayManager``` by default

The second, your classes should be implemented with required ```public static getOmnipayGatewayAlias``` method, this method should return the name of the **Omnipay** gateway which will be using with this adapter class

For example:
```php
// ...
class MyAdapter extends AbstractGatewayAdapter
{
    // ...
    public static function getOmnipayGatewayAlias(): string
    {
        return 'Kuberaco';
    }
    // ...
}
```
That's it! All you have to do is write some code. Configure your gateways and create some logic for identical calling gateway methods 

> Note: This bundle does not provide you complete functionality to interact with different gateways in the same way. You should write your own logic. Don't forget use interfaces to do this.

### Advanced configuration
As it was described above, your adapter classes, which was implemented with abstract bundle class, will be automatically registered as services into ```GatewayManager``` class.
However, they won't be created until you use them, that means you can configure them as you want.

There are couple different ways to do that:

- Inject in your adapter some services directly from the container
- Inject some other arguments which you can describe in config files
- Override ```getHttpClient``` and ```getHttpRequest``` methods to change the default client, and the request objects for the **Omnipay** gateway

#### Injecting services
In the example below, we just injected simple service directly from the container, without any modification of config files
```php
use App\Repository\ClientRepository;

class MyAdapter extends AbstractGatewayAdapter
{
    // ...
    private ClientRepository $clientRepository;
    // ...
    public function __construct(ClientRepository $clientRepository)
    {
        $this->clientRepository = $clientRepository;
    
        parent::__construct(); // required
    }
}
```
Of Course, you can do more difficult injecting logic, and inject some variables, or pass into constructor different arguments.
To do that you should describe this adapter in your ```config/services.yaml``` file.
```yaml
services:
  # ...
  App\Adapter\Gateway\MyAdapter:
    arguments:
      $clientRepository: '@App\Repository\ClientRepository'
      $adminEmail: 'manager@example.com'
```
Then you can use them in your adapter construct method
```php
use App\Repository\ClientRepository;

class MyAdapter extends AbstractGatewayAdapter
{
    // ...
    private ClientRepository $clientRepository;
    
    private string $email;
    // ...
    public function __construct(ClientRepository $clientRepository, string $adminEmail)
    {
        $this->clientRepository = $clientRepository;
        $this->email = $adminEmail;
    
        parent::__construct(); // required
    }
}
```
Also, you can use dynamic number of the arguments
```yaml
services:
  # ...
  App\Adapter\Gateway\MyAdapter:
    arguments:
      - '@App\Repository\ClientRepository'
      - '@App\Repository\CustomerRepository'
```
After that, use it in the constructor method of your adapter class
```php
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepositoryInterface;

class MyAdapter extends AbstractGatewayAdapter
{
    // ...
    public function __construct(ServiceEntityRepositoryInterface ...$repositories)
    {
        // ...
    
        parent::__construct(); // required
    }
}
```
This symfony features provide you different solutions to configure your adapters

> Note: All available service configuration options you can read in the official symfony documentation

## How to use
After you've configured your adapters all you have to do is inject ```GatewayManager``` into your service class or controller method and use it
```php
use Creatortsv\OmnipayManagerBundle\GatewayManger;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MyController extends AbstractController
{
    // ...
    public function createPayment(GatewayManger $manager)
    {
        // ...
        $response = $manager
            ->use('Kuberaco')
            ->createPayment(); // Your described method to do payment in the same way for different gateways
        // ...
    }
}
```
Done!

> Note: Describing identical method for all gateways depends on **Omnipay** gateway driver
