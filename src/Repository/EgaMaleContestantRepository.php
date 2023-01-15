<?php

namespace App\Repository;

use App\Entity\EgaMaleContestant;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<EgaMaleContestant>
 *
 * @method EgaMaleContestant|null find($id, $lockMode = null, $lockVersion = null)
 * @method EgaMaleContestant|null findOneBy(array $criteria, array $orderBy = null)
 * @method EgaMaleContestant[]    findAll()
 * @method EgaMaleContestant[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EgaMaleContestantRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EgaMaleContestant::class);
    }

    public function save(EgaMaleContestant $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(EgaMaleContestant $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
