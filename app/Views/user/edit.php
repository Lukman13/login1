<?= $this->extend('layout/nav'); ?>

<?= $this->section('content'); ?>

<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-4 text-gray-800"><?= $title; ?></h1>

    <div class="row">
        <div class="col-lg-8">
            <?= form_open_multipart('', ['class' => 'user']); ?>
            <?= csrf_field(); ?>
            <input type="hidden" name="imageLama" value="<?= $user['image']; ?>">
            <input type="hidden" name="id" value="<?= $user['id']; ?>">
            <div class="form-group row">
                <label for="nama" class="col-sm-2 col-form-label">Email</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" id="email" name="email" autofocus value="<?= $user['email']; ?>" readonly>
                </div>
            </div>
            <div class="form-group row">
                <label for="nama" class="col-sm-2 col-form-label">Name</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" id="name" name="name" autofocus value="<?= $user['name']; ?>">
                    <div class="invalid-feedback errorName">
                    </div>
                </div>
            </div>

            <div class="form-group row">
                <label for="foto" class="col-sm-2 col-form-label">Foto</label>
                <div class="col-sm-2">
                    <img src="/img/<?= $user['image']; ?>" class="img-thumbnail img-preview">
                </div>
                <div class="col-sm-8">
                    <div class="custom-file">
                        <input type="file" class="custom-file-input" id="image" name="image" onchange="previewImg()">
                        <div class="invalid-feedback errorImage">
                        </div>
                        <label class="custom-file-label" for="image"><?= $user['image']; ?></label>
                    </div>
                </div>
            </div>
            <div class="form-group row justify-content-end">
                <div class="col-sm-10 mt-4">
                    <button type="submit" class="btn btn-primary btnedit">Change Data</button>
                </div>
            </div>
            <?= form_close(); ?>
        </div>
    </div>


</div>
<!-- /.container-fluid -->

</div>
<!-- End of Main Content -->

<script>
    $(document).ready(function() {
        $('.user').submit(function(e) {
            e.preventDefault();
            let form = $('.user')[0];

            let data = new FormData(this);
            $.ajax({
                type: "POST",
                url: "/user/edit",
                data: data,
                enctype: 'multipart/form-data',
                processData: false,
                contentType: false,
                cache: false,
                dataType: "json",
                beforeSend: function() {
                    $('.btnedit').attr('disable', 'disabled');
                    $('.btnedit').html('<i class="fa fa-spin fa-spinner"></i>');
                },
                complete: function() {
                    $('.btnedit').removeAttr('disable');
                    $('.btnedit').html('Register Account');
                },
                success: function(res) {
                    console.log(res);
                    console.log(res);
                    if (res.error) {
                        if (res.error.name) {
                            $('#name').addClass('is-invalid');
                            $('.errorName').html(res.error.name);
                        } else {
                            $('#name').removeClass('is-invalid');
                            $('.errorNama').html('');
                        }
                        if (res.error.image) {
                            $('#image').addClass('is-invalid');
                            $('.errorImage').html(res.error.image);
                        } else {
                            $('#image').removeClass('is-invalid');
                            $('.errorImage').html('');
                        }
                    }
                    if (res.sukses) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: res.sukses.msg
                        }).then((result) => {
                            if (result.value) {
                                window.location = res.sukses.link;
                            }
                        })
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



<?= $this->endSection(); ?>