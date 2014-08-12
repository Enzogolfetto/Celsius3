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
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class InstanceFilterType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
                ->add('name', null,
                        array('required' => false,))
                ->add('abbreviation', null,
                        array('required' => false,))
                ->add('email', null,
                        array('required' => false,))
                ->add('institution', 'document',
                        array('required' => false,
                                'class' => 'Celsius3CoreBundle:Institution'));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver
                ->setDefaults(
                        array('csrf_protection' => false,));
    }

    public function getName()
    {
        return 'celsius3_corebundle_instancefiltertype';
    }

}