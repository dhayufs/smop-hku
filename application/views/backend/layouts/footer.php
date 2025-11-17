<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$CI =& get_instance(); 
?>
            </div>
            </div>
    </div>
    <footer class="pc-footer">
      <div class="footer-wrapper container-fluid">
        <div class="grid grid-cols-12 py-3 px-6">
          <div class="col-span-12 sm:col-span-6 my-1">
            <p class="text-theme-bodycolor dark:text-themedark-bodycolor">
                SMOP Haramainku | Powered by Dhayu Fandy Stiawan - &copy; <?php echo date('Y'); ?>
            </p>
          </div>
          <div class="col-span-12 sm:col-span-6 my-1 justify-self-end">
          </div>
        </div>
      </div>
    </footer>
    
    <script src="<?php echo base_url('assets/backend/js/plugins/jquery.min.js'); ?>"></script>
    <script src="<?php echo base_url('assets/backend/js/plugins/bootstrap.min.js'); ?>"></script> 
    
    <script src="<?php echo base_url('assets/backend/js/plugins/simplebar.min.js'); ?>"></script> 
    
    <script src="<?php echo base_url('assets/backend/js/plugins/popper.min.js'); ?>"></script>
    <script src="<?php echo base_url('assets/backend/js/icon/custom-icon.js'); ?>"></script>
    <script src="<?php echo base_url('assets/backend/js/plugins/feather.min.js'); ?>"></script>
    <script src="<?php echo base_url('assets/backend/js/component.js'); ?>"></script>
    <script src="<?php echo base_url('assets/backend/js/theme.js'); ?>"></script>
    <script src="<?php echo base_url('assets/backend/js/script.js'); ?>"></script>

    <script>
        // Deklarasi dummy Class SimpleBar & feather untuk mencegah crash
        if (typeof SimpleBar === 'undefined') { function SimpleBar(element, options) { } }
        if (typeof feather === 'undefined') { var feather = { replace: function() { } }; }
        
        // Inisialisasi ikon Feather
        if (typeof feather !== 'undefined') {
          feather.replace();
        }

        // FALLBACK MANUAL: Jika SimpleBar gagal, paksa overflow: auto;
        document.addEventListener('DOMContentLoaded', function() {
            var sidebarContent = document.querySelector('.pc-sidebar .navbar-content');
            if (sidebarContent) {
                // Tambahkan pengecekan ini: Jika SimpleBar tidak diinisialisasi oleh script theme, paksa scroll
                if (typeof SimpleBar === 'function' && !sidebarContent.hasAttribute('data-simplebar')) {
                    // new SimpleBar(sidebarContent); // Uncomment jika SimpleBar sudah dimuat
                } else {
                    // Jika SimpleBar tidak terdefinisi (gagal load), pakai CSS native
                    sidebarContent.style.overflowY = 'auto';
                }
            }
        });
    </script>
    
    <?php if ($CI->session->flashdata('success')): ?>
        <script>
            alert('Sukses: <?php echo $CI->session->flashdata('success'); ?>');
        </script>
    <?php endif; ?>
    <?php if ($CI->session->flashdata('error')): ?>
        <script>
            alert('Error: <?php echo $CI->session->flashdata('error'); ?>');
        </script>
    <?php endif; ?>

</body>
</html>