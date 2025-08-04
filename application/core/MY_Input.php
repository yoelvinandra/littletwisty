<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class MY_Input extends CI_Input
{
    function post($index = NULL, $default_value = NULL, $xss_clean = FALSE)
    {
        $value = parent::post($index, $xss_clean);

        if(!$value)
            $value = $default_value;

        return $value;
    }

    function get($index = NULL, $default_value = NULL, $xss_clean = FALSE)
    {
        $value = parent::get($index, $xss_clean);

        if(!$value)
            $value = $default_value;

        return $value;
    }
}