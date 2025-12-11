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

<script src="<?php echo base_url('assets/sneat/vendor/libs/jquery/jquery.js'); ?>"></script>
<script src="<?php echo base_url('assets/sneat/vendor/libs/popper/popper.js'); ?>"></script>
<script src="<?php echo base_url('assets/sneat/vendor/js/bootstrap.js'); ?>"></script>
<script src="<?php echo base_url('assets/sneat/vendor/libs/perfect-scrollbar/perfect-scrollbar.js'); ?>"></script>
<script src="<?php echo base_url('assets/sneat/vendor/js/menu.js'); ?>"></script>

<script src="<?php echo base_url('assets/sneat/js/main.js'); ?>"></script>

<script>
    // Logika pengisian data modal (LAMA - dipertahankan jika masih ada modal lain)
    $('#updateModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var id = button.data('id');
        var desc = button.data('desc');
        var status = button.data('status');
        var foto_url = button.data('foto');
        var old_foto_path = button.data('old-foto-path');
        
        var modal = $(this);
        modal.find('#modal_grup_item_id').val(id);
        modal.find('#modal_old_foto_path').val(old_foto_path);
        modal.find('#task_description').text(desc);
        modal.find('#modal_status').val(status); 
        
        if (foto_url) {
            modal.find('#foto_preview_img').attr('src', foto_url);
            modal.find('#current_foto_preview').show();
        } else {
            modal.find('#current_foto_preview').hide();
        }
    });
    
    // LOGIKA BARU UNTUK MODAL BUKTI FOTO
    $('#buktiModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget); // Tombol yang mengaktifkan modal
        var fotoUrl = button.data('foto-url'); // Mengambil URL dari data-foto-url
        
        var modal = $(this);
        modal.find('#modalFotoBukti').attr('src', fotoUrl); // Memuat gambar ke tag <img> di dalam modal
    });
</script>
</body>
</html>