<?php


class TransferCest
{
    public function ensureTransferMoneyWorksForLoggedUser(AcceptanceTester $I)
    {
        $this->login($I);

        $I->amOnPage('transaction/create');
        $I->see('Transfer money', 'h1');

        $I->submitForm('#create-form', [
            'TransferForm[username]' => 'tanya',
            'TransferForm[amount]' => '9.99',
        ]);

        $I->seeInDatabase('transaction', [
            'amount' => 9.99,
            'receiver_id' => 1,
            'sender_id' => 2,
        ]);
        
        $I->seeInDatabase('user', [
            'username' => 'tanya',
            'balance' => 79.99,
        ]);

        $I->seeInDatabase('user', [
            'username' => 'alex',
            'balance' => -29.99,
        ]);
    }

    public function ensureTransferMoneyDoesNotWorkForNotLoggedUser(AcceptanceTester $I)
    {
        $I->amOnPage('transaction/create');
        $I->dontSee('Transfer money', 'h1');
    }

    public function login(AcceptanceTester $I)
    {
        $I->amOnPage('user/login');
        $I->fillField('input[name="LoginForm[username]"]', 'alex');
        $I->click('login-button');
    }
}
