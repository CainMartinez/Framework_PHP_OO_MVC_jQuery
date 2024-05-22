<?php
    class middleware {
        public static function create_email_token($email) {
            try {
                $jwt = parse_ini_file(UTILS . "credentials.ini");
                if (!$jwt) {
                    // error_log("Failed to read credentials.ini", 3, "debug.log");
                    throw new Exception("Failed to read JWT credentials");
                }
    
                $header = $jwt['JWT_HEADER'] ?? null;
                $secret = $jwt['JWT_SECRET'] ?? null;
                
                if (!$header || !$secret) {
                    // error_log("JWT_HEADER or JWT_SECRET missing in credentials.ini", 3, "debug.log");
                    throw new Exception("JWT_HEADER or JWT_SECRET missing");
                }
    
                // error_log("JWT header: " . $header, 3, "debug.log");
                // error_log("JWT secret: " . $secret, 3, "debug.log");
    
                $payload = json_encode([
                    'iat' => time(),
                    'exp' => time() + 600,
                    'email' => $email
                ]);
                // error_log("JWT payload: " . $payload, 3, "debug.log");
    
                $JWT = new JWT();
                $token = $JWT->encode($header, $payload, $secret);
                if (!$token) {
                    // error_log("Failed to generate JWT token", 3, "debug.log");
                    throw new Exception("JWT token generation failed");
                }
    
                // error_log("Generated JWT token: " . $token, 3, "debug.log");
                return $token;
            } catch (Exception $e) {
                // error_log("Exception in create_email_token: " . $e->getMessage(), 3, "debug.log");
                throw $e;
            }
        }
        public static function decode_token($token){
            try {
                error_log("decode_token called with token: $token", 3, "debug.log");

                $jwt = parse_ini_file(UTILS . "credentials.ini");
                $secret = $jwt['JWT_SECRET'];
                $JWT = new JWT;

                error_log("JWT Secret: $secret", 3, "debug.log");

                $token_dec = $JWT->decode($token, $secret);

                error_log("Decoded token: $token_dec", 3, "debug.log");

                $rt_token = json_decode($token_dec, TRUE);

                error_log("Final token: " . json_encode($rt_token), 3, "debug.log");

                return $rt_token;
            } catch (Exception $e) {
                error_log("Error occurred in decode_token: " . $e->getMessage(), 3, "debug.log");
                return null;
            }
        }
        public static function decode_email_token($token){
            $jwt = parse_ini_file(UTILS . "credentials.ini");
            $secret = $jwt['JWT_SECRET'];
            $JWT = new JWT;
            $token_dec = $JWT->decode($token, $secret);
            $rt_token = json_decode($token_dec, TRUE);
            return $rt_token;
        }
        public static function create_access_token($username){
            $jwt = parse_ini_file(UTILS . "credentials.ini");
            $header = $jwt['JWT_HEADER'];
            $secret = $jwt['JWT_SECRET'];
            $payload = '{"iat":"' . time() . '","exp":"' . time() + (6000) . '","username":"' . $username . '"}';
            $JWT = new JWT;
            $token = $JWT->encode($header, $payload, $secret);
            return $token;
        }
        public static function create_refresh_token($username){
            $jwt = parse_ini_file(UTILS . "credentials.ini");
            $header = $jwt['JWT_HEADER'];
            $secret = $jwt['JWT_SECRET'];
            $payload = '{"iat":"' . time() . '","exp":"' . time() + (60000) . '","username":"' . $username . '"}';
            $JWT = new JWT;
            $token = $JWT->encode($header, $payload, $secret);
            return $token;
        }
    }
?>