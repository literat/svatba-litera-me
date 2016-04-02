<?php

use Nette\Application\Routers\Route;
use Nette\Forms\Form;
use Nette\Mail\Message;

// Load libraries
require __DIR__ . '/../app/libs/nette.phar';

$configurator = new Nette\Configurator;

// Enable Nette Debugger for error visualisation & logging
$configurator->enableDebugger(__DIR__ . '/../app/temp/log');

// Configure libraries
$configurator->setTempDirectory(__DIR__ . '/../app/temp');

// Create Dependency Injection container from config.neon file
$configurator->addConfig(__DIR__ . '/../app/config/config.neon', $configurator::AUTO);
$container = $configurator->createContainer();

// Setup routes
// http://kegege.net/[cs|en]
$router = $container->getService('router');
$router[] = new Route('', function($presenter) {

	// create template
	$template = $presenter->createTemplate()->setFile(__DIR__ . '/../app/templates/main.latte');

	// register template helpers like {$foo|date}
	// $template->registerHelper('date', function($date) use ($lang) {
	// 	if ($lang === 'en') {
	// 		return date('F j, Y', (int) $date);
	// 	} else {
	// 		static $months = array(1 => 'leden', 'únor', 'březen', 'duben', 'květen', 'červen', 'červenec', 'srpen', 'září', 'říjen', 'listopad', 'prosinec');
	// 		$date = getdate((int) $date);

	// 		return "$date[mday]. {$months[$date['mon']]} $date[year]";
	// 	}
	// });

	return $template;
});

// Run the application!
$container->getService('application')->run();
