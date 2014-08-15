<?php

namespace RKA;

use Zend\Form\Form;
use Zend\InputFilter\InputFilterProviderInterface;

class ExampleForm extends Form implements InputFilterProviderInterface
{
    public function init()
    {
        $this->add([
            'name' => 'email',
            'options' => [
                'label' => 'Email address',
            ],
            'attributes' => [
                'id'       => 'email',
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
