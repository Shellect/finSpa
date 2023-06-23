<?php

namespace app\controllers;

use engine\Controller;
use engine\helpers\JwtHelper;
use engine\Response;
use app\models\User;

class UserController extends Controller
{
    public function actionIndex(): Response
    {
        return new Response("");
    }

    public function actionSignup(): Response
    {
        sleep(2);
        $username = $this->request->postParams['username'] ?? '';
        $password = $this->request->postParams['password'] ?? '';
        if(!$username && !$password){
            return $this->json(['status' => 'Fail', 'isLoggedIn' => false, 'message' => 'User data is empty']);
        }
        $userQuery = User::where(['username' => $username]);
        if($userQuery->count()) {
            return $this->json(['status' => 'Fail', 'isLoggedIn' => false, 'message' => 'User already exists']);
        }
        $user = new User();
        $user['username'] = $username;
        $user['password'] = password_hash($password, PASSWORD_DEFAULT);
        $userId = $user->save();
        if (isset($userId)) {
            return $this->json(['status' => 'Success',
                'isLoggedIn' => true,
                'username' => $username,
//                'roles' => ['ROLE_USER'],
                'authToken' => JwtHelper::createToken($userId)]);
        }
        return $this->json(['status' => 'Fail', 'isLoggedIn' => false, 'message' => 'Internal server error']);
    }

    public function actionSignin(): Response
    {
        sleep(1);
        $username = $this->request->postParams['username'];
        $password = $this->request->postParams['password'];

        $user = User::where(['username' => $username])->one();
        if(password_verify($password, $user['password'])) {
            return $this->json(['status' => 'Success',
                'isLoggedIn' => true,
                'username' => $user['username'],
//                'roles' => $user['role'],
                'authToken' => JwtHelper::createToken($user['id'])]);
        }
        return $this->json(['status' => 'Fail', 'isLoggedIn' => false, 'message' => 'Authentication failed']);
    }
}