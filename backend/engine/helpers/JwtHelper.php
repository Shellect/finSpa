<?php

namespace engine\helpers;

use engine\App;

class JwtHelper
{
    private static function base64url_encode($str): string
    {
        return str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($str));
    }

    public static function createToken($user_id): string
    {
        $header = json_encode(['typ' => 'JWT', 'alg' => 'HS256']);
        $payload = json_encode(['user_id' => $user_id, 'exp' => time()]);
        $base64UrlHeader = self::base64url_encode($header);
        $base64UrlPayload = self::base64url_encode($payload);
        $signature = hash_hmac('sha256', "$base64UrlHeader.$base64UrlPayload", App::$config['secret_key'], true);
        $base64UrlSignature = self::base64url_encode($signature);
        return "$base64UrlHeader.$base64UrlPayload.$base64UrlSignature";
    }

    public static function validateToken($token)
    {
        $tokenParts = explode('.', $token);
        $header = base64_decode($tokenParts[0]);
        $payload = base64_decode($tokenParts[1]);
        $signature_provided = $tokenParts[2];

        $expiration = json_decode($payload)->exp;
        $is_token_expired = ($expiration - time()) < 0;

        $base64_url_header = self::base64url_encode($header);
        $base64_url_payload = self::base64url_encode($payload);
        $signature = hash_hmac('SHA256', "$base64_url_header.$base64_url_payload", App::$config['secret_key'], true);
        $base64_url_signature = self::base64url_encode($signature);
        $is_signature_valid = ($base64_url_signature === $signature_provided);
        return !$is_token_expired && $is_signature_valid;
    }
}