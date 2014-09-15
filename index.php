<?php

require 'vendor/autoload.php';
$app = new \Slim\Slim([
    'view' => new \Slim\Views\Twig()
]);

// Configure Twig
$view = $app->view();
$view->parserOptions = [
    'debug' => true,
    'cache' => false,
];
$view->parserExtensions = [
    new \Slim\Views\TwigExtension(),    // useful Slim integration( urlfor, etc)
    new Twig_Extension_Debug(),         // required for {{ dump() }}
];

// Set up service manager for Zend\Form
$config = [
    'validators' => [
        'invokables' => [
            'email-address' => 'RKA\Validator\EmailAddress',
            'string-length' => 'RKA\Validator\StringLength',
            // etc.
        ],
    ],
    'form_elements' => [
        'invokables' => [
            'RKA\ExampleForm'  => 'RKA\ExampleForm',
        ],
    ],
];

$smConfigurator = new RKA\ServiceManagerConfigurator();
$app->serviceManager = $smConfigurator->createServiceManager($config);

// Set up Twig fallback function
$viewHelperManager = $app->serviceManager->get('ViewHelperManager');
$renderer = new \Zend\View\Renderer\PhpRenderer();
$renderer->setHelperPluginManager($viewHelperManager);

$view->getInstance()->registerUndefinedFunctionCallback(
    function ($name) use ($viewHelperManager, $renderer) {
        if (!$viewHelperManager->has($name)) {
            return false;
        }

        $callable = [$renderer->plugin($name), '__invoke'];
        $options  = ['is_safe' => ['html']];
        return new \Twig_SimpleFunction(null, $callable, $options);
    }
);

// Setup routes
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

    $app->render('home.twig', [
        'form' => $form
    ]);
})->via('GET', 'POST');

// Run application
$app->run();
