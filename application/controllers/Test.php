<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Test extends CI_Controller {

    public function index()
    {
        echo "<h1>Jangkrikk! Halaman Test Berhasil Dimuat!</h1>";
        echo "<p>Artinya CodeIgniter dan Routing sudah OK.</p>";
        echo "<p>Lanjutkan ke halaman login: <a href='" . site_url('auth') . "'>/auth</a></p>";
    }
}