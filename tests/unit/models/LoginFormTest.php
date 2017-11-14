<?php

namespace tests\models;

use app\models\LoginForm;
use Codeception\Specify;
use Codeception\Test\Unit;

class LoginFormTest extends Unit
{
    private $model;

    /**
     * Method runs after each test
     * @return null
     */
    protected function _after()
    {
        \Yii::$app->user->logout();
    }

    /**
     * @return null
     */
    public function testLoginExistingUser()
    {
        $this->model = new LoginForm([
            'username' => 'tanya',
        ]);

        expect_that($this->model->login());
        expect_not(\Yii::$app->user->isGuest);
    }

    /**
     * @return null
     */
    public function testLoginNewUser()
    {
        /** @var \PHPUnit_Framework_MockObject_MockBuilder|LoginForm $loginForm */
        $this->model = $this->getMockBuilder('app\models\LoginForm')
            ->setMethods(['login'])
            ->getMock();

        $this->model->expects($this->once())
            ->method('login')
            ->will($this->returnValue(true));

        $this->model->username = 'user';

        verify($this->model->login())->true();
    }
}
