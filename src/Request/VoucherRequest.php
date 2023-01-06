<?php
declare(strict_types=1);

namespace App\Request;

use Symfony\Component\Validator\Constraints as Assert;

class VoucherRequest
{
    /**
     * @Assert\NotBlank
     * @Assert\Type("int")
     */
    public $amount;

    /**
     * @Assert\NotBlank
     * @Assert\DateTime
     */
    public $expirationAt;
}
