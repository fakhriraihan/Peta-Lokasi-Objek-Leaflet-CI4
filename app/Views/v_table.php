<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <title>Tabel</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.2.0/css/bootstrap.min.css" />
    <link rel="stylesheet" href="https://cdn.datatables.net/1.12.1/css/dataTables.bootstrap5.min.css" />

</head>

<body>
    <script src="https://code.jquery.com/jquery-3.6.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.12.1/js/dataTables.bootstrap5.min.js"></script>

    <div class="container">
        <div class="card shadow mt-3 text-bg-dark">
            <div class="card-header">
                <h3 class="card-title">Tabel Data Objek</h3>
            </div>
            <div class="card-body">
                <table id="table_objek" class="table table-dark table-hover">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th>Deskripsi</th>
                            <th>Longitude</th>
                            <th>Latitude</th>
                            <th>Foto</th>
                            <th>Dibuat</th>
                            <th>Diperbarui</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php
                        $i = 1;
                        foreach ($objek as $obj) : ?>
                            <tr>
                                <td><?= $i++; ?></td>
                                <td><?= $obj['nama']; ?></td>
                                <td><?= $obj['deskripsi']; ?></td>
                                <td><?= $obj['longitude']; ?></td>
                                <td><?= $obj['latitude']; ?></td>
                                <td><?= $obj['foto']; ?></td>
                                <td><?= $obj['created_at']; ?></td>
                                <td><?= $obj['updated_at']; ?></td>
                                <td class="">
                                    <a href="<?= base_url('objek/edit/' . $obj['id']) ?>" class="btn btn-warning btn-sm">Edit</a>
                                    <a href="<?= base_url('objek/hapus/' . $obj['id']) ?>" type="button" class="btn btn-danger btn-sm" onclick="return confirm('Yakin mau hapus <?= $obj['nama'] ?> dek?')">Hapus</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $('#table_objek').DataTable();
        });
    </script>
</body>

</html>