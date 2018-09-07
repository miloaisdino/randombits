<?php
/**
 * Created by PhpStorm.
 * User: maximustan
 * Date: 9/11/16
 * Time: 5:38 PM
 */
class Get_model extends CI_Model {
    /**
     * @param $url
     * @param array $header
     * @param int $return
     * @param string $agent
     * @param int $debug
     * @return mixed
     */
    function curl($url, array $header = [],  $return = 1, $agent = '', $debug = 0) {
        $curl = curl_init();
        curl_setopt ($curl, CURLOPT_URL, $url);
        curl_setopt ($curl, CURLOPT_USERAGENT, $agent);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, $return);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
        if ($debug == 1) {
            curl_setopt($curl, CURLOPT_VERBOSE, true);
            curl_setopt($curl, CURLOPT_STDERR, fopen('php://stderr', 'w'));
        }
        return curl_exec ($curl);

        //return 'results';

    }

    function hawk($url, array $header = [],  $return = 1, $agent = 'SMRTConnect/2.12 Android/7.0', $debug = 0) {
        $key = 'ww-connectv3-android';
        $secret = 'censored';
        $credentials = new Dragooon\Hawk\Credentials\Credentials(
            $secret,        // shared key
            'sha256',  // default: sha256
            $key          // identifier, default: null
        );
        //$crypto = new Dragooon\Hawk\Crypto\Crypto();
        //$timeProvider = new timeprovider();
        $client = Dragooon\Hawk\Client\ClientBuilder::create()
          //  ->setCrypto($crypto)
            //->setTimeProvider($timeProvider)
            //->setNonceProvider($nonceProvider)
            ->build();
        $request = $client->createRequest(
            $credentials, // Instance of Dragooon\Hawk\Credentials\CredentialsInterface
            $url,
            'GET',
            array(
            )
        );

// Once the request has been created,
        /*$headerField = $request->header()->fieldName(); // Field name, for example "Authorization"
        $headerValue = $request->header()->fieldValue(); // Value for the above field*/
        //$hawk = Hawk::generateHeader($key, $secret, 'GET', $url);

//        var_dump($url);


// OVERIDE HERE







        $header[] = $request->header()->fieldName() . ': ' . $request->header()->fieldValue();
        //$header[] = "Authorization: $hawk";
        $curl = curl_init();
        curl_setopt ($curl, CURLOPT_URL, $url);
        curl_setopt ($curl, CURLOPT_USERAGENT, $agent);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, $return);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
        if ($debug == 1) {
            curl_setopt($curl, CURLOPT_VERBOSE, true);
            curl_setopt($curl, CURLOPT_STDERR, fopen('php://stderr', 'w'));
        }

        //var_dump(curl_exec ($curl));
        $result = curl_exec ($curl);
        //curl_close($curl);
       // var_dump($result);

        return $result;
	curl_close($curl);
    }

    function curl_post($url, array $header = [],  $body, $return = 1, $agent = '', $debug = 0) {
        $curl = curl_init();
        curl_setopt ($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $body);
        curl_setopt ($curl, CURLOPT_USERAGENT, $agent);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, $return);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
        if ($debug == 1) {
            curl_setopt($curl, CURLOPT_VERBOSE, true);
            curl_setopt($curl, CURLOPT_STDERR, fopen('/var/www/html/log/curl_post.log', 'w'));
        }
        //var_dump(curl_exec($curl));
        return curl_exec ($curl);

        //return 'results';

    }

    function datagov($dataset, array $attributes){
        $header = ['api-key: Qt0HPfAW1uNTVmhsuqVeAlvAV8trxRTB'];
        $query = '?';
        foreach($attributes as $variable => $value){
            if(strlen($value)>0 && strlen($variable)>=2) {
                $query .= "$variable=$value&";
            }
        }
        $url = 'https://api.data.gov.sg/v1/' . $dataset . $query;
        $json = $this->curl($url, $header);
        $data = json_decode($json, true);
        return $data;
    }

    function smrtapi($dataset, array $attributes, $out = 0){

        //$query = '?format=json&';
        $query = '/?';
        foreach($attributes as $variable => $value){
            if(strlen($value)>0 && strlen($variable)>0) {
                $query .= "$variable=$value&";
            }
        }
        $url = 'https://connectv3.smrt.wwprojects.com/smrt/api/' . $dataset . $query;
        $json = $this->hawk($url);
	//no dev means use hawk $$$
        if($out == 1){
            $data = $json;
        } else {
            $data = json_decode($json, true);
        }
        return $data;
    }

    function gettrainarrival($platform, $json = 0){
        $array = $this->smrtapi('train_arrival_time_by_id', ['station' => $platform], $json);
        return $array;
    }


    function getpsi($date = null, $datetime = null){
        $array = $this->datagov('environment/psi', ['date' => $date, 'date_time' => $datetime]);
        return $array;
    }

    function getpm25($date = null, $datetime = null){
        $array = $this->datagov('environment/pm25', ['date' => $date, 'date_time' => $datetime]);
        return $array;
    }
}
class timeprovider {
    function createTimestamp()
    {
        return time();
    }
}
