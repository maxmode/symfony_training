<?php

namespace AppBundle\Repository;

/**
 * BatterySubmitRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class BatterySubmitRepository extends \Doctrine\ORM\EntityRepository
{
  public function findAllCountByType()
    {

      $em = $this->getEntityManager();

      $qb = $em->createQueryBuilder()
               ->select('sum(bs.count) AS total', 'bs.type')
               ->groupBy('bs.type')
               ->from('AppBundle:BatterySubmit','bs')
               ->orderBy('bs.type', 'ASC');

      return $qb->getQuery()->getResult();

    }
}