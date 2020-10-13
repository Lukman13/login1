<div class="modal fade " id="modaledit" tabindex="-1" role="dialog" aria-labelledby="tambahdata1Label" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="tambahdata1Label">Add New Data</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <?= form_open('/user/eventAksi', ['class' => 'formdata']); ?>
            <?= csrf_field(); ?>
            <div class="modal-body">
                <input type="hidden" id="id" name="id" value="<?= $id; ?>">
                <input type="hidden" id="mode" name="mode" value="update">
                <div class="form-group">
                    <input type="text" class="form-control" id="nama" name="nama" value="<?= $nama; ?>">
                    <div class="invalid-feedback errorNama">
                    </div>
                </div>
                <div class="form-group">
                    <input type="text" class="form-control" id="gambar" name="gambar" value="<?= $gambar; ?>">
                    <div class="invalid-feedback errorGambar">
                    </div>
                </div>
                <div class="form-group">
                    <input type="text" class="form-control" id="job" name="job" value="<?= $job; ?>">
                    <div class="invalid-feedback errorJob">
                    </div>
                </div>
                <div class="form-group">
                    <input type="text" class="form-control" id="address" name="address" value="<?= $address; ?>">
                    <div class="invalid-feedback errorAddress">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary" id="input1">Update</button>
            </div>

            <?= form_close(); ?>
        </div>
    </div>
</div>


<script>
    $(document).ready(function() {
        $('.formdata').submit(function(e) {
            e.preventDefault();
            $.ajax({
                type: "POST",
                url: $(this).attr('action'),
                data: $(this).serialize(),
                dataType: "json",
                beforeSend: function() {
                    $('#input1').attr('disable', 'disabled');
                    $('#input1').html('<i class="fa fa-spin fa-spinner"></i>');
                },
                complete: function() {
                    $('#input1').removeAttr('disable');
                    $('#input1').html('Update');
                },
                success: function(res) {
                    console.log(res);
                    if (res.error) {
                        if (res.error.nama) {
                            $('#nama').addClass('is-invalid');
                            $('.errorNama').html(res.error.nama);
                        } else {
                            $('#nama').removeClass('is-invalid');
                            $('.errorNama').html('res.error.nama');
                        }
                        if (res.error.gambar) {
                            $('#gambar').addClass('is-invalid');
                            $('.errorGambar').html(res.error.gambar);
                        } else {
                            $('#gambar').removeClass('is-invalid');
                            $('.errorGambar').html('res.error.gambar');
                        }
                        if (res.error.job) {
                            $('#job').addClass('is-invalid');
                            $('.errorJob').html(res.error.job);
                        } else {
                            $('#job').removeClass('is-invalid');
                            $('.errorJob').html('res.error.job');
                        }
                        if (res.error.address) {
                            $('#address').addClass('is-invalid');
                            $('.errorAddress').html(res.error.address);
                        } else {
                            $('#address').removeClass('is-invalid');
                            $('.errorAddress').html('res.error.address');
                        }
                    } else {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: res.sukses
                        })

                        $('#modaledit').modal('hide');

                        dataajax();
                    }
                },
                error: function(xhr, ajaxOptions, thrownError) {
                    alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError)
                }

            })
            return false
        })
    })
</script>