<?php
declare(strict_types=1);

namespace App\Repository;

use App\Entity\Order;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Order>
 *
 * @method Order|null find($id, $lockMode = null, $lockVersion = null)
 * @method Order|null findOneBy(array $criteria, array $orderBy = null)
 * @method Order[]    findAll()
 * @method Order[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OrderRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Order::class);
    }

    public function getPaginatedOrders(int $ordersPerPage, int $page = 1): Paginator
    {
        $query = $this->createQueryBuilder('a')
            ->orderBy('a.createdAt', 'DESC')
            ->getQuery();
        $paginator = new Paginator($query);
        $paginator->getQuery()
            ->setFirstResult($ordersPerPage * ($page - 1))
            ->setMaxResults($ordersPerPage);

        return $paginator;
    }
}
