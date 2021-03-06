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
     * @param bool $movie_id
     *
     * @return \Illuminate\Http\JsonResponse
     * @internal $response_object
     */
    function index($movie_id = false)
    {
        $movie_id                = request()->query('movie_id') ? request()->query('movie_id') : $movie_id;
        $response_object         = collect(['message' => 'no recommendations found', 'status' => 204, 'data' => null]);
        $recommended_movies      = $this->movie_repo->resolveRecommandations($movie_id);
        $recommendation_is_empty = $recommended_movies->isEmpty();

        if (!$recommendation_is_empty) {
            $message         = "these movies are recommended according to the movie id {$movie_id}";
            $response_object = collect(['message' => $message, 'status' => 200, 'data' => $recommended_movies]);

            $this->resolveRecursive($movie_id, $response_object);
        }

        return response()->json($response_object);
    }

    /**
     * @param int                            $movie_id
     * @param \Illuminate\Support\Collection $response_object
     *
     * @return bool
     */
    private function resolveRecursive(int $movie_id, $response_object)
    {
        $depth = request()->query('depth') ? request()->query('depth') : false;

        if (!$depth) {
            return true;
        }

        if ($depth > 3) {
            $not_allowed_response = ['data' => null, 'message' => 'max allowed depth is 3!', 'status' => 400];

            foreach ($not_allowed_response as $key => $value) {
                $response_object->put($key, $value);
            }

            return true;
        }

        $recommendations = $this->movie_repo->getRecommendedRecursively($movie_id, $depth);
        if ($recommendations) {
            $response_object->put('data', $recommendations);
        }
    }
}
