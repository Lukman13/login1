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
                                    <h1 class="h4 text-gray-900">Change your password for</h1>
                                    <h5 class="mb-4"><?= session()->get('reset_email'); ?></h5>

                                    <?php if (session()->getFlashdata('pesan')) : ?>
                                        <div class="alert alert-success" role="alert">
                                            <?= session()->getFlashdata('pesan'); ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <form class="user" method="post" action="/home/change">
                                    <div class="form-group">
                                        <input type="password" class="form-control form-control-user <?= ($validation->hasError('password1')) ? 'is-invalid' : ''; ?>" id="password1" name="password1" placeholder="Enter New Password...">
                                        <div class=" invalid-feedback pl-4">
                                            <?= $validation->getError('password1'); ?>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <input type="password" class="form-control form-control-user <?= ($validation->hasError('password2')) ? 'is-invalid' : ''; ?>" id="password2" name="password2" placeholder="Repeat New Password...">
                                        <div class=" invalid-feedback pl-4">
                                            <?= $validation->getError('password2'); ?>
                                        </div>
                                    </div>
                                    <button type="submit" class="btn btn-primary btn-user btn-block">
                                        change Password
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>

</div>
<?= $this->endSection(); ?>