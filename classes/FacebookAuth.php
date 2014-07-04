<?php

class FacebookAuth
{
    private $_authUrl = "https://graph.facebook.com/oauth/access_token";
    private $_appId = null;
    private $_appSecret = null;

    public function __construct($appId, $appSecret)
    {
        $this->_appId = $appId;
        $this->_appSecret = $appSecret;
    }

    public function getAccessToken()
    {
        $params = array(
            'client_id'     => $this->_appId,
            'client_secret' => $this->_appSecret,
            'grant_type'    => 'client_credentials'
        );

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $this->_authUrl.'?'.http_build_query($params));
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $output = curl_exec($ch);
        
        curl_close($ch);

        return $this->extract($output);
    }

    private function extract($string)
    {
        if ($string && stripos($string, 'access_token') !== false) {
            return str_replace('access_token=', '', $string);
        }

        return false;
    }

}