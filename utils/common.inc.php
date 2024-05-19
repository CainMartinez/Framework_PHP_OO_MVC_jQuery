<?php
    class common {
        public static function load_error() {
            require_once (VIEW_PATH_INC . 'top_page.html');
            require_once (VIEW_PATH_INC . 'header.html');
            require_once (VIEW_PATH_INC . 'error404.html');
            require_once (VIEW_PATH_INC . 'footer.html');
        }
        
        public static function load_view($topPage, $view) {
            // echo 'hola';
            $topPage = VIEW_PATH_INC . $topPage;
            if ((file_exists($topPage)) && (file_exists($view))) {
                require_once ($topPage);
                // require_once ('C:/xampp/htdocs/Ejercicios/Framework_PHP_OO_MVC/view/inc/header.html');
                require_once (VIEW_PATH_INC . 'header.html');
                require_once ($view);
                require_once (VIEW_PATH_INC . 'bottom_page.html');
                require_once (VIEW_PATH_INC . 'footer.html');
            }else {
                // self::load_error();
            }
        }
        
        public static function load_model($model, $function = null, $args = null) {
            try {
                $dir = explode('_', $model);
                $path = constant('MODEL_' . strtoupper($dir[0])) .  $model . '.class.singleton.php';
                // error_log("Loading model from path: $path", 3, "debug.log");

                if (file_exists($path)) {
                    require_once ($path);
                    // error_log("Model file included: $path", 3, "debug.log");

                    if (method_exists($model, $function)) {
                        // error_log("Function $function exists in model $model", 3, "debug.log");
                        $obj = $model::getInstance();

                        if ($args != null) {
                            // error_log("Arguments $args[0],$args[1],$args[2], exists in function $function", 3, "debug.log");
                            return call_user_func(array($obj, $function), $args);
                        }
                        // error_log("Not exists arguments in function $function", 3, "debug.log");
                        return call_user_func(array($obj, $function));
                    } else {
                        // error_log("Function $function does not exist in model $model", 3, "debug.log");
                        throw new Exception("Function $function not found in model $model");
                    }
                } else {
                    // error_log("Model file not found: $path", 3, "debug.log");
                    throw new Exception("Model $model not found");
                }
            } catch (Exception $e) {
                // error_log("Exception in load_model: " . $e->getMessage(), 3, "debug.log");
                throw $e;
            }
        }
    }
?>