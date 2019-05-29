<?php


namespace Celsius3\CoreBundle\Repository;


use Celsius3\CoreBundle\Entity\Instance;
use Doctrine\ORM\EntityRepository;

class DataRequestRepository extends EntityRepository
{
    public function findExportedRequests(Instance $instance)
    {
        $qb = $this->createQueryBuilder('dr');

        $qb->select('dr.id')
            ->addSelect('dr.file')
            ->where('dr.instance = :instance')
            ->andWhere('dr.exported = :exported')
            ->andWhere('dr.visible = :visible')
            ->andWhere('dr.file IS NOT NULL')
            ->setParameter('instance', $instance->getId())
            ->setParameter('visible', true)
            ->setParameter('exported', true);

        return $qb->getQuery()->getArrayResult();
    }

    public function findAllDownloadedOrExpired()
    {
        $qb = $this->createQueryBuilder('dr');

        $qb->select('dr.id')
            ->addSelect('dr.file')
            ->where('dr.downloaded = :downloaded')
            ->andWhere('dr.')
    }
}