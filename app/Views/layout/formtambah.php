<?= form_open('/user/eventAksi', ['class' => 'formbanyak']); ?>
<?= csrf_field(); ?>
<button type="button" class="btn btn-primary my-3 btnkembali">Kembali</button>
<button type="submit" class="btn btn-success my-3 btnsimp">Simpan</button>
<table class="table table-sm table-bordered">
    <thead>
        <tr>
            <th>Gambar</th>
            <th>Nama</th>
            <th>Job</th>
            <th>Address</th>
            <th>#</th>
        </tr>
    </thead>
    <tbody class="formbd" id="formbd">
        <tr>
            <td>
                <input type="text" name="gambar[]" class="form-control" id="nama">
            </td>
            <td>
                <input type="text" name="nama[]" class="form-control">
            </td>
            <td>
                <input type="text" name="job[]" class="form-control">
            </td>
            <td>
                <input type="text" name="address[]" class="form-control">
            </td>
            <td>
                <input type="hidden" name="mode" id="mode" value="simpanbanyak">
                <button type="button" class="btn btn-primary btnadd">
                    <i class="fa fa-plus"></i>
                </button>
            </td>
        </tr>
    </tbody>
</table>
<?= form_close(); ?>

<script>
    $(document).ready(function(e) {

        $('.formbanyak').submit(function(e) {
            e.preventDefault();
            var uname = $('#nama').val();
            if (uname == 0) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'tidak ada data'

                })
            } else {
                $.ajax({
                    type: "POST",
                    url: $(this).attr('action'),
                    data: $(this).serialize(),
                    dataType: "json",
                    beforeSend: function() {
                        $('.btnsimp').attr('disable', 'disabled');
                        $('.btnsimp').html('<i class="fa fa-spin fa-spinner"></i>');
                    },
                    complete: function() {
                        $('.btnsimp').removeAttr('disable');
                        $('.btnsimp').html('Simpan');
                    },
                    success: function(res) {
                        console.log(res);
                        if (res.error) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: res.error

                            })
                        }
                        if (res.sukses) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil',
                                html: `${res.sukses}`
                            }).then((result) => {
                                if (result.value) {
                                    window.location.href = ("/user/event");
                                }
                            })
                        }
                    },
                    error: function(xhr, ajaxOptions, thrownError) {
                        alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError)
                    }

                })
            }
            return false
        })


        $('.btnadd').click(function(e) {
            e.preventDefault();

            $('.formbd').append(`
            <tr>
            <td>
                <input type="text" name="gambar[]" class="form-control">
            </td>
            <td>
                <input type="text" name="nama[]" class="form-control">
            </td>
            <td>
                <input type="text" name="job[]" class="form-control">
            </td>
            <td>
                <input type="text" name="address[]" class="form-control">
            </td>
            <td>
                <button type="button" class="btn btn-danger btnhps">
                    <i class="fa fa-trash"></i>
                </button>
            </td>
        </tr>
            `);
        });
        $('.btnkembali').click(function(e) {
            e.preventDefault();
            dataajax();
        })
    });

    $(document).on('click', '.btnhps', function(e) {
        e.preventDefault();

        $(this).parents('tr').remove();

    })
</script>