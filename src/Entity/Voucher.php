<?php
declare(strict_types=1);

namespace App\Entity;

use App\Repository\VoucherRepository;
use App\Request\VoucherRequest;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=VoucherRepository::class)
 */
class Voucher
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
     * @ORM\Column(type="boolean")
     */
    private bool $used;

    /**
     * @ORM\Column(type="datetime_immutable")
     */
    private \DateTimeImmutable $expirationAt;

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

    public function isUsed(): ?bool
    {
        return $this->used;
    }

    public function setUsed(bool $used): void
    {
        $this->used = $used;
    }

    public function getExpirationAt(): \DateTimeImmutable
    {
        return $this->expirationAt;
    }

    public function setExpirationAt(\DateTimeImmutable $expirationAt): void
    {
        $this->expirationAt = $expirationAt;
    }

    public static function getFromRequest(VoucherRequest $voucherRequest, Voucher $voucher = null): Voucher
    {
        if (!$voucher) {
            $voucher = new self();
            $voucher->setUsed(false);
        }

        $voucher->setAmount($voucherRequest->amount);
        $voucher->setExpirationAt(new \DateTimeImmutable($voucherRequest->expirationAt));

        return $voucher;
    }
}
