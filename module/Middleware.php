<?php

namespace Module;

use Router\Request;
use Router\Response;

/**
 * Middleware Handler Class
 *
 * Abstract class for services middlewares,
 * include the handle method and secure your HTTP requests.
 * 
 */
abstract class Middleware {

    abstract public function handle(Request $req, Response $res): bool;

}