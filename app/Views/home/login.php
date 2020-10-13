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
                                    <h1 class="h4 text-gray-900 mb-4">Login Page</h1>
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
                                <?= form_open('/home/log', ['class' => 'user']); ?>
                                <?= csrf_field(); ?>
                                <div class="form-group">
                                    <input type="text" class="form-control form-control-user email" id="email" name="email" placeholder="Enter Email Address...">
                                    <div class=" invalid-feedback pl-4 errorEmail">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <input type="password" class="form-control form-control-user" id="password" name="password" placeholder="Password">
                                    <div class=" invalid-feedback pl-4 errorPassword">
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary btn-user btn-block btnlogin">
                                    Login
                                </button>

                                <?= form_close(); ?>
                                <hr>
                                <div class="text-center">
                                    <a class="small" href="/home/forgotpassword">Forgot Password?</a>
                                </div>
                                <div class="text-center">
                                    <a class="small" href="/home/registration">Create an Account!</a>
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
            e.preventDefault;
            $.ajax({
                type: "post",
                url: $(this).attr('action'),
                data: $(this).serialize(),
                dataType: "json",
                beforeSend: function() {
                    $('.btnlogin').prop('disable', 'disabled');
                    $('.btnlogin').html('<i class="fa fa-spin fa-spinner"></i>')
                },
                complete: function() {
                    $('.btnlogin').removeAttr('disable');
                    $('.btnlogin').html('Login');
                },
                success: function(res) {
                    if (res.error) {
                        if (res.error.email) {
                            $('#email').addClass('is-invalid');
                            $('.errorEmail').html(res.error.email);
                        } else {
                            $('#email').removeClass('is-invalid');
                            $('.errorEmail').html('res.error.email');
                        }
                        if (res.error.password) {
                            $('#password').addClass('is-invalid');
                            $('.errorPassword').html(res.error.password);
                        } else {
                            $('#password').removeClass('is-invalid');
                            $('.errorPassword').html('res.error.password');
                        }
                    }
                    if (res.errorlog) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: res.errorlog.msg

                        })
                        if (res.errorlog.email) {
                            $('#email').addClass('is-invalid');
                            //$('.errorEmail').html(res.errorlog.email);
                        } else {
                            $('#email').removeClass('is-invalid');
                            //$('.errorEmail').html('res.error.email');
                        }
                        if (res.errorlog.pw) {
                            $('#password').addClass('is-invalid');
                            // $('.errorPassword').html(res.errorlog.pw);
                        } else {
                            $('#password').removeClass('is-invalid');
                            //$('.errorPassword').html('');
                        }
                        if (res.errorlog.aktivasi) {
                            $('#email').addClass('is-invalid');
                            //$('.errorEmail').html(res.errorlog.aktivasi);
                        } else {
                            $('#email').removeClass('is-invalid');
                            // $('.errorEmail').html('');
                        }
                    }
                    if (res.sukses) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Login',
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
            return false;
        })
    })
</script>

<?= $this->endSection(); ?>