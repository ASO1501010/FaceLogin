<?php
namespace App\Test\TestCase\Controller\Component;

use App\Controller\Component\S3ClientComponent;
use Cake\Controller\ComponentRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Controller\Component\S3ClientComponent Test Case
 */
class S3ClientComponentTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Controller\Component\S3ClientComponent
     */
    public $S3Client;

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $registry = new ComponentRegistry();
        $this->S3Client = new S3ClientComponent($registry);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->S3Client);

        parent::tearDown();
    }

    /**
     * Test initial setup
     *
     * @return void
     */
    public function testInitialization()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
