<?php
class MY_Loader extends CI_Loader {

    function __construct() {
        parent::__construct();
        $CI =& get_instance();
        $CI->load = $this;
    }
}
?>