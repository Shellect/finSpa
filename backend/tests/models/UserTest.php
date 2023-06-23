<?php

namespace models;

use app\models\User;
use engine\App;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    protected function setUp(): void
    {
        $config = include('../../config.php');
        new App($config);
    }

    protected function tearDown(): void
    {

    }
    public function testGetAllUsers()
    {
        $user = User::find(['username' => 'asd'])->one();
        $this->assertSame($user['username'], 'asd');
    }

    public function testSaveOneUser()
    {
        $user = new User();
        $user->username = 'asd2';
        $user->password = 'asd2';
        $userId = $user->save();
        $addedUser = User::find(['username' => 'asd2'])->one();
        $this->assertSame($userId, $addedUser['id']);
    }
}
