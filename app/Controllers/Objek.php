<?php

namespace App\Controllers;

use App\Models\TabelObjekModel;
use App\Controllers\BaseController;

class Objek extends BaseController
{

    protected $TabelObjekModel;

    public function __construct()
    {
        $this->TabelObjekModel = new TabelObjekModel();
    }

    public function index()
    {
        return view('v_input');
    }

    public function simpantambahdata()
    {
        $data = [
            'nama' => $this->request->getPost('input_nama'),
            'deskripsi' => $this->request->getPost('input_deskripsi'),
            'longitude' => $this->request->getPost('input_longitude'),
            'latitude' => $this->request->getPost('input_latitude'),
            'foto' => $this->request->getPost('foto'),
        ];

        $this->TabelObjekModel->save($data);

        return redirect()->to('objek');

        // $this->tabelObjekModel->insert($data);
        // return redirect()->to(base_url('objek'));

        // dd($data);
    }

    public function view()
    {
        return view('v_point');
    }

    public function table()
    {
        $data['objek'] = $this->TabelObjekModel->findAll();

        return view('v_table', $data);
    }

    public function Hapus($id)
    {
        $this->TabelObjekModel->delete($id);

        return redirect()->to('objek/table');
    }

    public function Edit($id)
    {
        $data['objek'] = $this->TabelObjekModel->find($id);

        return view('v_edit', $data);
    }
}
