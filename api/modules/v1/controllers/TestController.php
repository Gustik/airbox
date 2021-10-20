<?php


namespace api\modules\v1\controllers;


class TestController extends \yii\rest\Controller
{
    function actionIndex()
    {
        return ['result' => 'test ok'];
    }
}