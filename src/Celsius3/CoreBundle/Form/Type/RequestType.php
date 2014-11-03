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

namespace Celsius3\CoreBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Celsius3\CoreBundle\Manager\OrderManager;
use Celsius3\CoreBundle\Entity\Instance;
use Celsius3\CoreBundle\Entity\BaseUser;
use JMS\TranslationBundle\Annotation\Ignore;
use Celsius3\CoreBundle\Manager\InstanceManager;
use Doctrine\ORM\EntityRepository;

class RequestType extends AbstractType
{
    private $instance;
    private $user;
    private $operator;
    private $librarian;

    public function __construct(Instance $instance, BaseUser $user = null, BaseUser $operator = null, $librarian = false)
    {
        $this->instance = $instance;
        $this->user = $user;
        $this->operator = $operator;
        $this->librarian = $librarian;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
                ->add('type', 'choice', array(
                    'choices' => array(
                        /** @Ignore */ OrderManager::TYPE__SEARCH => ucfirst(OrderManager::TYPE__SEARCH),
                        /** @Ignore */ OrderManager::TYPE__PROVISION => ucfirst(OrderManager::TYPE__PROVISION),
                    ),
                ))
                ->add('comments', 'textarea', array(
                    'required' => false,
                ))
                ->add('owner', 'celsius3_corebundle_user_selector', array(
                    'attr' => array(
                        'value' => (!is_null($this->user)) ? $this->user->getId() : '',
                        'class' => 'container',
                        'readonly' => 'readonly',
                    ),
                ))
        ;

        if ($this->librarian) {
            $builder
                    ->add('target', 'choice', array(
                        'choices' => array(
                            'me' => 'Me',
                            'other' => 'Other'
                        ),
                        'mapped' => false,
                    ))
                    ->add('librarian', 'celsius3_corebundle_user_selector', array(
                        'attr' => array(
                            'readonly' => 'readonly',
                        ),
                    ))
                    ->add('owner_autocomplete', 'text', array(
                        'attr' => array(
                            'class' => 'autocomplete',
                            'target' => 'BaseUser',
                            'value' => $this->user,
                        ),
                        'mapped' => false,
                        'label' => 'Owner',
                    ))
            ;
        }

        if (!is_null($this->operator)) {
            $builder
                    ->add('owner_autocomplete', 'text', array(
                        'attr' => array(
                            'class' => 'autocomplete',
                            'target' => 'BaseUser',
                            'value' => $this->user,
                        ),
                        'mapped' => false,
                        'label' => 'Owner',
                    ))
                    ->add('operator', 'celsius3_corebundle_user_selector', array(
                        'attr' => array(
                            'value' => (!is_null($this->operator)) ? $this->operator->getId() : '',
                            'class' => 'container',
                            'readonly' => 'readonly',
                        ),
                    ))
            ;
        }

        if ($this->instance->getUrl() === InstanceManager::INSTANCE__DIRECTORY) {
            $builder
                    ->add('instance', null, array(
                        'query_builder' => function (EntityRepository $repository) {
                            return $repository->findAllExceptDirectory();
                        },
                    ))
            ;
        } else {
            $builder
                    ->add('instance', 'celsius3_corebundle_instance_selector', array(
                        'data' => $this->instance,
                        'attr' => array(
                            'value' => $this->instance->getId(),
                            'readonly' => 'readonly',
                        ),
                    ))
            ;
        }
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Celsius3\\CoreBundle\\Entity\\Request',
        ));
    }

    public function getName()
    {
        return 'celsius3_corebundle_requesttype';
    }
}