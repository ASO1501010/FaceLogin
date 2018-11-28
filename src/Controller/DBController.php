<?php
namespace App\Controller;

use Cake\ORM\TableRegistry;
use Cake\ORM\Query;
use Cake\ORM\Entity;
use Cake\ORM\Table;
use Cake\I18n\FrozenDate;
use \Exception;
use Cake\Log\Log;

class DBController extends AppController{
    public function initialize(){
        FrozenDate::setToStringFormat('yyyy/MM/dd');
	    $this->viewBuilder()->enableAutoLayout(false);
        date_default_timezone_set('Asia/Tokyo');

        $this->loadModel('School');
        $this->loadModel('Qualification');
        $this->loadModel('Schedule');
    }

    public function searchUserInfo($number){
        $res['base_info'] = $this->School->find('all', [
			'conditions'=>['number' => $number]
		]);
        foreach($res['base_info'] as $user){
		    $res['qualification_info'] = $this->Qualification->find('all',[
			    'conditions'=>['school_id'=>$user->school_id]
		    ]);
		    $res['schedule_info'] = $this->Schedule->find('all',[
			    'conditions'=>['school_id'=>$user->school_id]
		    ]);
        }

        return $res;
        //$data->order(['contributiondate'=>'DESC']);
        // header('Content-Type: application/json; charset=utf-8');
		// echo json_encode($res, JSON_UNESCAPED_UNICODE);
    }

    public function searchQualificationInfo($number){
        $this->Qualification = TableRegistry::get('Qualification');
		$qualification_info = $this->Qualification->find('all',[
			    'condition'=>['school_id'=>$number]
		]);
        return $qualification_info;
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

    public function addQualification($school_id, $name, $pass_date){
        if($this->request->is('post')){
            $data = array(
                'school_id'=>h($school_id),
                'qualification_name'=>h($name),
                'pass_date'=>$pass_date,
            );
            $this->Qualification->save($this->Qualification->newEntity($data));
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

	public function addSchedule($school_id, $company, $start, $end){
        if($this->request->is('post')){
            $data = array(
                'school_id'=>h($school_id),
                'company'=>h($company),
				// 'category'=>h($this->request->data['category']),
				// 'content'=>h($this->request->data['content']),
                'start_date'=>$start,
				'end_date'=>$end,
				// 'pass_flag'=>h($this->request->data['pass_flag']),
				// 'attendance_flag'=>h($this->request->data['attendance_flag'])
            );
            $this->Schedule->save($this->Schedule->newEntity($data));
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