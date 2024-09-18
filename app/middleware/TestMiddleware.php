<?php

// @ Copy and paste this base in new Middlewares.
namespace App\Middleware;

use Error;
use Router\Response;
use Router\Request;
use Module\Authentication;
use Module\Middleware;

class TestMiddleware extends Middleware
{

    // Follow the abstract class main method and secure your request.
    public function handle(Request $req, Response $res): bool
    {
        try {
            $headers = $req->getHeaders(); // Load all headers from request.

            // Example
            if (!isset($headers['x-access-token'])) {
                throw new Error("Please, try sending a token");
            }

            // Verify is token is valid
            $authenticationHandler = new Authentication();

            $authenticationHandler->setSignature('your-secret-key');

            // This function return an false in case of invalid token;
            if (!$authenticationHandler->verifyToken($headers['x-access-token'])) {
                throw new Error('Invalid or expired token');
            }
            // @ IMPORTANT - If this function isn't executed, you won't see the Controller Final Response.
            return $req->next();

        } catch (\Throwable $th) {
            $res->send(array("error" => $th->getMessage()), 400);
            return false;
        }
    }
}
