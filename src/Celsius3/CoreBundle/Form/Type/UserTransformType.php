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

namespace Celsius3\CoreBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Celsius3\CoreBundle\Manager\UserManager;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class UserTransformType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $choices = array(
            UserManager::$roles_names[UserManager::ROLE_LIBRARIAN] => UserManager::ROLE_LIBRARIAN,
            UserManager::$roles_names[UserManager::ROLE_ADMIN] => UserManager::ROLE_ADMIN,
            UserManager::$roles_names[UserManager::ROLE_ORDER_MANAGER] => UserManager::ROLE_ORDER_MANAGER,
        );

        if (!is_null($options['instance'])) {


            if (in_array(UserManager::ROLE_SUPER_ADMIN,$options['user']->getRoles())){
                $choices[UserManager::$roles_names[UserManager::ROLE_STATISTICS]] = UserManager::ROLE_STATISTICS;
            }
            if (in_array(UserManager::ROLE_ADMIN,$options['user']->getRoles())){
                $choices[UserManager::$roles_names[UserManager::ROLE_ORDER_MANAGER]] = UserManager::ROLE_ORDER_MANAGER;

            }

            $builder->add($options['user']->getInstance()->getUrl(), ChoiceType::class, array(
                'choices' => $choices,
                'expanded' => true,
                'multiple' => true,
                'data' => $options['user']->getRoles()
            ));
        } else {

            $choices[UserManager::$roles_names[UserManager::ROLE_SUPER_ADMIN]] = UserManager::ROLE_SUPER_ADMIN;

            $builder->add($options['user']->getInstance()->getUrl(), ChoiceType::class, array(
                'choices' => $choices,
                'expanded' => true,
                'multiple' => true,
                'data' => $options['user']->getRoles()
            ));

            foreach ($options['user']->getSecondaryInstances() as $instance) {
                $builder->add($instance['url'], ChoiceType::class, array(
                    'choices' => $choices,
                    'expanded' => true,
                    'multiple' => true,
                    'data' => $instance['roles']
                ));
            }
        }

//        Esto se utiliza para la selección de la institución base sobre la que tiene injerencia
//        Un usuario con rol bibliotecario. Se comenta por no estar completamente implementado.
//
//        $builder->add('institution', EntityType::class, array(
//            'class' => 'Celsius3CoreBundle:Institution',
//            'mapped' => false,
//            'label' => ucfirst('institution'),
//            'placeholder' => '',
//            'required' => false,
//            'attr' => array(
//                'class' => 'institution-select'
//            ),
//            'auto_initialize' => false,
//        ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'user' => null,
            'instance' => null,
        ));
    }

    /**
     * Returns the name of this type.
     *
     * @return string The name of this type
     */
    public function getName()
    {
        // TODO: Implement getName() method.
    }

    /**
     * Returns the prefix of the template block name for this type.
     *
     * The block prefix defaults to the underscored short class name with
     * the "Type" suffix removed (e.g. "UserProfileType" => "user_profile").
     *
     * @return string The prefix of the template block name
     */
    public function getBlockPrefix()
    {
        // TODO: Implement getBlockPrefix() method.
    }

    /**
     * Sets the default options for this type.
     *
     * @param OptionsResolverInterface $resolver The resolver for the options
     *
     * @deprecated since version 2.7, to be renamed in 3.0.
     *             Use the method configureOptions instead. This method will be
     *             added to the FormTypeInterface with Symfony 3.0.
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        // TODO: Implement setDefaultOptions() method.
    }
}
