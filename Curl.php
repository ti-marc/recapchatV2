<?php 


/**
 * Curl retrieve information from API 
 */
class Curl
{    
    /**
     * url 
     *
     * @var mixed
     */
    private $url;    
    /**
     * __construct
     *
     * @param  mixed $url must take a URL website
     * @return void
     */
    public function __construct($url)
    {
        $this->url = $url;
    }
    
    /**
     * getData
     * 
     * curl manuel => https://www.php.net/manual/en/ref.curl.php
     * 
     * @return array  return data array   or ['errors_curl'] 
     */
    public function getData():array
    {
        $curl = curl_init($this->url);
        $error_curl = curl_error($curl);
        $option = [];
        $option[CURLOPT_RETURNTRANSFER] = true;
        
        if($_SERVER['SERVER_NAME'] === "localhost")
        {
            $option[CURLOPT_SSL_VERIFYPEER] = false;

        }
        curl_setopt_array($curl,$option);
        $data = curl_exec($curl);
        if(!empty($error_curl))
        {
           
            return ['errors_curl' =>"Errors curl : ".$error_curl];
        }elseif($data === false)
        {
            return ["error_curl" => "cannot return data with curl_exec == false" ];
        }
        return json_decode($data,true);
    }

}