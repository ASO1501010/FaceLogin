<?php
namespace App\Controller;

use Cake\ORM\TableRegistry;
use Cake\I18n\FrozenDate;
use \Exception;
use Cake\Log\Log;

class InfoController extends AppController{
    public function initialize(){
        FrozenDate::setToStringFormat('yyyy/MM/dd');
	    $this->viewBuilder()->enableAutoLayout(false);
        date_default_timezone_set('Asia/Tokyo');

        $this->loadModel('School');
        $this->loadModel('Qualification');
        $this->loadModel('Schedule');
    }

    public function searchUserInfo($number){
        $res['user_info'] = $this->School->find('all', [
			'conditions'=>['number' => $number]
		]);
		// $res['qualification'] = $this->Qualifications->find('all',[
		// 	'condition'=>['school_id'=>$user_info['school_id']]
		// ]);
		// $res['schedule'] = $this->Schedules->find('all',[
		// 	'condition'=>['school_id'=>$user_info['school_id']]
		// ]);

        return $res;
        //$data->order(['contributiondate'=>'DESC']);
        // header('Content-Type: application/json; charset=utf-8');
		// echo json_encode($res, JSON_UNESCAPED_UNICODE);
    }

    public function addUser($number, $username, $level){
        $data = array(
			'number'=>h($number),
            'first_name'=>h($username),
            'level'=>h($level),
        );
        $this->School->save($this->School->newEntity($data));
    }

    public function editUserInfo(){
        if($this->request->is('post')){
           try{
               $entity = $this->Schools->get($this->request->data['id']);
               $this->Schools->patchEntity($entity, $this->request->data);
               $this->Schools->save($entity);
           }catch(Exception $e){
               Log::write('debug', $e->getMessage());
           }
        }
    }

    public function addQualification(){
        if($this->request->is('post')){
            $data = array(
                'qualification_name'=>h($this->request->data['qualification_name']),
                'pass_date'=>date("Y/m/d"),
            );
            $this->Qualifications->save($this->Qualifications->newEntity($data));
        }
    }

    public function editQualification(){
        if($this->request->is('post')){
           try{
               $entity = $this->Qualifications->get($this->request->data['school_id']);
               $this->Qualifications->patchEntity($entity, $this->request->data);
               $this->Qualifications->save($entity);
           }catch(Exception $e){
               Log::write('debug', $e->getMessage());
           }
        }
    }

	public function addSchedule(){
        if($this->request->is('post')){
            $data = array(
                'company'=>h($this->request->data['company']),
				'category'=>h($this->request->data['category']),
				'content'=>h($this->request->data['content']),
                'start_date'=>h($this->request->data['start_date']),
				'end_date'=>h($this->request->data['end_date']),
				'pass_flag'=>h($this->request->data['pass_flag']),
				'attendance_flag'=>h($this->request->data['attendance_flag'])
            );
            $this->Schedules->save($this->Schedules->newEntity($data));
        }
    }

    public function editSchedule(){
        if($this->request->is('post')){
           try{
               $entity = $this->Schedules->get($this->request->data['school_id']);
               $this->Schedules->patchEntity($entity, $this->request->data);
               $this->Schedules->save($entity);
           }catch(Exception $e){
               Log::write('debug', $e->getMessage());
           }
        }
    }


    /*public function delUser(){
        if($this->request->is('post')){
            try{
                $entity = $this->Schools->get($this->request->data['id']);
                $this->Schools->delete($entity);
            }catch(Exception $e){
                Log::write('debug', $e->getMessage());
            }
        }
    }*/
}