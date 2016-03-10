<?php
/**
 * Created by PhpStorm.
 * User: mchurylov
 * Date: 10/15/14
 * Time: 12:49 PM
 */

namespace Blog\Controller;

use Framework\Controller\Controller;

class HelloController extends Controller
{

    public function indexAction()
    {
        return $this->render('index.html');
    }
}