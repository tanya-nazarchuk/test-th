<?php


class TransactionCest
{
    public function ensureTransactionListPageOpensForLoggedUser(AcceptanceTester $I)
    {
        $this->login($I);

        $I->amGoingTo('transaction');
        $I->seeLink('Transactions', 'a');
    }

    public function ensureTransactionListPageDoesNotOpenForNotLoggedUser(AcceptanceTester $I)
    {
        $I->amOnPage('transaction');
        $I->dontSee('Transactions');
    }

    private function login(AcceptanceTester $I)
    {
        $I->amOnPage('user/login');
        $I->fillField('input[name="LoginForm[username]"]', 'user');
        $I->click('login-button');
    }
}
