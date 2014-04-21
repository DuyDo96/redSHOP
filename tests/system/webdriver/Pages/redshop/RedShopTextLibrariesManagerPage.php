<?php
/**
 * @package     RedShop
 * @subpackage  Model
 * @copyright   Copyright (C) 2012 - 2014 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
use SeleniumClient\By;
use SeleniumClient\SelectElement;
use SeleniumClient\WebDriver;
use SeleniumClient\WebDriverWait;
use SeleniumClient\DesiredCapabilities;
use SeleniumClient\WebElement;

/**
 * Page class for the back-end Text Library Redshop.
 *
 * @package     RedShop.Test
 * @subpackage  Webdriver
 * @since       1.0
 */
class RedShopTextLibrariesManagerPage extends AdminManagerPage
{
	/**
	 * XPath string used to uniquely identify this page
	 *
	 * @var    string
	 *
	 * @since    1.0
	 */
	protected $waitForXpath = "//h2[contains(text(),'Text Library Management')]";

	/**
	 * URL used to uniquely identify this page
	 *
	 * @var    string
	 * @since  3.0
	 */
	protected $url = 'administrator/index.php?option=com_redshop&view=textlibrary';

	/**
	 * Function to add Library
	 *
	 * @param   string  $tagName         Name of the Tag
	 * @param   string  $tagDescription  Description of the tag
	 * @param   string  $tagSection      Section for the new Tag
	 *
	 * @return RedShopTextLibrariesManagerPage
	 */
	public function addLibrary($tagName = 'Sample RedShop', $tagDescription = 'Testing RedShop', $tagSection = 'Category')
	{
		$elementObject = $this->driver;
		$elementObject->findElement(By::xPath("//a[@onclick=\"Joomla.submitbutton('add')\"]"))->click();
		$elementObject->waitForElementUntilIsPresent(By::xPath("//input[@id='text_name']"));
		$nameField = $elementObject->findElement(By::xPath("//input[@id='text_name']"));
		$nameField->clear();
		$nameField->sendKeys($tagName);
		$descField = $elementObject->findElement(By::xPath("//input[@id='text_desc']"));
		$descField->clear();
		$descField->sendKeys($tagDescription);
		$elementObject->findElement(By::xPath("//select[@id='section']"))->click();
		sleep(1);
		$elementObject->findElement(By::xPath("//select[@id='section']//option[@value='" . $tagSection . "']"))->click();
		$elementObject->findElement(By::xPath("//a[@onclick=\"Joomla.submitbutton('save')\"]"))->click();
		$elementObject->waitForElementUntilIsPresent(By::xPath("//li[contains(text(),'Text Library Detail Saved')]"), 10);
	}

	/**
	 * Function to edit a Library
	 *
	 * @param   string  $field        Field Which we are going to edit
	 * @param   string  $newValue     New Value of the Field
	 * @param   string  $description  Description of the Library
	 *
	 * @return RedShopTextLibrariesManagerPage
	 */
	public function editLibrary($field, $newValue, $description)
	{
		$elementObject = $this->driver;
		$searchField = $elementObject->findElement(By::xPath("//input[@id='filter']"));
		$searchField->clear();
		$searchField->sendKeys($description);
		$elementObject->findElement(By::xPath("//button[@onclick=\"this.form.submit();\"]"))->click();
		$elementObject->waitForElementUntilIsPresent(By::xPath("//tbody/tr/td[4][contains(text(),'" . $description . "')]"), 10);
		$row = $this->getRowNumber($description) - 1;
		$elementObject->waitForElementUntilIsPresent(By::xPath("//input[@id='cb" . $row . "']"), 10);
		$elementObject->findElement(By::xPath("//input[@id='cb" . $row . "']"))->click();
		$elementObject->findElement(By::xPath("//li[@id='toolbar-edit']/a"))->click();
		$elementObject->waitForElementUntilIsPresent(By::xPath("//input[@id='text_name']"), 10);

		switch ($field)
		{
			case "Name":
				$nameField = $elementObject->findElement(By::xPath("//input[@id='text_name']"));
				$nameField->clear();
				$nameField->sendKeys($newValue);
				break;
			case "Description":
				$descField = $elementObject->findElement(By::xPath("//input[@id='text_desc']"));
				$descField->clear();
				$descField->sendKeys($newValue);
				break;
			case "Section":
				$elementObject->findElement(By::xPath("//select[@id='section']"))->click();
				sleep(1);
				$elementObject->findElement(By::xPath("//select[@id='section']//option[@value='" . $newValue . "']"))->click();
				break;
		}

		$elementObject->findElement(By::xPath("//a[@onclick=\"Joomla.submitbutton('save')\"]"))->click();
		$elementObject->waitForElementUntilIsPresent(By::xPath("//input[@id='filter']"), 10);
	}

	/**
	 * Function to delete a Library
	 *
	 * @param   string  $description  Description of the library which is to be deleted
	 *
	 * @return RedShopTextLibrariesManagerPage
	 */
	public function deleteLibrary($description)
	{
		$elementObject = $this->driver;
		$searchField = $elementObject->findElement(By::xPath("//input[@id='filter']"));
		$searchField->clear();
		$searchField->sendKeys($description);
		$elementObject->findElement(By::xPath("//button[@onclick=\"this.form.submit();\"]"))->click();
		$elementObject->waitForElementUntilIsPresent(By::xPath("//tbody/tr/td[4][contains(text(),'" . $description . "')]"), 10);
		$row = $this->getRowNumber($description) - 1;
		$elementObject->waitForElementUntilIsPresent(By::xPath("//input[@id='cb" . $row . "']"), 10);
		$elementObject->findElement(By::xPath("//input[@id='cb" . $row . "']"))->click();
		$elementObject->findElement(By::xPath("//li[@id='toolbar-delete']/a"))->click();
	}
}
