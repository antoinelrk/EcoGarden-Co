<?php

namespace App\Controller;

use App\Enums\HttpMethodsEnum;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\Cache\TagAwareCacheInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

final class WeatherController extends AbstractController
{
    /**
     * Base URL for the OpenWeatherMap API.
     * @var string
     */
    private const string BASE_URL = 'https://api.openweathermap.org/data/2.5';

    /**
     * WeatherController constructor.
     *
     * @param TagAwareCacheInterface $tagAwareCache
     * @param HttpClientInterface $httpClient
     */
    public function __construct(
        private readonly TagAwareCacheInterface $tagAwareCache,
        private readonly HttpClientInterface $httpClient,
    ) {}

    /**
     * Fetches weather data for a given city or the user's default city.
     *
     * @param Request $request
     * @param string|null $city
     * @return JsonResponse
     *
     * @throws TransportExceptionInterface|\Psr\Cache\InvalidArgumentException
     */
    #[Route('/api/meteo/{city?}', name: 'app_weather', methods: ['GET'])]
    public function meteo(Request $request, ?string $city): JsonResponse
    {
        $city = $city ?? $this->getUser()->getCity();
        $cacheId = 'weather_data_' . $city;

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
