<?php

return array(

	/*
	|--------------------------------------------------------------------------
	| Enable Tracking
	|--------------------------------------------------------------------------
	|
	| Enable Google Analytics tracking.
	|
	*/
	'enabled' => true,

	/*
	|--------------------------------------------------------------------------
	| Google Analytics Tracking Identifier
	|--------------------------------------------------------------------------
	|
	| A Google Analytics tracking identifier to link Googlitics to Google
	| Analytics.
	|
	*/
	'id' => env('GOOGLE_API_KEY', 'AIzaSyCTwWv4rTQdNtZAaAd8D1SK5m94eTp8GiQ'),

	/*
	|--------------------------------------------------------------------------
	| Tracking Domain
	|--------------------------------------------------------------------------
	|
	| The domain which is being tracked. Leave as 'auto' if you want Googlitics
	| to automatically set the current domain. Otherwise enter your domain,
	| e.g. google.com
	|
	*/
	'domain' => 'auto',

	/*
	|--------------------------------------------------------------------------
	| Anonymise Tracking
	|--------------------------------------------------------------------------
	|
	| Anonymise users IP addresses when tracking them via Googlitics.
	|
	*/
	'anonymise' => true,

	/*
	|--------------------------------------------------------------------------
	| Enable Automatic Tracking
	|--------------------------------------------------------------------------
	|
	| Enable automatic tracking to ensure users are tracked automatically by
	| Googlitics.
	|
	*/
	'automatic' => true,

);
