<?php
namespace App\Controller;
use App\Controller\AppController;
/**
 * Pings Controller
 *
 *
 * @method \App\Model\Entity\Ping[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class TestController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function testApi()
    {
        // .jsonなしでもOKに
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
            'data' => $data,
            '_serialize' => ['data']
        ]);
    }
}