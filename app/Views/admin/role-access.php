<?= $this->extend('layout/nav'); ?>

<?= $this->section('content'); ?>

<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-4 text-gray-800"><?= $title; ?></h1>

    <div class="row">
        <div class="cil-lg6">
            <?php

            use \App\Models\FilterModel;

            if (session()->getFlashdata('pesan')) : ?>
                <div class="alert alert-success" role="alert">
                    <?= session()->getFlashdata('pesan'); ?>
                </div>
            <?php endif; ?>

            <h5>Role : <?= $role['role']; ?></h5>

            <table class="table table-hover">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Menu</th>
                        <th scope="col">Access</th>
                    </tr>
                </thead>
                <tbody>
                    <?php

                    use App\Controllers\Admin;

                    $this->joinModel = new Admin();
                    $sub = $this->joinModel->rd($role['id']);

                    //dd($sub);

                    $i = 1; ?>
                    <?php foreach ($mn as $m) : ?>
                        <tr>
                            <th scope="row"><?= $i++; ?></th>
                            <td><?= $m['menu']; ?></td>
                            <td>
                                <div class="form-check">

                                    <?php foreach ($sub as $s) {
                                        //dd($s['menu_id']);
                                        if ($s['menu_id'] == $m['id']) :
                                            $cek = "checked='checked'";
                                            break;
                                    ?>

                                        <?php else :
                                            $cek = ""
                                        ?>
                                            <input class="form-check-input" type="checkbox">
                                        <?php endif; ?>
                                    <?php }; ?>

                                    <input class="form-check-input" type="checkbox" <?= $cek; ?>>
                                </div>
                            </td>

                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

        </div>
    </div>


</div>
<!-- /.container-fluid -->

</div>
<!-- End of Main Content -->


<!-- Button trigger modal -->


<!-- Modal -->


<?= $this->endSection(); ?>