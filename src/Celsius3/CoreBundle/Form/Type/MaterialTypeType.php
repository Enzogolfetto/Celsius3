<?php

namespace Celsius3\CoreBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class MaterialTypeType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
                ->add('title')
                ->add('authors')
                ->add('year')
                ->add('startPage')
                ->add('endPage')
        ;
        $builder->setAttribute('label', false);
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Celsius3\\CoreBundle\\Document\\MaterialType',
        ));
    }

    public function getName()
    {
        return 'celsius3_corebundle_materialtypetype';
    }

}
