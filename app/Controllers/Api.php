<?php

namespace App\Controllers;

use App\Models\TabelObjekModel;
use CodeIgniter\RESTful\ResourceController;
use Config\Feature;

class Api extends ResourceController
{
    /**
     * Return an array of resource objects, themselves in array format
     *
     * @return mixed
     */
    protected $format = 'json';
    protected $TabelObjekModel;

    public function __construct()
    {
        $this->TabelObjekModel = new TabelObjekModel();
    }

    public function index()
    {
        $dataobjek = $this->TabelObjekModel->findAll();

        $feature = array();

        foreach ($dataobjek as $d) {
            $feature[] = [
                'type' => 'Feature',
                'geometry' => [
                    'type' => 'Point',
                    'coordinates' => [
                        floatval($d['longitude']),
                        floatval($d['latitude']),
                    ]
                ],
                'properties' => [
                    'id' => $d['id'],
                    'nama' => $d['nama'],
                    'deskripsi' => $d['deskripsi'],
                    'foto' => $d['foto'],
                ]
            ];
        }

        $geojson = array(
            'type' => 'FeatureCollection',
            'features' => $feature
        );

        return $this->respond($geojson);
    }


    /**
     * Return the properties of a resource object
     *
     * @return mixed
     */
    public function show($id = null)
    {
        //
    }

    /**
     * Return a new resource object, with default properties
     *
     * @return mixed
     */
    public function new()
    {
        //
    }

    /**
     * Create a new resource object, from "posted" parameters
     *
     * @return mixed
     */
    public function create()
    {
        //
    }

    /**
     * Return the editable properties of a resource object
     *
     * @return mixed
     */
    public function edit($id = null)
    {
        //
    }

    /**
     * Add or update a model resource, from "posted" properties
     *
     * @return mixed
     */
    public function update($id = null)
    {
        //
    }

    /**
     * Delete the designated resource object from the model
     *
     * @return mixed
     */
    public function delete($id = null)
    {
        //
    }

    //Leaflet Draw Point GeoJSON
    public function point()
    {
        $db = db_connect();
        $query = $db->query("SELECT id, nama, ST_AsGeoJSON(geom) AS geom, deskripsi, foto, created_at, updated_at FROM tbl_data_point WHERE deleted_at IS NULL");

        $query_array = $query->getResultArray();

        $feature = array();

        foreach ($query_array as $q) {
            $feature[] = [
                'type' => 'Feature',
                'geometry' => json_decode($q['geom']),
                'properties' => [
                    'id' => $q['id'],
                    'nama' => $q['nama'],
                    'deskripsi' => $q['deskripsi'],
                    'foto' => $q['foto'],
                    'created_at' => $q['created_at'],
                    'updated_at' => $q['updated_at'],
                ]
            ];
        }

        $geojson = array(
            'type' => 'FeatureCollection',
            'features' => $feature
        );

        return $this->respond($geojson);
    }

    //leaflet Draw Polyline GeoJSON
    public function polyline()
    {
        $db = db_connect();
        $query = $db->query("SELECT id, nama, ST_AsGeoJSON(geom) AS geom, ST_Length(geom, true) AS panjang_m, deskripsi, foto, created_at, updated_at FROM tbl_data_polyline WHERE deleted_at IS NULL");

        $query_array = $query->getResultArray();

        $feature = array();

        foreach ($query_array as $q) {
            $feature[] = [
                'type' => 'Feature',
                'geometry' => json_decode($q['geom']),
                'properties' => [
                    'id' => $q['id'],
                    'nama' => $q['nama'],
                    'deskripsi' => $q['deskripsi'],
                    'foto' => $q['foto'],
                    'panjang_m' => $q['panjang_m'],
                    'panjang_km' => $q['panjang_m'] / 1000,
                    'created_at' => $q['created_at'],
                    'updated_at' => $q['updated_at'],
                ]
            ];
        }

        $geojson = array(
            'type' => 'FeatureCollection',
            'features' => $feature
        );

        return $this->respond($geojson);
    }

    // Leaflet Draw Polygon GeoJSON
    public function polygon()
    {
        $db = db_connect();
        $query = $db->query("SELECT id, nama, ST_AsGeoJSON(geom) AS geom, ST_Area(geom, true) AS luas_m2, deskripsi, foto, created_at, updated_at FROM tbl_data_polygon WHERE deleted_at IS NULL");

        $query_array = $query->getResultArray();

        $feature = array();

        foreach ($query_array as $q) {
            $feature[] = [
                'type' => 'Feature',
                'geometry' => json_decode($q['geom']),
                'properties' => [
                    'id' => $q['id'],
                    'nama' => $q['nama'],
                    'deskripsi' => $q['deskripsi'],
                    'foto' => $q['foto'],
                    'luas_m2' => $q['luas_m2'],
                    'luas_ha' => $q['luas_m2'] / 10000,
                    'luas_km2' => $q['luas_m2'] / 1000000,
                    'created_at' => $q['created_at'],
                    'updated_at' => $q['updated_at'],
                ]
            ];
        }

        $geojson = array(
            'type' => 'FeatureCollection',
            'features' => $feature
        );

        return $this->respond($geojson);
    }

    // Leaflet Draw One Polygon GeoJSON
    public function one_polygon($id)
    {
        $db = db_connect();
        $query = $db->query("SELECT id, nama, ST_AsGeoJSON(geom) AS geom, ST_Area(geom, true) AS luas_m2, deskripsi, foto, created_at, updated_at FROM tbl_data_polygon WHERE deleted_at IS NULL AND id = $id");

        $query_array = $query->getResultArray();

        $feature = array();

        foreach ($query_array as $q) {
            $feature[] = [
                'type' => 'Feature',
                'geometry' => json_decode($q['geom']),
                'properties' => [
                    'id' => $q['id'],
                    'nama' => $q['nama'],
                    'deskripsi' => $q['deskripsi'],
                    'foto' => $q['foto'],
                    'luas_m2' => $q['luas_m2'],
                    'luas_ha' => $q['luas_m2'] / 10000,
                    'luas_km2' => $q['luas_m2'] / 1000000,
                    'created_at' => $q['created_at'],
                    'updated_at' => $q['updated_at'],
                ]
            ];
        }

        $geojson = array(
            'type' => 'FeatureCollection',
            'features' => $feature
        );

        return $this->respond($geojson);
    }
}
