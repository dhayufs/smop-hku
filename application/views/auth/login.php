<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html lang="id" data-pc-preset="preset-1" data-pc-sidebar-caption="true" data-pc-direction="ltr" dir="ltr" data-pc-theme="light">
  <head>
    <title>Login | SMOP Haramainku</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />

    <link rel="icon" href="<?php echo base_url('assets/backend/images/favicon.svg'); ?>" type="image/x-icon" />
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;500;600&display=swap" rel="stylesheet" />
    
    <link rel="stylesheet" href="<?php echo base_url('assets/backend/fonts/phosphor/duotone/style.css'); ?>" />
    <link rel="stylesheet" href="<?php echo base_url('assets/backend/fonts/tabler-icons.min.css'); ?>" />
    <link rel="stylesheet" href="<?php echo base_url('assets/backend/fonts/feather.css'); ?>" />
    <link rel="stylesheet" href="<?php echo base_url('assets/backend/fonts/fontawesome.css'); ?>" />
    <link rel="stylesheet" href="<?php echo base_url('assets/backend/fonts/material.css'); ?>" />
    <link rel="stylesheet" href="<?php echo base_url('assets/backend/css/style.css'); ?>" id="main-style-link" />
  </head>
  <body class="bg-gray-100 dark:bg-themedark-bodybg">
    <div class="loader-bg fixed inset-0 bg-white dark:bg-themedark-cardbg z-[1034]">
        <div class="loader-track h-[5px] w-full inline-block absolute overflow-hidden top-0">
            <div class="loader-fill w-[300px] h-[5px] bg-primary-500 absolute top-0 left-0 animate-[hitZak_0.6s_ease-in-out_infinite_alternate]"></div>
        </div>
    </div>
    <div class="auth-main relative">
      <div class="auth-wrapper v1 flex items-center justify-center w-full min-h-screen">
        <div class="auth-form flex items-center justify-center grow flex-col relative p-6">
          
          <div class="w-full max-w-[350px] relative mx-auto">
            
            <div class="auth-bg">
              <span class="absolute top-[-100px] right-[-100px] w-[300px] h-[300px] block rounded-full bg-theme-bg-1 animate-[floating_7s_infinite]"></span>
              <span class="absolute left-[-100px] bottom-[-100px] w-[300px] h-[300px] block rounded-full bg-theme-bg-2 animate-[floating_9s_infinite]"></span>
            </div>

            <div class="card sm:my-12 w-full shadow-none relative">
              <div class="card-body !p-10">
                
                <div class="text-center mb-8">
                  <a href="<?php echo site_url('auth'); ?>">
                    <img src="<?php echo base_url('assets/backend/images/logo-dark.svg'); ?>" alt="logo" class="mx-auto auth-logo"/>
                  </a>
                </div>
                
                <h4 class="text-center font-medium mb-4">Login ke SMOP Haramainku</h4>
                <center><p class="mb-4 text-gray-500 dark:text-themedark-bodycolor">Sistem Monitoring Operasional Perjalanan</p></center>

                <?php if ($this->session->flashdata('error')): ?>
                    <div class="alert alert-danger p-2 mb-3 text-red-700 bg-red-100 rounded-md text-sm text-center">
                        <?php echo $this->session->flashdata('error'); ?>
                    </div>
                <?php endif; ?>
                
                <?php echo form_open('auth/login_process'); ?>
                    
                    <div class="mb-3">
                      <label class="block text-sm font-medium mb-1" for="username">Username</label>
                      <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring focus:ring-primary-500 dark:bg-themedark-input dark:border-themedark-border" id="username" name="username" placeholder="Masukkan Username" value="<?php echo set_value('username'); ?>" required />
                      <?php echo form_error('username', '<p class="text-danger-500 text-xs mt-1">', '</p>'); ?>
                    </div>
                    
                    <div class="mb-4">
                      <label class="block text-sm font-medium mb-1" for="password">Password</label>
                      <input type="password" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring focus:ring-primary-500 dark:bg-themedark-input dark:border-themedark-border" id="password" name="password" placeholder="Masukkan Password" required />
                      <?php echo form_error('password', '<p class="text-danger-500 text-xs mt-1">', '</p>'); ?>
                    </div>
                    
                    <div class="flex mt-1 justify-between items-center flex-wrap">
                        <div class="form-check">
                        <input class="form-check-input input-primary" type="checkbox" id="customCheckc1" name="remember" />
                        <label class="form-check-label text-muted" for="customCheckc1">Ingat saya</label>
                      </div>
                    </div>
                    
                    <div class="mt-4 text-center">
                      <button type="submit" class="btn btn-primary w-full py-2 rounded-md font-semibold bg-primary-500 text-white hover:bg-primary-600 transition duration-150">Login</button>
                    </div>
                    <br>
                <center><p class="inline-block max-sm:mr-3 sm:ml-2">Powered by Dhayu Fandy Stiawan</p></center>
                <?php echo form_close(); ?>

              </div>
            </div>
          </div>
          
        </div>
      </div>
    </div>
    
    <script src="<?php echo base_url('assets/backend/js/plugins/simplebar.min.js'); ?>"></script>
    <script src="<?php echo base_url('assets/backend/js/plugins/popper.min.js'); ?>"></script>
    <script src="<?php echo base_url('assets/backend/js/icon/custom-icon.js'); ?>"></script>
    <script src="<?php echo base_url('assets/backend/js/plugins/feather.min.js'); ?>"></script>
    <script src="<?php echo base_url('assets/backend/js/component.js'); ?>"></script>
    <script src="<?php echo base_url('assets/backend/js/theme.js'); ?>"></script>
    <script src="<?php echo base_url('assets/backend/js/script.js'); ?>"></script>

    <script>
        if (typeof feather !== 'undefined') {
            feather.replace();
        }
        // Pastikan fungsi JS dari DattaAble dimuat (jika ada)
        if (typeof layout_change === 'function') layout_change('false');
        if (typeof layout_theme_sidebar_change === 'function') layout_theme_sidebar_change('dark');
        if (typeof change_box_container === 'function') change_box_container('false');
    </script>
  </body>
  </html>