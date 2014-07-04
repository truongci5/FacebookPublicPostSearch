<?php

class FacebookSearch
{
    private $_searchUrl = "https://graph.facebook.com/search";
    private $_accessToken = null;

    public function __construct($accessToken)
    {
        $this->_accessToken = $accessToken;
    }

    public function search($query=null, $since=0, $limit=25)
    {
        $posts = array();

        if (!$since) {
            $since =  time() - 7*86400;
        }

        $query = explode(",", $query);
        if (!is_array($query)) $query = array($query);

        foreach ($query as $q) {
            $params = array(
                'q'            => $q,
                'access_token' => $this->_accessToken,
                'type'         => 'post',
                'since'        => $since,
                'limit'        => $limit
            );

            $ch = curl_init();

            curl_setopt($ch, CURLOPT_URL, $this->_searchUrl.'?'.http_build_query($params));
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            $output = curl_exec($ch);

            curl_close($ch);

            $posts = array_merge($posts,$this->extract($output));
        }
        

        return $posts;
    }


    private function extract($string)
    {
        $output = array();
        $data = json_decode($string,true);

        if (isset($data['data'])) {
            $data = $data['data'];
            foreach ($data as $item) {
                $output[] = array(
                    'id' => $item['id'],
                    'user_id' => $item['from']['id'],
                    'user_name' => $item['from']['name'],
                    'type' => isset($item['type']) ? $item['type'] : '',
                    'created_at' => strtotime($item['created_time']),
                    'link' => isset($item['link']) ? $item['link'] : '',
                    'text' => isset($item['name']) ? $item['name'] : (isset($item['message']) ? $item['message'] : (isset($item['story']) ? $item['story'] : ''))
                    );
            }
        }

        return $output;
    }
}