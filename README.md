[![tests](https://github.com/creatortsv/omnipay-manager-bundle/actions/workflows/php.yml/badge.svg?branch=main)](https://github.com/creatortsv/omnipay-manager-bundle/actions/workflows/php.yml)

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
The first thing that you should is supposed to create your ```Adapter``` extended with ```AbstractGatewayAdapter``` class for each **Omnipay** gateway package which you're going to use

```php
// ...
use Creatortsv\OmnipayManagerBundle\Adapter\OmnipayGatewayAdapter;

class MyAdapter extends OmnipayGatewayAdapter
{
    // ...
}
```
This classes will be automatically registered with the ```Creatortsv\OmnipayManagerBundle\GatewayManager``` by default

The second, your classes should be implemented with required ```public static getOmnipayGatewayAlias``` method, this method should return the name of the **Omnipay** gateway which will be used with this adapter class

For example:
```php
// ...
class MyAdapter extends OmnipayGatewayAdapter
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

## How to use
After you've configured your adapters all you have to do is inject ```GatewayManager``` into your service class or controller method and use it

```php
use Creatortsv\OmnipayManagerBundle\GatewayManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MyController extends AbstractController
{
    // ...
    public function createPayment(GatewayManager $manager)
    {
        $response = $manager
            ->get('Kuberaco')
            ->createPayment(); // Your described method to do payment in the same way for different gateways
        
        // ...
    }
}
```
Done!

> Note: Describing identical method for all gateways depends on **Omnipay** gateway driver
