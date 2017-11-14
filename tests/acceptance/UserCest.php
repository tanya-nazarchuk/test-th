<?php


class UserCest
{
    public function ensureUserListPageOpens(AcceptanceTester $I)
    {
        $I->amOnPage('/');
        $I->see('Users', 'h1');
    }
}
