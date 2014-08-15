<?php
namespace RKA;

use Slim\Slim;
use Zend\View\Renderer\RendererInterface;

class View extends \Slim\View implements RendererInterface
{
    protected $viewHelpers;
    protected $helperCache;

    public function __construct()
    {
        parent::__construct();

        $app = Slim::getInstance();
        $sm = $app->serviceManager;
        $this->viewHelpers = $sm->get('ViewHelperManager');
    }

    public function plugin($name, array $options = null)
    {
        $helper = $this->viewHelpers->get($name, $options);
        $helper->setView($this);
        return $helper;
    }

    public function __call($method, $argv)
    {
        if (!isset($this->helperCache[$method])) {
            $this->helperCache[$method] = $this->plugin($method);
        }
        if (is_callable($this->helperCache[$method])) {
            return call_user_func_array($this->helperCache[$method], $argv);
        }
        return $this->helperCache[$method];

    }

    // Required by RendererInterface
    public function render($template, $data = null)
    {
        return parent::render($template, $data);
    }

    public function getEngine()
    {
        return $this;
    }
    
    public function setResolver(\Zend\View\Resolver\ResolverInterface $resolver)
    {
        return $this;
    }
}
