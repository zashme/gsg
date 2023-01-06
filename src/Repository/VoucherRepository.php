<?php
declare(strict_types=1);

namespace App\Repository;

use App\Entity\Voucher;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Voucher>
 *
 * @method Voucher|null find($id, $lockMode = null, $lockVersion = null)
 * @method Voucher|null findOneBy(array $criteria, array $orderBy = null)
 * @method Voucher[]    findAll()
 * @method Voucher[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VoucherRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Voucher::class);
    }

    public function findAllActive(): array
    {
        return $this->createQueryBuilder('v')
            ->andWhere('v.used = :used')
            ->setParameter('used', false)
            ->andWhere('v.expirationAt > :date')
            ->setParameter('date', new \DateTime())
            ->getQuery()
            ->getResult();
    }

    public function findAllExpired(): array
    {
        return $this->createQueryBuilder('v')
            ->andWhere('v.used = :used')
            ->setParameter('used', false)
            ->andWhere('v.expirationAt <= :date')
            ->setParameter('date', new \DateTime())
            ->getQuery()
            ->getResult();
    }

    public function findByStatus(string $status): array
    {
        switch ($status) {
            case 'expired':
                return $this->findAllExpired();
            case 'active':
            default:
                return $this->findAllActive();
        }
    }

    public function add(Voucher $entity): void
    {
        $this->getEntityManager()->persist($entity);
        $this->getEntityManager()->flush();
    }

    public function remove(Voucher $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
