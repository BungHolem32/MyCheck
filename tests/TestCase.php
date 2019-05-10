<?php

namespace Tests;

use App\Http\Repositories\MoviesRepo;
use Faker\Factory;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

/**
 * @property MoviesRepo movie_repo
 */
abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;
}
