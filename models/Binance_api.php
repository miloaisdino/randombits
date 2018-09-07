<?php
/**
 * Created by PhpStorm.
 * User: maximustan
 * Date: 18/8/17
 * Time: 11:25 PM
 */
class Binance_api extends CI_Model
{
    const DOMAIN = "https://www.binance.com";
    const APIKEY = 'deleted';
    const SECRET = 'deleted';
    function __construct()
    {
        parent::__construct();
        //$this->load->model('Get_model');
        //self::DOMAIN = "http://127.0.0.1/";
    }

    function curl($url, array $header = []) {
        $curl = curl_init();
        curl_setopt ($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

        curl_setopt($curl, CURLOPT_VERBOSE, true);
        curl_setopt($curl, CURLOPT_STDERR, fopen('php://stderr', 'w'));

        //curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
        return curl_exec ($curl);
    }

    function geturi($method, array $params = []){
        $params = array_filter($params, function($v, $_k){
            if($v == 'ignore'){
                return 0;
            } else {
                return 1;
            }
        }, ARRAY_FILTER_USE_BOTH);
        $url = self::DOMAIN . $method . '?' . urldecode(http_build_query($params));
        return $this->curl($url);
    }

    function geturisigned($method, array $params = []){
        $params = array_filter($params, function($v, $_k){
            if($v == 'ignore'){
                return 0;
            } else {
                return 1;
            }
        }, ARRAY_FILTER_USE_BOTH);
        $params['recvWindow'] = 10000000;
        $params['timestamp'] = time()*1000;
        $params['signature'] = $this->sha256sum($params); //must be called LAST
        $url = self::DOMAIN . $method . '?' . urldecode(http_build_query($params));
        return $this->curl($url, [
            "X-MBX-APIKEY: " . self::APIKEY
        ]);
    }

    function arrayasargs($func, $array){
        return call_user_func_array([$this, $func], $array);
    }

    function sha256sum(array $query = []){
        $querystring = urldecode(http_build_query($query));
        return hash('sha256', self::SECRET . '|' . $querystring);
    }






    function klineshistorical($symbol, $interval, $startTime = 'ignore', $endTime = 'ignore'){
        return $this->geturi('/api/v1/klines', [
            'symbol' => $symbol,
            'interval' => $interval,
            'startTime' => $startTime,
            'endTime' => $endTime
        ]);
    }

    function tradehistory($symbol){
        return $this->geturisigned('/api/v1/myTrades', [
            'symbol' => $symbol
        ]);
    }

    function account(){
        return $this->geturisigned('/api/v1/account', [
        ]);
    }

    function dayticker($symbol){
        return $this->geturi('/api/v1/ticker/24hr', [
            'symbol' => $symbol
        ]);
    }

    function depth($symbol){
        return $this->geturi('/api/v1/depth', [
            'symbol' => $symbol
        ]);
    }

    function aggregatetrades($symbol){
        return $this->geturi('/api/v1/aggTrades', [
            'symbol' => $symbol
        ]);
    }

    function orderstatus($symbol){
        return $this->geturisigned('/api/v1/openOrders', [
            'symbol' => $symbol
        ]);
    }

    function orderhistory($symbol){
        return $this->geturisigned('/api/v1/allOrders', [
            'symbol' => $symbol
        ]);
    }

    function ping(){
        return $this->geturi('/api/v1/ping');
    }

    function time(){
        return $this->geturi('/api/v1/time');
    }

    function timediff(){
        $time = time() * 1000;
        $servertime = json_decode($this->geturi('/api/v1/time'), true)['serverTime'];
        return $servertime . ' - ' . $time . ' = ' . ($servertime - $time);
    }

    function perf(){
        $time = time();
        echo 'Before curl: ' . $time . '<br>';
        $servertime = json_decode($this->geturi('/api/v1/time'), true)['serverTime'];
        $time2 = time();
        echo 'After curl: ' . $time2 . '<br>';
        echo 'Total time: ' . $time2 . ' - ' . $time . ' = ' . ($time2 - $time) . '<br>';
        echo 'Delay in request to server: ' . $servertime . ' - ' . $time*1000 . ' = ' . ($servertime - $time*1000) . '<br>';
    }
}
