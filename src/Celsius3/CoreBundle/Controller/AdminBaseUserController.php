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

namespace Celsius3\CoreBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Celsius3\CoreBundle\Entity\BaseUser;
use Celsius3\CoreBundle\Form\Type\BaseUserType;
use Celsius3\CoreBundle\Form\Type\UserTransformType;
use Celsius3\CoreBundle\Filter\Type\BaseUserFilterType;
use Celsius3\CoreBundle\Manager\StateManager;

/**
 * BaseUser controller.
 *
 * @Route("/admin/user")
 */
class AdminBaseUserController extends BaseUserController
{

    /**
     * Lists all BaseUser entities.
     *
     * @Route("/", name="admin_user")
     * @Template()
     *
     * @return array
     */
    public function indexAction()
    {
        return $this->baseIndex('BaseUser', $this->createForm(new BaseUserFilterType($this->getInstance())));
    }

    /**
     * Shows the data of a user
     *
     * @Route("/{id}/show", name="admin_user_show")
     * @Template()
     *
     * @return array
     */
    public function showAction($id)
    {
        $entity = $this->findQuery('BaseUser', $id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find BaseUser.');
        }

        $em = $this->getDoctrine()->getManager();

        $activeOrders = $em->getRepository('Celsius3CoreBundle:Order')
                ->findForInstance($this->getInstance(), null, array(StateManager::STATE__CREATED, StateManager::STATE__SEARCHED, StateManager::STATE__REQUESTED, StateManager::STATE__APPROVAL_PENDING), $entity);

        $readyOrders = $em->getRepository('Celsius3CoreBundle:Order')
                ->findForInstance($this->getInstance(), null, StateManager::STATE__RECEIVED, $entity);

        $historyOrders = $em->getRepository('Celsius3CoreBundle:Order')
                ->findForInstance($this->getInstance(), null, array(StateManager::STATE__DELIVERED, StateManager::STATE__ANNULLED, StateManager::STATE__CANCELLED), $entity);

        $messages = $this->get('fos_message.thread_manager')
                        ->getParticipantSentThreadsQueryBuilder($entity)
                        ->getQuery()->getResult();

        return array(
            'element' => $entity,
            'orders' => array(
                'active' => $activeOrders,
                'ready' => $readyOrders,
                'history' => $historyOrders,
            ),
            'messages' => $messages,
        );
    }

    /**
     * Displays a form to create a new BaseUser entity.
     *
     * @Route("/new", name="admin_user_new")
     * @Template()
     *
     * @return array
     */
    public function newAction()
    {
        return $this->baseNew('BaseUser', new BaseUser(), new BaseUserType($this->container, 'Celsius3\CoreBundle\Entity\BaseUser', $this->getInstance()));
    }

    /**
     * Creates a new BaseUser entity.
     *
     * @Route("/create", name="admin_user_create")
     * @Method("post")
     * @Template("Celsius3CoreBundle:AdminBaseUser:new.html.twig")
     *
     * @return array
     */
    public function createAction(Request $request)
    {
        $entity = new BaseUser();
        $form = $this->createForm(new BaseUserType($this->container, 'Celsius3\CoreBundle\Entity\BaseUser', $this->getInstance()), $entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            $this->get('celsius3_core.custom_field_helper')->processCustomFields($this->getInstance(), $form, $entity);

            $this->get('session')
                    ->getFlashBag()
                    ->add('success', 'The BaseUser was successfully created.');

            return $this->redirect($this->generateUrl('admin_user'));
        }

        $this->get('session')
                ->getFlashBag()
                ->add('error', 'There were errors creating the BaseUser.');

        return array(
            'entity' => $entity,
            'form' => $form->createView()
        );
    }

    /**
     * Displays a form to edit an existing BaseUser entity.
     *
     * @Route("/{id}/edit", name="admin_user_edit", options={"expose"=true})
     * @Template()
     *
     * @param string $id
     *                   The entity ID
     *
     * @return array
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException If entity doesn't exists
     */
    public function editAction($id)
    {
        return $this->baseEdit('BaseUser', $id, new BaseUserType($this->container, 'Celsius3\CoreBundle\Entity\BaseUser', $this->getInstance(), true));
    }

    /**
     * Edits an existing BaseUser entity.
     *
     * @Route("/{id}/update", name="admin_user_update")
     * @Method("post")
     * @Template("Celsius3CoreBundle:AdminBaseUser:edit.html.twig")
     *
     * @param string $id
     *                   The entity ID
     *
     * @return array
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException If entity doesn't exists
     */
    public function updateAction($id, Request $request)
    {
        $entity = $this->findQuery('BaseUser', $id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find BaseUser.');
        }

        $editForm = $this->createForm(new BaseUserType($this->container, 'Celsius3\CoreBundle\Entity\BaseUser', $this->getInstance(), true), $entity);

        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            $this->get('celsius3_core.custom_field_helper')->processCustomFields($this->getInstance(), $editForm, $entity);

            $this->get('session')
                    ->getFlashBag()
                    ->add('success', 'The BaseUser was successfully edited.');

            return $this->redirect($this->generateUrl('admin_user_edit', array(
                                'id' => $id
            )));
        }

        $this->get('session')
                ->getFlashBag()
                ->add('error', 'There were errors editing the BaseUser.');

        return array(
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
        );
    }

    /**
     * Displays a form to transform an existing BaseUser entity.
     *
     * @Route("/{id}/transform", name="admin_user_transform")
     * @Template()
     *
     * @param string $id The entity ID
     *
     * @return array
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException If entity doesn't exists
     */
    public function transformAction($id, Request $request)
    {
        if ($request->getMethod() === 'POST') {
            return $this->baseDoTransformAction($id, new UserTransformType($this->getInstance()), 'admin_user');
        }

        return $this->baseTransformAction($id, new UserTransformType($this->getInstance()));
    }

    /**
     * Enables a BaseUser entity.
     *
     * @Route("/{id}/enable", name="admin_user_enable", options={"expose"=true})
     *
     * @param string $id
     *                   The entity ID
     *
     * @return array
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException If entity doesn't exists
     */
    public function enableAction($id)
    {
        return $this->baseEnableAction($id);
    }

    /**
     * Batch actions.
     *
     * @Route("/batch", name="admin_user_batch")
     *
     * @return array
     */
    public function batchAction()
    {
        return $this->baseBatch();
    }

    protected function batchEnable($element_ids)
    {
        return $this->baseBatchEnable($element_ids);
    }

    protected function batchUnion($element_ids)
    {
        return $this->render('Celsius3CoreBundle:AdminBaseUser:batchUnion.html.twig', $this->baseUnion('BaseUser', $element_ids));
    }

    /**
     * Unifies a group of Journal entities.
     *
     * @Route("/batch/doUnion", name="admin_user_doUnion")
     * @Method("post")
     *
     * @return array
     */
    public function doUnionAction(Request $request)
    {
        $element_ids = $request->request->get('element');
        $main_id = $request->request->get('main');

        return $this->baseDoUnion('BaseUser', $element_ids, $main_id, 'admin_user', false);
    }
}
