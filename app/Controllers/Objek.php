<?php

namespace App\Controllers;

use App\Models\TabelObjekModel;
use App\Controllers\BaseController;
use CodeIgniter\Session\Session;

class Objek extends BaseController
{

    protected $TabelObjekModel;

    public function __construct()
    {
        $this->TabelObjekModel = new TabelObjekModel();
        $this->data['validation'] = \Config\Services::validation();
    }

    public function index()
    {
        session();
        return view('v_input', $this->data);
    }

    public function simpantambahdata()
    {
        session();
        if (!$this->validate([
            'input_nama' => [
                'rules' => 'required|is_unique[tbl_objek.nama]',
                'errors' => [
                    'required' => 'Nama harus diisi',
                    'is_unique' => 'Nama sudah ada'
                ]
            ],
            'input_longitude' => [
                'rules' => 'required|numeric',
                'errors' => [
                    'required' => 'Longitude harus diisi',
                    'numeric' => 'Longitude harus angka'
                ]
            ],
            'input_latitude' => [
                'rules' => 'required|numeric',
                'errors' => [
                    'required' => 'Latitude harus diisi',
                    'numeric' => 'Latitude harus angka'
                ]
            ],
            'input_foto' => [
                'rules' => 'max_size[input_foto,1024]|mime_in[input_foto,image/jpg,image/jpeg,image/png]',
                'errors' => [
                    'max_size' => 'Ukuran foto terlalu besar (lebih dari 1 MB)',
                    'mime_in' => 'File yang diupload harus berupa JPG, JPEG, atau PNG'
                ]
            ]
        ])) {
            return redirect()->to('/objek')->with("message", "Data gagal ditambahkan")->withInput();
        }

        //upload foto
        $fileFoto = $this->request->getFile('input_foto');

        if ($fileFoto->getError() == 4) {
            $namaFoto = NULL;
        } else {
            $fotoDir = 'img/objek/';
            if (!is_dir($fotoDir)) {
                mkdir($fotoDir, 0777, TRUE);
            }
            $namaFoto = 'foto_' . preg_replace('/\s+/', '', $_POST['input_nama']) . '.' . $fileFoto->getExtension();

            //memindahkan file
            $fileFoto->move($fotoDir, $namaFoto);
        }

        $data = [
            'nama' => $_POST['input_nama'],
            'deskripsi' => $_POST['input_deskripsi'],
            'longitude' => $_POST['input_longitude'],
            'latitude' => $_POST['input_latitude'],
            'foto' => $namaFoto,
        ];

        $this->TabelObjekModel->save($data);

        return redirect()->to('objek/table')->with('message', 'Data sampun ditambahkan');

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

        return redirect()->to('objek/table')->with('message', 'Data sampun dihapus');
    }

    public function Edit($id)
    {
        $data['objek'] = $this->TabelObjekModel->find($id);

        return view('v_edit', $data);
    }

    public function Simpaneditdata($id)
    {
        Session();

        //upload foto
        $fileFoto = $this->request->getFile('input_foto');

        if ($fileFoto->getError() == 4) {
            if ($_POST['input_foto_lama'] !== '') {
                $namaFoto = $_POST['input_foto_lama'];
            } else {
                $namaFoto = NULL;
            }
        } else {
            $fotoDir = 'img/objek/';
            if (!is_dir($fotoDir)) {
                mkdir($fotoDir, 0777, TRUE);
            }

            //Cek foto lama existing
            if ($_POST['input_foto_lama'] !== '') {
                if (file_exists($fotoDir . $_POST['input_foto_lama'])) {
                    unlink($fotoDir . $_POST['input_foto_lama']);
                }
            }

            $namaFoto = 'foto_' . preg_replace('/\s+/', '', $_POST['input_nama']) . '.' . $fileFoto->getExtension();


            //memindahkan file
            $fileFoto->move($fotoDir, $namaFoto);
        }

        $data = [
            'id' => $id,
            'nama' => $_POST['input_nama'],
            'deskripsi' => $_POST['input_deskripsi'],
            'longitude' => $_POST['input_longitude'],
            'latitude' => $_POST['input_latitude'],
            'foto' => $namaFoto,
        ];

        $this->TabelObjekModel->update($id, $data);

        return redirect()->to('objek/table')->with('message', 'Data sampun diubah');
    }
}
