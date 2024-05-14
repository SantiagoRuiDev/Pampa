<?php

// @ Copy and paste this base in new Middlewares.
namespace App\Middleware;

use Error;
use Router\Response;
use Router\Request;
use Utils\Authentication;

class TestMiddleware
{
    // @ BASE // 

    // @ Check and verify the Body/Headers/Params details here.
    public function test(Request $req, Response $res): mixed
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
            return $res->send(array("error" => $th->getMessage()), 400);
        }
    }
}
