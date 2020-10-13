<?= $this->extend('layout/nav'); ?>

<?= $this->section('content'); ?>

<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->

    <section>
        <div class="card-wrapper">
            <div class="card">
                <img class="card-img" src="/img/bg1.jpg" alt="card background">
                <img class="profile-img" src="/img/<?= $user['image']; ?>" alt="profikle image">
                <h1><?= $user['name']; ?></h1>
                <p class="job-title">Develover</p>
                <p class="about">
                    Lorem ipsum dolor sit amet consectetur adipisicing elit. Quasi ipsum earum officia mollitia est qui? Alias dolore assumenda voluptatibus aut?
                </p>
                <a href="" class="btn">Contact</a>
                <ul class="social-media">
                    <li><a href=""><i class="fab fa-facebook-square"></i></a></li>
                    <li><a href=""><i class="fab fa-twitter-square"></i></a></li>
                    <li><a href=""><i class="fab fa-instagram"></i></a></li>
                    <li><a href=""><i class="fab fa-google-plus-square"></i></a></li>
                </ul>
            </div>
        </div>
    </section>

</div>
<!-- /.container-fluid -->

</div>
<!-- End of Main Content -->


<?= $this->endSection(); ?>