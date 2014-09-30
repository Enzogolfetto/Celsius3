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

namespace Celsius3\CoreBundle\Repository;

use Doctrine\ODM\MongoDB\DocumentRepository;
use Doctrine\ODM\MongoDB\Query\Builder;
use Celsius3\CoreBundle\Document\BaseUser;
use Celsius3\CoreBundle\Document\Instance;
use Celsius3\CoreBundle\Manager\StateManager;

/**
 * OrderRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class OrderRepository extends DocumentRepository
{

    protected function getIds($value)
    {
        return $value['order']['$id'];
    }

    protected function getOrdersIds($value)
    {
        return $value['order']['$id'];
    }

    public function findByTerm($term, Instance $instance = null, $in = array(), $limit = null)
    {
        $qb = $this->createQueryBuilder();

        if (count($in) > 0) {
            $secondary = array();
            foreach ($in as $repository => $term) {
                $secondary = array_keys($this->getDocumentManager()
                                ->getRepository('Celsius3CoreBundle:' . $repository)
                                ->findByTerm($term, $instance)
                                ->execute()
                                ->toArray());
            }

            $qb = $qb->field('owner.id')->in($secondary);
        } else {
            $expr = new \MongoRegex('/.*' . $term . '.*/i');
            $qb = $qb->addOr($qb->expr()->field('code')->equals(intval($term)))
                    ->addOr($qb->expr()->field('materialData.title')->equals($expr))
                    ->addOr($qb->expr()->field('materialData.authors')->equals($expr))
                    ->addOr($qb->expr()->field('materialData.year')->equals($expr));
        }

        if (!is_null($instance)) {
            $requests = array_values(array_map(function($request) {
                return $request['order']['$id'];
            },$this->getDocumentManager()->getRepository('Celsius3CoreBundle:Request')
                            ->createQueryBuilder()
                            ->select('order.id')
                            ->hydrate(false)
                            ->field('instance.id')->equals($instance->getId())
                            ->getQuery()
                            ->execute()
                            ->toArray()));
            
            $qb = $qb->field('id')->in($requests);
        }

        if (!is_null($limit)) {
            $qb = $qb->limit($limit);
        }

        return $qb->getQuery();
    }

    public function findForInstance(Instance $instance, BaseUser $user = null, $state = null, BaseUser $owner = null, $orderType = null )
    {
        $states = $this->getDocumentManager()
                        ->getRepository('Celsius3CoreBundle:State')
                        ->createQueryBuilder()
                        ->hydrate(false)
                        ->select('order.id')
                        ->field('isCurrent')->equals(true)
                        ->field('instance.id')->equals($instance->getId());
        if (is_array($state)) {
            $states = $states->field('type')->in($state);
        } else {
            $states = $states->field('type')->equals($state);
        }
        
        if(!($orderType == 'allTypes') && !($orderType == null)) {
            $states = $states->field('requestType')->equals($orderType);
        }
        
        if (!is_null($user)) {
            $states = $states->addOr($states->expr()->field('operator.id')->equals($user->getId()))
                    ->addOr($states->expr()->field('operator.id')->equals(null));
        }
        
        if (!is_null($owner)) {
            $states = $states->field('owner.id')->equals($owner->getId());
        }

        return $states;
    }

    public function findOneForInstance($id, Instance $instance)
    {
        $order_id = $this->getDocumentManager()
                ->getRepository('Celsius3CoreBundle:Request')
                ->createQueryBuilder()
                ->hydrate(false)
                ->select('order')
                ->field('order.id')->equals($id)
                ->field('instance.id')->equals($instance->getId())
                ->getQuery()
                ->getSingleResult();

        return $this->createQueryBuilder()->field('id')->equals($order_id['order']['$id']);
    }

    public function findByStateType($type, $startDate, BaseUser $user = null, Instance $instance = null)
    {
        $stateType = $this->getDocumentManager()
                ->getRepository('Celsius3CoreBundle:StateType')
                ->createQueryBuilder()
                ->select('id')
                ->field('name')->equals($type)
                ->getQuery()
                ->getSingleResult();

        $states = $this->getDocumentManager()
                        ->getRepository('Celsius3CoreBundle:State')
                        ->createQueryBuilder()
                        ->hydrate(false)
                        ->select('order')
                        ->field('type.id')->equals($stateType->getId());

        if (!is_null($instance)) {
            $states = $states->field('instance.id')->equals($instance->getId());
        }

        $qb = $this->createQueryBuilder()
                        ->field('id')->in(array_map(array($this, 'getIds'), $states->getQuery()->execute()->toArray()));

        if (!is_null($user)) {
            $qb = $qb->field('owner.id')->equals($user->getId());
        }

        if (!is_null($startDate)) {
            $qb = $qb->field($type)->gte(new \DateTime($startDate));
        }

        return $qb->getQuery()
                        ->execute();
    }

    public function addFindByStateType(array $types, Builder $query, Instance $instance = null, BaseUser $user = null)
    {
        $stateTypes = array_keys($this->getDocumentManager()
                        ->getRepository('Celsius3CoreBundle:StateType')
                        ->createQueryBuilder()
                        ->hydrate(false)
                        ->select('id')
                        ->field('name')->in($types)
                        ->getQuery()
                        ->execute()
                        ->toArray());

        $states = $this->getDocumentManager()
                        ->getRepository('Celsius3CoreBundle:State')
                        ->createQueryBuilder()
                        ->hydrate(false)
                        ->select('request')
                        ->field('isCurrent')->equals(true)
                        ->field('type.id')->in($stateTypes);

        if (!is_null($instance)) {
            $states = $states->field('instance.id')->equals($instance->getId());
        }

        $requests = $this->getDocumentManager()
                        ->getRepository('Celsius3CoreBundle:Request')
                        ->createQueryBuilder()
                        ->hydrate(false)
                        ->select('order')
                        ->field('id')->in(array_map(function ($item) {
                    return $item['request']['$id'];
                }, $states->getQuery()->execute()->toArray()));

        if ($user) {
            $requests = $requests->addOr($requests->expr()->field('owner.id')->equals($user->getId()))
                    ->addOr($requests->expr()->field('librarian.id')->equals($user->getId()));
        }

        $requests = $requests->getQuery()
                ->execute()
                ->toArray();

        return $query->field('id')->in(array_map(array($this, 'getIds'), $requests));
    }

    public function findActiveForUser(BaseUser $user, Instance $instance)
    {
        $qb = $this->createQueryBuilder();

        return $this->addFindByStateType(array(
                            StateManager::STATE__CREATED,
                            StateManager::STATE__SEARCHED,
                            StateManager::STATE__REQUESTED,
                            StateManager::STATE__APPROVAL_PENDING,
                            StateManager::STATE__RECEIVED,
                                ), $qb, $instance, $user)
                        ->getQuery()
                        ->execute();
    }
}
