<?php

	/**
	 * Site configuration file.
	 *
	 * Change the settings in this file to customise for a new client
	 *
	 * @copyright 2013 Bluepod Media Ltd
	 * @author Barry Jones <barry@onalldevices.com>
	 */

	// Debug mode
	define("DEV_DEBUG", true);

	// Language file to use
	define("LANG", 'en');

	// UAM Secret and password
	define('UAM_SECRET', "C0ns3ga");
	define('UAM_PWORD', 1);

	// Site URL root
	define('SITE_URL', 'https://regtest.movieworx.co.uk/hotspotlogin/');

	// Login page navigation list. Leave as an empty array to hide the navigation element
	global $nav, $postloginurl;
	$nav = array(
		array(
			"name"      => 'About This Hotspot',
			"title"     => 'About this WiFi Hotspot',
			"url"       => 'http://onalldevices.com/about'
		),
		array(
			"name"      => 'Contact Us',
			"title"     => 'Contact On All Devices',
			"url"       => 'http://onalldevices.com/contact'
		),
		array(
			"name"      => 'Terms &amp; Conditions',
			"title"     => 'WiFi Hotspot Terms and Conditions',
			"url"       => 'http://onalldevices.com'
		)
	);

	// Where to redirect to after login - either local view (views/consumer-hub.php) or external landing page
	//$postloginurl = 'http://bbc.co.uk';
	$postloginurl = 'pages/consumer-hub.php';

	// External URL - used to force a new login session
	$exturl = 'http://onalldevices.com';

	// Login auth cookie
	$cookiename     = 'daloauth';
	$cookieexpire   = (24 * 30 * 24 * 3600); // 1 year. (3600 = 1 hour)