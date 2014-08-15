<?php

namespace RKA;

use Zend\Form\Form;
use Zend\InputFilter\InputFilterProviderInterface;

class ExampleForm extends Form implements InputFilterProviderInterface
{
    public function __construct($name = null, $options = array())
    {
        $name = "example";
        parent::__construct($name, $options);

        // $this->init();
    }

    public function init()
    {
        $this->add([
            'name' => 'email',
            'type' => 'text',
            'options' => [
                'label' => 'Email address',
            ],
            'attributes' => [
                'class'    => 'form-control',
                'required' => 'required',
            ],
        ]);

        $this->add([
            'name' => 'submit',
            'type' => 'button',
            'options' => [
                'label' => 'Go!',
            ],
            'attributes' => [
                'class' => 'btn btn-default',
            ],
        ]);
    }

    public function getInputFilterSpecification()
    {
        return [
            'email' => [
                'required' => true,
                'filters'  => [
                    ['name' => 'StringTrim'],
                    ['name' => 'StripTags'],
                ],
                'validators' => [
                    ['name' => 'EmailAddress'],
                ],
            ],
        ];
    }
}
