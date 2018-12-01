<?php

namespace WebDL\CrawltrackBundle\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use WebDL\CrawltrackBundle\Entity\CrawltrackConfig;

class CrawltrackConfigRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CrawltrackConfig::class);
    }

    /**
     * Get a config value by its name
     */
    public function getValue(string $name = 'version'): ?string
    {
        $q = $this->createQueryBuilder('cc')
            ->where('cc.name = :cfgName')
            ->setParameter('cfgName', $name)
            ->getQuery();
        try {
            $res = $q->getOneOrNullResult();
            return $res->getValue();
        } catch(\Exception $e) {
            return null;
        }
    }
}
