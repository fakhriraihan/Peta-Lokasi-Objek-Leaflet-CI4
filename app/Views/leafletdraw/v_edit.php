<?php

use App\Controllers\Api;
use Faker\Provider\Base;
?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <title>Leaflet Draw Editing</title>

    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.1/dist/leaflet.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/leaflet-draw@1.0.4/dist/leaflet.draw.css">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">

    <!-- Font Awesome CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.9.0/css/all.min.css" integrity="sha512-q3eWabyZPc1XTCmF+8/LuE1ozpg5xxn7iO89yfSOd5/oKvyqLngoNGsx8jq92Y8eXJ/IRxQbEC+FGSYxtk2oiw==" crossorigin="anonymous" referrerpolicy="no-referrer" />


    <!-- Tab browser icon -->
    <link rel="icon" type="image/x-icon" href="https://awsimages.detik.net.id/community/media/visual/2017/10/24/cdbcb323-422c-4335-af51-8382ebdcf787.png?w=700&q=90">

    <style>
        /*Tampilan peta fullscreen*/
        html,
        body,
        #map {
            height: 100%;
            width: 100%;
            margin: 0px;
            overflow: hidden;
        }

        #map {
            width: auto;
            height: calc(100% - 56px);
            margin-top: 56px;
        }
    </style>
</head>

<body>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.1.min.js"></script>

    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet@1.9.1/dist/leaflet.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/leaflet-draw@1.0.4/dist/leaflet.draw.min.js"></script>

    <!-- Terraformer -->
    <script src="https://cdn.jsdelivr.net/npm/terraformer@1.0.12/terraformer.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/terraformer-wkt-parser@1.2.1/terraformer-wkt-parser.min.js"></script>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-OERcA2EqjJCMA+/3y+gxIOqMEjwtxJY7qPCqsdltbNJuaOe923+mo//f6V8Qbsw3" crossorigin="anonymous"></script>

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
                            <a class="nav-link" href="<?= base_url('objek/table') ?>"><i class="fas fa-table"></i> Tabel Data</a>
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
                    <li class="nav-item">
                        <a class="nav-link" href="#" data-bs-toggle="modal" data-bs-target="#InfoModal"><i class="fas fa-info-circle"></i> Info</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- MAP -->
    <div id="map"></div>

    <!-- Modal -->
    <div class="modal fade" id="InfoModal" tabindex="-1" aria-labelledby="InfoModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="InfoModalLabel"><i class="fas fa-info-circle"></i> Info</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Aplikasi Peta Lokasi Objek yang dibuat dengan CodeIgniter-4 dan Leaflet</p>
                    <p>Make with love by Fakhri Raihan</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
</body>

