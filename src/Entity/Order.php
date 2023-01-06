<?php
declare(strict_types=1);

namespace App\Entity;

use App\Repository\OrderRepository;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Serializer\Annotation\Context;
use Symfony\Component\Serializer\Annotation\Ignore;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;

/**
 * @ORM\Entity(repositoryClass=OrderRepository::class)
 * @ORM\Table(name="`order`")
 */
class Order
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @ORM\Column(type="integer")
     */
    private int $amount;

    /**
     * @ORM\Column(type="integer")
     */
    private int $toPay;

    /**
     * @ORM\OneToOne(targetEntity=Voucher::class, cascade={"persist", "remove"})
     * @Ignore()
     */
    private Voucher $voucher;

    /**
     * @ORM\Column(type="datetime")
     * @Context({ DateTimeNormalizer::FORMAT_KEY = "Y-m-d H:i:s" })
     * @Gedmo\Timestampable(on="create")
     */
    protected \DateTime $createdAt;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAmount(): ?int
    {
        return $this->amount;
    }

    public function setAmount(int $amount): void
    {
        $this->amount = $amount;
    }

    /**
     * @return int
     */
    public function getToPay(): int
    {
        return $this->toPay;
    }

    /**
     * @param int $toPay
     */
    public function setToPay(int $toPay): void
    {
        $this->toPay = $toPay;
    }

    public function getVoucher(): ?Voucher
    {
        return $this->voucher;
    }

    public function setVoucher(?Voucher $voucher): void
    {
        $this->voucher = $voucher;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

}
