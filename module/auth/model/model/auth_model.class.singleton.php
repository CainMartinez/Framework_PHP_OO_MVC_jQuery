<?php
    class auth_model{
        private $bll;
        static $_instance;

        function __construct(){
            $this->bll = auth_bll::getInstance();
        }

        public static function getInstance(){
            if (!(self::$_instance instanceof self)) {
                self::$_instance = new self();
            }
            return self::$_instance;
        }
        public function get_register($args){
            return $this->bll->register_BLL($args);
        }
        public function get_verify($token){
            return $this->bll->verify_BLL($token);
        }
        public function get_recover($email){
            return $this->bll->recover_BLL($email);
        }
        public function get_verify_token($token){
            return $this->bll->verify_token_BLL($token);
        }
        public function get_new_password($args){
            return $this->bll->new_password_BLL($args);
        }
        public function get_login($args){
            return $this->bll->login_BLL($args);
        }
        public function get_data_user($access_token){
            return $this->bll->data_user_BLL($access_token);
        }
        public function get_logout(){
            return $this->bll->logout_BLL();
        }
        public function get_control_user($args){
            return $this->bll->control_user_BLL($args);
        }
        public function get_activity(){
            return $this->bll->activity_BLL();
        }
        public function get_refresh_cookies(){
            return $this->bll->refresh_cookies_BLL();
        }
        public function get_otp($otp_code){
            return $this->bll->otp_BLL($otp_code);
        }
        public function get_social_auth($args){
            return $this->bll->social_auth_BLL($args);
        }
    }
?>