<?php
namespace App\Model\Table;

use Cake\Datasource\AwsS3Table;

class S3Table extends AwsS3Table {
    protected static $_connectionName = 's3';
}