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

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Celsius3\CoreBundle\Entity\Instance;
use Celsius3\CoreBundle\Manager\UserManager;

/**
 * BaseUserRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class BaseUserRepository extends EntityRepository
{

    public function findAdmins(Instance $instance)
    {
        return $this->createQueryBuilder('u')
                        ->where('u.instance = :instance_id')
                        ->andWhere('u.roles LIKE :roles')
                        ->setParameter('instance_id', $instance->getId())
                        ->setParameter('roles', '%"' . UserManager::ROLE_ADMIN . '"%')
                        ->getQuery()
                        ->getResult();
    }

    public function findPendingUsers(Instance $instance = null)
    {
        $qb = $this->createQueryBuilder('u')
                ->where('u.enabled = false')
                ->andWhere('u.locked = false');

        if (!is_null($instance)) {
            $qb = $qb->andWhere('u.instance = :instance_id')
                    ->setParameter('instance_id', $instance->getId());
        }

        return $qb->getQuery()->getResult();
    }

    public function countUsers(Instance $instance = null)
    {
        $qb = $this->createQueryBuilder('u')
                ->where('u.enabled = false')
                ->andWhere('u.locked = false');

        if (!is_null($instance)) {
            $qb = $qb->andWhere('u.instance = :instance_id')
                    ->setParameter('instance_id', $instance->getId());
        }

        return array(
            'pending' => count($qb->getQuery()->getResult()),
        );
    }

    public function findByTerm($term, $instance = null, $limit = null, array $institutions = array())
    {
        $expr = new \MongoRegex('/.*' . $term . '.*/i');

        $qb = $this->createQueryBuilder('u')
                ->where('u.name LIKE :term')
                ->orWhere('u.surname LIKE :term')
                ->orWhere('u.username LIKE :term')
                ->orWhere('u.email LIKE :term')
                ->setParameter('term', '%' . $term . '%');

        if (!is_null($instance)) {
            $qb = $qb->andWhere('u.instance = :instance_id')
                    ->setParameter('instance_id', $instance->getId());
        }

        if (count($institutions) > 0) {
            $qb = $qb->andWhere('u.institution IN (:institution_ids)')
                    ->setParameter('institution_ids', $institutions);
        }

        if (!is_null($limit)) {
            $qb = $qb->setMaxResults(10);
        }

        return $qb->getQuery();
    }

    public function addFindByStateType(array $data, QueryBuilder $query, Instance $instance = null)
    {
        $alias = $query->getRootAliases()[0];
        foreach ($data as $value) {
            switch ($value) {
                case 'enabled':
                    $query = $query->orWhere($alias . 'enabled = true')
                            ->orWhere($alias . 'locked = false');
                    break;
                case 'pending':
                    $query = $query->orWhere($alias . 'enabled = false')
                            ->orWhere($alias . 'locked = false');
                    break;
                case 'rejected':
                    $query = $query->orWhere($alias . 'locked = true');
                    break;
            }
        }

        if (!is_null($instance)) {
            $qb = $qb->andWhere('u.instance = :instance_id')
                    ->setParameter('instance_id', $instance->getId());
        }

        return $query;
    }

    public function findUsersPerInstance()
    {
        return $this->createQueryBuilder('u')
                        ->select('IDENTITY(u.instance), COUNT(u.id) as c')
                        ->groupBy('u.instance')
                        ->getQuery()
                        ->getResult();
    }

    public function findNewUsersPerInstance()
    {
        return $this->createQueryBuilder('u')
                        ->select('IDENTITY(u.instance), COUNT(u.id) as c')
                        ->where('u.enabled = true')
                        ->groupBy('u.instance')
                        ->getQuery()
                        ->getResult();
    }

    public function countNewUsersForInterval($instance, $initialYear, $finalYear)
    {
        return $this->createQueryBuilder('user')
                        ->select('YEAR(user.createdAt) year')
                        ->addSelect('COUNT(user.id) newUsers')
                        ->where('user.instance = :instance')->setParameter('instance', $instance)
                        ->groupBy('year')
                        ->orderBy('year', 'ASC')
                        ->having('year >= :initialYear')->setParameter('initialYear', $initialYear)
                        ->andHaving('year <= :finalYear')->setParameter('finalYear', $finalYear)
                        ->getQuery()
                        ->getResult();
    }

    public function countNewUsersForYear($instance, $year)
    {
        return $this->createQueryBuilder('user')
                        ->select('MONTH(user.createdAt) month')
                        ->addSelect('COUNT(user.id) newUsers')
                        ->where('YEAR(user.createdAt) >= :y')->setParameter('y', $year)
                        ->andWhere('user.instance = :instance')->setParameter('instance', $instance)
                        ->groupBy('month')
                        ->orderBy('month', 'ASC')
                        ->getQuery()
                        ->getResult();
    }
    
    public function getTotalUsersUntilYear($instance, $year)
    {
        return $this->createQueryBuilder('user')
                        ->select('COUNT(user.id) newUsers')
                        ->where('user.instance = :instance')->setParameter('instance', $instance)
                        ->andWhere('YEAR(user.createdAt) <= :year')->setParameter('year', $year)
                        ->getQuery()->getSingleResult();
    }
    
    public function getYears($instance)
    {
        return $this->createQueryBuilder('user')
                        ->select('YEAR(user.createdAt) year')
                        ->where('user.instance = :instance')->setParameter('instance', $instance)
                        ->groupBy('year')
                        ->orderBy('year')
                        ->getQuery()
                        ->getResult();
    }
}
