<div class="modal fade " id="modaluplod" tabindex="-1" role="dialog" aria-labelledby="tambahdata1Label" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="tambahdata1Label">Upload Foto</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <?= form_open_multipart('', ['class' => 'formuplod']); ?>
            <?= csrf_field(); ?>
            <div class="modal-body">
                <input type="hidden" name="id" value="<?= $id; ?>">
                <div class="form-group row">
                    <div class="col-sm-5" id="results">
                        <img src="/img/<?= $foto; ?>" class="img-thumbnail img-preview">
                    </div>
                    <input type="hidden" name="imagecam" class="image-tag">
                    <div class="col-sm-5">
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" id="foto" name="foto" onchange="previewFoto()">
                            <div class="invalid-feedback errorFoto">
                            </div>
                            <label class="custom-file-label" for="image"><?= $foto; ?></label>
                        </div>
                        <div class="my_camera my-2">

                        </div>
                        <p>
                            <button type="button" class="btn btn-sm btn-info" onclick="take_picture()">Ambil Gambar</button>
                        </p>
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary btnuplod">Upload</button>
            </div>

            <?= form_close(); ?>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('.btnuplod').click(function(e) {
            e.preventDefault();
            let form = $('.formuplod')[0];

            let data = new FormData(form);
            console.log(data);
            $.ajax({
                type: "post",
                url: "/user/ajaxupload",
                data: data,
                enctype: 'multipart/form-data',
                processData: false,
                contentType: false,
                cache: false,
                dataType: "json",
                beforeSend: function(e) {
                    $('.btnuplod').prop('disable', 'disabled');
                    $('.btnuplod').html('<i class="fa fa-spin fa-spinner"></i>')
                },
                complete: function() {
                    $('.btnuplod').removeAttr('disable');
                    $('.btnuplod').html('Upload');
                },
                success: function(res) {
                    if (res.error) {
                        if (res.error.foto) {
                            $('#foto').addClass('is-invalid');
                            $('.errorFoto').html(res.error.foto);
                        }

                    } else {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: res.sukses
                        })
                        $('#modaluplod').modal('hide');
                        dataajax();
                    }
                },
                error: function(xhr, ajaxOptions, thrownError) {
                    alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError)
                }
            })
        })
    })
</script>

<script>
    Webcam.set({
        width: 320,
        height: 240,
        image_format: 'jpeg',
        jpeg_quality: 100
    });
    Webcam.attach('.my_camera');

    function take_picture() {
        Webcam.snap(function(data_uri) {

            $(".image-tag").val(data_uri);

            document.getElementById('results').innerHTML = '<img src="' + data_uri + '"/>';
        });
    }
</script>