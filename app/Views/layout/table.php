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

<table width="100%" class="table table-hover table-bordered table-responsive" cellspacing="0" width="100%" id="datatab">
    <thead>
        <tr>
            <th>
                <input type="checkbox" id="ceklis">
            </th>
            <th style="vertical-align: middle" width="5%">no</th>
            <th style="vertical-align: middle" width="20%">img</th>
            <th style="vertical-align: middle" width="20%">Nama</th>
            <th style="vertical-align: middle" width="20%">Job</th>
            <th style="vertical-align: middle" width="20%">Address</th>
            <th style="vertical-align: middle" width="15%">Action</th>
        </tr>
    </thead>
    <tbody>

    </tbody>
</table>
<?= form_close(); ?>

<script>
    function listdata() {
        oTable = $('#datatab').dataTable({
            processing: true,
            serverSide: true,
            scrollX: false,

            //'searching': true,

            dom: '<"top"flr>t<"bottom"ip>',
            "lengthMenu": [
                [10, 25, 50, -1],
                [10, 25, 50, "All"]
            ],
            columnDefs: [{
                    "className": "dt-tengah",
                    "targets": [1]
                },
                {
                    targets: "_all",
                    createdCell: function(td, cellData, rowData, row, col) {
                        $(td).css('vertical-align', 'middle');
                    }
                },
                {
                    "targets": [0, 2], //first column / numbering column
                    "orderable": false
                }
            ],
            //autoWidth = TRUE,
            'searching': true,
            pagingType: 'numbers',
            language: {
                "search": "Pencarian : ",
                "info": "<b>Menampilkan _START_ sampai _END_ dari _TOTAL_ Data</b>",
            },
            ajax: {
                'type': 'POST',
                'url': '/user/tabled',
                // 'data': {
                //     prodi: prodi_kode,
                // },
                'dataSrc': function(json) {
                    dataFull = json.data;
                    return json.data;
                },
                complete: function() {
                    $('[data-toggle="tooltip"]').tooltip();
                },
            },
            drawCallback: function() {
                $('div.dataTables_length select').css({
                    'height': '35px',
                    // 'margin-top': '-5px'
                });
            },
            columns: [{
                    data: 'id',
                    render: function(id) {
                        var btncek = `<input type="checkbox" name="id[]" class="form=control centang" value="` + id + `">`
                        return btncek
                    }
                },
                {
                    data: 'id',
                    render: function(data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    }
                },
                {
                    data: 'img'
                },
                {
                    data: 'nama'
                },
                {
                    data: 'job',
                },
                {
                    data: 'address',
                },
                {
                    data: 'id',
                    searchable: false,
                    orderable: false,
                    render: function(id) {
                        var edit = ` <button type="button" class="btn btn-info btn-sm" onclick="edit('` + id + `')"><i class="fa fa-tags"></i></button>`;
                        var hapus = ` <button type="button" class="btn btn-danger btn-sm" onclick="hapus('` + id + `')"><i class="fa fa-trash"></i></button>`;
                        var uplod = ` <button type="button" class="btn btn-warning btn-sm" onclick="uplod('` + id + `')"><i class="fa fa-image"></i></button>`;

                        var btnEdit = `<a class="btn btn-xs btn-default" id="btn_edit" data-toggle="tooltip" data-nim="` + id + `" data-backdrop="static" title="Edit"><span style="color : #1aabbb" class="fa fa-edit"></span></a>`;
                        var btnHapus = `<a class="btn btn-xs btn-default" id="btn_hapus" data-toggle="tooltip" data-nim="` + id + `"  data-backdrop="static" title="Hapus"><span style="color : #e33244" class="i i-trashcan"></span></a>`;

                        return edit + hapus + uplod;
                    }
                },
            ],
        });
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