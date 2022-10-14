<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <title>Peta Lokasi Objek</title>

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.1/dist/leaflet.css" />

    <!-- Tab browser icon -->
    <link rel="icon" type="image/x-icon" href="https://awsimages.detik.net.id/community/media/visual/2017/10/24/cdbcb323-422c-4335-af51-8382ebdcf787.png?w=700&q=90">

    <style>
        /* Background pada Judul */
        *.info {
            padding: 6px 8px;
            font: 14px/16px Arial, Helvetica, sans-serif;
            background: rgb(28, 27, 27);
            background: rgba(25, 24, 24, 0.8);
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.2);
            border-radius: 5px;
            text-align: center;
        }

        .info h2 {
            margin: 7px 5px 5px;
            color: rgb(255, 255, 255);
        }

        .info h4 {
            color: rgb(255, 255, 255);
        }

        /*Tampilan peta fullscreen*/
        html,
        body,
        #map {
            height: 100%;
            width: 100%;
            margin: 0px;
        }
    </style>
</head>

<body>
    <script src="https://code.jquery.com/jquery-3.6.1.min.js"></script>
    <script src="https://unpkg.com/leaflet@1.9.1/dist/leaflet.js"></script>
    <div id="map"></div>
</body>

<script>
    /* Initial Map */
    var map = L.map("map").setView([-7.9, 110.45], 10);

    /* Tile Basemap */
    var basemap1 = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '<a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> | <a href="SITEMAN" target="_blank">SITEMAN</a>' //menambahkan nama//
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

    /* Judul dan Subjudul */
    var info = new L.Control();

    info.onAdd = function(map) {
        this._div = L.DomUtil.create('div', 'info');
        this.update();
        return this._div;
    };

    info.update = function() {
        this._div.innerHTML = '<h2>Peta Lokasi Objek </h2> <h4>Peta untuk mengetahui lokasi objek</h4>'
    };

    info.addTo(map);

    /* GeoJSON Point */
    $.getJSON("<?= base_url('Api') ?>", function(data) {
        geojson.addData(data);
        map.addLayer(geojson);
        map.fitBounds(geojson.getBounds());
    });

    var geojson = L.geoJson(null, {
        pointToLayer: function(feature, latlng) {
            return L.marker(latlng, {
                icon: L.icon({
                    iconUrl: "<?= base_url('/img/marker.png') ?>",
                    iconSize: [30, 30],
                    iconAnchor: [15, 30],
                    popupAnchor: [0, -30],
                }),
            });
        },
        onEachFeature: function(feature, layer) {
            /* Variabel content untuk memanggil atribut dari data file geojson */
            var content = "Nama : " + feature.properties.nama + "<br>" +
                "Deskripsi : " + feature.properties.deskripsi + "<br>" +
                "<img src='<?= base_url('/img/objek') ?>" + "/" + feature.properties.foto + "' width='100px' height='100px'>";

            layer.on({
                click: function(e) { //Fungsi ketika icon simbol di-klik
                    geojson.bindPopup(content);
                },

                mouseover: function(e) {
                    geojson.bindTooltip(feature.properties.nama, {
                        sticky: true
                    });
                },

                mouseout: function(e) {
                    geojson.closePopup();
                }
            });
        },
    });

    /* Control Layer */
    var baseMaps = {
        "OpenStreetMap": basemap1,
        "OpenTopoMap": basemap2,
        "ESRI World Imagery": basemap3,
        "Stadia Dark Mode": basemap4
    };

    var overlayMaps = {
        "Objek": geojson
    };

    L.control.layers(baseMaps, overlayMaps, {
        collapsed: false
    }).addTo(map);

    /* Scale Bar */
    L.control.scale({
        maxWidth: 150,
        position: 'bottomright'
    }).addTo(map);
</script>

</html>