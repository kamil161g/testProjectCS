<?php

declare(strict_types=1);

namespace App\UserInterface\Controller;

use JMS\Serializer\SerializationContext;
use JMS\Serializer\SerializerInterface as JMSSerializerInterface;
use Psr\Log\LoggerInterface;
use RuntimeException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

/**
 * @author Kamil Gąsior <kamilgasior07@gmail.com>
 */
class AbstractApiController extends AbstractController
{
    private const string JSON_TYPE = 'json';

    public function __construct(private JMSSerializerInterface $serializer, private LoggerInterface $logger) {}

    public function getSuccessResponse($value, string $format = 'json', $groups = ['Default']): JsonResponse
    {
        return new JsonResponse($this->serialize($value, $format, $groups), Response::HTTP_OK, [], true);
    }

    public function getErrorResponse(Throwable $exception): JsonResponse
    {
        $text = sprintf('%s%s%s%s%s%s', 'ApiError: ', $exception->getMessage(),
            ' File: ', $exception->getFile(), ' Line: ', $exception->getLine());

        if ($exception->getCode() === Response::HTTP_NOT_FOUND) {
            $this->logger->info($text);
        } else {
            $this->logger->error($text);
        }

        return new JsonResponse('Something went wrong, please try again later.', 500);
    }

    protected function serialize($data, string $format, array $groups): string
    {
        $context = SerializationContext::create();
        $context->setSerializeNull(true);
        $context->setGroups($groups);

        return $this->serializer->serialize($data, $format, $context);
    }

    protected function transformJson(Request $request): array
    {
        try {
            if ($request->getContentTypeFormat() !== self::JSON_TYPE) {
                throw new RuntimeException("This format isn't JSON");
            }

            return $request->toArray();
        } catch (Throwable $exception) {
            throw new RuntimeException($exception->getMessage());
        }
    }

}
