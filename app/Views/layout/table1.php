<?= form_open('/user/eventAksi', ['class' => 'formhapusbanyak']); ?>
<?= csrf_field(); ?>

<!-- DataTables -->
<link href="/vendor/plugins/datatables/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css" />
<link href="/vendor/plugins/datatables/buttons.bootstrap4.min.css" rel="stylesheet" type="text/css" />
<!-- Required datatable js -->
<script src="/vendor/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="/vendor/plugins/datatables/dataTables.bootstrap4.min.js"></script>


<script src="https://cdnjs.cloudflare.com/ajax/libs/webcamjs/1.0.25/webcam.min.js"></script>
<input type="hidden" name="mode" id="mode" value="hapusbanyak">
<button type="submit" class="btn btn-danger my-2">
    <i class="fa fa-trash-o"></i> Hapus Yg Ceklis
</button>

<table class="table table-striped" id="datatab">
    <thead>
        <tr>
            <th>
                <input type="checkbox" id="ceklis">
            </th>
            <th>no</th>
            <th>img</th>
            <th>Nama</th>
            <th>Job</th>
            <th>Address</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>

    </tbody>
</table>
<?= form_close(); ?>

<script>
    function listdata() {
        var table = $('#datatab').DataTable({
            "processing": true,
            "serverSide": true,
            "order": [],
            "ajax": {
                "url": "/user/event",
                "type": "POST"
            },
            scrollY: '250px',
            // dom: 'Bfrtip',
            // buttons: [
            //     'csv', 'excel', 'print', 'copy'
            // ],
            "columnDefs": [{
                "targets": 0,
                "orderable": false,
            }]
        })
    }
    $(document).ready(function() {
        listdata()
        $('#ceklis').click(function(e) {

            if ($(this).is(':checked')) {
                $('.centang').prop('checked', true)
            } else {
                $('.centang').prop('checked', false)
            }
        })

        $('.formhapusbanyak').submit(function(e) {
            e.preventDefault();
            let jmldata = $('.centang:checked');
            console.log(jmldata);
            if (jmldata.length === 0) {
                Swal.fire({
                    icon: 'error',
                    title: 'Perhatian',
                    text: 'Ceklis data yang ingin dihapus'
                })
            } else {
                Swal.fire({
                    title: 'Hapus Semua data',
                    text: `Yakin data yang dihapus sebanyak ${jmldata.length}`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, Hapus',
                    cancelButtonText: 'Tidak'
                }).then((result) => {
                    if (result.value) {
                        $.ajax({
                            type: 'post',
                            url: $(this).attr('action'),
                            data: $(this).serialize(),
                            dataType: 'json',
                            success: function(res) {
                                if (res.sukses) {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Berhasil',
                                        text: res.sukses
                                    })
                                    dataajax()
                                }
                            },
                            error: function(xhr, ajaxOptions, thrownError) {
                                alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError)
                            }
                        })
                    }
                })
            }

            return false
        })
    })


    function edit(id) {
        $.ajax({
            type: "post",
            url: '/user/eventAksi',
            data: {
                id: id,
                mode: 'edit'
            },
            dataType: "json",
            success: function(res) {
                if (res.sukses) {
                    $('.viewmodal').html(res.sukses).show();
                    $('#modaledit').modal('show');
                }
            },
            error: function(xhr, ajaxOptions, thrownError) {
                alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError)
            }
        })
    }

    function hapus(id) {
        Swal.fire({
            title: 'Hapus',
            text: "Yakin menghapus data ini?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya',
            cancelButtonText: 'Tidak'
        }).then((result) => {
            if (result.value) {
                $.ajax({
                    type: "post",
                    url: '/user/eventAksi',
                    data: {
                        id: id,
                        mode: 'delete'
                    },
                    dataType: "json",
                    success: function(res) {
                        if (res.sukses) {
                            Swal.fire({
                                icon: 'Succes',
                                title: 'Berhasil',
                                text: res.sukses,

                            })

                            dataajax();
                        }
                    },
                    error: function(xhr, ajaxOptions, thrownError) {
                        alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError)
                    }
                })
            }
        })
    }

    function uplod(id) {
        $.ajax({
            type: "post",
            url: '/user/eventAksi',
            data: {
                id: id,
                mode: 'formuplod'
            },
            dataType: "json",
            success: function(res) {
                if (res.sukses) {
                    $('.viewmodal').html(res.sukses).show();
                    $('#modaluplod').modal('show');
                }
            },
            error: function(xhr, ajaxOptions, thrownError) {
                alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError)
            }
        })
    }
</script>