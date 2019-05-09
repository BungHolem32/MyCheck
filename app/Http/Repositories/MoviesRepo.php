<?php

namespace App\Http\Repositories;

use Tmdb\ApiToken;
use Tmdb\Client;
use Tmdb\Model\Collection;
use Tmdb\Model\Movie;
use Tmdb\Repository\MovieRepository;

/**
 * @property MovieRepository movie_repository_api
 * @property Client          client
 */
class MoviesRepo
{
    /**
     * MoviesRepo constructor.
     */
    public function __construct()
    {
        $this->client               = $this->generateClient();
        $this->movie_repository_api = new MovieRepository($this->client);
    }

    /**
     * @return Client
     */
    private function generateClient()
    {
        $config = config('tmdb');

        $token          = new ApiToken($config['api_key']);
        $configurations = [
            'cache'  => $config['options']['cache'],
            'log'    => $config['options']['log'],
            'secure' => $config['options']['secure']
        ];

        return new Client($token, $configurations);
    }

    /**
     * @param $movie_id
     *
     * @return array|\Illuminate\Support\Collection
     */
    public function resolveRecommandations($movie_id)
    {
        $params          = ['language' => 'us'];
        $recommandations = $this->getRecommendationsById($movie_id, $params);

        if (!$recommandations) {
            return [];
        }

        return $this->prepareResults($recommandations) ?? [];
    }

    /**
     * @param        $movie_id
     * @param        $params
     *
     * @return Collection | \Tmdb\Model\AbstractModel
     */
    public function getRecommendationsById($movie_id, $params = ['language' => 'us'])
    {
        return $this->movie_repository_api->getRecommendations($movie_id, $params) ?? collect();
    }

    /**
     * @param $results
     *
     * @return \Illuminate\Support\Collection
     */
    private function prepareResults($results)
    {
        $results = $results->map(/**
         * @param $idx
         * @param $movie Movie
         *
         * @return array
         */
            function ($idx, $movie) {
                return [
                    'id'           => $movie->getId(),
                    'name'         => $movie->getTitle(),
                    'release-year' => $movie->getReleaseDate()->format('Y')
                ];
            });

        $sliced_results = collect($results)->slice(0, 3)->values();

        return $sliced_results;
    }


    /**
     * @param $id
     * @param $depth
     *
     * @return array
     */
    public function getRecommendedRecursively($id, $depth)
    {
        $movie_recommandations = collect($this->resolveRecommandations($id));

        return $movie_recommandations
            ->map(function ($movie) use ($depth) {
                $records = $this->loadRecommended($movie, $depth - 1);

                return $records;
            })->toArray();
    }

    /**
     * @param $movie
     * @param $depth
     *
     * @return mixed
     */
    private function loadRecommended($movie, $depth)
    {
        //get the recommended movies [for this movie]
        $recommended_movies = $this->resolveRecommandations($movie['id']);

        if ($depth > 0) {
            $recommended_movies = $recommended_movies->map(function ($recommended_movie) use ($depth, $movie) {
                return $this->loadRecommended($recommended_movie, $depth - 1);
            });
        }
        $movie['recommendations'] = $recommended_movies->toArray();

        return $movie;
    }
}
