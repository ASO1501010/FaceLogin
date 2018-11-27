<?php
namespace App\Controller;

use Cake\ORM\TableRegistry;
use Cake\ORM\Query;
use Cake\ORM\Entity;
use Cake\ORM\Table;
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
        //sleep(2);
        //$this->log($result);
        if($result['@metadata']['statusCode'] == 200){
            $resultFile_name = "log_".str_replace('.', '_', $file_name).".json";
            $this->log($resultFile_name);
            $bucket_name = "face-results0921";
            try{
                $result = $this->S3Client->getFile($resultFile_name, $bucket_name);
                //$this->log($result);
            }catch(Exception $e){
                $this->log('エラー');
                //$result = null;
            }
            if($result != null){
                $json = $result->get('Body');
                $data = (string)$json;
                $content = json_decode($data, true);

                $this->S3Client->deleteFile($resultFile_name, $bucket_name);
            }
            if($result != null && $result['@metadata']['statusCode'] == 200 && $content['FaceMatches'] != null){
                //$content = $result['Body']->getContents();
                $similarity = $content['FaceMatches'][0]['Similarity'];
                // $this->log($number);
                // $this->log(gettype($number));
                $this->log($similarity);
                $number = str_replace('.jpg', '', $content['FaceMatches'][0]['Face']['ExternalImageId']);
                $this->setInfo($number);
            }else{
                $this->log('tag1');
                header("Content-type: text/plain; charset=UTF-8");
                echo "login_failed";
            }
            $this->S3Client->deleteFile($file_name, "face-images0921");
        }else{
            $this->log($result['@metadata']['statusCode']);
            header("Content-type: text/plain; charset=UTF-8");
            echo "login_failed";
        }
    }

    public function setInfo($number){
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

        $this->log(gettype($json_user_out['school_id']));
        $number_tmp = $json_user_out['school_id'];
        
        $json_qualification_out = [];
        $sikaku = $this->Qualification->find('all',[
			    'condition'=>['Qualification.school_id'=>11]
		]);
        $this->log($sikaku->count());
        $cnt = 0;
        foreach($userInfo['qualification_info'] as $qualification){
            $date = new Date(strval($qualification->pass_date));
            $json_qualification_out[] = ['pass_date' => $date->format('Y-m-d'), 
                                         'qualification_name' => $qualification->qualification_name
            ];
            //$json_qualification_out[$cnt++];
        }
        $this->log($json_qualification_out);

        $json_schedule_out = [];
        if(!(is_null($userInfo['schedule_info']))){
            foreach($userInfo['schedule_info'] as $schedule){
                $start_date = new Date(strval($schedule->start_date));
                $end_date = new Date(strval($schedule->end_date));
                $json_schedule_out[] = ['start_date' => $start_date->format('Y-m-d'),
                                        'end_date' => $end_date->format('Y-m-d'),
                                        'company' => $schedule->company
                ];
            }
        }

        header("Content-type: application/json; charset=UTF-8");
        echo json_encode($json_user_out, JSON_UNESCAPED_UNICODE);
        echo json_encode($json_qualification_out, JSON_UNESCAPED_UNICODE);
        echo json_encode($json_schedule_out, JSON_UNESCAPED_UNICODE);
    }
}