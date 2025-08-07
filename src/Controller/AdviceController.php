<?php

namespace App\Controller;

use App\Entity\Advice;
use App\Enums\Serializer\AdviceEnum;
use App\Repository\AdviceRepository;
use Doctrine\ORM\EntityManagerInterface;
use JMS\Serializer\SerializationContext;
use Psr\Cache\CacheException;
use Psr\Cache\InvalidArgumentException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use JMS\Serializer\SerializerInterface;
use Symfony\Contracts\Cache\ItemInterface;
use Symfony\Contracts\Cache\TagAwareCacheInterface;

final class AdviceController extends AbstractController
{
    public function __construct(
        private readonly AdviceRepository $adviceRepository,
        private readonly SerializerInterface $serializer,
        private readonly EntityManagerInterface $entityManager,
        private readonly TagAwareCacheInterface $tagAwareCache
    ) {}

    /**
     * Returns a list of advices.
     *
     * @param Request $request
     * @return JsonResponse
     *
     * @throws InvalidArgumentException
     * @throws CacheException
     */
    #[Route('/api/conseils', name: 'app_advice', methods: ['GET'])]
    public function index(Request $request): JsonResponse
    {
        $cacheId = 'advice_index_' . $request->get('page', 1) . '_' . $request->get('limit', AdviceRepository::MAX_PAGE);

        $advices = $this->tagAwareCache->get($cacheId, function (ItemInterface $item) use ($request) {
            $item->tag('advice_index');

            $context = SerializationContext::create()->setGroups([AdviceEnum::ADVICE_LIST->value]);

            return $this->serializer->serialize($this->adviceRepository->all(
                $request),
                'json', $context
            );
        });

        return new JsonResponse($advices, Response::HTTP_OK, [], true);
    }

    /**
     * Returns a single advice by its ID.
     *
     * @param Advice $advice
     * @return JsonResponse
     *
     * @throws CacheException|InvalidArgumentException
     */
    #[Route('/api/conseils/{id}', name: 'app_advice_show', requirements: ['id' => '\d+'], methods: ['GET'])]
    public function show(Advice $advice): JsonResponse
    {
        $cacheId = 'advice_show_' . $advice->getId();

        // For development, use:
        // $tagAwareCache->invalidateTags(['advice_show']);
        // to clear the cache for this specific advice.

        $advice = $this->tagAwareCache->get($cacheId, function (ItemInterface $item) use ($advice) {
            $item->tag('advice_show');

            return $this->serializer->serialize(
                $this->adviceRepository->find($advice->getId()),
                'json'
            );
        });

        return new JsonResponse($advice, Response::HTTP_OK, [], true);
    }

    /**
     * Creates a new advice
     *
     * @param Request $request
     *
     * @return JsonResponse
     *
     * @throws ExceptionInterface
     */
    #[Route('/api/conseils', name: 'app_advice_create', methods: ['POST'])]
    public function store(Request $request): JsonResponse
    {
        $advice = $this->serializer->deserialize($request->getContent(), Advice::class, 'json');

        $advice->setCreatedAt(new \DateTimeImmutable());

        $this->entityManager->persist($advice);
        $this->entityManager->flush();

        $advice = $this->serializer->serialize($advice, 'json');

        return new JsonResponse($advice, Response::HTTP_CREATED, [], true);
    }

    #[Route('/api/conseils/{id}', name: 'app_advice_update', requirements: ['id' => '\d+'], methods: ['PUT'])]
    public function update(Advice $advice): JsonResponse
    {
        // TODO: Faire l'update de l'advice
        return $this->json($advice, Response::HTTP_NOT_IMPLEMENTED);
    }

    /**
     * Deletes an advice by its ID.
     *
     * @param Advice $advice
     *
     * @return JsonResponse
     *
     * @throws InvalidArgumentException
     */
    #[Route('/api/conseils/{id}', name: 'app_advice_delete', requirements: ['id' => '\d+'], methods: ['DELETE'])]
    public function delete(Advice $advice): JsonResponse
    {
        $this->entityManager->remove($advice);
        $this->entityManager->flush();

        $this->tagAwareCache->invalidateTags(['advice_index']);

        return $this->json(null, Response::HTTP_NO_CONTENT);
    }
}
