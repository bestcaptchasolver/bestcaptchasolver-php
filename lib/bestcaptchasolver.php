<?php

define('BASE_URL', 'https://bcsapi.xyz/api');
define('USER_AGENT', 'phpAPI1.0');

// Utils class
class Utils {
    // Check if string starts with
    public static function starts_with($haystack, $needle) {
        $length = strlen($needle);
        return (substr($haystack, 0, $length) === $needle);
    }

    // Make get request
    public static function GET($url, $user_agent, $timeout) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_USERAGENT, $user_agent);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        $results = curl_exec($ch);
        curl_close($ch);
        $js = json_decode($results, true);
        if(isset($js['status'])) if($js['status'] === 'error') throw new Exception($js['error']);
        return $js;
    }

    // Make post request
    public static function POST($url, $params, $user_agent, $timeout) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($params));
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_USERAGENT, $user_agent);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        $results = curl_exec($ch);
        curl_close($ch);
        $js = json_decode($results, true);
        if(isset($js['status'])) if($js['status'] === 'error') throw new Exception($js['error']);
        return $js;
    }

    // Read file
    public static function read_file($file_path) {
        $fp = fopen($file_path, "rb");      // open file
        if (!$fp)
            throw new Exception("cannot read captcha file: " . $file_path);
        $file_size = filesize($file_path);      // get file size

        if ($file_size <= 0)        // check it's length (if OK)
            throw new Exception("cannot read captcha file: " . $file_path);

        $data = fread($fp, $file_size);     // read file
        fclose($fp);                        // close file

        $b64_data = base64_encode($data);   // encode it to base64
        return $b64_data;                   // return it
    }

}

class BestCaptchaSolver {
    private $_access_token;
    private $_timeout;

    function __construct($access_token, $timeout = 120) {
        $this->_access_token = $access_token;
        $this->_timeout = $timeout;
    }

    // Get balance for account
    function account_balance(){
        $url = BASE_URL . "/user/balance?access_token=$this->_access_token";
        $response = Utils::GET($url, USER_AGENT, $this->_timeout);
        return "$" . $response['balance'];
    }

    // Solve captcha
    function submit_image_captcha($captcha_file, $case_sensitive = FALSE) {
        $data = array();
        if(file_exists($captcha_file)) $captcha_file = Utils::read_file($captcha_file);
        $data['access_token'] = $this->_access_token;
        $data['b64image'] = $captcha_file;
        if($case_sensitive) $data['case_sensitive'] = '1';
        $url = BASE_URL . "/captcha/image";
        $response = Utils::POST($url, $data, USER_AGENT, $this->_timeout);
        return $response['id'];
    }

    // Submit recaptcha
    function submit_recaptcha($page_url, $site_key, $proxy = '') {
        $data = array(
            "access_token" => $this->_access_token,
            "page_url" => $page_url,
            "site_key" => $site_key,
        );
        // if proxy was given, add it
        if(!empty($proxy)) {
            $data['proxy'] = $proxy;
            $data['proxy_type'] = 'HTTP';
        }
        $url = BASE_URL . "/captcha/recaptcha";
        $response = Utils::POST($url, $data, USER_AGENT, $this->_timeout);
        return $response['id'];
    }

    // Get recaptcha response using captcha ID
    function retrieve($captcha_id) {
        $url = BASE_URL . "/captcha/$captcha_id?access_token=$this->_access_token";
        $response = Utils::GET($url, USER_AGENT, $this->_timeout);
        if($response['status'] === 'pending') return NULL;      // still pending
        if(isset($response['gresponse'])) return $response['gresponse'];
        else if(isset($response['text'])) return $response['text'];
    }

    // Set captcha bad
    function set_captcha_bad($captcha_id) {
        // set data array
        $data = array(
            "access_token" => $this->_access_token,
        );
        $url = BASE_URL . "/captcha/bad/$captcha_id";
        $resp = Utils::POST($url, $data, USER_AGENT, $this->_timeout);
        return $resp['status'];
    }
}
?>
