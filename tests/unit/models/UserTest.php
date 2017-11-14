<?php
namespace models;

use app\models\User;
use Codeception\Test\Unit;

class UserTest extends Unit
{
    /**
     * @return null
     */
    public function testFindUserById()
    {
        expect_that($user = User::findIdentity(1));
        expect($user->username)->equals('tanya');

        expect_not(User::findIdentity(1000));
    }

    /**
     * @return null
     */
    public function testFindUserByUsername()
    {
        expect_that($user = User::findByUsername('tanya'));
        expect_not(User::findByUsername('not-tanya'));
    }

    /**
     * @param array $attrib
     * @param callable $verify
     * @group UserTest_TestCase_testChangeBalance
     * @dataProvider providerChangeBalance
     */
    public function testChangeBalance(array $attrib, callable $verify)
    {
        $userId = array_key_exists('id', $attrib) ? $attrib['id'] : 0;
        $amount = 9.99;

        /** @var \PHPUnit_Framework_MockObject_MockBuilder|User $userModel */
        $findModel = $this->getMockBuilder('app\models\User')
            ->setMethods(['save'])
            ->setConstructorArgs([$attrib])
            ->getMock();

        $findModel->expects($this->any())
            ->method('save')
            ->will($this->returnCallback(function () use ($attrib) {
                return array_key_exists('id', $attrib) ? $attrib['id'] > 0 : false;
            }));

        if ($attrib === []) {
            $findModel = null;
        }

        /** @var \PHPUnit_Framework_MockObject_MockBuilder|User $userModel */
        $userModel = $this->getMockBuilder('app\models\User')
            ->setMethods(['findById'])
            ->getMock();

        $userModel->expects($this->once())
            ->method('findById')
            ->will($this->returnValue($findModel));

        $verify($userModel->changeBalance($userId, $amount));
    }

    /**
     * @return array
     */
    public function providerChangeBalance()
    {
        return [
            [
                'attrib' => [
                    'id' => 1,
                ],
                'verify' => function ($actual) {
                    verify($actual)->true();
                },
            ],
            [
                'attrib' => [
                    'username' => 'tanya',
                    'id' => 0,
                ],
                'verify' => function ($actual) {
                    verify($actual)->false();
                },
            ],
            [
                'attrib' => [
                    'username' => 'tanya',
                    'id' => -1,
                ],
                'verify' => function ($actual) {
                    verify($actual)->false();
                },
            ],
            [
                'attrib' => [],
                'verify' => function ($actual) {
                    verify($actual)->false();
                },
            ],
        ];
    }
}
