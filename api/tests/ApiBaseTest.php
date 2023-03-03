<?php

namespace Tests;

use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * Class ApiBaseTest
 *
 * @package Tests
 */
abstract class ApiBaseTest extends TestCase
{
    use RefreshDatabase;
}