<?php

namespace Celsius\Celsius3Bundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class PatentTypeType extends MaterialTypeType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        parent::buildForm($builder, $options);
    }
    
    public function getDefaultOptions(array $options)
    {
        return array(
            'data_class' => 'Celsius\\Celsius3Bundle\\Document\\PatentType',
        );
    }

    public function getName()
    {
        return 'celsius_celsius3bundle_patenttype';
    }
}
