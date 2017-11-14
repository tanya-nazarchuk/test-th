<?php
namespace models;

use Yii;
use app\models\User;
use Codeception\Test\Unit;

class TransferFormTest extends Unit
{
    private $model;

    /**
     * Method runs before each test
     * @return null
     */
    protected function _before()
    {
        expect_that($user = User::findIdentity(1));
        expect_that(Yii::$app->user->login($user));
        expect_not(\Yii::$app->user->isGuest);
    }

    /**
     * @param array $attrib
     * @param callable $verify
     * @group TransferFormTest_testTransferMoneyToUser
     * @dataProvider providerTransferMoneyToUser
     */
    public function testTransferMoneyToUser(array $attrib, callable $verify)
    {
        $this->model = $this->getMockBuilder('app\models\TransferForm')
            ->setMethods(['performTransfer'])
            ->getMock();

        $this->model->expects($this->any())
            ->method('performTransfer')
            ->will($this->returnValue(true));

        $this->model->attributes = $attrib;

        $verify($this->model->transfer());
    }

    /**
     * @return array
     */
    public function providerTransferMoneyToUser()
    {
        return [
            [
                'attrib' => [
                    'username' => 'alex',
                    'amount' => +9.99,
                ],
                'verify' => function($actual) {
                    verify($actual)->true();
                }
            ],
            [
                'attrib' => [
                    'username' => 'user',
                    'amount' => +9.99,
                ],
                'verify' => function($actual) {
                    verify($actual)->true();
                }
            ],
            [
                'attrib' => [
                    'username' => 'alex',
                    'amount' => -9.99,
                ],
                'verify' => function($actual) {
                    verify($actual)->false();
                }
            ],
            [
                'attrib' => [],
                'verify' => function($actual) {
                    verify($actual)->false();
                }
            ],
            [
                'attrib' => [
                    'username' => 'tanya',
                    'amount' => 9.99,
                ],
                'verify' => function($actual) {
                    verify($actual)->false();
                }
            ],
        ];
    }
}
