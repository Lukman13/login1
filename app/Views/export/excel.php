<?php
header("Content-type: aplication/vnd-ms-excel");
header("Content-Disposition: attachment; filename=Data Mahasiswa.xls");


?>
<html>

<head>
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
        }

        td,
        th {
            border: 1px solid #000000;
        }
    </style>
</head>

<body>
    <div style="font-size: 64px;"><?= $title; ?></div>
    <table cellpadding="6">
        <thead>
            <tr>
                <th>no</th>
                <th>Nama</th>
                <th>Job</th>
                <th>Address</th>
            </tr>
        </thead>
        <tbody>
            <?php $i = 1;
            foreach ($table as $tab) : ?>
                <tr>
                    <td><?= $i++; ?></td>
                    <td><?= $tab['nama']; ?></td>
                    <td><?= $tab['job']; ?></td>
                    <td><?= $tab['address']; ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>

</html>