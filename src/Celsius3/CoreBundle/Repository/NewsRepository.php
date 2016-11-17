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

namespace Celsius3\CoreBundle\Repository;

use Celsius3\CoreBundle\Entity\Instance;

/**
 * NewsRepository.
 */
class NewsRepository extends BaseRepository
{
    public function findLastNews(Instance $instance, $limit = 3)
    {
        return $this->createQueryBuilder('n')
                        ->where('n.instance = :instance_id')
                        ->setParameter('instance_id', $instance->getId())
                        ->orderBy('n.date', 'desc')
                        ->setMaxResults($limit)
                        ->getQuery()
                        ->getResult();
    }

    public function findByInstanceQB(Instance $instance)
    {
        return $this->createQueryBuilder('n')
                    ->where('n.instance = :instance_id')
                    ->orderBy('n.createdAt', 'desc')
                    ->setParameter(':instance_id', $instance->getId())
                    ->getQuery();
    }
}
