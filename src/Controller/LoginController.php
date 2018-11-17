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
            $result = $this->S3Client->getFile($resultFile_name, $bucket_name);
            sleep(2);
            //$content = $result['Body']->getContents();
            $json = $result->get('Body');
            $data = (string)$json;
            $content = json_decode($data, true);
            $number = str_replace('.jpg', '', $content['FaceMatches'][0]['Face']['ExternalImageId']);
            $similarity = $content['FaceMatches'][0]['Similarity'];
            $this->log($number);
            $this->log(gettype($number));
            $this->log($similarity);
            $this->log(gettype($similarity));
            //$content = (array)$stdclass;
            // $data = $result['Body'];
            // $this->log($data);
            // $content = $data->getContents();
            //$this->log($content['FaceMatches']['Face']['ExternalImageId']);
            //$this->log(is_array($content));
            // $this->log($content);
            // $this->log(gettype($content));
            //$json = json_decode($content);
            // $this->log($content);
            // if(is_array($content) && is_string($content)){
            //     $this->log("jsonです");
            // }else{
            //     $this->log("jsonではない");
            // }
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
            //$this->log($content['FaceMatches'][0]['Face']['ExternalImageId']);
            //$this->log($content[1]);
        }
    }
}