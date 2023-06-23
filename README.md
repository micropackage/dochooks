# DocHooks

[![BracketSpace Micropackage](https://img.shields.io/badge/BracketSpace-Micropackage-brightgreen)](https://bracketspace.com)
[![Latest Stable Version](https://poser.pugx.org/micropackage/dochooks/v/stable)](https://packagist.org/packages/micropackage/dochooks)
[![PHP from Packagist](https://img.shields.io/packagist/php-v/micropackage/dochooks.svg)](https://packagist.org/packages/micropackage/dochooks)
[![Total Downloads](https://poser.pugx.org/micropackage/dochooks/downloads)](https://packagist.org/packages/micropackage/dochooks)
[![License](https://poser.pugx.org/micropackage/dochooks/license)](https://packagist.org/packages/micropackage/dochooks)

<p align="center">
    <img src="https://bracketspace.com/extras/micropackage/micropackage-small.png" alt="Micropackage logo"/>
</p>

## 🧬 About DocHooks

The Laravel or Symfony projects are using method annotations for various things. This helps to have the project organized without too much code. WordPress has no implementation of such concept and this package is a remedy.

DocHooks package allows you to do:

```php
class Example extends HookAnnotations {

	/**
	 * @action test
	 */
	public function test_action() {}

	/**
	 * @filter test 5
	 */
	public function test_filter( $val, $arg ) {
		return $val;
	}

	/**
	 * @shortcode test
	 */
	public function test_shortcode( $atts, $content ) {
		return 'This is test';
	}

}

$example = new Example();
$example->add_hooks();
```

Instead of old:

```php
$example = new Example();

add_action( 'test', [ $example, 'test_action' ] );
add_filter( 'test', [ $example, 'test_filter' ], 5, 2 );
add_shortcode( 'test', [ $example, 'test_shortcode' ] );
```

## 💾 Installation

``` bash
composer require micropackage/dochooks
```

## 🕹 Usage

### Annotations

```
@action <hook_name*> <priority>
@filter <hook_name*> <priority>
@shortcode <shortcode_name*>
```

The hook and shortcode name is required while default priority is `10`.

You don't provide the argument count, the class will figure it out. Just use the callback params you want.

### Test if DocHooks are working

When OPCache has the `save_comments` and `load_comments` disabled, this package won't work, because the comments are stripped down. [See the fallback solution](#fallback).

```php
use Micropackage\DocHooks\Helper;

(bool) Helper::is_enabled();
```

### Using within the class

You can extend the HookAnnotations class:

```php
use Micropackage\DocHooks\HookAnnotations;

class Example extends HookAnnotations {

	/**
	 * @action test
	 */
	public function test_action() {}

}

$example = new Example();
$example->add_hooks();
```

Or use the Trait:

```php
use Micropackage\DocHooks\HookTrait;

class Example {

	use HookTrait;

	/**
	 * @action test
	 */
	public function test_action() {}

}

$example = new Example();
$example->add_hooks();
```

### Using as a standalone object

```php
use Micropackage\DocHooks\Helper;

class Example {

	/**
	 * @action test
	 */
	public function test_action() {}

}

$example = Helper::hook( new Example() );
```

### Fallback

Because the HookAnnotations object stores the called hooks in `_called_doc_hooks` property, you are able to pull them out and parse them into a list of old `add_action`, `add_filter` and `add_shortcode` functions.

For this you'll need a central "repository" of all objects with hooks ie. Runtime class. [See the example of this approach](https://github.com/BracketSpace/Notification/blob/master/src/Cli/DumpHooks.php) in the Notification plugin, which uses the WP CLI to dump all the hooks into separate file.

## 📦 About the Micropackage project

Micropackages - as the name suggests - are micro packages with a tiny bit of reusable code, helpful particularly in WordPress development.

The aim is to have multiple packages which can be put together to create something bigger by defining only the structure.

Micropackages are maintained by [BracketSpace](https://bracketspace.com).

## 📖 Changelog

[See the changelog file](./CHANGELOG.md).

## 📃 License

This software is released under MIT license. See the [LICENSE](./LICENSE) file for more information.
