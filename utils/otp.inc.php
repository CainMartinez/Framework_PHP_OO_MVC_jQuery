<?php
require_once __DIR__ . '/vendor/autoload.php';
    class otp{
        public static function send_otp($values){
            try{
                error_log("send_otp called with values: " . json_encode($values), 3, "debug.log");

                $otp = parse_ini_file(UTILS . "credentials.ini");
                $ultramsg_token = $otp['OTP_TOKEN'];
                $instance_id = $otp['OTP_INSTANCE'];
                $client = new UltraMsg\WhatsAppApi($ultramsg_token, $instance_id);
                $to = $otp['OTP_NUMBER'];
                $body = $values['text'];
                $api = $client->sendChatMessage($to, $body);

                error_log("API response: " . json_encode($api), 3, "debug.log");

                return json_encode($api);
            } catch (Exception $e) {
                error_log("Error occurred in send_otp: " . $e->getMessage(), 3, "debug.log");
                return json_encode(['status' => 'error', 'message' => $e->getMessage()]);
            }
        }

        public static function send_message($message){
            try {
                error_log("send_message called with message: " . json_encode($message), 3, "debug.log");

                switch ($message['type']) {
                    case 'verify'; //para el recover
                        $message['text'] = "Use this code to verify your account: " . $message['token'];
                        break;
                    case 'activate'; //para el login despues de 3 intentos fallidos
                        $message['text'] = "Use this code to activate your account: " . $message['token'];
                        break;
                }

                $result = self::send_otp($message);

                error_log("Result from send_otp: " . json_encode($result), 3, "debug.log");

                return $result;
            } catch (Exception $e) {
                error_log("Error occurred in send_message: " . $e->getMessage(), 3, "debug.log");
                return json_encode(['status' => 'error', 'message' => $e->getMessage()]);
            }
        }
    }
?>