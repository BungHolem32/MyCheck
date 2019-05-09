<?php

namespace App\Http\Controllers;


use App\Http\Repositories\MoviesRepo;
use Tmdb\Api\Movies;

/**
 * @property  movies
 * @property MoviesRepo movie_repo
 */
class RecommendationsController extends Controller
{

    public function __construct()
    {
        $this->movie_repo = new MoviesRepo();
    }

    /**
     * @param      $id
     * @param bool $depth
     *
     * @internal $response_object
     * @return \Illuminate\Http\JsonResponse
     */
    function index($id, $depth = false)
    {
        $response_object         = collect(['message' => 'no recommendations found', 'status' => 204, 'data' => null]);
        $recommended_movies      = $this->movie_repo->resolveRecommandations($id);
        $recommendation_is_empty = $recommended_movies->isEmpty();

        if (!$recommendation_is_empty) {
            $message         = "these movies are recommended according to the movie id {$id}";
            $response_object = collect(['message' => $message, 'status' => 200, 'data' => $recommended_movies]);
            $this->resolveRecursive($id, $depth, $response_object);
        }

        return response()->json($response_object);
    }

    /**
     * @param int                            $id
     * @param                                $depth
     * @param \Illuminate\Support\Collection $response_object
     */
    private function resolveRecursive(int $id, $depth, $response_object)
    {
        if ($depth) {
            $recommendations = $this->movie_repo->getRecommendedRecursively($id, $depth);

            if ($recommendations) {
                $response_object->put('data', $recommendations);
            }
        }
    }
}
