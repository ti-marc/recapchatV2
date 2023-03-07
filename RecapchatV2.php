<?php
//if you are using a namespace, remove the need and replace it with your namespace 
//you need Curl.php to make RecapchatV2.php class work
require_once "Curl.php";
/**
 * ConfigRecapchatV2
 *  you must go to https://www.google.com/recaptcha/about/ Then in the v3 Admin console section to find the keys
 *
 */
class RecapchatV2
{
    //Use this site key in your website .
    private static $key_website = "6LeQzHEkAAAAAFOQejgzjq0y26nWbecR7hrecPRV"; // for testing http://localhost/ or http://localhost:8000/ key =>  6LeQzHEkAAAAAFOQejgzjq0y26nWbecR7hrecPRV
    //Use this secret key for communication between your site and the reCAPTCHA service.
    private static $key_private = "6LeQzHEkAAAAAEHVcEkrvgav0V0YIkZINUvSiEr1"; // for testing http://localhost/ or http://localhost:8000/ key =>  6LeQzHEkAAAAAEHVcEkrvgav0V0YIkZINUvSiEr1
    //customize your error message for the getFullContent() method   
    private static $message_error = "<div style='color:#fc3a3a;margin-bottom: 5px;margin-top: 5px;'>capchat invalid</div>";   


    /**
     * is_valid
     *
     * @return bool verify recpachat is valid 
     * valid = true 
     * invalid = false
     * null = the form has not been sent
     * must not be called with the getFullContent() method is_valid() can only be called once
     */
    public static function  is_valid():?bool
    {
        if(isset($_POST['g-recaptcha-response']))
        {
            $curl = new Curl(self::getUrlGoogleApi());
            $data = $curl->getData();
            if((bool) $data['success'] === true && isset($data['success']))
            {
                return true;
            }elseif( (bool)$data['success'] === false && isset($data['success']))
            {
                return false;
            }
        }
        return null;
    }
                
        /**
         * getFullRecapchat you don't need to use any other method if you use this one
         * @return string allows to return the script the captcha is an error if there is an error
         * 
         */
        public static function getFullRecapchat():string
        {
           
            $is_valid = self::is_valid();
            $recapchat = self::getHTmlRecapchat();
            $script = self::getScriptRecapchat();
            if(isset($_POST['g-recaptcha-response']))
            {
                if($is_valid  === false)
                {
                    $message_error = self::$message_error;
                    return <<<HTML
                     $recapchat
                     $message_error
                     $script    
                    HTML;
                }else
                {
                    return <<<HTML
                    $recapchat
                    $script    
                 HTML;
                }
            }else
            {
                return <<<HTML
                $recapchat
                $script    
             HTML;
            }
        }
 
        /**
         * getHTmlRecapchat
         *
         * @return string returns the recaptcha in html
         */
        public static function getHTmlRecapchat():string
        {
            $key_website = self::$key_website;

            return "<div class=\"g-recaptcha\" data-sitekey=\"{$key_website}\"></div>";
          
            
        }        
        /**
         * getScriptRecapchat 
         *
         * @return string returns the JS google script to make the recaptcha v2 work
         */
        public static function getScriptRecapchat():string
        {
            return "<script src=\"https://www.google.com/recaptcha/api.js\" async defer></script>";
        }

           /**
     * getUrlGoogleApi 
     *
     * @return string Returns the google recaptcha link
     */
    private static function getUrlGoogleApi():string
    {
        return "https://www.google.com/recaptcha/api/siteverify?secret=".self::$key_private."&response=".$_POST['g-recaptcha-response'] ;
    }          
    }
