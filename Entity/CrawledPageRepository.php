<?php

namespace WebDL\CrawltrackBundle\Entity;

/**
 * CrawledPageRepository
 */
class CrawledPageRepository extends \Doctrine\ORM\EntityRepository
{
    public function findByURI($uri) {
        $qb = $this->createQueryBuilder('cp')
            ->where('cp.uri = :uri')
            ->setParameter('uri', $uri);
        $q = $qb->getQuery()
            ->useQueryCache(true)
            ->useResultCache(true, 3600, 'crawler_' . md5($uri));
        try {
            return $q->getOneOrNullResult();
        } catch(\Exception $e) {
            return null;
        }
    }

    public function getTotalCount() {
        return $this->createQueryBuilder('cp')
            ->select('COUNT(cp)')
            ->getQuery()
            ->getSingleScalarResult();
    }
}
