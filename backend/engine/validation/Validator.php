<?php
namespace engine\validation;

use engine\App;

class Validator{

    public static function createValidator($type, $model, $attributes, $params = [])
    {
        $params['attributes'] = $attributes;

        if ($type instanceof \Closure) {
            $params['class'] = __NAMESPACE__ . '\InlineValidator';
            $params['method'] = $type;
        } elseif (!isset(static::$builtInValidators[$type]) && $model->hasMethod($type)) {
            // method-based validator
            $params['class'] = __NAMESPACE__ . '\InlineValidator';
            $params['method'] = [$model, $type];
        } else {
            unset($params['current']);
            if (isset(static::$builtInValidators[$type])) {
                $type = static::$builtInValidators[$type];
            }
            if (is_array($type)) {
                $params = array_merge($type, $params);
            } else {
                $params['class'] = $type;
            }
        }

        return App::createObject($type, $params);
    }
}
