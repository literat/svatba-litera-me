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
$mailer = $container->getService('mailer');

// Setup routes
$router = $container->getService('router');
$router[] = new Route('', function($presenter) use ($mailer) {

	// create contact form
	$form = new Form;
	$form->addText('name', 'Vaše jméno')
		->addRule(Form::FILLED, 'Zadejte vaše jméno')
		->setAttribute('class', 'form-control')
		->setAttribute('placeholder', 'Vaše jméno');
	$form->addText('email', 'Váš E-mail')
		->addRule(Form::FILLED, 'Zadejte váš e-mail')
		->addRule(Form::EMAIL, 'Zadejte platnou e-mailovou adresu')
		->setAttribute('class', 'form-control')
		->setAttribute('placeholder', 'Váš E-mail');
	$form->addText('subject', 'Předmět')
		->addRule(Form::FILLED, 'Zadejte předmět zprávy')
		->setAttribute('class', 'form-control')
		->setAttribute('placeholder', 'Předmět');
	$form->addTextArea('message', 'Zpráva')
		->addRule(Form::FILLED, 'Zadejte zprávu')
		->setAttribute('class', 'form-control')
		->setAttribute('placeholder', 'Prosím, něco hezkého mi napište...')
		->setAttribute('row', 10);
	$form->addSubmit('send', 'ODESLAT ZPRÁVU')
		->setAttribute('class', 'btn btn-default btn-xl wow tada');

	// create template
	$template = $presenter->createTemplate()->setFile(__DIR__ . '/../app/templates/main.latte');
	if(!isset($template->flashMessage)) {
		$template->flashMessage = '';
	}
	// assign form
	$template->form = $form;

	// form on success
	if ($form->isSuccess()) {
		$values = $form->getValues();

		$message = new Message;
		$message->addTo('tomas@litera.me')
			->setFrom($values['email'], $values['name'])
			->setSubject($values['subject'])
			->setBody($values['message']);

		$mailTemplate = $presenter->createTemplate()->setFile(__DIR__ . '/../app/templates/email.latte');
		$mailTemplate->title = 'Zpráva ze svatebního formuláře';
		$mailTemplate->values = $values;

		$message->setHtmlBody($mailTemplate);
		$mailer->send($message);

		$template->flashMessage = 'Vaše zpráva byla odeslána! Děkuji.';
		$presenter->redirectUrl($presenter->context->httpRequest->url->baseUrl . '#contact-form');
	}

	return $template;
});

// Run the application!
$container->getService('application')->run();
