
<?php
class Psitable_model extends CI_Model {
    function __construct()
    {
        parent::__construct();
        $this->load->model('Get_model');
        $this->load->model('Psicalc_model');
    }

    function index(){
        $psiarray = $this->Get_model->getpsi();
        $pm25array = $this->Get_model->getpm25();
        $table = [];
        //$debug['items'][0]['update_timestamp'];
        $psiassoc = $psiarray['items'][0]['readings'];
        $pm25assoc = $pm25array['items'][0]['readings'];
        $pm25assoc['pm25_one_hourly']['national'] = round(
            ($pm25assoc['pm25_one_hourly']['north'] +
            $pm25assoc['pm25_one_hourly']['south'] +
            $pm25assoc['pm25_one_hourly']['east'] +
            $pm25assoc['pm25_one_hourly']['west'] +
            $pm25assoc['pm25_one_hourly']['central']) / 5 );

        $assoc = array_merge($psiassoc, $pm25assoc);
        $mergedcolumns = ['1-hour PSI'=>'2'];
        $titlelist =   ['Region', '1-hour PSI',      '24-hour PSI',     'PM2.5 µg/m3']; //012345
        $readinglist = [      '',               '', '',  'psi_twenty_four_hourly', 'pm25_one_hourly']; //012345
        //--------------------------------------------------------------------------------------------------------------
        $rowlist =     ['', 'national', 'north', 'south', 'east', 'west', 'central']; //123456 (row no 0 is reserved for titles)
        $rownames =     ['Region', '<b>Singapore</b>', '<b>North</b>', '<b>South</b>', '<b>East</b>', '<b>West</b>', '<b>Central</b>']; //123456
        $nocolumns = count($readinglist);
        $norows = count($rowlist) ;
        for ($x=1; $x < $norows; $x++) {
            for ($y = 0; $y < $nocolumns; $y++) {
                $table[$x][$y] = '-'; //blank spaces
            }
        }
        $increment = 0;
        foreach($titlelist as $col => $title){ //set titles
            //$col = array_search($title, $titlelist);
            $col = $col + $increment;
            if(array_key_exists($title, $mergedcolumns)){
                $table[0][$col] = $title;
                $increment = $increment + $mergedcolumns[$title] - 1;
                $col = $col + $increment;
                $table[0][$col] = 'm';
                continue;
            }
            $table[0][$col] = $title;
        }
        //foreach($readinglist as $colname){ //ONLY FOR GETTING COL POS!!!
            //if ($collist == $colname) {
                foreach($assoc as $collist => $byarea) {//$byarea is an array with areas as keys and their psi as values
                            $colno = array_search($collist, $readinglist);
                            //foreach ($byarea as $area => $psi) {
                            foreach($rowlist as $rowindex => $rowarea){
                                if($rowindex == 0){continue;}
                                $rowno = $rowindex ;
                                //$rowno = array_search($area, $rowlist) + 1;
                                //$rowno = array_search($area, array_keys($byarea)) + 1;
                                $table[$rowno][0] = $rowarea;
                                $table[$rowno][$colno] = $byarea[$rowarea];
                            }
                }
            //}
        //}
        //ksort($table);
        $y = array_search('pm25_one_hourly', $readinglist);
        $table[0][$y] = 'd';
        for ($x=1; $x < $norows; $x++) {
            $calcarray = $this->Psicalc_model->pm25($table[$x][$y]);
            $table[$x][1] = round($calcarray[0]);
            $table[$x][2] = '<p class="nobp" style="color: '.$calcarray[2].';">'.$calcarray[1].'</p>';
            $table[$x][$y] = 'd';
        }

        foreach ($table as $rowno => $rows){
            foreach ($rows as $colno => $value){
                if ($value == 'd'){
                    unset($table[$rowno][$colno]);
                }
                if ($colno == 0){
                    $table[$rowno][$colno] = $rownames[$rowno];
                }
            }
        }
        ksort($table);
        $time = $pm25array['items'][0]['update_timestamp'];
        $return = ['table' => $table, 'time' => $time];
        return $return;
        /*
        echo '<pre>';
        print_r($debug);
        echo '</pre>';
        */

    }
}



