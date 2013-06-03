<?php

namespace Celsius\Celsius3Bundle\Manager;
use Doctrine\ODM\MongoDB\DocumentManager;
use Celsius\Celsius3Bundle\Document\Instance;

class CatalogManager
{
    const CATALOG__NON_SEARCHED = 'non_searched';
    const CATALOG__FOUND = 'found';
    const CATALOG__PARTIALLY_FOUND = 'partially_found';
    const CATALOG__NOT_FOUND = 'not_found';

    private $dm;

    public function __construct(DocumentManager $dm)
    {
        $this->dm = $dm;
    }

    public function getCatalogs(Instance $instance = null)
    {
        return $this->dm->getRepository('CelsiusCelsius3Bundle:Catalog')
                ->findBy(array('instance.id', $instance));
    }

    public function getAllCatalogs(Instance $instance)
    {
        $qb = $this->dm->getRepository('CelsiusCelsius3Bundle:Catalog')
                ->createQueryBuilder();

        return $qb->addOr($qb->expr()->field('instance.id')->equals($instance))
                ->addOr($qb->expr()->field('instance.id')->equals(null))
                ->getQuery()->execute();
    }
}
