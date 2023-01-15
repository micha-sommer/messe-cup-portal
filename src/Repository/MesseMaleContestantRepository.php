<?php

namespace App\Repository;

use App\Entity\MesseMaleContestant;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<MesseMaleContestant>
 *
 * @method MesseMaleContestant|null find($id, $lockMode = null, $lockVersion = null)
 * @method MesseMaleContestant|null findOneBy(array $criteria, array $orderBy = null)
 * @method MesseMaleContestant[]    findAll()
 * @method MesseMaleContestant[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MesseMaleContestantRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MesseMaleContestant::class);
    }

    public function save(MesseMaleContestant $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(MesseMaleContestant $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
