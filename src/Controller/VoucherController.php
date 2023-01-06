<?php
declare(strict_types=1);

namespace App\Controller;

use App\Entity\Voucher;
use App\Exception\UserException;
use App\Exception\ValidationErrorsException;
use App\Repository\VoucherRepository;
use App\Request\VoucherRequest;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\ConstraintViolationListInterface;

/**
 * @Route("/voucher", name="voucher_")
 */
class VoucherController extends AbstractController
{
    /**
     * @Route("", name="list_active", methods={"GET"})
     */
    public function listActive(VoucherRepository $voucherRepository): Response
    {
        return $this->json(['data' => $voucherRepository->findAllActive()]);
    }

    /**
     * @Route("/status/{status}", name="list", methods={"GET"}, requirements={"status": "active|expired"})
     */
    public function list(string $status, VoucherRepository $voucherRepository): Response
    {
        return $this->json(['data' => $voucherRepository->findByStatus($status)]);
    }

    /**
     * @Route("", name="create", methods={"POST"})
     * @ParamConverter("voucherRequest", converter="fos_rest.request_body")
     */
    public function create(
        VoucherRequest                   $voucherRequest,
        ConstraintViolationListInterface $validationErrors,
        VoucherRepository                $repository
    ): Response
    {
        if ($validationErrors->count() > 0) {
            throw new ValidationErrorsException($validationErrors);
        }

        $voucher = Voucher::getFromRequest($voucherRequest);
        $repository->add($voucher);

        return $this->json(['data' => $voucher]);
    }

    /**
     * @Route("/{id}", name="edit", methods={"PUT"})
     * @ParamConverter("voucherRequest", converter="fos_rest.request_body")
     */
    public function edit(
        VoucherRequest                   $voucherRequest,
        ConstraintViolationListInterface $validationErrors,
        Voucher                          $voucher,
        EntityManagerInterface           $entityManager
    ): Response
    {
        if ($validationErrors->count() > 0) {
            throw new ValidationErrorsException($validationErrors);
        }

        if ($voucher->isUsed()) {
            throw new UserException('Editing used vouchers is not allowed', 400);
        }

        if ($voucher->getExpirationAt() < new \DateTimeImmutable()) {
            throw new UserException('Editing expired vouchers is not allowed', 400);
        }

        $voucher = Voucher::getFromRequest($voucherRequest, $voucher);
        $entityManager->flush();

        return $this->json(['data' => $voucher]);
    }

    /**
     * @Route("/{id}", name="delete", methods={"DELETE"})
     */
    public function delete(Voucher $voucher, VoucherRepository $voucherRepository): Response
    {
        if ($voucher->isUsed()) {
            throw new UserException('Deleting used vouchers is not allowed', 400);
        }

        $voucherId = $voucher->getId();
        $voucherRepository->remove($voucher, true);

        return $this->json([
            'data' => [
                'id' => $voucherId,
                'deleted' => true
            ]
        ]);
    }
}
