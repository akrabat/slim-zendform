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

$serviceManager = new \Zend\ServiceManager\ServiceManager();
$smConfigurator = new RKA\ServiceManagerConfigurator($serviceManager, $config);
$app->container->set('serviceManager', $serviceManager);

$app->map('/', function () use ($app) {

    $formElementManager = $app->serviceManager->get('FormElementManager');
    $form = $formElementManager->get("RKA\ExampleForm");

    if ($app->request->isPost()) {
        $data = $app->request->post();
        // $data = array('email' => 'asdf');
        $form->setData($data);
        $isValid = $form->isValid();
        LDBG($isValid);
        LDBG($form->getMessages());
        exit;
    }


    $app->render('home.php', array(
        'form' => $form
    ));
})->via('GET', 'POST');

$app->run();
