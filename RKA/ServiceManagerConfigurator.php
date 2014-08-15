<?php

namespace RKA;

use Zend\ServiceManager\Config as ServiceConfig;
use Zend\ServiceManager\ServiceManager;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class ServiceManagerConfigurator
{
    // list of plugin managers to register
    public $pluginManagers = [
        'FilterManager'      => 'Zend\Filter\FilterPluginManager',
        'FormElementManager' => 'Zend\Form\FormElementManager',
        'InputFilterManager' => 'Zend\InputFilter\InputFilterPluginManager',
        'ValidatorManager'   => 'Zend\Validator\ValidatorPluginManager',
        'ViewHelperManager'  => 'Zend\View\HelperPluginManager',
    ];
    
    // config keys to look for in the config array
    public $configKeys = [
        'validators'      => 'ValidatorManager',
        'filters'         => 'FilterManager',
        'form_elements'   => 'FormElementManager',
        'input_filters'   => 'InputFilterManager',
        'view_helpers'    => 'ViewHelperManager',
    ];

    public function createServiceManager(array $config)
    {
        // create serviceManager
        $serviceManager = new ServiceManager();

        // set an initializer for ServiceLocatorAwareInterface
        $serviceManager->addInitializer(
            function ($instance, ServiceLocatorInterface $serviceLocator) {
                if ($instance instanceof ServiceLocatorAwareInterface) {
                    $instance->setServiceLocator($serviceLocator);
                }
            }
        );
        // add $serviceManger to the config keys so we can configure it
        $this->configKeys = ['service_manager' => $serviceManager] + $this->configKeys;

        // add the pluginManagers to the ServiceManager
        foreach ($this->pluginManagers as $key => $className) {
            $serviceManager->setInvokableClass($key, $className);
        }

        // add Zend\Form's view helpers to the ViewHelperManager
        $viewHelperManager = $serviceManager->get('ViewHelperManager');
        $vhConfig = new \Zend\Form\View\HelperConfig;
        $vhConfig->configureServiceManager($viewHelperManager);

        // apply configuration
        $this->applyConfig($serviceManager, $config);
        return $serviceManager;
    }

    public function applyConfig($serviceManager, $config)
    {
        foreach ($this->configKeys as $keyName => $thisManager) {

            $smConfig = array();
            if (isset($config[$keyName]) && is_array($config[$keyName])) {
                $smConfig = $config[$keyName];
            }
            
            // Get this service manager from the main service manager if it
            // isn't an instance already
            if (!$thisManager instanceof ServiceManager) {
                $thisManager = $serviceManager->get($thisManager);
            }

            // Apply the config to this service manager
            $serviceConfig = new ServiceConfig($smConfig);
            $serviceConfig->configureServiceManager($thisManager);
        }
    }
}
