<?php

namespace App\Repository;

use App\Entity\Round;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function addUsersToRound(Round $round)
    {
        $users = $this->findAll();
        $luckyUsers = [];
        $countToAdd = rand(1, count($users));

        shuffle($users);
        for ($i = 0; $i < $countToAdd; $i++) {
            $luckyUsers[] = array_pop($users);
        }

        foreach ($luckyUsers as $luckyUser) {
            $round->addUser($luckyUser);
        }

        $this->getEntityManager()->getRepository(Round::class)->getEntityManager()->persist($round);
        $this->getEntityManager()->getRepository(Round::class)->getEntityManager()->flush();
    }

    public function getActiveUsers()
    {
        $sql = "
        SELECT u.id, u.name, COUNT(r.id) AS r_count, AVG(l.spin_count) as avg_spin_count
        FROM user u
        JOIN round_user ru ON ru.user_id = u.id
        JOIN `round` r ON ru.round_id = r.id
        JOIN ( 
            SELECT round_id, COUNT(id) AS spin_count from `log` GROUP BY round_id
        ) l ON l.round_id = r.id
        GROUP BY u.id
        ORDER BY r_count DESC;
        ";
        $conn = $this->getEntityManager()->getConnection();
        $query = $conn->prepare($sql);
        $query->execute();

        return $query->fetchAll();
    }
}
