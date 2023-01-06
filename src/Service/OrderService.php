<?php
declare(strict_types=1);

namespace App\Service;

use App\Entity\Order;
use App\Entity\Voucher;
use App\Repository\VoucherRepository;
use App\Request\OrderRequest;
use Doctrine\ORM\EntityManagerInterface;

class OrderService
{
    private VoucherRepository $voucherRepository;
    private EntityManagerInterface $entityManager;

    public function __construct(
        VoucherRepository      $voucherRepository,
        EntityManagerInterface $entityManager
    )
    {
        $this->voucherRepository = $voucherRepository;
        $this->entityManager = $entityManager;
    }

    public function createOrder(OrderRequest $orderRequest): Order
    {
        $order = new Order();
        $order->setAmount($orderRequest->amount);
        $order->setToPay($orderRequest->amount);

        if ($orderRequest->voucherId !== null) {
            $this->processVoucher($order, $orderRequest);
        }

        $this->entityManager->persist($order);
        $this->entityManager->flush();

        return $order;
    }

    private function processVoucher(Order $order, OrderRequest $orderRequest): void
    {
        $voucher = $this->voucherRepository->find($orderRequest->voucherId);
        if ($voucher instanceof Voucher) {
            $toPay = $orderRequest->amount - $voucher->getAmount();
            $order->setToPay($toPay >= 0 ? $toPay : 0);
            $order->setVoucher($voucher);
            $voucher->setUsed(true);
        }
    }
}
