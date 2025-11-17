<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if ( ! function_exists('backend_asset'))
{
    // Fungsi untuk memanggil aset backend (DattaAble)
    function backend_asset($uri = '')
    {
        $CI =& get_instance();
        return $CI->config->base_url('assets/backend/' . $uri);
    }
}