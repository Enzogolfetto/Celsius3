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

namespace Celsius3\CoreBundle\Manager;

use Celsius3\CoreBundle\Entity\Catalog;
use Celsius3\CoreBundle\Entity\Instance;
use Doctrine\ORM\EntityManager;
use Celsius3\CoreBundle\Helper\InstanceHelper;

class CatalogManager
{

    const CATALOG__NON_SEARCHED = 'non_searched';
    const CATALOG__FOUND = 'found';
    const CATALOG__PARTIALLY_FOUND = 'partially_found';
    const CATALOG__NOT_FOUND = 'not_found';

    private $em;
    private $instance_helper;

    public function __construct(EntityManager $em, InstanceHelper $instance_helper)
    {
        $this->em = $em;
        $this->instance_helper = $instance_helper;
    }

    public static function getResults()
    {
        return array(
            self::CATALOG__FOUND,
            self::CATALOG__PARTIALLY_FOUND,
            self::CATALOG__NOT_FOUND,
            self::CATALOG__NOT_SEARCHED,
        );
    }

    public function getCatalogs(Instance $instance = null)
    {
        return $this->em->getRepository('Celsius3CoreBundle:Catalog')
                        ->findBy(array('instance' => $instance->getId(),));
    }

    public function isCatalogEnabled(Catalog $catalog)
    {
        $position = $catalog->getPosition($this->instance_helper->getSessionInstance());

        return $position->getEnabled();
    }

    public function getDisabledCatalogsCount(Instance $instance, Instance $directory)
    {
        return $this->em->getRepository('Celsius3CoreBundle:Catalog')
                ->getDisabledCatalogsCount($instance, $directory);
    }

}
