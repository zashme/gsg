<?php
declare(strict_types=1);

namespace App\Controller;

use App\Exception\ValidationErrorsException;
use App\Repository\OrderRepository;
use App\Request\OrderRequest;
use App\Service\OrderService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\ConstraintViolationListInterface;

/**
 * @Route("/order", name="order_")
 */
class OrderController extends AbstractController
{
    private const ORDERS_PER_PAGE = 2;

    /**
     * @Route("", name="list", methods={"GET"})
     */
    public function list(OrderRepository $orderRepository, Request $request): Response
    {
        $data = $orderRepository->getPaginatedOrders(self::ORDERS_PER_PAGE, (int) $request->query->get('page', 1));

        return $this->json(['data' => $data]);
    }

    /**
     * @Route("", name="create", methods={"POST"})
     * @ParamConverter("orderRequest", converter="fos_rest.request_body")
     */
    public function create(
        OrderRequest                     $orderRequest,
        ConstraintViolationListInterface $validationErrors,
        OrderService                     $orderService
    ): Response
    {
        if ($validationErrors->count() > 0) {
            throw new ValidationErrorsException($validationErrors);
        }

        $order = $orderService->createOrder($orderRequest);

        return $this->json(['data' => $order]);
    }
}
