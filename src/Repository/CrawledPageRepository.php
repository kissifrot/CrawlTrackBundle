<?php

namespace WebDL\CrawltrackBundle\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use WebDL\CrawltrackBundle\Entity\CrawledPage;

class CrawledPageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CrawledPage::class);
    }

    public function findByURI(string $uri): ?CrawledPage
    {
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

    public function getTotalCount()
    {
        return $this->createQueryBuilder('cp')
            ->select('COUNT(cp)')
            ->getQuery()
            ->getSingleScalarResult();
    }
}
