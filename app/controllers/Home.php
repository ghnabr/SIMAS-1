<?php

class Home extends Controller
{
    public function index()
    {
        $this->checkSession();
        $data['judul'] = 'SIMAS - Home';
        $data['username'] = Login::getCurrentSession()->username;
        $data['role'] = Login::getCurrentSession()->role;
        $data['akses'] = Login::getCurrentSession()->akses;
        $data['user'] = $this->model('Login', 'Login_model')->getDataByName($data['username']);
        $data['jmlSiswa'] = $this->model('Master', 'Siswa_model')->getJmlData()['count'];
        $data['jmlGuru'] = $this->model('Master', 'Guru_model')->getJmlData()['count'];
        $data['jmlKaryawan'] = $this->model('Master', 'Karyawan_model')->getJmlData()['count'];
        $this->view('templates/header', $data);
        $this->view('home/index', $data);
        $this->view('templates/footerwm');
    }
}