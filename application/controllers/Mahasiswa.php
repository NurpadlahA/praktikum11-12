<?php 
class Mahasiswa extends CI_Controller{
    // buat methood index
    public function index(){
        $this->load->model('mahasiswa_model');
        $mahasiswa = $this->mahasiswa_model->getAll();
        $data['mahasiswa'] = $mahasiswa;
        //render data dan kirim data ke dalam view
        $this->load->view('layouts/header');
        $this->load->view('mahasiswa/index', $data);
        $this->load->view('layouts/footer');
    }
    public function detail($id){
        // akses model mahassiswa
        $this->load->model('mahasiswa_model');
        $siswa= $this->mahasiswa_model->getById($id);
        $data['siswa'] = $siswa;
        $this->load->view('layouts/header');
        $this->load->view('mahasiswa/detail', $data);
        $this->load->view('layouts/footer');
    }
    public function form(){
        // render view
        $this->load->view('layouts/header');
        $this->load->view('mahasiswa/form');
        $this->load->view('layouts/footer');
    }
    public function save(){
        // akses model mahasiswa
        $this->load->model('mahasiswa_model','mahasiswa'); // langkah 1
        $_nim = $this->input->post('nim');
        $_nama = $this->input->post('nama');
        $_gender = $this->input->post('gender');
        $_tmp_lahir = $this->input->post('tmp_lahir');
        $_tgl_lahir = $this->input->post('tgl_lahir');
        $_ipk = $this->input->post('ipk');

        $data_mahasiswa['nim'] = $_nim; // langkah 2
        $data_mahasiswa['nama'] = $_nama;
        $data_mahasiswa['gender'] = $_gender;
        $data_mahasiswa['tmp_lahir'] = $_tmp_lahir;
        $data_mahasiswa['tgl_lahir'] = $_tgl_lahir;
        $data_mahasiswa['ipk'] = $_ipk;

        if((!empty($_idedit))){
            $data_mahasiswa['id'] = $_idedit;
            $this->mahasiswa->update($data_mahasiswa);
        }else{
            // data baru
            $this->mahasiswa->simpan($data_mahasiswa);
        }
        redirect('mahasiswa','refresh');
    }
    public function edit($id){
        // akses model mahasiswa
        $this->load->model('mahasiswa_model','mahasiswa');
        $obj_mahasiswa = $this->mahasiswa->getById($id);
        $data['obj_mahasiswa'] = $obj_mahasiswa;
        $this->load->view('layouts/header');
        $this->load->view('mahasiswa/edit', $data);
        $this->load->view('layouts/footer'); 
    }
    public function delete($id){
        $this->load->model('mahasiswa_model','mahasiswa');
        // ngcek data mahasiswa berdasarkan id
        $data_mahasiswa['id'] = $id;
        $this->mahasiswa->delete($data_mahasiswa);
        redirect('mahasiswa','refresh');
    }
    public function __construct()
    {
        parent::__construct();
        if(!$this->session->userdata('logged_in')){
            redirect('/login');
        }
    }
    public function upload(){
        $_idmahasiswa = $this->input->post('idmahasiswa');
        // akes model mahasiswa
        $this->load->model('mahasiswa_model','mahasiswa');
        $siswa= $this->mahasiswa->getById($_idmahasiswa);
        $data['siswa'] = $siswa;

        $config ['upload_path']='./uploads/photos';
        $config ['allowed_types']='jpg|png';
        $config ['max_size']=2894;
        $config ['max_width']=2894;
        $config ['max_height']=2894;
        $config ['file_name']=$siswa->id;

        // aktifkan library upload
        $this->load->library('upload',$config);
        // jika tidak ada file yang di upload 
        if (!$this->upload->do_upload('foto')) {
              // maka tampilan pesan eror
            $data['error'] = $this->upload->display_errors();
        } else {
            // jika ada file yang di upload
            // maka tampilkan pesan data berhasil di upload
            $data['upload_data'] = $this->upload->data();
            $data['error'] = 'data sukses';
        }
        // kirim ke view
        $this->load->view('layouts/header');
        $this->load->view('mahasiswa/detail', $data);
        $this->load->view('layouts/footer');
        
    }
}
?>