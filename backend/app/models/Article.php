<?php

namespace app\models;

use engine\db\ActiveRecord;

class Article extends ActiveRecord
{
    public string $primary_key = 'id';

    public static function getTableName(): string
    {
        return 'articles';
    }
}