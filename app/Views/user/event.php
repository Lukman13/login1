<?= $this->extend('layout/nav'); ?>

<?= $this->section('content'); ?>

<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-4 text-gray-800"><?= $title; ?></h1>
    <div class="row">
        <div class="col-md">
            <button class="btn btn-primary mr-1" id="tambahd">Add New Menu</button>
            <button class="btn btn-info" id="tambahbnyk">Multiple Insert</button>
        </div>
        <div class="col-md ">
            <div class="row justify-content-end mr-4">

                <a href="/user/pdf" class="btn btn-info mr-2">Download Pdf</a>
                <a href="/user/excel" class="btn btn-danger">Download Excel</a>
            </div>
        </div>
    </div>

    <div id="load"></div>
    <div id="table"></div>

    <div class="viewmodal" style="display: none;"></div>




    <!-- /.container-fluid -->

</div>
<!-- End of Main Content -->
<script type="text/javascript">
    function dataajax() {
        $.ajax({
            type: "post",
            url: "/user/eventAksi",
            beforeSend: function() {
                $('#load').html('Load Data...');
            },
            data: {
                mode: "select"
            },
            dataType: "json",
            success: function(res) {
                $('#load').html('');
                $('#table').html(res.data);
            },
            error: function(xhr, ajaxOptions, thrownError) {
                alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError)
            }
        });
    }
    $(document).ready(function() {
        dataajax();
    })
    $('#tambahd').click(function(e) {
        e.preventDefault();
        $.ajax({
            url: "/user/eventAksi",
            type: 'post',
            data: {
                mode: 'tambah'
            },
            dataType: "json",
            success: function(res) {
                console.log(res);

                $('.viewmodal').html(res.data).show();
                $('#tambahdata1').modal('show');
                //$('#modaltambah1').modal('show');
            },
            error: function(xhr, ajaxOptions, thrownError) {
                alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError)
            }
        });
    })

    $('#tambahbnyk').click(function(e) {
        e.preventDefault();
        $.ajax({
            url: "/user/eventAksi",
            type: 'post',
            data: {
                mode: 'tambahbanyak'
            },
            dataType: "json",
            beforeSend: function() {
                $('#table').html('<i class="fa fa-spin fa-spinner"></i>')
            },
            success: function(res) {
                console.log(res);
                $('#table').html(res.data).show();;
            },
            error: function(xhr, ajaxOptions, thrownError) {
                alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError)
            }
        });
    })

    $('#downpdff').click(function(e) {
        e.preventDefault();
        $.ajax({
            url: "/user/pdf",
            type: 'post',
            data: {
                mode: 'pdf'
            },
            dataType: "json",
            beforeSend: function() {
                $('#table').html('<i class="fa fa-spin fa-spinner"></i>')
            },
            success: function(res) {
                console.log(res);
                $('#table').html(res.data).show();;
            },
            error: function(xhr, ajaxOptions, thrownError) {
                alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError)
            }
        });
    })


    $('#hide').on('click', function() {
        $("#table").html("");
    })


    $("#input1").on("click", function() {
        $.ajax({
            url: "/user/input",
            beforeSend: function(f) {
                $('#load').html('Input Data...');
            },
            success: function(res) {
                $('#load').html('');
                $('#table').html(res);
            }
        });

    });
</script>


<?= $this->endSection(); ?>