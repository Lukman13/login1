<?= $this->extend('layout/template'); ?>

<?= $this->section('content'); ?>

<div class="container">

    <div class="card o-hidden border-0 shadow-lg my-5 col-lg-7 mx-auto">
        <div class="card-body p-0">
            <!-- Nested Row within Card Body -->
            <div class="row">
                <div class="col-lg">
                    <div class="p-5">
                        <div class="text-center">
                            <h1 class="h4 text-gray-900 mb-4">Create an Account!</h1>
                        </div>
                        <?= form_open('/home/val', ['class' => 'user']); ?>
                        <?= csrf_field(); ?>
                        <div class="form-group">
                            <input type="text" class="form-control form-control-user" id="name" name="name" placeholder="Full Name..." value="<?= old('name'); ?>">
                            <div class=" invalid-feedback pl-4 errorNama">
                            </div>
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-control form-control-user" id="email" name="email" placeholder="Email Address" value="<?= old('email'); ?>">
                            <div class=" invalid-feedback pl-4 errorEmail">
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-6 mb-3 mb-sm-0">
                                <input type="password" class="form-control form-control-user" id="password1" name="password1" placeholder="Password">
                                <div class=" invalid-feedback pl-4 errorPassword">
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <input type="password" class="form-control form-control-user" id="password2" name="password2" placeholder="Repeat Password">
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary btn-user btn-block btnregis">
                            Register Account
                        </button>

                        <?= form_close(); ?>
                        <hr>
                        <div class="text-center">
                            <a class="small" href="/home/forgotpassword">Forgot Password?</a>
                        </div>
                        <div class="text-center">
                            <a class="small" href="/home">Already have an account? Login!</a>
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
                    $('.btnregis').attr('disable', 'disabled');
                    $('.btnregis').html('<i class="fa fa-spin fa-spinner"></i>');
                },
                complete: function() {
                    $('.btnregis').removeAttr('disable');
                    $('.btnregis').html('Register Account');
                },
                success: function(res) {
                    console.log(res);
                    if (res.error) {
                        if (res.error.name) {
                            $('#name').addClass('is-invalid');
                            $('.errorNama').html(res.error.name);
                        } else {
                            $('#name').removeClass('is-invalid');
                            $('.errorNama').html('');
                        }
                        if (res.error.email) {
                            $('#email').addClass('is-invalid');
                            $('.errorEmail').html(res.error.email);
                        } else {
                            $('#email').removeClass('is-invalid');
                            $('.errorEmail').html('res.error.email');
                        }
                        if (res.error.password1) {
                            $('#password1').addClass('is-invalid');
                            $('.errorPassword').html(res.error.password1);
                        } else {
                            $('#password1').removeClass('is-invalid');
                            $('.errorPassword').html('res.error.password1');
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