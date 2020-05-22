<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace mpyii\base;

use yii\db\ActiveRecord;

/**
 * Description of MpModel
 *
 * @author dong
 */
class MpModel extends ActiveRecord{
    //put your code here
    public static function getDb()
    {
        $dbConfig = \Yii::$app->params['db'];
        $connection = new \yii\db\Connection([
            'dsn' => $dbConfig['dsn'],
            'username' => $dbConfig['username'],
            'password' => $dbConfig['password'],
            'charset' => $dbConfig['charset'],
            'tablePrefix' => $dbConfig['tablePrefix'],
            
            'enableSchemaCache' => true,
            // Duration of schema cache.
            'schemaCacheDuration' => 3600,

            // Name of the cache component used to store schema information
            'schemaCache' => 'cache',
        ]);
        
        $connection->open();

        return $connection;
    }
    
    /**
     * {@inheritdoc}
     * @return ActiveQuery the newly created [[ActiveQuery]] instance.
     */
    public static function findWithCid()
    {
        $obj = parent::find();
        $obj->andWhere(['cid' => 1]);
        return $obj;
    }
    

}
