<?php
/**
 * Created by PhpStorm.
 * User: maximustan
 * Date: 5/12/16
 * Time: 3:40 PM
 */
class Utils extends CI_Model
{
    function __construct() {
        parent::__construct();
    }

    public function changekey( $array, $old_key, $new_key) {

        if( ! array_key_exists( $old_key, $array ) ) {
            return $array;
        }

        $keys = array_keys( $array );
        $keys[ array_search( $old_key, $keys ) ] = $new_key;

        return array_combine( $keys, $array );
    }

}