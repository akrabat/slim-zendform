<?php

namespace RKA;

use Zend\ServiceManager\Config as ServiceConfig;
use Zend\ServiceManager\ServiceManager;
use Zend\Stdlib\ArrayUtils;

class ServiceManagerConfigurator
{
    protected $serviceManagers = array();
    protected $defaultServiceConfig = array();

    public function __construct($serviceManager, $config = null)
    {
        $this->defaultServiceManager = $serviceManager;
        $this->setupPluginManagers($serviceManager);

        $this->addServiceManager($serviceManager, 'service_manager');
        $this->addServiceManager('ValidatorManager', 'validators');
        $this->addServiceManager('FilterManager', 'filters');
        $this->addServiceManager('FormElementManager', 'form_elements');
        $this->addServiceManager('InputFilterManager', 'input_filters');
        // $this->addServiceManager('ViewHelperManager', 'view_helpers');
         
        if ($config) {
            $this->configureServiceManager($config);
        }
    }

    public function setupPluginManagers($serviceManager)
    {
        $pluginManagers = array(
            'FilterManager' => 'Zend\Filter\FilterPluginManager',
            'FormElementManager' => 'Zend\Form\FormElementManager',
            'InputFilterManager' => 'Zend\InputFilter\InputFilterPluginManager',
            'ValidatorManager' => 'Zend\Validator\ValidatorPluginManager',
            // 'ViewHelperManager' => 'Zend\View\HelperPluginManager'
        );

        foreach ($pluginManagers as $key => $className) {
            $m = new $className;
            $m->setServiceLocator($serviceManager);
            $serviceManager->setService($key, $m);
        }

        // configure ViewHelperManager
        // $viewHelperManager = $this->defaultServiceManager->get('ViewHelperManager');
        // $config = new Zend\Form\View\HelperConfig;
        // $config->configureServiceManager($viewHelperManager);

    }

    public function configureServiceManager($config)
    {
        foreach ($this->serviceManagers as $key => $sm) {

            $smConfig = array();
            if (isset($config[$sm['config_key']])
                && is_array($config[$sm['config_key']])) {
                // Use the configuration for this ServiceManager within $config
                $smConfig = $config[$sm['config_key']];
            }
            
            // Get this service manager from the default service manager
            if (!$sm['service_manager'] instanceof ServiceManager) {
                $instance = $this->defaultServiceManager->get($sm['service_manager']);
                if (!$instance instanceof ServiceManager) {
                    throw new \RuntimeException(sprintf(
                        'Could not find a valid ServiceManager for %s',
                        $sm['service_manager']
                    ));
                }
                $sm['service_manager'] = $instance;
            }

            // Apply the config to this service manager
            $serviceConfig = new ServiceConfig($smConfig);
            $serviceConfig->configureServiceManager($sm['service_manager']);
        }
    }

    public function addServiceManager($serviceManager, $key)
    {
        if (is_string($serviceManager)) {
            $smKey = $serviceManager;
        } elseif ($serviceManager instanceof ServiceManager) {
            $smKey = spl_object_hash($serviceManager);
        } else {
            throw new \RuntimeException(sprintf(
                'Invalid service manager provided, expected ServiceManager or string, %s provided',
                (string) $serviceManager
            ));
        }

        $this->serviceManagers[$smKey] = array(
            'service_manager'        => $serviceManager,
            'config_key'             => $key,
        );

        if ($key === 'service_manager' && $this->defaultServiceConfig) {
            $this->serviceManagers[$smKey]['configuration']['default_config'] = $this->defaultServiceConfig;
        }

        return $this;
    }
}
