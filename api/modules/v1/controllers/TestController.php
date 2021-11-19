<?php

namespace api\modules\v1\controllers;


use Yii;

class TestController extends \yii\rest\Controller
{
    public function behaviors()
    {
        return [
            'corsFilter' => [
                'class' => \yii\filters\Cors::class,
                'cors' => [
                    'Origin' => ['*'],
                ]
            ],
        ];
    }

    function actionIndex()
    {
        return ['result' => 'test ok1'];
    }

    public function actionValidatorStatus()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_RAW;
        return '<Response Result="1" Error="0" Message="Ok">
  <Session SessionId="00006367120084642600" SessionIdCreateTime="2018-08-30T04:40:46.4264613+03:00" TotalAmount="100">
    <StackedBanknotes>
      <Banknote Nominal="50" StackedTime="30.08.2018 5:06:57" />
      <Banknote Nominal="50" StackedTime="30.08.2018 5:07:03" />
    </StackedBanknotes>
  </Session>
  <Validator Status="0" IsStarted="False" IsError="False" IsBusy="False" Firmware="SM-RU1382AF" Port="COM4" />
  </Response>';
    }
}