<script>
    /* Initial Map */
    var map = L.map("map").setView([-7.9, 110.45], 10);

    /* Tile Basemap */
    var basemap1 = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '<a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> | <a href="https://github.com/fakhriraihan" target="_blank">FR</a>' //menambahkan nama//
    });

    var basemap2 = L.tileLayer('https://{s}.tile.opentopomap.org/{z}/{x}/{y}.png', {
        attribution: 'Map data: &copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors, <a href="http://viewfinderpanoramas.org">SRTM</a> | Map style: &copy; <a href="https://opentopomap.org">OpenTopoMap</a> (<a href="https://creativecommons.org/licenses/by-sa/3.0/">CC-BY-SA</a>)'
    });

    var basemap3 = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
        attribution: 'Tiles &copy; Esri &mdash; Source: Esri, i-cubed, USDA, USGS, AEX, GeoEye, Getmapping, Aerogrid, IGN, IGP, UPR-EGP, and the GIS User Community'
    });

    var basemap4 = L.tileLayer('https://tiles.stadiamaps.com/tiles/alidade_smooth_dark/{z}/{x}/{y}{r}.png', {
        maxZoom: 20,
        attribution: '&copy; <a href="https://stadiamaps.com/">Stadia Maps</a>, &copy; <a href="https://openmaptiles.org/">OpenMapTiles</a> &copy; <a href="http://openstreetmap.org">OpenStreetMap</a> contributors'
    });
    basemap4.addTo(map);

    //GeoJSON Point
    var point = L.geoJson(null, {
        onEachFeature: function(feature, layer) {
            var popupContent = "Nama: " + feature.properties.nama + "<br>" +
                "Deskripsi: " + feature.properties.deskripsi;
            layer.on({
                click: function(e) {
                    var confirm = window.confirm("Yakin mau menghapus " + feature.properties.nama + "?");

                    //Hapus Point
                    if (confirm) {
                        var id = feature.properties.id;
                        //hapus point dengan AJAX
                        $.ajax({
                            url: "<?= base_url('leafletdraw/hapusdatapoint') ?>",
                            type: "POST",
                            data: {
                                "id": id,
                                "<?= csrf_token() ?>": "<?= csrf_hash() ?>"
                            },
                            success: function(data) {
                                // redirect
                                window.location.href = "<?= base_url('leafletdraw/delete') ?>";
                                console.log("Data berhasil dihapus");
                            },
                            error: function(data) {
                                console.log("Data gagal dihapus");
                            }
                        });
                    }
                },
                mouseover: function(e) {
                    point.bindTooltip(feature.properties.nama);
                },
            });
        },
    });
    $.getJSON("<?= base_url('api/point') ?>", function(data) {
        point.addData(data);
        map.addLayer(point);
    });

    //GeoJSON Polyline
    var line = L.geoJson(null, {
        /* Style polyline */
        style: function(feature) {
            return {
                color: "#3388ff",
                weight: 3,
                opacity: 1,
            };
        },
        onEachFeature: function(feature, layer) {
            var popupContent = "Nama: " + feature.properties.nama + "<br>" +
                "Panjang: " + feature.properties.panjang_km + " km";
            layer.on({
                click: function(e) {
                    var confirm = window.confirm("Yakin mau menghapus " + feature.properties.nama + "?");

                    //Hapus Polyline
                    if (confirm) {
                        var id = feature.properties.id;
                        //hapus polyline dengan AJAX
                        $.ajax({
                            url: "<?= base_url('leafletdraw/hapusdatapolyline') ?>",
                            type: "POST",
                            data: {
                                "id": id,
                                "<?= csrf_token() ?>": "<?= csrf_hash() ?>"
                            },
                            success: function(data) {
                                // redirect
                                window.location.href = "<?= base_url('leafletdraw/delete') ?>";
                                console.log("Data berhasil dihapus");
                            },
                            error: function(data) {
                                console.log("Data gagal dihapus");
                            }
                        });
                    }
                },
                mouseover: function(e) {
                    line.bindTooltip(feature.properties.nama, {
                        sticky: true,
                    });
                },
            });
        },
    });
    $.getJSON("<?= base_url('api/polyline') ?>", function(data) {
        line.addData(data);
        map.addLayer(line);
    });

    //GeoJSOn Polygon
    var polygon = L.geoJson(null, {
        /* Style polygon */
        style: function(feature) {
            return {
                color: "#3388ff",
                fillColor: "green",
                weight: 2,
                opacity: 1,
                fillOpacity: 0.2,
            };
        },
        onEachFeature: function(feature, layer) {
            var popupContent = "Nama: " + feature.properties.nama + "<br>" +
                "Deskripsi: " + feature.properties.deskripsi + "<br>" +
                "Luas: " + feature.properties.luas_km2 + " km2";
            layer.on({
                click: function(e) {
                    var idpolygon = feature.properties.id;

                    //redirect ke halaman edit polygon
                    window.location.href = "<?= base_url('leafletdraw/edit_polygon') ?>" + "/" + idpolygon;

                },
                mouseover: function(e) {
                    polygon.bindTooltip(feature.properties.nama, {
                        sticky: true,
                    });
                },
            });
        },
    });
    $.getJSON("<?= base_url('api/polygon') ?>", function(data) {
        polygon.addData(data);
        map.addLayer(polygon);
    });

    /* Control Layer */
    var baseMaps = {
        "OpenStreetMap": basemap1,
        "OpenTopoMap": basemap2,
        "ESRI World Imagery": basemap3,
        "Stadia Dark Mode": basemap4
    };

    L.control.layers(baseMaps, null, {
        collapsed: false
    }).addTo(map);

    /* Scale Bar */
    L.control.scale({
        maxWidth: 150,
        position: 'bottomright'
    }).addTo(map);
</script>

</html>