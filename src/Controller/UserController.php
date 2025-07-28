<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class UserController extends AbstractController
{
    #[Route('/api/user', name: 'app_user_create', methods: ['POST'])]
    public function store(): JsonResponse
    {
        return $this->json(null, Response::HTTP_NOT_IMPLEMENTED);
    }

    #[Route('/api/user/{id}', name: 'app_user_show', requirements: ['id' => '\d+'], methods: ['GET'])]
    public function show(int $id): JsonResponse
    {
        return $this->json(null, Response::HTTP_NOT_IMPLEMENTED);
    }

    #[Route('/api/user/{id}', name: 'app_user_update', requirements: ['id' => '\d+'], methods: ['PUT'])]
    public function update(int $id): JsonResponse
    {
        return $this->json(null, Response::HTTP_NOT_IMPLEMENTED);
    }

    #[Route('/api/user/{id}', name: 'app_user_delete', requirements: ['id' => '\d+'], methods: ['DELETE'])]
    public function delete(int $id): JsonResponse
    {
        return $this->json(null, Response::HTTP_NOT_IMPLEMENTED);
    }
}
