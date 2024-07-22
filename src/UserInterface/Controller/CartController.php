<?php

declare(strict_types=1);

namespace App\UserInterface\Controller;

use App\Application\Command\AddProductCommand;
use App\Application\Command\RemoveProductCommand;
use App\Application\DTO\AddProductDTO;
use App\Application\DTO\RemoveProductDTO;
use App\Application\Query\GetCartQuery;
use App\UserInterface\Form\AddProductType;
use App\UserInterface\Form\RemoveProductType;
use JMS\Serializer\SerializerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Throwable;

/**
 * @author Kamil Gąsior <kamilgasior07@gmail.com>
 */
class CartController extends AbstractApiController
{
    public function __construct(
        SerializerInterface $serializer,
        LoggerInterface $logger,
        private readonly MessageBusInterface $commandBus
    ) {
        parent::__construct($serializer, $logger);
    }

    public function addProduct(Request $request): JsonResponse
    {
        try {
            $data = $this->transformJson($request);
            $form = $this->createForm(AddProductType::class);
            $form->submit($data);

            if ($form->isValid()) {
                $productDTO = AddProductDTO::fromArray($data);
                $command = new AddProductCommand($productDTO);
                $this->commandBus->dispatch($command);

                return $this->getSuccessResponse(new JsonResponse());
            }

            return new JsonResponse(['error' => 'Invalid data'], Response::HTTP_BAD_REQUEST);
        } catch (Throwable $exception) {
            return $this->getErrorResponse($exception);
        }
    }

    public function removeProduct(Request $request): JsonResponse
    {
        try {
            $data = $this->transformJson($request);
            $form = $this->createForm(RemoveProductType::class);
            $form->submit($data);

            if ($form->isValid()) {
                $productDTO = RemoveProductDTO::fromArray($data);
                $command = new RemoveProductCommand($productDTO);
                $this->commandBus->dispatch($command);

                return $this->getSuccessResponse(new JsonResponse());
            }

            return new JsonResponse(['error' => 'Invalid data'], Response::HTTP_BAD_REQUEST);
        } catch (Throwable $exception) {
            return $this->getErrorResponse($exception);
        }
    }

    public function getCart(): JsonResponse
    {
        try {
            $query = new GetCartQuery();
            $this->commandBus->dispatch($query);

            return $this->getSuccessResponse($query->getView());
        } catch (Throwable $exception) {
            return $this->getErrorResponse($exception);
        }

    }
}
