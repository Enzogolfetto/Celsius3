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
 * RequestRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class RequestRepository extends EntityRepository
{
    public function countByMonthAndYear() {
        
        $qb = $this->createQueryBuilder();
        $requests = $qb
            ->map('function() { emit( , 1) }')    
            ->reduce('function(k, vals) {
                var sum = 0;
                for (var i in vals) {
                    sum += vals[i];
                }
                return sum;
            }')->getQuery()->execute()->toArray();
        
        $string = '';
        foreach ($requests as $r) {
            $string .= $r['_id'] . '-' . $r['value'] . '<br>';
        }
        return $string;
    }
    
    public function countActiveUsersForInterval($instance,$initialYear,$finalYear) {
        return $this->createQueryBuilder('request')
            ->select('YEAR(request.createdAt) year')
            ->addSelect('COUNT(DISTINCT request.owner) activeUsers')
            ->where('request.instance = :instance')->setParameter('instance',$instance)
            ->groupBy('year')
            ->orderBy('year','ASC')
            ->having('year >= :initialYear')->setParameter('initialYear', $initialYear)
            ->andhaving('year <= :finalYear')->setParameter('finalYear', $finalYear)
            ->getQuery()
            ->getResult();
    }
    
    public function countActiveUsersForYear($instance,$year) {
        return $this->createQueryBuilder('request')
            ->select('MONTH(request.createdAt) month')
            ->addSelect('COUNT(request.owner) activeUsers')
            ->where('YEAR(request.createdAt) >= :year')->setParameter('year',$year)
            ->andWhere('request.instance = :instance')->setParameter('instance',$instance)
            ->groupBy('month')
            ->orderBy('month','ASC')
            ->getQuery()
            ->getResult();
    }
    
    public function getYears($instance) {
        return $this->createQueryBuilder('request')
                ->select('YEAR(request.createdAt) year')
                ->where('request.instance = :instance')->setParameter('instance',$instance)
                ->groupBy('year')
                ->orderBy('year')
                ->getQuery()
                ->getResult();
    }
}