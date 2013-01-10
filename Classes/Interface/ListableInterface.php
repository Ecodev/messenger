<?php

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2013 Fabien Udriot <fabien.udriot@gebruederheitz.de>, Gebruederheitz
 *
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

/**
 * @package messenger
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 *
 */
interface Tx_Messenger_Interface_ListableInterface {

	/**
	 * Get fields being displayed in the list.
	 *
	 * @return array
	 */
	public function getFields();

	/**
	 * Returns a set of records.
	 *
	 * @return mixed
	 */
	public function getRecords();

	/**
	 * Get data about a particular record.
	 *
	 * @param mixed $identifier an identifier for the record.
	 * @return mixed
	 */
	public function getRecord($identifier);

	/**
	 * Return recipient info according to an identifier. The returned array must look like:
	 * array('email' => 'recipient name');
	 *
	 * @param mixed $identifier an identifier for the record.
	 * @return mixed
	 */
	public function getRecipientInfo($identifier);
}
?>