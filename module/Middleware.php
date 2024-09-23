<?php

namespace Module;

use Router\Request;
use Router\Response;

/**
 * Abstract Middleware Class
 *
 * An abstract class for implementing service middlewares.
 * Provides the `handle` method to process and secure HTTP requests.
 * Designed to add layers of security, validation, and request modification in a service pipeline.
 * 
 */
abstract class Middleware {

    abstract public function handle(Request $req, Response $res): bool;

}