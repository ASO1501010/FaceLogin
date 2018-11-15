<?php
namespace App\Controller;

use Cake\ORM\TableRegistry;
use Cake\I18n\FrozenDate;
use \Exception;
use Cake\Log\Log;

use Cake\Controller\Component;
use Cake\Controller\ComponentRegistry;
use App\Controller\Component\S3ClientComponent;

class LoginController extends AppController{
    public function initialize(){
        FrozenDate::setToStringFormat('yyyy/MM/dd');
	    $this->viewBuilder()->enableAutoLayout(false);
        date_default_timezone_set('Asia/Tokyo');

        $this->S3Client = new S3ClientComponent(new ComponentRegistry());
        $this->storage_path=STORAGE_PATH;
    }

    public function compFace(){
        $this->autoRender = false;
        $face = file_get_contents("php://input");
        $this->log($face);
        $file_name = uniqid().".jpg";
        $bucket_name = "face-images0921";
        $result=$this->S3Client->putFile($face, $file_name, $bucket_name);
        $this->log($result);
        $resultFile_name = "log_".str_replace('.', '_', $file_name)."json";
        $bucket_name = "face-result0921";
        $result_json = $this->S3Client->getFile($resultFile_name, $bucket_name);
        $this->log($result_json['FaceMatches']['Face']['ExternalImageId']);
    }
}