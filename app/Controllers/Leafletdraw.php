<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\TbldatapointModel;
use App\Models\TbldatapolylineModel;
use App\Models\TbldatapolygonModel;

class Leafletdraw extends BaseController
{
    protected $TbldatapointModel;
    protected $TbldatapolylineModel;
    protected $TbldatapolygonModel;

    public function __construct()
    {
        $this->TbldatapointModel = new TbldatapointModel();
        $this->TbldatapolylineModel = new TbldatapolylineModel();
        $this->TbldatapolygonModel = new TbldatapolygonModel();
    }

    public function index()
    {
        return view('leafletdraw/v_create');
    }

    public function simpan_point()
    {
        $data = [
            'nama' => $this->request->getPost('input_point_name'),
            'geom' => $this->request->getPost('input_point_geometry'),
        ];

        $this->TbldatapointModel->save($data);
        return redirect()->to(base_url('leafletdraw'));
    }

    public function simpan_polyline()
    {
        $data = [
            'nama' => $this->request->getPost('input_polyline_name'),
            'geom' => $this->request->getPost('input_polyline_geometry'),
        ];

        $this->TbldatapolylineModel->save($data);
        return redirect()->to(base_url('leafletdraw'));
    }

    public function simpan_polygon()
    {
        $data = [
            'nama' => $this->request->getPost('input_polygon_name'),
            'geom' => $this->request->getPost('input_polygon_geometry'),
        ];

        $this->TbldatapolygonModel->save($data);
        return redirect()->to(base_url('leafletdraw'));
    }
}
