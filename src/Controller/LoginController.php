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
        //$this->log($face);
        $file_name = uniqid().".jpg";
        $bucket_name = "face-images0921";
        $result=$this->S3Client->putFile($face, $file_name, $bucket_name);
        if($result['@metadata']['statusCode'] == 200){
            $resultFile_name = "log_".str_replace('.', '_', $file_name).".json";
            $this->log($resultFile_name);
            $bucket_name = "face-results0921";
            sleep(2);
            $result = $this->S3Client->getFile($resultFile_name, $bucket_name);
            //$content = $result['Body']->getContents();
            $content = (string)$result->get('Body');
            // $data = $result['Body'];
            // $this->log($data);
            // $content = $data->getContents();
            //$this->log($content['FaceMatches']['Face']['ExternalImageId']);
            //$this->log(is_array($content));
            // $this->log($content);
            // $this->log(gettype($content));
            //$json = json_decode($content);
            $this->log($content);
            //$data = (string)$content;
            // $this->log($result['Body']);
            // if(is_null($result['Body'])){
            //     $this->log("nullです");
            // }else{
            //     $this->log("nullではない");
            //     $this->log($result['Body']);
            // }
            //$result_json = json_decode($result, true);
            // $this->log(gettype($result_json));
            //$this->log($data['FaceMatches']['Face']['ExternalImageId']);
            //$this->log(is_json($json));
            //$this->log(gettype($json));
            //$this->log($json['FaceMatches']['Face']['ExternalImageId']);
            $this->log($content['FaceMatches']['Face']['ExternalImageId']);
        }
    }
}