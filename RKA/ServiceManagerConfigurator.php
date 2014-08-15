<?php

namespace RKA;

use Zend\ServiceManager\Config as ServiceConfig;
use Zend\ServiceManager\ServiceManager;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class ServiceManagerConfigurator
{
    // List of plugin managers to register (key name => class name)
    public $pluginManagers = [
        'FilterManager'      => 'Zend\Filter\FilterPluginManager',
        'FormElementManager' => 'Zend\Form\FormElementManager',
        'InputFilterManager' => 'Zend\InputFilter\InputFilterPluginManager',
        'ValidatorManager'   => 'Zend\Validator\ValidatorPluginManager',
        'ViewHelperManager'  => 'Zend\View\HelperPluginManager',
    ];
    
    // List of config keys to look for in the config array (config key name => sm key name)
    public $configKeys = [
        'validators'      => 'ValidatorManager',
        'filters'         => 'FilterManager',
        'form_elements'   => 'FormElementManager',
        'input_filters'   => 'InputFilterManager',
        'view_helpers'    => 'ViewHelperManager',
    ];

    // Main method that does the work and gives us back a configured ServiceManager
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

    // Helper method to apply config to the service manager and all plugin managers
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
