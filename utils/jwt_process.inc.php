<?php
class jwt_process {
    public static function encode($user) {
        $jwt = parse_ini_file(UTILS . "credentials.ini");
        $header = $jwt['JWT_HEADER'];
        $secret = $jwt['JWT_SECRET'];
        $payload = json_encode(['iat' => time(), 'exp' => time() + (60 * 60), 'name' => $user]);
        $JWT = new jwt();
        return $JWT -> encode($header, $payload, $secret);
    }

    public static function decode_token($token) {
        $jwt = parse_ini_file(UTILS . "jwt.ini");
        $JWT = new jwt();
        return $JWT -> decode($token, $jwt['JWT_SECRET']);
    }
}