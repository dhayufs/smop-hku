</div>
    
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
    
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script> 
    
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