<?php

namespace Tests\Unit;

use App\Helpers\Helpers;
use App\Http\Repositories\MoviesRepo;
use Tests\TestCase;
use Tmdb\Client;
use Tmdb\Repository\MovieRepository;

class MovieRepoTest extends TestCase
{

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testValidRepository()
    {
        $this->assertIsObject($this->movie_repo);
        $this->assertObjectHasAttribute('client', $this->movie_repo);
        $this->assertObjectHasAttribute('movie_repository_api', $this->movie_repo);
        $this->assertTrue($this->movie_repo->client instanceof Client);
        $this->assertTrue($this->movie_repo->movie_repository_api instanceof MovieRepository);
    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testGetRecommandationsByMovieId()
    {
        $movie_id    = '33';
        $recommended = $this->movie_repo->resolveRecommandations($movie_id);
        $length      = count($recommended);
        $data        = $recommended->first();

        $this->assertNotEmpty($recommended);
        $this->assertIsObject($recommended);
        $this->assertEquals(3, $length);
        $this->assertIsArray($data);
        $this->assertEquals(3, count($data));
        $this->assertArrayHasKey('id', $data);
        $this->assertArrayHasKey('name', $data);
        $this->assertArrayHasKey('release-year', $data);
    }


    /**
     * A basic test example.
     *
     * @return void
     */
    public function testGetRecommandationsByMovieIdRecursively()
    {
        $movie_id    = '33';
        $depth       = 2;
        $recommended = $this->movie_repo->getRecommendedRecursively($movie_id, $depth);
        $length      = count($recommended);
        $first_result        = $recommended[0];

        $this->assertNotEmpty($recommended);
        $this->assertEquals(3, $length);
        $this->assertIsArray($first_result);
        $this->assertEquals(4, count($first_result));
        $this->assertArrayHasKey('id', $first_result);
        $this->assertArrayHasKey('name', $first_result);
        $this->assertArrayHasKey('release-year', $first_result);
    }


    /**
     *
     */
    public function setUp()
    {
        parent::setUp();
        $this->movie_repo = new MoviesRepo();
    }
}
