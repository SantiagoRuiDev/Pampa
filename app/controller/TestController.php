<?php

// @ Copy and paste this base in new Middlewares.
namespace App\Controller;

use App\Model\TestModel;
use Utils\Filemanager;
use Utils\Authentication;
use Router\Request;
use Router\Response;
use Error;

class TestController
{
    private Filemanager $filemanager;
    private Authentication $authentication;
    private TestModel $model;

    public function __construct(Request $request) // Get the request from Router.
    {
        $this->model = new TestModel();
        $this->filemanager = new Filemanager();
        $this->authentication = new Authentication(); // Create a new token with user Identifier.
    }
    // @ BASE //


    function getExample(Request $req, Response $res): mixed {
        //Remember to include your secret signature, default signature == "pampa-framework";
        $this->authentication->setSignature("your-secret-key");
        $this->authentication->setTokenExpireDate(6600); // 2 hours from now.
        $this->authentication->setTokenData('id', 3); // This can be user identifier, email, etc.
        $this->authentication->setTokenData('mail', 'test@pampa-framework.com'); // This can be user identifier, email, etc.
        $this->authentication->createToken(); // After you edit the JWT settings, generate your token.

        $passwordHashExample = $this->authentication->hashPassword("HashingPasswordExample");
        $comparePasswords = $this->authentication->comparePassword("HashingPasswordExample", $passwordHashExample);

        $body = $req->getBody();

        // Export the generated token.
        return $res->send(array(
            "token" => $this->authentication->exportToken(),
            "passwordMatch" => $comparePasswords,
            "httpRequestBody" => $body
        ), 200);
    }

    function postExample(Request $req, Response $res): mixed
    {
        try {
            $files = $req->getFiles();

                                                   // FINAL DIR            // FORM-DATA KEY NAME
            $saveFile = $this->filemanager->saveZip('public/files/zips/', $files['image']);

            return $res->send(array("message" => $saveFile), 200);
        } catch (\Throwable $th) {
            // In this example if you try send an expired or invalid token you will get this response 
            return $res->send(array("error" => $th->getMessage()), 400);
        }
    }

    function deleteExample(Request $req, Response $res): mixed
    {
        try {
            $body = $req->getBody();

            if(!isset($body->dir)){ // You can verify this on Middleware also.
                throw new Error('Please, fill the dir field');
            }

            $searchAndDelete = $this->filemanager->removeFile($body->dir);

            if ($searchAndDelete)
                return $res->send(array("message" => 'File removed successfully'), 200);

        } catch (\Throwable $th) {
            // In this example if you try send an expired or invalid token you will get this response 
            return $res->send(array("error" => $th->getMessage()), 400);
        }
    }

    function putExample(Request $req, Response $res): mixed
    {
        try {
            $headers = $req->getHeaders();

            $params = $req->getParams();
            //Remember to include your secret signature, or you won't be able to read the content inside the JWTs exported with your signature.
            $this->authentication->setSignature('your-secret-key');
            
            // Read the token inserted in HTTP Headers and return details.
            $tokenDetails = $this->authentication->decodeToken($headers['x-access-token']);

            return $res->send(array("message" => $tokenDetails, "identifierInserted" => $params[':ID']), 200);
        } catch (\Throwable $th) {
            // In this example if you try send an expired or invalid token you will get this response 
            return $res->send(array("error" => $th->getMessage()), 400);
        }
    }
}
