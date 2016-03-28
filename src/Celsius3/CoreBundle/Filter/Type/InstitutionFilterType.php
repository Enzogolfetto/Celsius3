<?php

/*
 * Celsius3 - Order management
 * Copyright (C) 2014 PREBI-SEDICI <info@prebi.unlp.edu.ar> http://prebi.unlp.edu.ar http://sedici.unlp.edu.ar
 *
 * This file is part of Celsius3.
 *
 * Celsius3 is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Celsius3 is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Celsius3.  If not, see <http://www.gnu.org/licenses/>.
 */

namespace Celsius3\CoreBundle\Filter\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class InstitutionFilterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->setMethod('GET');

        $builder
                ->add('name', null, array(
                    'required' => false,
                ))
                ->add('abbreviation', null, array(
                    'required' => false,
                ));
        $builder->add('country', EntityType::class, array(
                'class' => 'Celsius3CoreBundle:Country',
                'mapped' => false,
                'placeholder' => '',
                'required' => false,
                'attr' => array(
                    'class' => 'country-select'
                ),
                'auto_initialize' => false,
            ));

        $builder->add('city', EntityType::class, array(
                'class' => 'Celsius3CoreBundle:City',
                'mapped' => true,
                'placeholder' => '',
                'required' => false,
                'attr' => array(
                    'class' => 'city-select'
                ),
                'auto_initialize' => false,
            ));
              
        $builder->add('parent', EntityType::class, array(
                'class' => 'Celsius3CoreBundle:Institution',
                'mapped' => true,
                'label' => ucfirst('institution padre'),
                'placeholder' => '',
                'required' => false,
                'attr' => array(
                    'class' => 'institution-select'
                ),
                'auto_initialize' => false,
            ));

               
               
        ;
        if (is_null($options['instance'])) {
            $builder
                    ->add('instance', EntityType::class, array(
                        'required' => false,
                        'class' => 'Celsius3CoreBundle:Instance',
                        'label' => 'Owning Instance',
                    ))
                    ->add('celsiusInstance', EntityType::class, array(
                        'required' => false,
                        'class' => 'Celsius3CoreBundle:Instance',
                        'label' => 'Celsius Instance',
                    ))
            ;
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'csrf_protection' => false,
            'instance' => null,
        ));
    }

    public function getBlockPrefix()
    {
        return '';
    }
}
