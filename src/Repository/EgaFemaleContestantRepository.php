<?php

namespace App\Repository;

use App\Entity\EgaFemaleContestant;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<EgaFemaleContestant>
 *
 * @method EgaFemaleContestant|null find($id, $lockMode = null, $lockVersion = null)
 * @method EgaFemaleContestant|null findOneBy(array $criteria, array $orderBy = null)
 * @method EgaFemaleContestant[]    findAll()
 * @method EgaFemaleContestant[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EgaFemaleContestantRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EgaFemaleContestant::class);
    }

    public function save(EgaFemaleContestant $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(EgaFemaleContestant $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

}
