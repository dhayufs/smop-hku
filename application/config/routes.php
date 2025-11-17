<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
*/
$route['default_controller'] = 'auth'; 
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

/*
| -------------------------------------------------------------------------
| CUSTOM ROUTES SMOP
| -------------------------------------------------------------------------
*/

// Route untuk Controller Tim Lapangan
$route['mytasks'] = 'MyTasks'; // Map /mytasks ke Controller MyTasks
$route['mytasks/grup_detail/(:num)'] = 'MyTasks/grup_detail/$1'; // ROUTE SPESIFIK untuk mencegah 404 di URL panjang
$route['mytasks/update_task_form/(:num)'] = 'MyTasks/update_task_form/$1'; // Route spesifik untuk form update
$route['mytasks/(:any)'] = 'MyTasks/$1'; // Ini sebagai fallback umum

// Tambahkan juga rute Admin agar lebih aman
$route['admin'] = 'Admin';
$route['admin/(:any)'] = 'Admin/$1';