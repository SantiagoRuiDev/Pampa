<?php

// @ Copy and paste this base in new Middlewares.
namespace App\Controller;

use App\Model\TestModel;
use Module\Filemanager;
use Module\Authentication;
use Router\Request;
use Router\Response;
use Error;
use Module\Cookie;
use Module\Session;

class TestController
{
    private Filemanager $filemanager;
    private Authentication $authentication;
    private Session $session;
    private TestModel $model;

    public function __construct(Request $request) // Get the request from Router.
    {
        $this->model = new TestModel();
        $this->filemanager = new Filemanager();
        $this->session = new Session();
        $this->authentication = new Authentication(); // Create a new token with user Identifier.
    }
    // @ BASE //
    function destroyMySession(Request $req, Response $res) {
        $this->session->initialize();
        $this->session->destroy();
        $res->send(array("session-saved-token" => $this->session->get("token")), 200);
        return;
    }

    function getSessionInfo(Request $req, Response $res) {
        $this->session->initialize();
        $res->send(array("session-saved-token" => $this->session->get("token")), 200);
        return;
    }

    function getExample(Request $req, Response $res): void {
        //Remember to include your secret signature, default signature == "pampa-framework";
        $this->authentication->setSignature("your-secret-key");
        $this->authentication->setTokenExpireDate(6600); // 2 hours from now.
        $this->authentication->setTokenData('id', 3); // This can be user identifier, email, etc.
        $this->authentication->setTokenData('mail', 'test@pampa-framework.com'); // This can be user identifier, email, etc.
        $this->authentication->createToken(); // After you edit the JWT settings, generate your token.

        $this->session->initialize();
        $this->session->set('token', $this->authentication->exportToken());

        $passwordHashExample = $this->authentication->hashPassword("HashingPasswordExample");
        $comparePasswords = $this->authentication->comparePassword("HashingPasswordExample", $passwordHashExample);

        $cookie = new Cookie();

        $cookie->setName("user-preferences");
        $cookie->setExpiration(86400);
        $cookie->setPath("/");
        $cookie->setValue(json_encode(array("user" => "preferences-here")));
        $cookie->setHttpOnly();
        $cookie->initialize();

        $body = $req->getBody();

        // Export the generated token.
        $res->send(array(
            "token" => $this->authentication->exportToken(),
            "passwordMatch" => $comparePasswords,
            "cookie" => Cookie::fetch("user-preferences"),
            "httpRequestBody" => $body
        ), 200);
        return;
        
    }

    function postExample(Request $req, Response $res): void
    {
        try {
            $files = $req->getFiles();

                                                   // FINAL DIR            // FORM-DATA KEY NAME
            $saveFile = $this->filemanager->saveImage('public/files/images/', $files['image']);

            $res->send(array("message" => $saveFile), 200);
            return;
        } catch (\Throwable $th) {
            // In this example if you try send an expired or invalid token you will get this response 
            $res->send(array("error" => $th->getMessage()), 400);
            return;
        }
    }

    function deleteExample(Request $req, Response $res): void
    {
        try {
            $body = $req->getBody();

            if(!isset($body->dir)){ // You can verify this on Middleware also.
                throw new Error('Please, fill the dir field');
            }

            $searchAndDelete = $this->filemanager->removeFile($body->dir);

            if ($searchAndDelete)
                $res->send(array("message" => 'File removed successfully'), 200);
                return;

        } catch (\Throwable $th) {
            // In this example if you try send an expired or invalid token you will get this response 
            $res->send(array("error" => $th->getMessage()), 400);
            return;
        }
    }

    function putExample(Request $req, Response $res): void
    {
        try {
            $headers = $req->getHeaders();

            $params = $req->getParams();
            //Remember to include your secret signature, or you won't be able to read the content inside the JWTs exported with your signature.
            $this->authentication->setSignature('your-secret-key');
            
            // Read the token inserted in HTTP Headers and return details.
            $tokenDetails = $this->authentication->decodeToken($headers['x-access-token']);

            $res->send(array("message" => $tokenDetails, "identifierInserted" => $params[':ID']), 200);
            return;
        } catch (\Throwable $th) {
            // In this example if you try send an expired or invalid token you will get this response 
            $res->send(array("error" => $th->getMessage()), 400);
            return;
        }
    }
}
