<?php


class LoginCest
{
    public function ensureLoginWorks(AcceptanceTester $I)
    {
        $I->amOnPage('user/login');
        $I->see('Login', 'h1');

        $I->fillField('input[name="LoginForm[username]"]', 'user');
        $I->click('login-button');
        
        $I->seeCurrentUrlEquals('/');
        $I->see('Logout');
        $I->see('Users', 'h1');

        $I->seeInDatabase('user', ['username' => 'user']);
    }
}
