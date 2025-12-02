<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$CI =& get_instance(); 
?>
                    </div>
                    <footer class="content-footer footer bg-footer-theme">
                        <div class="container-xxl d-flex flex-wrap justify-content-between py-2 flex-md-row flex-column">
                            <div class="mb-2 mb-md-0">
                                © <script>document.write(new Date().getFullYear());</script>, SMOP Haramainku | Powered by Dhayu Fandy Stiawan
                            </div>
                            </div>
                    </footer>
                    <div class="content-backdrop fade"></div>
                </div>
                </div>
            </div>

        <div class="layout-overlay layout-menu-toggle"></div>
    </div>
    <script src="<?php echo base_url('assets/sneat/vendor/libs/jquery/jquery.js'); ?>"></script>
    <script src="<?php echo base_url('assets/sneat/vendor/libs/popper/popper.js'); ?>"></script>
    <script src="<?php echo base_url('assets/sneat/vendor/js/bootstrap.js'); ?>"></script>
    
    <script src="<?php echo base_url('assets/sneat/vendor/libs/perfect-scrollbar/perfect-scrollbar.js'); ?>"></script>
    <script src="<?php echo base_url('assets/sneat/vendor/js/menu.js'); ?>"></script>

    <script src="<?php echo base_url('assets/sneat/js/main.js'); ?>"></script>

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