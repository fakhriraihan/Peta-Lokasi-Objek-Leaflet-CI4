<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <title>Tabel</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.2.0/css/bootstrap.min.css" />
    <link rel="stylesheet" href="https://cdn.datatables.net/1.12.1/css/dataTables.bootstrap5.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.9.0/css/all.min.css" integrity="sha512-q3eWabyZPc1XTCmF+8/LuE1ozpg5xxn7iO89yfSOd5/oKvyqLngoNGsx8jq92Y8eXJ/IRxQbEC+FGSYxtk2oiw==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <style>
        .container {
            margin-top: 100px;
        }
    </style>
</head>

<body>
    <script src="https://code.jquery.com/jquery-3.6.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.12.1/js/dataTables.bootstrap5.min.js"></script>

    <!-- NAVBAR -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
        <div class="container-fluid">
            <a class="navbar-brand" href="#"><i class="fas fa-map-marker-alt"></i> Peta Lokasi Objek</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">

                    <?php if (auth()->loggedIn()) : ?>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= base_url() ?>"><i class="fas fa-map-marker-alt"></i> Peta</a>
                        </li>
                    <?php endif; ?>
                    <?php if (auth()->loggedIn()) : ?>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= base_url('objek') ?>"><i class="fas fa-plus-circle"></i> Input Data</a>
                        </li>
                    <?php endif; ?>

                    <li class="nav-item">
                        <a class="nav-link <?= auth()->loggedIn() ? 'text-danger' : "" ?>" href="<?= auth()->loggedIn() ? base_url('logout') : base_url('login') ?>">
                            <i class="fas fa-sign-in-alt"></i>
                            <?= auth()->loggedIn() ? 'Logout' : 'Login' ?>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container">
        <div class="card shadow mt-3">
            <div class="card-header text-center">
                <h3 class="card-title"><i class="fas fa-table"></i> Tabel Data Objek</h3>
            </div>
            <div class="card-body">

                <?php if (session()->getFlashdata('message')) : $flashdata = session()->getFlashdata('message'); ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <?= $flashdata ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif ?>

                <div class="table-responsive">
                    <table id="table_objek" class="table table-striped table bordered">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama</th>
                                <th>Deskripsi</th>
                                <th>Lokasi</th>
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
                                    <td><a href=<?= 'https://www.google.com/maps/dir/?api=1&destination=' . $obj['latitude'] . ',' . $obj['longitude'] . '&travelmode=driving' ?> target="_blank" title="Tampilkan Rute"><?= $obj['latitude'] . ',' . $obj['longitude'] ?></a></td>
                                    <td><img src="<?= base_url('img/objek/' . $obj['foto']) ?>" alt="Tidak ada mie" width="100px" height="100px"></td>
                                    <td><?= $obj['created_at']; ?></td>
                                    <td><?= $obj['updated_at']; ?></td>
                                    <td class="">
                                        <div class="d-flex justify-content-center">
                                            <a href="<?= base_url('objek/edit/' . $obj['id']) ?>" class="btn btn-warning btn-sm mx-2"><i class="fas fa-edit"></i></a>
                                            <a href="<?= base_url('objek/hapus/' . $obj['id']) ?>" type="button" class="btn btn-danger btn-sm" onclick="return confirm('Yakin mau hapus <?= $obj['nama'] ?> dek?')"><i class="fas fa-trash-alt"></i></a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
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