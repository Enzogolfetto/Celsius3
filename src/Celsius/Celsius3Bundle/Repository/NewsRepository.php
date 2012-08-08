<?php

namespace Celsius\Celsius3Bundle\Repository;

use Doctrine\ODM\MongoDB\DocumentRepository;

/**
 * NewsRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class NewsRepository extends DocumentRepository
{

    public function findLastNews($instance, $limit = 5)
    {
        return $this->createQueryBuilder()
                        ->field('instance.id')->equals($instance->getId())
                        ->sort(array('date' => 'desc'))
                        ->limit($limit)
                        ->getQuery();
    }

}