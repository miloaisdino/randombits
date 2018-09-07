<?php
/**
 * Created by PhpStorm.
 * User: maximustan
 * Date: 18/11/16
 * Time: 5:59 PM
 */
class Psicalc_model extends CI_Model
{
    //public $calc;

    function __construct()
    {
        parent::__construct();
    }

    function severity($band){

        if ($band == 1) {
            $severity = 'Good';
        } else {
            $severity = '-';
        }
        if ($band == 2) {
            $severity = 'Moderate';
        }
        if ($band == 3) {
            $severity = 'Unhealthy';
        }
        if ($band == 4) {
            $severity = 'Very Unhealthy';
        }
        if ($band == 5) {
            $severity = 'Hazardous';
        }
        if ($band == 6) {
            $severity = 'Very Hazardous';
        }
        return $severity;
    }

    function colour($band){ //#0288D1
        if ($band == 1) {
            $colour = '#43A047'; //good
        } else {
            $colour = '';
        }
        if ($band == 2) {
            $colour = '#0288D1'; //Moderate
        }
        if ($band == 3) {
            $colour = '#F9A825'; //unhealthy
        }
        if ($band == 4) {
            $colour = '#EF6C00'; //very unhealthy
        }
        if ($band == 5) {
            $colour = '#DD2C00'; //Hazardous
        }
        if ($band == 6) {
            $colour = '#d60000'; //Very Hazardous
        }
        return $colour;
    }

    function pm25band($raw){

        if ($raw > 0) {
            $calc = 1;
        } else {
            $calc = 0;
        }
        if ($raw > 12) {
            $calc = 2;
        }
        if ($raw > 55) {
            $calc = 3;
        }
        if ($raw > 150) {
            $calc = 4;
        }
        if ($raw > 250) {
            $calc = 5;
        }
        if ($raw > 350) {
            $calc = 6;
        }
        return $calc;
    }

    function pm25($raw)
    {
        $calc = $this->pm25band($raw);

        switch ($calc) {
            default:
                $psi = 0;
                break;
            case 1;
                $psi = 4.167 * ($raw - 0) + 0;
                break;
            case 2;
                $psi = 1.163 * ($raw - 12) + 50;
                break;
            case 3;
                $psi = 1.053 * ($raw - 55) + 100;
                break;
            case 4;
                $psi = 1.000 * ($raw - 150) + 200;
                break;
            case 5;
                $psi = 1.000 * ($raw - 250) + 300;
                break;
            case 6;
                $psi = 0.667 * ($raw - 350) + 400;
                break;

        }
        $severity = $this->severity($calc);
        $colour = $this->colour($calc);
        return [$psi, $severity, $colour];
    }
}
