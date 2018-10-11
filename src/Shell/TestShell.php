<?php
namespace App\Shell;
 
use Cake\Console\Shell;
use Cake\Controller\Component;
use Cake\Controller\ComponentRegistry;
use App\Controller\Component\S3ClientComponent;
 
class TestShell extends Shell
{
    public function initialize()
    {
        parent::initialize();
        $this->S3Client = new S3ClientComponent(new ComponentRegistry());
        //$this->loadComponent('S3Client');
        $this->autoRender=false;

        $this->storage_path=STORAGE_PATH;
    }
 
    public function upload()
    {
        $file_name="t-Issey.jpg";
        //$store_dir="face/";

        $file_local_path=sprintf('%s%s', $this->storage_path, $file_name);
        //$file_store_path=sprintf('%s%s', $store_dir,$file_name);

        $result=$this->S3Client->putFile($file_local_path, $file_name);
    }
}