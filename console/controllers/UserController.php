<?php


namespace console\controllers;


use common\models\User;
use yii\console\Controller;

class UserController extends Controller
{
    public function actionCreateAdmin()
    {
        $user = new User();
        $user->username = 'admin';
        $user->email = 'gustav.kutugutov@gmail.com';
        $user->status =User::STATUS_ACTIVE;
        $user->setPassword('admin');
        $user->generateAuthKey();
        $user->save();
    }
}