<?php

namespace app\controllers;

use yii\web\Controller;
use Yii;

class BaseController extends Controller
{

    public function beforeAction($action)
    {
        if (!parent::beforeAction($action))
            return false;

        $session = Yii::$app->session;
        !$session->isActive ? $session->open() : $session->close();
        Yii::$app->language = $session->get('language');
        $session->close();

        return true;
    }
}