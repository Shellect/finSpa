<?php

namespace app\controllers;


use engine\App;
use engine\Controller;
use engine\Response;

class MainController extends Controller
{
    public function actionIndex(): Response
    {
        return $this->render('index');
    }

    public function actionSignin(){
        return $this->render('index');
    }

    public function actionSignup(){
        return $this->render('index');
    }

}