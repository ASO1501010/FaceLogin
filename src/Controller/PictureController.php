<?php
namespace App\Controller;

use Cake\ORM\TableRegistry;
use Cake\I18n\FrozenDate;
use \Exception;
use Cake\Log\Log;

use Cake\Controller\Component;
use Cake\Controller\ComponentRegistry;
use App\Controller\Component\S3ClientComponent;

class PictureController extends AppController{
    public function initialize(){
        FrozenDate::setToStringFormat('yyyy/MM/dd');
	    $this->viewBuilder()->enableAutoLayout(false);
        date_default_timezone_set('Asia/Tokyo');

        $this->S3Client = new S3ClientComponent(new ComponentRegistry());
        $this->storage_path=STORAGE_PATH;
    }

    public function upload(){
        $this->autoRender = false;
        // S3に画像をアップロードする
        // $file_name="test1.jpg";
        // //$store_dir="face/";

        // $file_local_path=sprintf('%s%s', $this->storage_path, $file_name);
        // //$file_store_path=sprintf('%s%s', $store_dir,$file_name);

        // $result=$this->S3Client->putFile($file_local_path, $file_name);
        $json = file_get_contents("php://input");
        $data = json_decode($json, true);
        $this->log($data['number']);
        // $fp = fopen("\storage\data.jpg", 'wb');
        // $fp = fopen($this->storage_path."data.jpg", 'wb');
        // fwrite($fp, $data);
        $face = $data['face'];
        $file_name = $data['number'].".jpg";
        // $file_local_path=sprintf('%s%s', $this->storage_path, $file_name);
        $result=$this->S3Client->putFile($face, $file_name);
    }

    public function face(){
        $this->viewBuilder()->setClassName('Json');
        $this->log("エラーログを出力します。");
        $data = [
            "status" => '200',
            "test data" => [
                "ccc1" => 10,
                "ccc2" => [
                    "aaa",
                    "bbb",
                    "ccc",
                ],
            ],
        ];
        $this->set([
            'data' => $_POST['word'],
            //'_serialize' => ['data']
        ]);

        //echo $_POST['word'];
        // if($this->request->is('post')) {  // Post通信
        //    // 受信データ格納
        //   $input_data = $this->request->input();
        }
}