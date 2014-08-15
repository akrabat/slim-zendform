<?php

require 'vendor/autoload.php';
$app = new \Slim\Slim();

$config = [
    'validators' => array(
        'invokables' => array(
            'email-address' => 'RKA\Validator\EmailAddress',
            'string-length' => 'RKA\Validator\StringLength',
            // etc.
        ),
    ),
    'form_elements' => array(
        'invokables' => array(
            'RKA\ExampleForm'  => 'RKA\ExampleForm',
        ),
    ),

];

$smConfigurator = new RKA\ServiceManagerConfigurator();
$app->serviceManager = $smConfigurator->createServiceManager($config);
$app->view(new RKA\View());

$app->map('/', function () use ($app) {
    $formElementManager = $app->serviceManager->get('FormElementManager');
    $form = $formElementManager->get("RKA\ExampleForm");

    if ($app->request->isPost()) {
        $data = $app->request->post();
        $form->setData($data);
        $isValid = $form->isValid();
        if ($form->isValid()) {
            echo "Success!";
            exit;
        }
    }

    $app->render('home.php', array(
        'form' => $form
    ));
})->via('GET', 'POST');

$app->run();
