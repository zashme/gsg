<?php
declare(strict_types=1);

namespace App\Validator;

use App\Entity\Voucher;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class ActiveVoucherValidator extends ConstraintValidator
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function validate($value, Constraint $constraint): void
    {
        if ($value === null) {
            return;
        }

        $repo = $this->entityManager->getRepository(Voucher::class);
        $voucher = $repo->find($value);

        if (!$voucher instanceof Voucher) {
            $this->context
                ->buildViolation('Voucher not found')
                ->addViolation();

            return;
        }

        if ($voucher->isUsed()) {
            $this->context
                ->buildViolation('Voucher already used')
                ->addViolation();
        }

        if ($voucher->getExpirationAt() <= new \DateTime()) {
            $this->context
                ->buildViolation('Voucher was expired')
                ->addViolation();
        }
    }
}
