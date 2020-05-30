<?php

namespace App\Repository;

use App\Entity\Round;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\AbstractQuery;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Round|null find($id, $lockMode = null, $lockVersion = null)
 * @method Round|null findOneBy(array $criteria, array $orderBy = null)
 * @method Round[]    findAll()
 * @method Round[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RoundRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Round::class);
    }

    /**
     * @return int|mixed|string|null
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function getCurrentRound()
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.finished = :finished')
            ->setParameters([
                'finished' => Round::ROUND_NOT_FINISHED
            ])
            ->getQuery()->getOneOrNullResult();
    }

    /**
     * @return Round
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function createRound(): Round
    {
        $round = new Round();
        $state = [];
        for ($i = 0; $i < 50; $i++ ) {
            $state[$i] = [
                'key' => $i,
                'value' => rand(1, 10),
                'used' => 0
            ];
        }
        $state[] = [
            'key' => $i,
            'value' => 0,
            'used' => 0
        ];
        $round
            ->setStartState(json_encode($state))
            ->setCurrentState(json_encode($state));

        $this->getEntityManager()->persist($round);
        $this->getEntityManager()->flush();

        return $round;
    }

    /**
     * @param Round $round
     * @return int
     */
    public function spinRound(Round $round): int
    {
        $cell = Round::ROUND_INVALID_CELL;
        if ($round->getFinished() !== Round::ROUND_FINISHED) {
            $currentState = json_decode($round->getCurrentState(), true);
            $activeCells = array_filter($currentState, function($item) {
                return $item['used'] === 0 && $item['value'];
            });
            $itemsWithChanceList = array_reduce($activeCells, function ($acc, $item) {
                for($i = 0; $i < $item['value']; $i++) {
                    $acc[] = $item['key'];
                }
                return $acc;
            }, []);

            if ($this->shouldAddJackpot($currentState)) {
                $itemsWithChanceList[] = $currentState[count($currentState) - 1]['key'];
            }

            shuffle($itemsWithChanceList);
            $cell = $itemsWithChanceList[rand(0, count($itemsWithChanceList) - 1)];
            $currentState[$cell]['used'] = 1;
            $round->setCurrentState(json_encode($currentState));

            if ( $cell == count($currentState) - 1) {
                $round->setFinished(Round::ROUND_FINISHED);
            }

            $this->getEntityManager()->persist($round);
            $this->getEntityManager()->flush();
        }

        return $cell;
    }

    private function shouldAddJackpot($state)
    {
        $usedValues = array_reduce($state, function($acc, $item) {
            if ($item['used']) {
                $acc[$item['value']] = 1;
            }
            return $acc;
        }, []);

        return array_sum($usedValues) === 10 || count($usedValues) === count($state) - 1;
    }

    public function getActiveRounds($limit = 5)
    {
        $qb = $this->createQueryBuilder('r')
            ->andWhere('r.finished = :finished')
            ->setMaxResults($limit)
            ->orderBy('r.id','desc')
            ->setParameters([
                'finished' => Round::ROUND_NOT_FINISHED
            ]);

        return $qb->getQuery()->getResult();
    }

    public function getRoundPlayers()
    {
        $qb = $this->createQueryBuilder('r')
            ->select('r.id, COUNT(u.id) as user_count')
            ->join('r.users', 'u')
            ->groupBy('r.id')
        ;

        return $qb->getQuery()->getResult(AbstractQuery::HYDRATE_SCALAR);
    }
}
