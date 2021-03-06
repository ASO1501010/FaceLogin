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

class InfoController extends AppController{
    public function initialize(){
        FrozenDate::setToStringFormat('yyyy/MM/dd');
	    $this->viewBuilder()->enableAutoLayout(false);
        date_default_timezone_set('Asia/Tokyo');

        $this->S3Client = new S3ClientComponent(new ComponentRegistry());
        $this->storage_path=STORAGE_PATH;
    }

    public function addQualification(){
        $this->autoRender = false;
        $json = file_get_contents("php://input");
        $data = json_decode($json, true);
        $pass_date = new Date($data['pass_date']);
        //$pass_date = $pass_date_tmp->format('Y-m-d');

        $DBController = new DBController;
        $DBController->addQualification(intval($data['school_id']), $data['qualification_name'], $pass_date);
    }

    public function addSchedule(){
        $this->autoRender = false;
        $json = file_get_contents("php://input");
        $data = json_decode($json, true);
        $start_date = new Date($data['start_date']);
        $this->log($start_date);
        //$start_date = $date_tmp->format('Y-m-d');
        $end_date = new Date($data['end_date']);
        $this->log($end_date);
        //$end_date = $date_tmp->format('Y-m-d');

        $DBController = new DBController;
        $DBController->addSchedule(intval($data['school_id']), $data['company'], $start_date, $end_date);
    }

    public function editUserInfo(){
        $this->autoRender = false;
        $json = file_get_contents("php://input");
        $data = json_decode($json, true);
        $birthday = new Date($data['birthday']);
        if($data['sex'] == "男性"){
            $sex = 0;
        }else{
            $sex = 1;
        }
        //$pass_date = $pass_date_tmp->format('Y-m-d');

        $DBController = new DBController;
        $DBController->editUserInfo(intval($data['school_id']), $data['name'], $data['kana'], intval($data['number']), $data['address'],
                                    $data['home_address'], $birthday, $sex, intval($data['phone_number']), intval($data['home_number']),
                                    $data['mail_address'], $data['teacher']);
    }
}