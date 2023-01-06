<?php
declare(strict_types=1);

namespace App\Request;

use Symfony\Component\Validator\Constraints as Assert;
use App\Validator as UserAssert;

class OrderRequest
{
    /**
     * @Assert\NotBlank
     * @Assert\Type("int")
     */
    public $amount;

    /**
     * @Assert\Type("int")
     * @UserAssert\ActiveVoucher()
     */
    public $voucherId;
}
