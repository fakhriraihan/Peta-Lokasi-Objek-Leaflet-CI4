<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Peta Lokasi Objek</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-iYQeCzEYFbKjA/T2uDLTpkwGzCiq6soy8tYaI1GyVh/UjpbCx/TYkiZhlZB6+fzT" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.8.0/leaflet.min.css" integrity="sha512-oIQ0EBio8LJupRpgmDsIsvm0Fsr6c3XNHLB7at5xb+Cf6eQuCX9xuX8XXGRIcokNgdqL1ms7nqbQ6ryXMGxXpg==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <style>
        #map {
            height: 400px;
            width: 100%;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="card">
            <div class="card-header">
                <h2>EDIT DATA</h2>
            </div>
            <div class="card-body">
                <form action="<?= base_url('objek/simpaneditdata/' . $objek['id']) ?>" method="POST" enctype="multipart/form-data">
                    <?= csrf_field(); ?>
                    <div class="mb-3">
                        <label for="input_nama" class="form-label">Nama</label>
                        <input type="text" class="form-control text-primary" id="input_nama" name="input_nama" placeholder="Isikan nama objek" value="<?= $objek['nama'] ?>">
                    </div>
                    <div class="mb-3">
                        <label for="input_deskripsi" class="form-label">Deskripsi</label>
                        <textarea class="form-control text-primary" id="input_deskripsi" name="input_deskripsi" rows="3"><?= $objek['deskripsi'] ?></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="input_longitude" class="form-label">Longitude</label>
                        <input type="text" class="form-control text-primary" id="input_longitude" name="input_longitude" placeholder="Isikan longitude" value="<?= $objek['longitude'] ?>">
                    </div>
                    <div class="mb-3">
                        <label for="input_latitude" class="form-label">Latitude</label>
                        <input type="text" class="form-control text-primary" id="input_latitude" name="input_latitude" placeholder="Isikan latitude" value="<?= $objek['latitude'] ?>">
                    </div>

                    <div id="map"></div>

                    <div class="my-3 row">
                        <div class="col-3">
                            <img src="<?= base_url('img/objek/' . $objek['foto']) ?>" alt="Tidak ada mie" width="250px" height="250px">
                        </div>
                        <div class="col">
                            <label for="input_foto" class="form-label">Foto</label>
                            <input type="file" class="form-control" id="input_foto" name="input_foto">

                            <input type="text" name="input_foto_lama" value="<?= $objek['foto'] ?>">

                        </div>
                    </div>

                    <hr>
                    <div class="row">
                        <div class="col mb-3 d-flex justify-content-start">
                            <a href="<?= base_url('objek/table') ?>" class="btn btn-secondary">BATAL</a>
                        </div>
                        <div class="col mb-3 d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary ms-auto">Simpan</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-u1OknCvxWvY5kfmNBILK2hRnQC3Pr17a+RTT6rIHI7NnikvbZlHgTPOOmMi466C8" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.8.0/leaflet-src.min.js" integrity="sha512-ef1e48BfD+c0pYPlQS9eYZnlRe+jXWbyYc1ORZ7MeIoej97R/9VhTQ1RpnPvy1cEfwO6A/eHirAtK0F4oEOIGw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <script>
        var center = [<?= $objek['latitude'] ?>, <?= $objek['longitude'] ?>] //diganti menggunakan php
        var map = L.map('map').setView(center, 13);

        L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '© OpenStreetMap'
        }).addTo(map);

        // marker
        var marker = L.marker(center, {
            draggable: true
        }).addTo(map);

        // event
        marker.on('dragend', function(e) {
            // var position = marker.getLatLng();
            // marker.setLatLng(position, {
            //     draggable: 'true'
            // }).bindPopup(position).update();
            // $("#input_longitude").val(position.lng);
            // $("#input_latitude").val(position.lat).keyup();
            var lat = marker.getLatLng().lat;
            var lng = marker.getLatLng().lng;
            document.getElementById("input_latitude").value = lat;
            document.getElementById("input_longitude").value = lng;
        });

        //map moveend
        map.on('moveend', function(event) {
            //get center
            var center = map.getCenter();
            var lng = center.lng;
            var lat = center.lat;
            //set marker position
            marker.setLatLng(center, {
                draggable: true
            }).update();
            //set input value
            document.getElementById("input_latitude").value = lat;
            document.getElementById("input_longitude").value = lng;
        });
    </script>
</body>

</html>