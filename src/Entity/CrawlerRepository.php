<?php

namespace WebDL\CrawltrackBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\Expr;

/**
 * CrawlerRepository
 *
 */
class CrawlerRepository extends EntityRepository
{
    /**
     * Exact equivalent of find() but with cache
     * @param $id
     * @return mixed
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function findById($id) {
        $q = $this->createQueryBuilder('c')
            ->where('c.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->useQueryCache(true)
            ->useResultCache(true)
            ->setResultCacheLifetime(3600);
        return $q->getOneOrNullResult();
    }

    public function findByExactIPOrUA($ip, $ua = null) {
        $qb = $this->createQueryBuilder('c')
            ->leftJoin('c.ips', 'ipaddr', Expr\Join::WITH, 'ipaddr.single = :isSingleOrExact')
            ->leftJoin('c.userAgents', 'uas', Expr\Join::WITH, 'uas.exact = :isSingleOrExact')
            ->where('c.active = :isActive')
            ->andWhere('ipaddr.ipAddress = :ip');
        if(!empty($ua)) {
            $qb->orWhere('uas.userAgent = :ua')
            ->setParameter('ua', $ua);
        }
        $qb->setParameter('ip', $ip)
            ->setParameter('isActive', true)
            ->setParameter('isSingleOrExact', true);
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

    /**
     * Get a list of crawlers and their associated IP ranges
     * @return array
     */
    public function findByIPRanges() {
        $qb = $this->createQueryBuilder('c')
            ->where('c.active = :isActive')
            ->innerJoin('c.ips', 'ipaddresses', Expr\Join::WITH, 'ipaddresses.single = :isSingle')
            ->setParameter('isActive', true)
            ->setParameter('isSingle', false)
            ->addSelect('ipaddresses');
        return $qb->getQuery()
            ->useQueryCache(true)
            ->useResultCache(true)
            ->setResultCacheLifetime(3600)
            ->getArrayResult();
    }

    /**
     * Get a list of crawlers and their associated "complex" user agents
     * @return array
     */
    public function findByComplexUAs() {
        $qb = $this->createQueryBuilder('c')
            ->where('c.active = :isActive')
            ->innerJoin('c.userAgents', 'uas', Expr\Join::WITH, 'uas.exact = :isExact')
            ->setParameter('isActive', true)
            ->setParameter('isExact', false)
            ->orderBy('uas.regexp', 'DESC') // results with regexp should appear first
            ->addSelect('uas');
        return $qb->getQuery()
            ->useQueryCache(true)
            ->useResultCache(true)
            ->setResultCacheLifetime(3600)
            ->getArrayResult();
    }

    public function getForSpecificDate($date) {
        return $this->createQueryBuilder('c')
            ->innerJoin('c.pageVisits', 'pv', Expr\Join::WITH, 'SUBSTRING(pv.visitDate, 1, 10) = :date')
            ->addSelect('pv')
            ->setParameter('date', $date)
            ->orderBy('c.name', 'ASC')
            ->getQuery()
            ->getArrayResult();
    }
}
