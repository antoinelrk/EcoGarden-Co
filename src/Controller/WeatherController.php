<?php

namespace App\Controller;

use App\Enums\HttpMethodsEnum;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\Cache\TagAwareCacheInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

final class WeatherController extends AbstractController
{
    private const string BASE_URL = 'https://api.openweathermap.org/data/2.5';

    public function __construct(
        private readonly TagAwareCacheInterface $tagAwareCache,
        private readonly HttpClientInterface $httpClient,
    ) {}


    #[Route('/api/meteo/{city?}', name: 'app_advice', methods: ['GET'])]
    public function meteo(Request $request, ?string $city): JsonResponse
    {
        $city = $city ?? $this->getUser()->getCity();
        $cacheId = 'weather_data_' . $city;

        $data = $this->httpClient->request(
            HttpMethodsEnum::GET->value,
            self::BASE_URL . '/weather',
            ['query' => [ 'q' => $city, 'appid' => $_ENV['WEATHER_API_KEY']]]
        );

        $data = $this->tagAwareCache->get($cacheId, function () use ($city) {
            $data = $this->httpClient->request(
                HttpMethodsEnum::GET->value,
                self::BASE_URL . '/weather',
                ['query' => [ 'q' => $city, 'appid' => $_ENV['WEATHER_API_KEY']]]
            );

            return $data->toArray();
        });

        return $this->json([
            $data,
            'status' => 'success'
        ]);
    }
}
