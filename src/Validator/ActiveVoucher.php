<?php
declare(strict_types=1);

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class ActiveVoucher extends Constraint
{
    public string $message = 'Voucher is not valid';
}
