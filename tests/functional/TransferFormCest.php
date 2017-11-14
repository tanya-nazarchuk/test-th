<?php


class TransferFormCest
{
    public function submitEmptyTransferForm(\FunctionalTester $I)
    {
        $this->login($I);

        $I->amOnRoute('transaction/create');
        $I->submitForm('#create-form', []);
        $I->expectTo('see validations errors');
        $I->see('Username cannot be blank.');
        $I->see('Amount cannot be blank.');
    }

    public function transferMoneyToExistingUser(\FunctionalTester $I)
    {
        $this->login($I);

        $I->amOnRoute('transaction/create');

        $I->submitForm('#create-form', [
            'TransferForm[username]' => 'alex',
            'TransferForm[amount]' => 9.99,
        ]);

        $I->seeInDatabase('transaction', [
            'amount' => 9.99,
            'receiver_id' => 2,
            'sender_id' => 1,
        ]);

        $I->seeInDatabase('user', [
            'username' => 'tanya',
            'balance' => 60.01,
        ]);

        $I->seeInDatabase('user', [
            'username' => 'alex',
            'balance' => -10.01,
        ]);
    }

    public function transferMoneyToNewUser(\FunctionalTester $I)
    {
        $this->login($I);

        $I->amOnRoute('transaction/create');

        $I->submitForm('#create-form', [
            'TransferForm[username]' => 'user',
            'TransferForm[amount]' => 9.99,
        ]);

        $I->seeInDatabase('transaction', [
            'amount' => 9.99,
            'receiver_id' => 8,
            'sender_id' => 1,
        ]);

        $I->seeInDatabase('user', [
            'username' => 'tanya',
            'balance' => 60.01,
        ]);

        $I->seeInDatabase('user', [
            'username' => 'user',
            'balance' => 9.99,
        ]);
    }

    public function attemptToTransferMoneyForNotLoggedUser(AcceptanceTester $I)
    {
        $I->amOnPage('transaction/create');
        $I->dontSee('Transfer money', 'h1');
    }

    private function login(FunctionalTester $I)
    {
        $I->amOnRoute('user/login');
        $I->amLoggedInAs(1);
    }
}
