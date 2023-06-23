<?php

namespace app\models;

use engine\db\ActiveRecord;
class User extends ActiveRecord
{
    public string $primary_key = 'id';

    public static function getTableName(): string
    {
        return 'users';
    }

}