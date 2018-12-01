<?php

namespace WebDL\CrawltrackBundle\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use WebDL\CrawltrackBundle\Entity\CrawlerIPData;

class CrawlerIPDataRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CrawlerIPData::class);
    }
}
