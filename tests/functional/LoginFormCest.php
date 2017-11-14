<?php

class LoginFormCest
{
    public function _before(\FunctionalTester $I)
    {
        $I->amOnRoute('user/login');
    }

    public function openLoginPage(\FunctionalTester $I)
    {
        $I->see('Login', 'h1');
    }

    public function internalLoginById(\FunctionalTester $I)
    {
        $I->amLoggedInAs(1);
        $I->amOnRoute(Yii::$app->defaultRoute);
        $I->see('Logout (tanya)');
    }

    public function internalLoginByInstance(\FunctionalTester $I)
    {
        $I->amLoggedInAs(\app\models\User::findByUsername('tanya'));
        $I->amOnPage('/');
        $I->see('Logout (tanya)');
    }

    public function loginWithEmptyCredentials(\FunctionalTester $I)
    {
        $I->submitForm('#login-form', []);
        $I->expectTo('see validations errors');
        $I->see('Username cannot be blank.');
    }

    public function loginExistingUserSuccessfully(\FunctionalTester $I)
    {
        $I->submitForm('#login-form', [
            'LoginForm[username]' => 'tanya',
        ]);
        $I->see('Logout (tanya)');
        $I->dontSeeElement('form#login-form');
    }

    public function loginNewUserSuccessfully(\FunctionalTester $I)
    {
        $I->submitForm('#login-form', [
            'LoginForm[username]' => 'user',
        ]);
        $I->see('Logout (user)');
        $I->dontSeeElement('form#login-form');

        $I->seeInDatabase('user', ['username' => 'user']);
    }
}
