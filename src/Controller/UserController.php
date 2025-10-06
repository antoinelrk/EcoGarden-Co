<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

final class UserController extends AbstractController
{
    /**
     * UserController constructor.
     *
     * @param UserRepository $userRepository
     */
    public function __construct(
        private readonly UserRepository $userRepository,
    ) {}

    /**
     * Returns a list of users.
     *
     * @return JsonResponse
     */
    #[Route('/api/users', name: 'app_user_index', methods: ['GET'])]
    #[IsGranted('ROLE_ADMIN', message: 'Message personnalisé: Vous n\'avez pas l\'authorisation.')]
    public function index(): JsonResponse
    {
        $users = $this->userRepository->findAll();

        return $this->json($users, Response::HTTP_OK);
    }

    /**
     * Creates a new user.
     *
     * @return JsonResponse
     */
    #[Route('/api/users', name: 'app_user_create', methods: ['POST'])]
    public function store(): JsonResponse
    {
        return $this->json(null, Response::HTTP_NOT_IMPLEMENTED);
    }

    /**
     * Shows a user.
     *
     * @param int $id
     * @return JsonResponse
     */
    #[Route('/api/users/{id}', name: 'app_user_show', requirements: ['id' => '\d+'], methods: ['GET'])]
    public function show(int $id): JsonResponse
    {
        return $this->json(null, Response::HTTP_NOT_IMPLEMENTED);
    }

    /**
     * Updates a user.
     *
     * @param int $id
     *
     * @return JsonResponse
     */
    #[Route('/api/users/{id}', name: 'app_user_update', requirements: ['id' => '\d+'], methods: ['PUT'])]
    public function update(int $id): JsonResponse
    {
        return $this->json(null, Response::HTTP_NOT_IMPLEMENTED);
    }

    /**
     * Deletes a user.
     *
     * @param int $id
     *
     * @return JsonResponse
     */
    #[Route('/api/users/{id}', name: 'app_user_delete', requirements: ['id' => '\d+'], methods: ['DELETE'])]
    public function delete(int $id): JsonResponse
    {
        return $this->json(null, Response::HTTP_NOT_IMPLEMENTED);
    }
}
