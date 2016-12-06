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

use Doctrine\ORM\EntityRepository;
use Celsius3\CoreBundle\Manager\InstanceManager;

/**
 * InstanceRepository.
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class InstanceRepository extends EntityRepository
{
    public function findAllExceptDirectory()
    {
        return $this->createQueryBuilder('i')
                        ->where('i.url <> :url')
                        ->andWhere('i.invisible <> :invisible')
                        ->setParameter('url', InstanceManager::INSTANCE__DIRECTORY)
                        ->setParameter('invisible', true);
    }

    public function findAllAndInvisibleExceptDirectory()
    {
        return $this->createQueryBuilder('i')
                        ->where('i.url <> :url')
                        ->setParameter('url', InstanceManager::INSTANCE__DIRECTORY);
    }
}
