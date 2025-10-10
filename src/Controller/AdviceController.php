<?php

namespace App\Controller;

use App\Entity\Advice;
use App\Enums\MonthEnum;
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
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use JMS\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Contracts\Cache\ItemInterface;
use Symfony\Contracts\Cache\TagAwareCacheInterface;

final class AdviceController extends AbstractController
{
    public function __construct(
        private readonly AdviceRepository $adviceRepository,
        private readonly SerializerInterface $serializer,
        private readonly EntityManagerInterface $entityManager,
        private readonly TagAwareCacheInterface $tagAwareCache,
        private readonly ValidatorInterface $validator
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

        // For development, use:
        // $this->tagAwareCache->invalidateTags(['advice_index']);

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
     * @param int $month
     * @return JsonResponse
     *
     * @throws CacheException|InvalidArgumentException
     */
    #[Route('/api/conseils/{month}', name: 'app_advice_show', requirements: ['id' => '\d+'], methods: ['GET'])]
    public function show(int $month): JsonResponse
    {
        // Validate month
        if (!MonthEnum::getEnumByValue($month)) {
            throw new UnprocessableEntityHttpException(sprintf('Mois invalide: %d (attendu 1..12)', $month));
        }

        $cacheId = 'advice_show_' . $month;

        // For development, use:
        // $this->tagAwareCache->invalidateTags(['advice_show']);
        // to clear the cache for this specific advice.

        $advice = $this->tagAwareCache->get($cacheId, function (ItemInterface $item) use ($month) {
            $item->tag('advice_show' . $month);

            return $this->serializer->serialize(
                $this->adviceRepository->findByMonth($month),
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
     * @throws \JsonException
     */
    #[Route('/api/conseils', name: 'app_advice_create', methods: ['POST'])]
    public function store(Request $request): JsonResponse
    {
        $advice = $this->adviceRepository->create($request);

        return new JsonResponse($advice, Response::HTTP_CREATED, [], true);
    }

    /**
     * Updates an existing advice.
     *
     * @param Request $request
     * @param Advice $advice
     *
     * @return JsonResponse
     *
     * @throws InvalidArgumentException
     * @throws \JsonException
     */
    #[Route('/api/conseils/{id}', name: 'app_advice_update', requirements: ['id' => '\d+'], methods: ['PUT'])]
    #[IsGranted('ROLE_ADMIN', message: 'Vous n\'avez pas les droits pour modifier un conseil.')]
    public function update(Request $request, Advice $advice): JsonResponse
    {
        $data = json_decode($request->getContent(), true, 512, JSON_THROW_ON_ERROR);

        $advice->setTitle($data['title'] ?? $advice->getTitle());
        $advice->setDescription($data['description'] ?? $advice->getDescription());
        $advice->setMonths(
            array_map('intval', $data['months'] ?? $advice->getMonths())
        );
        $advice->setUpdatedAt(new \DateTimeImmutable());

        $errors = $this->validator->validate($advice);

        if (count($errors) > 0) {
            return $this->json(['errors' => (string) $errors], Response::HTTP_BAD_REQUEST);
        }

        // Clear the cache for this specific advice
        $this->tagAwareCache->invalidateTags(['advice_show']);

        $this->entityManager->persist($advice);
        $this->entityManager->flush();

        return $this->json($advice, Response::HTTP_OK);
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
