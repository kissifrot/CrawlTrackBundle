<?php

namespace WebDL\CrawltrackBundle\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use WebDL\CrawltrackBundle\Entity\CrawlerUAData;

class CrawlerUADataRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CrawlerUAData::class);
    }
}
