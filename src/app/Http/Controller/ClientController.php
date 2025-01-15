<?php

namespace KpEsportes\App\Http\Controller;

use KpEsportes\App\Domain\Model\Client;
use KpEsportes\App\Domain\Service\ClientService;
use KpEsportes\App\Http\Request;
use KpEsportes\App\Util\Validator;

class ClientController extends Controller {

    public ClientService $clientService;
    public Request $request;

    public function __construct() {
        $this->clientService = new ClientService;
        $this->request = new Request;
    }

    public function signup() {
        Validator::validate([
            "name" => ["required", "min:3", "max:20"],
            "email" => ["required", "email", "unique:clients,email"],
            "password" => ["required", "min:8", "max:16"],
        ]);

        $client = new Client;

        $client->name = $this->request->getInput("name");
        $client->email = $this->request->getInput("email");
        $client->password = $this->request->getInput("password");

        $this->clientService->save($client);
    
        return [
            "message" => "Cliente cadastrado com sucesso",
        ];
    }

    public function login() {}

    public function logout() {}

}