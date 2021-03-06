<?php

namespace App\Modules\User\Repositories;

use App\Libraries\Logger;
use CodeIgniter\Controller;
use App\Modules\User\Models\UserModel;
use \Firebase\JWT\JWT;



class UserRepositories extends Controller
{
    public function __construct()
    {
        $this->logger = new Logger();
        $this->userModel = new UserModel();
    }

    public function getUserInfo($request)
    {
        try {
            $response = [
                'resultCode' => 200,
                'resultMessage' => 'Success',
                'data' => 'User data',
            ];
            $this->logger->writeApiLogs($request, $response, 'getInfo');
            return $response;
        } catch (\Exception $e) {
            $response = [
                'resultCode' => 500,
                'resultMessage' => $e->getMessage(),
            ];
            $this->logger->writeApiLogs($request, $response, 'getInfo');
            return $response;
        }
    }


    public function userRegister($request)
    {
        $data = [
            'username' => $request['payloads']['username'],
            'password' => password_hash($request['payloads']['password'], PASSWORD_DEFAULT),
            'firstname' => $request['payloads']['firstname'],
            'lastname' => $request['payloads']['lastname'],
        ];
        $response =  $this->userModel->addUser($data);
        $this->logger->writeApiLogs($request, $response, 'userRegis');
        return $response;
    }


    public function loginProcess($request)
    {
        $data = [
            'username' => $request['payloads']['username'],
            'password' => $request['payloads']['password'],
        ];
        $response = $this->userModel->getUserLogin($data);
        $this->logger->writeApiLogs($request, $response, 'userLogin');
        return $response;
    }

    public function getUserSecretData($request)
    {
        $token = $request['headers']['Authorization'];

        try {
            $decodeData = (array) JWT::decode($token, JWT_KEY, array(JWT_ALGORITHM));
            $id = $decodeData['id'];
            $response = [
                'resultCode' => 200,
                'resultMessage' => 'Success',
                'data' => "This is secret data. Your ID : {$id}"
            ];
            $this->logger->writeApiLogs($request, $response, 'getSecretData');
            return $response;
        } catch (\Exception $e) {
            $response = [
                'resultCode' => 500,
                'resultMessage' => $e->getMessage(),
            ];
            $this->logger->writeApiLogs($request, $response, 'getSecretData');
            return $response;
        }
    }

    public function getUserList($request)
    {
        $response = $this->userModel->allUser();
        $this->logger->writeApiLogs($request, $response, 'getUserList');
        return $response;
    }
}
