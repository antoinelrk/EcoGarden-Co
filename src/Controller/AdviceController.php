<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class AdviceController extends AbstractController
{
    #[Route('/api/advices', name: 'app_advice', methods: ['GET'])]
    public function index(): JsonResponse
    {
        // TODO: Ajouter la logique pour récupérer les conseils
        $conseils = [];

        return $this->json($conseils, Response::HTTP_OK);
    }

    #[Route('/api/advices/{id}', name: 'app_advice_show', requirements: ['id' => '\d+'], methods: ['GET'])]
    public function show(int $id): JsonResponse
    {
        // TODO: Ajouter la logique pour récupérer les conseils
        $conseil = [];

        return $this->json($conseil, Response::HTTP_OK);
    }

    #[Route('/api/advices', name: 'app_advice_create', methods: ['POST'])]
    public function store(): JsonResponse
    {
        return $this->json(null, Response::HTTP_NOT_IMPLEMENTED);
    }

    #[Route('/api/advices/{id}', name: 'app_advice_update', requirements: ['id' => '\d+'], methods: ['PUT'])]
    public function update(int $id): JsonResponse
    {
        return $this->json(null, Response::HTTP_NOT_IMPLEMENTED);
    }

    #[Route('/api/advices/{id}', name: 'app_advice_delete', requirements: ['id' => '\d+'], methods: ['DELETE'])]
    public function delete(int $id): JsonResponse
    {
        return $this->json(null, Response::HTTP_NOT_IMPLEMENTED);
    }
}
