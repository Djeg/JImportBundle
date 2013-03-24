JImportBundle
=============

An assetic filter for javascripts file that allow you to import client javascripts easily like that :

```javascript
@import('js/foo.js');
@import('js/rep/bar.js');
@import('js/myDirectory');

// some js code here ...
```

## How install it ?

In your `composer.json` add this line :

```javascript
"davidjegat/jimport-bundle": "*"
```

Launch a `composer.phar install` or `composer.phar update`. Finaly, add the bundle
into your Symfony2 Kernel :

```php
	new DavidJegat\JImportBundle\DavidJegatJImportBundle();
```

It's done ! Jimport is install on your symfony 2.

## How use it ?

This first things to do is to register your bundles into your assetic configuration :

```yaml
assetic:
	bundles: [ 'YourBundle' ]
```

Now, with assetic import your only file that you need and add the `jimport` filter :

```html+jinja
{% 
	javascripts
	'bundles/your/js/main.js'
	filter="jimport"
%}
	
	<script src="{{asset_url}}"></script>

{% endjavascripts %}
```

## How does it works ?

Into the `main.js` file, after having register your bundle, add this simple line
when you want to import it into your javascript :

```javascript
@import('js/my/lib.js');

```

### Import priority

When you register a bundle into the `assetic.bundles` array key, the `@import` function will
look at the `Resources/public` directory of this bundle. If no file is found then the function
will parse the next bundle. If definitively no file is found, the function will
be replace the `@import` statement by an empty character string.

## Rocks with Jimport ? Code you own extension !

Jimport is a very simple file parser. It works for javascript files, css files ... any assetic
files. You can defined your own JImport function. Let's take an exemple. You need to create
a special javascript function that allow you to get a given url from your project. A sort
of router ? Let's do it !

### The first step, FunctionInterface

You can easily create your own jimport function parser by respect
this interface :

```php
namespace DavidJegat\JImportBundle\Functions;

use DavidJegat\JImportBundle\Parser\Parser;

/**
 * Defined JImport Functions behavior
 * 
 * @author David Jegat <david.jegat@gmail.com>
 */
interface FunctionInterface
{
	/**
	 * Return your function name
	 * 
	 * @return string
	 */
	public function getName();

	/**
	 * Execute the function
	 * 
	 * @param array $arguments
	 * @param Parser $parser
	 * @return string, the function relacement
	 */
	public function execute(array $arguments, Parser $parser);
}
```

### Your own function !?

So, create an object in your bundle and add this code :

```php
namespace My\CoolBundle\Jimport;

use DavidJegat\JImportBundle\Functions\FunctionInterface;
use DavidJegat\JImportBundle\Parser\Parser;

class GiveMeTheUrlFunction implements FunctionInterface
{
	/**
	 * @var Router $router, The injected router service
	 * @access private
	 */
	private $router;

	/**
	 * return the function name
	 * @return string
	 */
	public function getName()
	{
		return 'giveMeTheUrl';
	}

	/**
	 * Execute the function
	 * @param array $args, The function arguments
	 * @param Parser $parser, The JImport parser
	 * @return string
	 */
	public function execute(array $args, Parser $parser)
	{
		// just return an absolute url
		return '"'.$this->router->generate('my_road', array(), true).'"';
	}

	/**
	 * constructor, it takes the router service
	 * @param Router $router
	 */
	public function __construct($router)
	{
		$this->router = $router;
	}
}
```

### Register the function

Into your services.yml add this kind of lines :

```yaml
services:
	my_cool.jimport_function:
		class: 'My\CoolBundle\Jimport\GiveMeTheUrlFunction'
		arguments: [ '@router' ] # inject the router
		tags:
			- { name: 'davidjegat_jimport.function' } # Tag the function !
```

### Now, let's rock !

Go into your file and used your own function like that for exemple :

```javascript
function getRoad()
{
	return @giveMeTheUrl();
}
```

## Caution !

You can't use jimport for code dynamical access to the database, or interaction
with some datas because, in devellopment your files will be corectly parsed but
once the assetic will be dumped the files just be parsed once ! This is bad !
prefer to use AJAX !