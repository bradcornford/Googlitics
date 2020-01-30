# An easy way to integrate Google Analytics with Laravel

[![Latest Stable Version](https://poser.pugx.org/cornford/Googlitics/version.png)](https://packagist.org/packages/cornford/googlitics)
[![Total Downloads](https://poser.pugx.org/cornford/googlitics/d/total.png)](https://packagist.org/packages/cornford/googlitics)
[![Build Status](https://travis-ci.org/bradcornford/Googlitics.svg?branch=master)](https://travis-ci.org/bradcornford/Googlitics)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/bradcornford/Googlitics/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/bradcornford/Googlitics/?branch=master)

### For Laravel 5.x, check [version 2.5.0](https://github.com/bradcornford/Googlitics/tree/v2.5.0)

### For Laravel 4.x, check [version 1.1.0](https://github.com/bradcornford/Googlitics/tree/v1.1.0)

Think of Googlitics as an easy way to integrate Google Analytics with Laravel, providing a variety of helpers to speed up the utilisation of application tracking. These include:

- `Analytics::trackPage`
- `Analytics::trackScreen`
- `Analytics::trackEvent`
- `Analytics::trackTransaction`
- `Analytics::trackItem`
- `Analytics::trackMetric`
- `Analytics::trackException`
- `Analytics::trackCustom`
- `Analytics::render`

## Installation

Begin by installing this package through Composer. Edit your project's `composer.json` file to require `cornford/googlitics`.

	"require": {
		"cornford/googlitics": "3.*"
	}

Next, update Composer from the Terminal:

	composer update

Once this operation completes, the next step is to add the service provider. Open `app/config/app.php`, and add a new item to the providers array.

	Cornford\Googlitics\AnalyticsServiceProvider::class,

The next step is to introduce the facade. Open `app/config/app.php`, and add a new item to the aliases array.

	'Analytics'         => Cornford\Googlitics\Facades\AnalyticsFacade::class,

Finally we need to introduce the configuration files into your application/

	php artisan vendor:publish --provider="Cornford\Googlitics\AnalyticsServiceProvider" --tag=googlitics

That's it! You're all set to go.

## Configuration

You can now configure Googlitics in a few simple steps. Open `app/config/packages/cornford/googlitics/config.php` and update the options as needed.

- `enabled` - Enable Google Analytics tracking.
- `id` A Google - Analytics tracking identifier to link Googlitics to Google Analytics.
- `domain` - The domain which is being tracked. Leave as 'auto' if you want Googlitics to automatically set the current domain. Otherwise enter your domain, e.g. google.com
- `anonymise` - Anonymise users IP addresses when tracking them via Googlitics.
- `automatic` - Enable automatic tracking to ensure users are tracked automatically by Googlitics.

## Usage

It's really as simple as using the Analytics class in any Controller / Model / File you see fit with:

`Analytics::`

This will give you access to

- [Track Page](#track-page)
- [Track Screen](#track-screen)
- [Track Event](#track-event)
- [Track Transaction](#track-transaction)
- [Track Item](#track-item)
- [Track Metric](#track-metric)
- [Track Exception](#track-exception)
- [Track Custom](#track-custom)
- [Render](#render)

### Track Page

The `trackPage` method allows a page to be tracked, with optional parameters for page, title and track type.

	Analytics::trackPage();
	Analytics::trackPage('Homepage', 'Homepage Title');
	Analytics::trackPage('Homepage', 'Homepage Title', Analytics::TYPE_PAGEVIEW);

### Track Screen

The `trackScreen` method allows a screen in an application to be tracked, with a parameter for the screen name.

	Analytics::trackScreen('Homepage');

### Track Event

The `trackEvent` method allows an event to be tracked, with parameters for category, and action option parameters for label and value.

	Analytics::trackEvent();
	Analytics::trackEvent('User', 'Sign up');
	Analytics::trackEvent('User', 'Sign up', 'User - Sign up', date());

### Track Transaction

The `trackTransaction` method allows an ecommerce transaction to tracked, with parameters for identifier, and an optional options parameter for affiliation, revenue, shipping, tax in a key value array format.

	Analytics::trackTransaction('123');
	Analytics::trackTransaction('123', ['affiliation' => 'Clothing', 'revenue' => '12.99', 'shipping' => '7.99', 'tax' => '1.59']);

### Track Item

The `trackItem` method allows an ecommerce item to tracked, with parameters for identifier and name, and an optional options parameter for sku, category, price, quantity in a key value array format.

	Analytics::trackItem('123', 'Socks');
	Analytics::trackItem('123', 'Socks', ['sku' => 'PR123', 'category' => 'Clothing', 'price' => '7.99', 'quantity' => '1']);

### Track Metric

The `trackMetric` method allows a metric to be tracked, with parameters for category, and an options parameter in a key value array format.

	Analytics::trackMetric('Metric', ['metric1' => 100]);

### Track Exception

The `trackException` method allows application exceptions to be tracked, with optional parameters for description and fatality.

	Analytics::trackException();
	Analytics::trackException('500 Server Error', true);

### Track Custom

The `trackCustom` method allows custom items to be tracked with a single parameter for the custom item.

	Analytics::trackCustom("ga('custom', 'parameter');");

### Render

The `render` method allows all tracking items to be rendered to the page, this method can be included in Views or added as controller passed parameter.

	Analytics::render();

### License

Googlitics is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT)