<?php
namespace App\Controller;

use Cake\ORM\TableRegistry;
use Cake\I18n\FrozenDate;
use Cake\I18n\Date;
use \Exception;
use Cake\Log\Log;

use Cake\Controller\Component;
use Cake\Controller\ComponentRegistry;
use App\Controller\Component\S3ClientComponent;
use App\Controller\DBController;

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
        // $this->log(gettype($json));
        $data = json_decode($json, true);
        // $this->log($data['number']);
        // $this->log($data['face']);
        //$this->log($data['face']);
        // $fp = fopen("\storage\data.jpg", 'wb');
        // $fp = fopen($this->storage_path."data.jpg", 'wb');
        // fwrite($fp, $data);
        $face = base64_decode($data['face']);
        //$this->log($face);
        $file_name = $data['number'].".jpg";
        // $file_name = "1501010.jpg";
        // $file_local_path=sprintf('%s%s', $this->storage_path, $file_name);
        $result=$this->S3Client->putFile($face, $file_name);

        $this->log($data['name']);
        $this->log(gettype($data['name']));

        $birthday = new Date($data['birthday']);
        if($data['sex'] == "男性"){
            $sex = 0;
        }else{
            $sex = 1;
        }

        $DBController = new DBController;
        $DBController->addUser(intval($data['number']), $data['name'], $data['kana'], $data['address'],
                                $data['home_address'], $birthday, $sex, intval($data['phone_number']), intval($data['home_number']),
                                $data['mail_address'], $data['teacher'], intval($data['level']));

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