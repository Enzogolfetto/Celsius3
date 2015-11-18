<?php

/*
 * Celsius3 - Order management
 * Copyright (C) 2014 PrEBi <info@prebi.unlp.edu.ar>
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

class JournalFilterType extends AbstractType
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
                ))
                ->add('responsible', null, array(
                    'required' => false,
                ))
                ->add('ISSN', null, array(
                    'required' => false,
                ))
                ->add('ISSNE', null, array(
                    'required' => false,
                ))
                ->add('frecuency', null, array(
                    'required' => false,
                ))
        ;

        if (is_null($options['instance'])) {
            $builder
                    ->add('instance', EntityType::class, array(
                        'required' => false,
                        'class' => 'Celsius3CoreBundle:Instance',
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
