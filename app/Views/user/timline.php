<?= $this->extend('layout/nav'); ?>

<?= $this->section('content'); ?>

<!-- Begin Page Content -->
<div class="container-fluid col-lg-11">
    <div class="col-sm-1"></div>
    <?php foreach ($timline as $tm) { ?>
        <div class="blog-post col-sm-10 mb-3">
            <div class="blog-post__img">
                <img src="<?= $tm['img']; ?>" alt="">
            </div>
            <div class="blog-post__info">
                <div class="blog-post__date">
                    <span><?= $tm['day']; ?></span>
                    <span><?= $tm['date']; ?></span>
                </div>
                <h1 class="blog-post__title">
                    <?= $tm['title']; ?>
                </h1>
                <p class="blog-post__text">
                    <?= $tm['text']; ?>
                </p>
                <a href="#" class="blog-post__cta  ">Read More</a>
            </div>
        </div>
    <?php }; ?>
</div>
<!-- /.container-fluid -->

</div>
<!-- End of Main Content -->


<?= $this->endSection(); ?>