<?php
/**
 * Created by PhpStorm.
 * User: maximustan
 * Date: 20/11/16
 * Time: 4:05 PM
 */
class Smrt extends CI_Controller {

    function __construct()
    {
        parent::__construct();
        if ($_SERVER['HTTP_HOST'] != "mrt.rhombuscake.me") {
            header('Location: http://mrt.rhombuscake.me' . $_SERVER['REQUEST_URI']);
        }
    }

    function index($platform = null, $reload = 'n'){
        if($platform == null){
            $this->load->view('smrtdashboard');
            return;
        }
        $platform = str_replace('_', ' ', $platform);
        if ($reload == 'n') {
            $this->load->view('smrt', ['station' => $platform]);
            return;
        }
        $this->load->model('Mrtarrival_model');
        $this->load->model('Utils');
        //$model = $this->Mrtarrival_model->array('EWL');
        //$platform = $this->input->get('platform', TRUE);
        $lrtlist = ['choa chu kang lrt', 'south view', 'keat hong', 'teck whye', 'phoenix', 'bukit panjang lrt', 'petir',
            'pending', 'bangkit', 'fajar', 'segar', 'jelapang', 'senja', 'ten mile junction'];
        //$hash = $this->Mrtarrival_model->smrthash();
        if(in_array(strtolower($platform), $lrtlist)){
          //  $array = $this->Mrtarrival_model->lrt($platform, $hash);
        } else {
            $array = $this->Mrtarrival_model->arrival($platform);
        }

/*        $exceptionlist = ['harbourfront', 'ten mile junction', 'joo koon', 'changi airport'];
        if(in_array(strtolower($platform), $exceptionlist)){
            $passobj = $array;
            $array = [0=>0];
        }
*/  
//var_dump($array);
      foreach($array['results'] as $key => $object){
            if(isset($passobj)) $object = $passobj;
            //var_dump($array);
            $object = (array) $object;
            unset($object['mrt']);
            unset($object['status']);
            //unset($object['Gate']);
//            unset($object['Last1']);
  //          unset($object['Last2']);
            unset($object['subseq_train_destination']);
            $object['next_train_arr'] = str_replace(' ', '&nbsp;', $object['next_train_arr']);
            $object['subseq_train_arr'] = str_replace(' ', '&nbsp;', $object['subseq_train_arr']);
            $object['next_train_destination'] = str_replace(' ', '&nbsp;', $object['next_train_destination']);
            $object = $this->Utils->changekey($object, 'code', 'Line');
            $object = $this->Utils->changekey($object, 'next_train_destination', 'Ends at');
            $object = $this->Utils->changekey($object, 'platform_ID', 'Platform');
            $object = $this->Utils->changekey($object, 'next_train_arr', 'Arrival');
            $object = $this->Utils->changekey($object, 'subseq_train_arr', 'Next');
            preg_match('/[A-Z]{2}/', $object['Line'], $match);
	    $object['Platform'] = substr($object['Platform'], strpos($object['Platform'], "_") + 1);
            switch ($match[0]) {
                default:
                    $class = 'unknown-mrt';
                    break;
                case 'NS';
                    $class = 'ns-mrt';
                    break;
                case 'CC';
                    $class = 'cc-mrt';
                    break;
                case 'EW';
                    $class = 'ew-mrt';
                    break;
                case 'DT';
                    $class = 'dt-mrt';
                    break;
                case 'NE';
                    $class = 'ne-mrt';
                    break;
            }
            $object['Line'] = '<span class="line-mrt '.$class.'">'.$object['Line'].'</span>';
            //$object['class'] = $class;
            $rebuild[$key] = $object;
        }
        $data = [
            'title' => 'MRT Arrival Times',
            'model' => $rebuild,
            'station' => $platform,
            //'time' => $model['time']
        ];
        if ($reload == 'reload'){
            $this->load->view('smrt_container', $data);
        }
    }

    function apiv3($platform = ''){
        header('Content-Type: application/json');
	$this->load->model('Mrtarrival_model');
        $platform = str_replace('_', '%20', $platform);
        //$platform = $this->input->get('platform', TRUE);
        $lrtlist = ['choa chu kang lrt', 'south view', 'keat hong', 'teck whye', 'phoenix', 'bukit panjang lrt', 'petir',
            'pending', 'bangkit', 'fajar', 'segar', 'jelapang', 'senja', 'ten mile junction'];
        //$hash = $this->Mrtarrival_model->smrthash();
        if(in_array(strtolower($platform), $lrtlist)){
            //$model = (array) $this->Mrtarrival_model->lrt($platform, $hash);
        } else {
            $model = (array) $this->Mrtarrival_model->arrival($platform);
        }

        /*for($key = 0; $key < count($model); $key++) {
            //unset($model[$key]->{$attr});
            unset($model[$key]->Text1);
            unset($model[$key]->Text2);
        }*/
        //header('Content-Type: application/json');
        echo json_encode($model);
    }

    function test($platform = ''){
        $this->load->model('Mrtarrival_model');
        $platform = str_replace('_', ' ', $platform);
        //$platform = $this->input->get('platform', TRUE);
        $lrtlist = ['choa chu kang lrt', 'south view', 'keat hong', 'teck whye', 'phoenix', 'bukit panjang lrt', 'petir',
            'pending', 'bangkit', 'fajar', 'segar', 'jelapang', 'senja', 'ten mile junction'];
        $hash = $this->Mrtarrival_model->smrthash();
        if(in_array(strtolower($platform), $lrtlist)){
            $model = (array) $this->Mrtarrival_model->lrt($platform, $hash);
        } else {
            $model = (array) $this->Mrtarrival_model->arrival($platform, $hash);
        }

        for($key = 0; $key < count($model); $key++) {
            //unset($model[$key]->{$attr});
            unset($model[$key]->Text1);
            unset($model[$key]->Text2);
        }
        echo '<pre>';
        print_r($model);
        echo '</pre>';
    }
}
