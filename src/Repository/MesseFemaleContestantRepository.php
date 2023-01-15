<?php

namespace App\Repository;

use App\Entity\MesseFemaleContestant;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<MesseFemaleContestant>
 *
 * @method MesseFemaleContestant|null find($id, $lockMode = null, $lockVersion = null)
 * @method MesseFemaleContestant|null findOneBy(array $criteria, array $orderBy = null)
 * @method MesseFemaleContestant[]    findAll()
 * @method MesseFemaleContestant[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MesseFemaleContestantRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MesseFemaleContestant::class);
    }

    public function save(MesseFemaleContestant $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(MesseFemaleContestant $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
