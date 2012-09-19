<?php

namespace Celsius\Celsius3Bundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class CatalogType extends AbstractType
{

    private $instance;

    public function __construct($instance = null)
    {
        $this->instance = $instance;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
                ->add('name')
                ->add('url')
                ->add('comments', 'textarea', array(
                    'required' => false,
                ))
                ->add('institution', null, array(
                    'required' => false,
                ))
        ;

        if (is_null($this->instance))
        {
            $builder->add('instance', null, array(
                'required' => false,
            ));
        } else
        {
            $builder->add('instance', 'instance_selector', array(
                'data' => $this->instance,
                'attr' => array(
                    'value' => $this->instance->getId(),
                    'readonly' => 'readonly',
                ),
            ));
        }
    }

    public function getName()
    {
        return 'celsius_celsius3bundle_catalogtype';
    }

}