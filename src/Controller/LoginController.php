<?php
namespace App\Controller;

use Cake\ORM\TableRegistry;
use Cake\ORM\Query;
use Cake\ORM\Entity;
use Cake\I18n\FrozenDate;
use Cake\I18n\Date;
use \Exception;
use Cake\Log\Log;

use Cake\Controller\Component;
use Cake\Controller\ComponentRegistry;
use App\Controller\Component\S3ClientComponent;
use App\Controller\InfoController;

class LoginController extends AppController{
    public function initialize(){
        FrozenDate::setToStringFormat('yyyy/MM/dd');
	    $this->viewBuilder()->enableAutoLayout(false);
        date_default_timezone_set('Asia/Tokyo');

        $this->loadModel('Qualification');
        $this->loadModel('Schedule');

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
        sleep(4);
        if($result['@metadata']['statusCode'] == 200){
            $resultFile_name = "log_".str_replace('.', '_', $file_name).".json";
            $this->log($resultFile_name);
            $bucket_name = "face-results0921";
            $result = $this->S3Client->getFile($resultFile_name, $bucket_name);
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
            $infoController = new InfoController;
            $userInfo = $infoController->searchUserInfo($number);
            $json_user_out = array();
            //$return_number = 0;
            foreach($userInfo['base_info'] as $user){
                $date = new Date(strval($user->birthday));
                $json_user_out = ['level' => $user->level,
                             'school_id' => $user->school_id,
                             'name' => $user->first_name,
                             'kana' => $user->first_name_kana,
                             'number' => $user->number,
                             'address' => $user->address,
                             'home_address' => $user->home_address,
                             'birthday' => $date->format('Y-m-d'),
                             'sex' => $user->sex,
                             'phone_number' => $user->phone,
                             'home_number' => $user->tel,
                             'mail_address' => $user->mail,
                             'teacher' => $user->teacher
                ];
                //$return_number = $user->number;
                $this->log($user->number);
            }

            $json_qualification_out = array();
            $sikaku = $infoController->searchQualificationInfo($json_user_out['school_id']);
            $this->log($sikaku);
            // $this->log(gettype($userInfo['qualification_info']));
            // $this->log(count($userInfo['qualification_info']));
            // $sikaku = $this->Qualification->find('all',[
			//     'condition'=>['school_id'=>$json_user_out['school_id']]
		    // ]);
            // $sikaku = $this->Qualification->find('all');
            // $this->log(count($sikaku));
            foreach($sikaku as $qualification){
                $date = new Date(strval($qualification->pass_date));
                $json_qualification_out += array('pass_date' => $date->format('Y-m-d'), 
                                                 'qualification_name' => $qualification->qualification_name
                                                 );
            }
            $this->log($json_qualification_out);

            $json_schedule_out = array();
            if(!(is_null($userInfo['schedule_info']))){
                foreach($userInfo['schedule_info'] as $schedule){
                    $start_date = new Date(strval($schedule->start_date));
                    $end_date = new Date(strval($schedule->end_date));
                    $json_schedule_out = ['start_date' => $start_date->format('Y-m-d'),
                                          'end_date' => $end_date->format('Y-m-d'),
                                          'company' => $schedule->company
                                         ];
                }
            }


            header("Content-type: application/json; charset=UTF-8");
            echo json_encode($json_user_out, JSON_UNESCAPED_UNICODE);
            echo json_encode($json_qualification_out, JSON_UNESCAPED_UNICODE);
            echo json_encode($json_schedule_out, JSON_UNESCAPED_UNICODE);
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