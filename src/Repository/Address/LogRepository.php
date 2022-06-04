<?php
namespace App\Repository\Address;

use App\Entity\Address\Log;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class LogRepository extends ServiceEntityRepository
{
    /**
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Log::class);
    }

    /**
     * @param $log
     * @return Log
     */
    public function persistLog(Log $log): Log
    {
        $this->getEntityManager()->persist($log);
        $this->getEntityManager()->flush();

        return $log;
    }
}