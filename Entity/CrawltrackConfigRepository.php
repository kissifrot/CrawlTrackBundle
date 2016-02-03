<?php

namespace WebDL\CrawltrackBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * CrawltrackConfigRepository
 *
 */
class CrawltrackConfigRepository extends EntityRepository
{
    /**
     * Get a config value by its name
     * @param string $name Name of the config value to get
     * @return string|null
     */
    public function getValue($name = 'version') {
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
