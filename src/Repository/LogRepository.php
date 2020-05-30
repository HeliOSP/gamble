<?php

namespace App\Repository;

use App\Entity\Log;
use App\Entity\Round;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Log|null find($id, $lockMode = null, $lockVersion = null)
 * @method Log|null findOneBy(array $criteria, array $orderBy = null)
 * @method Log[]    findAll()
 * @method Log[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LogRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Log::class);
    }

    public function addRoundLog(Round $round, $cell)
    {
        $log = new Log();
        $log
            ->setRound($round)
            ->setCell($cell);
        $this->getEntityManager()->persist($log);
        $this->getEntityManager()->flush();
    }
}
