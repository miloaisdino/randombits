
<?php
class Mrtarrival_model extends CI_Model {
    function __construct(){
        parent::__construct();
        $this->load->model('Get_model');
    }

    function array($line, $platform = 'all'){
        $platforms = $this->$line();
        $platformdata = [];
        foreach($platforms as $station => $platform) {
            foreach($platform as $letter) {
                    if($letter == $platform[0]){ continue; }
                    $platformname = $platform[0] . ' ' . $letter;
                    $internalname = $station . '_' . $letter;
                    $platformdata[$platformname] = $this->Get_model->gettrainarrival($internalname, 0);
                    $platformdata[$platformname]['name'] = $platformname;

            }
        }
        return $platformdata;
    }

    function json($platform){
        $return = $this->Get_model->gettrainarrival($platform, 1);
        return $return;
    }

    function arrival($station){
    /*$this->load->model('soap_client');
    $body = "<soap:Envelope xmlns:xsi='http://www.w3.org/2001/XMLSchema-instance' xmlns:xsd='http://www.w3.org/2001/XMLSchema' xmlns:soap='http://schemas.xmlsoap.org/soap/envelope/'>
        <soap:Body><getTrainArrivalTimingSecured xmlns='http://userProfile/'><input><string>DeviceId</string>
        <string>UserId</string><string>Password</string><string>$station</string><string>MRT</string></input>
        <appId>transitlink#2012_09_01</appId>
        <hashKey>$hash</hashKey>
        </getTrainArrivalTimingSecured></soap:Body></soap:Envelope>";
    $reply = $this->soap_client->request('http://arrtimes.smrt.wwprojects.com/SMRTWebServices/UserWebService.asmx',
        'http://userProfile/getTrainArrivalTimingSecured', $body);
    $reply = str_replace('diffgr:', '', $reply);
    $reply = str_replace('soap:', '', $reply);
    $reply = str_replace('xs:', '', $reply);
    //$reply = str_replace('@attributes', 'attributes', $reply);
    $xml = new SimpleXMLElement($reply);
    if(!isset($xml->Body->getTrainArrivalTimingSecuredResponse->getTrainArrivalTimingSecuredResult->diffgram->NewDataSet))
        die($station.': An error occurred. Retrying...<script>location.reload();</script>');
    $array = (array) $xml->Body->getTrainArrivalTimingSecuredResponse->getTrainArrivalTimingSecuredResult->diffgram->NewDataSet;
    $array = $array['SMRT_TrainArrival'];
    $GLOBALS['arrival'] = $array;
    return $array;*/

    //$this->load->model('Get_model');
    $platform = $station;
        $reply = $this->Get_model->gettrainarrival($platform);
        return $reply;

}

    function lrt($station, $hash){
        $station = str_replace(" LRT","", $station);
        $this->load->model('soap_client');
        $body = "<soap:Envelope xmlns:xsi='http://www.w3.org/2001/XMLSchema-instance' xmlns:xsd='http://www.w3.org/2001/XMLSchema' xmlns:soap='http://schemas.xmlsoap.org/soap/envelope/'>
        <soap:Body><getTrainArrivalTimingSecured xmlns='http://userProfile/'><input><string>DeviceId</string>
        <string>UserId</string><string>Password</string><string>$station</string><string>LRT</string></input>
        <appId>transitlink#2012_09_01</appId>
        <hashKey>$hash</hashKey>
        </getTrainArrivalTimingSecured></soap:Body></soap:Envelope>";
        $reply = $this->soap_client->request('http://arrtimes.smrt.wwprojects.com/SMRTWebServices/UserWebService.asmx',
            'http://userProfile/getTrainArrivalTimingSecured', $body);
        $reply = str_replace('diffgr:', '', $reply);
        $reply = str_replace('soap:', '', $reply);
        $reply = str_replace('xs:', '', $reply);
        //$reply = str_replace('@attributes', 'attributes', $reply);
        $xml = new SimpleXMLElement($reply);
        if(!isset($xml->Body->getTrainArrivalTimingSecuredResponse->getTrainArrivalTimingSecuredResult->diffgram->NewDataSet))
            die($station.': An error occurred. Retrying...<script>location.reload();</script>');
        $array = (array) $xml->Body->getTrainArrivalTimingSecuredResponse->getTrainArrivalTimingSecuredResult->diffgram->NewDataSet;
        $array = $array['SMRT_TrainArrival'];
        $GLOBALS['lrt'] = $array;
        return $array;
    }

    function smrthash(){
        /*//if((time() - $GLOBALS['tsmrthash']) > 5000) return $GLOBALS['smrthash'];
        $this->load->model('soap_client');
        $body = "<?xml version='1.0' encoding='utf-8'?>
        <soap12:Envelope xmlns:xsi='http://www.w3.org/2001/XMLSchema-instance' 
        xmlns:xsd='http://www.w3.org/2001/XMLSchema' xmlns:soap12='http://www.w3.org/2003/05/soap-envelope'>
        <soap12:Body><calculateHash xmlns='http://tempuri.org/'><appKey>2359#transitlink_20120831</appKey>
        </calculateHash></soap12:Body></soap12:Envelope>";
        $reply = $this->soap_client->request('https://www.transitlink.com.sg/smrt_hashing/DLLWrapper.asmx?op=calculateHash',
            '', $body);
        $reply = str_replace('soap:', '', $reply);
        $xml = new SimpleXMLElement($reply);
        $smrthash = (string) $xml->Body->calculateHashResponse->calculateHashResult;
        $GLOBALS['smrthash'] = $smrthash;
        $GLOBALS['tsmrthash'] = time();
        return $smrthash;*/
        return 0;
    }

}



