<?= $this->extend('layout/template'); ?>

<?= $this->section('content'); ?>

<div class="container">

    <!-- Outer Row -->
    <div class="row justify-content-center">

        <div class="col-lg-7">

            <div class="card o-hidden border-0 shadow-lg my-5">
                <div class="card-body p-0">
                    <!-- Nested Row within Card Body -->
                    <div class="row">
                        <div class="col-lg">
                            <div class="p-5">
                                <div class="text-center">
                                    <h1 class="h4 text-gray-900 mb-4">Forgot your password</h1>
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
                                </div>
                                <?= form_open('/home/forgot', ['class' => 'user']); ?>
                                <?= csrf_field(); ?>

                                <div class="form-group">
                                    <input type="text" class="form-control form-control-user" id="email" name="email" placeholder="Enter Email Address...">
                                    <div class=" invalid-feedback pl-4 errorEmail">
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary btn-user btn-block btnreset">
                                    Reset Password
                                </button>
                                <?= form_close(); ?>
                                <hr>
                                <div class="text-center">
                                    <a class="small" href="/home">Back to login</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>

</div>

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
                    $('.btnreset').attr('disable', 'disabled');
                    $('.btnreset').html('<i class="fa fa-spin fa-spinner"></i>');
                },
                complete: function() {
                    $('.btnreset').removeAttr('disable');
                    $('.btnreset').html('Register Account');
                },
                success: function(res) {
                    console.log(res);
                    if (res.error) {
                        if (res.error.name) {
                            $('#email').addClass('is-invalid');
                            $('.errorEmail').html(res.error.email);
                        } else {
                            $('#email').removeClass('is-invalid');
                            $('.errorEmail').html('');
                        }
                    }
                    if (re.errorlog) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: res.erroerlog.msg
                        })
                    }
                    if (res.sukses) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: res.sukses.msg
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