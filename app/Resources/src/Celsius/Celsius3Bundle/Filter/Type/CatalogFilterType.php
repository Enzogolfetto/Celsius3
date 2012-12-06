<?php

namespace Celsius\Celsius3Bundle\Filter\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class CatalogFilterType extends AbstractType
{

    private $instance;

    public function __construct($instance = null)
    {
        $this->instance = $instance;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
                ->add('name', null, array(
                    'required' => false,
                ))
                ->add('institution', 'document', array(
                    'required' => false,
                    'class' => 'CelsiusCelsius3Bundle:Institution',
                ))
        ;
        if (is_null($this->instance))
        {
            $builder->add('instance', 'document', array(
                'required' => false,
                'class' => 'CelsiusCelsius3Bundle:Instance',
            ));
        }
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'csrf_protection' => false,
        ));
    }

    public function getName()
    {
        return 'celsius_celsius3bundle_catalogfiltertype';
    }

}
