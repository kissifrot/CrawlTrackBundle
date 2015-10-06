<?php

namespace WebDL\CrawltrackBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\Expr;

/**
 * CrawlerRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class CrawlerRepository extends EntityRepository
{
    public function findByIPOrUA($ip, $ua = null) {
        $qb = $this->createQueryBuilder('c')
            ->leftJoin('c.ips', 'ips')
            ->leftJoin('c.UAs', 'uas')
            ->where('ips.ip = :ip');
        if(empty($ua)) {
            $qb->orWhere('uas.userAgent IS NULL');
        } else {
            $qb->orWhere('uas.userAgent = :ua')
            ->setParameter('ua', $ua);
        }
        $qb->setParameter('ip', $ip);
        $q = $qb->getQuery()
            ->useQueryCache(true)
            ->useResultCache(true)
            ->setResultCacheLifetime(3600);
        try {
            return $q->getOneOrNullResult();
        } catch(\Exception $e) {
            return null;
        }
    }

    public function getForSpecificDate($date) {
        return $this->createQueryBuilder('c')
            ->innerJoin('c.pageVisits', 'pv', 'WITH', 'SUBSTRING(pv.visitDate, 1, 10) = :date')
            ->addSelect('pv')
            ->setParameter('date', $date)
            ->orderBy('c.name', 'ASC')
            ->getQuery()
            ->getArrayResult();
    }
}
