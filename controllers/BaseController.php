<?php

namespace app\controllers;

use yii\web\Controller;
use Yii;
use yii\web\Response;

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

    /**
     * @param $local
     * @return Response
     * @throws \yii\web\BadRequestHttpException
     */
    public function actionChangeLang($local)
    {
        $available_locales = ['es', 'ca', 'en' ];
        if (!in_array($local, $available_locales)) {
            throw new \yii\web\BadRequestHttpException();
        }

        $session = Yii::$app->session;
        !$session->isActive ? $session->open() : $session->close();
        $session->set('language', $local);
        $session->close();

        return isset($_SERVER['HTTP_REFERER']) ? $this->redirect($_SERVER['HTTP_REFERER']) : $this->redirect(Yii::$app->homeUrl);
    }
}