<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ConseilController extends AbstractController
{
    #[Route('/api/conseils', name: 'app_conseil')]
    public function index(): JsonResponse
    {
        // TODO: Ajouter la logique pour récupérer les conseils
        $conseils = [];

        return $this->json($conseils, Response::HTTP_OK);
    }

    #[Route('/api/conseils/{id}', name: 'app_conseil_show', requirements: ['id' => '\d+'])]
    public function show(int $id): JsonResponse
    {
        // TODO: Ajouter la logique pour récupérer les conseils
        $conseil = [];

        return $this->json($conseil, Response::HTTP_OK);
    }

    #[Route('/api/conseils', name: 'app_conseil_create', methods: ['POST'])]
    public function create(): JsonResponse
    {
        return $this->json(null, Response::HTTP_NOT_IMPLEMENTED);
    }

    #[Route('/api/conseils/{id}', name: 'app_conseil_update', requirements: ['id' => '\d+'], methods: ['PUT'])]
    public function update(int $id): JsonResponse
    {
        return $this->json(null, Response::HTTP_NOT_IMPLEMENTED);
    }

    #[Route('/api/conseils/{id}', name: 'app_conseil_delete', requirements: ['id' => '\d+'], methods: ['DELETE'])]
    public function delete(int $id): JsonResponse
    {
        return $this->json(null, Response::HTTP_NOT_IMPLEMENTED);
    }
}
