<?php
/**
 * @package     redSHOP
 * @subpackage  MultiCurrenciesSteps
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Frontend\Module;
use FrontEndProductManagerJoomla3Page;
use CheckoutOnFrontEnd;

/**
 * Class MultiCurrenciesSteps
 * @package Frontend\Module
 * @since 2.1.3
 */
class MultiCurrenciesSteps extends CheckoutOnFrontEnd
{
	/**
	 * @param $categoryName
	 * @param $productName
	 * @throws \Exception
	 * @since 2.1.3
	 */
	public function checkModuleCurrencies($categoryName, $productName)
	{
		$I = $this;
		$I->amOnPage(FrontEndProductManagerJoomla3Page::$URL);
		$I->waitForElement(FrontEndProductManagerJoomla3Page::$categoryDiv, 30);
		$I->verifyNotices(false, $this->checkForNotices(), FrontEndProductManagerJoomla3Page::$page);
		$productFrontEndManagerPage = new FrontEndProductManagerJoomla3Page;
		$I->click($productFrontEndManagerPage->productCategory($categoryName));
		$I->waitForElement(FrontEndProductManagerJoomla3Page::$productList, 30);
		$I->click($productFrontEndManagerPage->product($productName));
		$I->waitForElementVisible(FrontEndProductManagerJoomla3Page::$productPrice, 30);
		$I->waitForText(FrontEndProductManagerJoomla3Page::$priceDenmark, 30, FrontEndProductManagerJoomla3Page::$productPrice);

		$I->chooseOnSelect2(FrontEndProductManagerJoomla3Page::$curentChooseButton, FrontEndProductManagerJoomla3Page::$currentEuro);
		$I->click(FrontEndProductManagerJoomla3Page::$submitCurrent);
		$I->wait(1);
		$I->waitForElementVisible(FrontEndProductManagerJoomla3Page::$productPrice, 30);
		$I->waitForText(FrontEndProductManagerJoomla3Page::$priceOfEuro, 30, FrontEndProductManagerJoomla3Page::$productPrice);
		$I->see(FrontEndProductManagerJoomla3Page::$priceOfEuro);

		$I->chooseOnSelect2(FrontEndProductManagerJoomla3Page::$curentChooseButton, FrontEndProductManagerJoomla3Page::$currentKorean);
		$I->click(FrontEndProductManagerJoomla3Page::$submitCurrent);
		$I->wait(1);
		$I->waitForElementVisible(FrontEndProductManagerJoomla3Page::$productPrice, 30);
		$I->waitForText(FrontEndProductManagerJoomla3Page::$priceKorean, 30, FrontEndProductManagerJoomla3Page::$productPrice);
	}
}

