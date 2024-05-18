<?php
    require __DIR__ . '/vendor/autoload.php';
    class mail {
        public static function send_email($email) {
            try {
                // error_log("send_email called with: " . json_encode($email), 3, "debug.log");
    
                switch ($email['type']) {
                    case 'validate':
                        $email['inputMatter'] = 'Email verification';
                        $email['inputMessage'] = "<h2>Email verification.</h2><a href='http://localhost/living_mobility/login/verify/$email[token]'>Click here for verify your email.</a>";
                        break;
                    case 'recover':
                        $email['inputMatter'] = 'Recover password';
                        $email['inputMessage'] = "<a href='http://localhost/living_mobility/login/recover/$email[token]'>Click here for recover your password.</a>";
                        break;
                    default:
                        throw new Exception("Unknown email type: " . $email['type']);
                }
    
                // error_log("Email details set: " . json_encode($email), 3, "debug.log");
                return self::send_resend($email);
            } catch (Exception $e) {
                // error_log("Exception in send_email: " . $e->getMessage(), 3, "debug.log");
                return json_encode(['status' => 'error', 'message' => $e->getMessage()]);
            }
        }
    
        public static function send_resend($values){
            try {
                // error_log("send_resend called with: " . json_encode($values), 3, "debug.log");
        
                $resend = parse_ini_file(UTILS . "credentials.ini");
                $api_key = $resend['MAIL_API_KEY'];
                $email = $resend['EMAIL'];
        
                // error_log("API key loaded: $api_key", 3, "debug.log");
        
                $resend = Resend::client($api_key);
        
                // error_log("Resend client created", 3, "debug.log");
        
                $result = $resend->emails->send([
                    'from' => 'Acme <onboarding@resend.dev>',
                    'to' => $email,  
                    'subject' => $values['inputMatter'],
                    'html' => $values['inputMessage'],
                ]);
        
                // error_log("Email sent, result: " . json_encode($result), 3, "debug.log");
                return $result->toJson();
            } catch (\Exception $e) {
                // error_log("Exception in send_resend: " . $e->getMessage(), 3, "debug.log");
                // error_log("Exception details: " . $e, 3, "debug.log");
        
                return json_encode(['status' => 'error', 'message' => $e->getMessage()]);
            }
        }
        
    }
    
?>