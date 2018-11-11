<?php 
class Dosyalar extends CI_Controller
{
  private $kullanici;
  public function __construct()
  {
    parent::__construct();
    $this->load->model("ModelDosyalar");
    $this->kullanici=$this->session->userdata("kullanici");
    if(!$this->kullanici)
    {
      redirect(base_url("Giris"));
    }
  }
  public function index()
  {
    $veri["dosyalar"]=$this->ModelDosyalar->getAll();
    $this->load->view("dosyalar",$veri);
  }
  public function yukle()
  {
    $config["allowed_types"]="jpeg|jpg|gif|png|pdf|docx|doc|xlsx|xlsm|xls|xml|csv|txt|xps|odt|ppt|pptx|potx|zip";
    $config["upload_path"]="uploads/";
    $config["file_ext_tolower"]=true;
    $config["file_name"]=replace_tr($_FILES["file"]["name"]);
    $this->load->library("upload",$config);
    if($this->upload->do_upload("file"))
    {
      $veri=array(
        "dosya_ismi"=>$this->upload->data("file_name"),
        "dosya_url" =>base_url("uploads/$dosya_ismi"),
        "dosya_boyut"=>$this->upload->data("file_size"),
        "dosya_yukleyen"=>$this->kullanici["kullanici_adi"]
      );
      if ($this->ModelDosyalar->insert($veri))
      {
        echo "Başarılı";
      }
      else
      {
        echo "Başarısız";
      }
    }
    else
    {
      echo "Dosya uzantısı istenmeyen bir uzantı.";
    }
  }
  public function indir($dosya_ismi)
  {
    $this->load->helper('download');
    $yol="uploads/".$dosya_ismi;
    force_download($yol,NULL);
  }
  public function sil($id,$dosya_ismi)
  {
    $veri=array(
      "dosya_id"=>$id
    );
      if($this->ModelDosyalar->delete($veri))
      {
        unlink("uploads/".$dosya_ismi);
        redirect(base_url());
      }
      else{
        echo "Silme Başarısız";
      }
  }
}
?>