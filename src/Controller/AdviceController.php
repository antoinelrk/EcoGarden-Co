<?php

namespace App\Controller;

use App\Entity\Advice;
use App\Repository\AdviceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\SerializerInterface;

final class AdviceController extends AbstractController
{
    public function __construct(
        private readonly AdviceRepository $adviceRepository,
        private readonly SerializerInterface $serializer,
        private readonly EntityManagerInterface $entityManager
    ) {}

    /**
     * Returns a list of advices.
     *
     * @return JsonResponse
     *
     * @throws ExceptionInterface
     */
    #[Route('/api/conseils', name: 'app_advice', methods: ['GET'])]
    public function index(Request $request): JsonResponse
    {
        $advices = $this->serializer->serialize($this->adviceRepository->all($request), 'json');

        return new JsonResponse($advices, Response::HTTP_OK, [], true);
    }

    /**
     * Returns a single advice by its ID.
     *
     * @param Advice $advice
     *
     * @return JsonResponse
     *
     * @throws ExceptionInterface
     */
    #[Route('/api/conseils/{id}', name: 'app_advice_show', requirements: ['id' => '\d+'], methods: ['GET'])]
    public function show(Advice $advice): JsonResponse
    {
        $advice = $this->serializer->serialize($advice, 'json');

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
        return $this->json(null, Response::HTTP_NOT_IMPLEMENTED);
    }

    #[Route('/api/conseils/{id}', name: 'app_advice_delete', requirements: ['id' => '\d+'], methods: ['DELETE'])]
    public function delete(Advice $advice): JsonResponse
    {
        $this->entityManager->remove($advice);
        $this->entityManager->flush();

        return $this->json(null, Response::HTTP_NO_CONTENT);
    }
}
