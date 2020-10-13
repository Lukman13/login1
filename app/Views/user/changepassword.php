<?= $this->extend('layout/nav'); ?>

<?= $this->section('content'); ?>

<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-4 text-gray-800"><?= $title; ?></h1>
    <?php if (session()->getFlashdata('pesan')) : ?>
        <div class="alert alert-success" role="alert">
            <?= session()->getFlashdata('pesan'); ?>
        </div>
    <?php endif; ?>
    <?php if (session()->getFlashdata('danger')) : ?>
        <div class="alert alert-danger" role="alert">
            <?= session()->getFlashdata('danger'); ?>
        </div>
    <?php endif; ?>
    <div class="row">
        <div class="col-lg-6">
            <?= form_open('/user/changepassword', ['class' => 'user']); ?>
            <?= csrf_field(); ?>
            <div class="form-group">
                <label for="current_password">Current Password</label>
                <input type="password" class="form-control" id="current_password" name="current_password">
                <div class=" invalid-feedback pl-4 errorCPw">
                </div>
            </div>
            <div class="form-group">
                <label for="new_password1">New Password</label>
                <input type="password" class="form-control" id="new_password1" name="new_password1">
                <div class=" invalid-feedback pl-4 errorPw1">
                </div>
            </div>
            <div class="form-group">
                <label for="new_password2">Repeat Password</label>
                <input type="password" class="form-control" id="new_password2" name="new_password2">
                <div class=" invalid-feedback pl-4">
                </div>
            </div>
            <div class="form-group row ">
                <div class="col-sm-10 mt-4">
                    <button type="submit" class="btn btn-primary btnubah">Change Password</button>
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
            $.ajax({
                type: "POST",
                url: $(this).attr('action'),
                data: $(this).serialize(),
                dataType: "json",
                beforeSend: function() {
                    $('.btnubah').attr('disable', 'disabled');
                    $('.btnubah').html('<i class="fa fa-spin fa-spinner"></i>');
                },
                complete: function() {
                    $('.btnubah').removeAttr('disable');
                    $('.btnubah').html('Register Account');
                },
                success: function(res) {
                    console.log(res);
                    if (res.error) {
                        if (res.error.current_password) {
                            $('#current_password').addClass('is-invalid');
                            $('.errorCPw').html(res.error.current_password);
                        } else {
                            $('#current_password').removeClass('is-invalid');
                            $('.errorCPw').html('');
                        }
                        if (res.error.new_password1) {
                            $('#new_password1').addClass('is-invalid');
                            $('.errorPw1').html(res.error.new_password1);
                        } else {
                            $('#new_password1').removeClass('is-invalid');
                            $('.errorEmail').html('');
                        }
                    }
                    if (res.errorlog) {
                        if (res.errorlog.wrongpw) {
                            $('#current_password').addClass('is-invalid');
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal',
                                text: res.errorlog.wrongpw
                            })
                        } else {
                            $('#current_password').removeClass('is-invalid');;
                        }
                        if (res.errorlog.samepw) {
                            $('#new_password1').addClass('is-invalid');
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal',
                                text: res.errorlog.samepw
                            })
                        } else {
                            $('#new_password1').removeClass('is-invalid');
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