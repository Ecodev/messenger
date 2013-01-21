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
 *
 *
 * @package messenger
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 *
 */
class Tx_Messenger_Controller_BackendController extends Tx_Extbase_MVC_Controller_ActionController {

	/**
	 * @var Tx_Messenger_Interface_ListableInterface
	 */
	protected $listManager;

	/**
	 * @var Tx_Messenger_Domain_Repository_MessageTemplateRepository
	 */
	protected $messageTemplateRepository;

	/**
	 * @param Tx_Messenger_Domain_Repository_MessageTemplateRepository $messageTemplateRepository
	 * @return void
	 */
	public function injectMessageTemplateRepository(Tx_Messenger_Domain_Repository_MessageTemplateRepository $messageTemplateRepository) {
		$this->messageTemplateRepository = $messageTemplateRepository;
	}

	/**
	 * Initializes the controller before invoking an action method.
	 * Override this method to solve tasks which all actions have in
	 * common.
	 *
	 * @return void
	 */
	public function initializeAction() {

		$this->listManager = Tx_Messenger_ListManager_Factory::getInstance();

		/** @var $validator Tx_Messenger_Validator_TableStructureValidator */
		$validator = $this->objectManager->get('Tx_Messenger_Validator_TableStructureValidator');
		$validator->validate($this->listManager);
	}

	/**
	 * send a message
	 *
	 * @param int $messageTemplateUid
	 * @param string $recipientUid
	 * @return void
	 */
	public function sendMessageAction($messageTemplateUid = 0, $recipientUid = '') {

		$result = 'The message could not be sent!';
		if ($messageTemplateUid > 0) {

			$recipients = t3lib_div::trimExplode(',', $recipientUid);
			foreach ($recipients as $recipient) {
				$markers = $this->listManager->getRecord($recipient);
				$recipient = $this->listManager->getRecipientInfo($recipient);

				/** @var $message Tx_Messenger_Domain_Model_Message */
				$message = $this->objectManager->get('Tx_Messenger_Domain_Model_Message');
				$isSent = $message->setMessageTemplate($messageTemplateUid)
					->setRecipients($recipient)
					->setMarkers($markers)
					->send();

				if ($isSent) {
					$result = 'ok';
				} else {
					$result = 'The message could not be sent for recipient uid ' . $recipient;
					break;
				}
			}

		}
		return $result;
	}

	/**
	 * send a message for testing
	 *
	 * @param int $messageTemplateUid
	 * @param string $testEmail
	 * @return void
	 */
	public function sendMessageTestAction($messageTemplateUid = 0, $testEmail = '') {

		$result = 'The message could not be sent! Missing email?';
		if ($messageTemplateUid > 0 && $testEmail != '') {

			/** @var $message Tx_Messenger_Domain_Model_Message */
			$message = $this->objectManager->get('Tx_Messenger_Domain_Model_Message');
			$isSent = $message->setMessageTemplate($messageTemplateUid)
				->setRecipients($testEmail)
				->send();
			$result = $isSent ? 'ok' : 'The message could not be sent! Contact an administrator.';

			// save email address as preference
			Tx_Messenger_Utility_BeUserPreference::set('messenger_testing_email', $testEmail);
		}
		return $result;
	}

	/**
	 * Action list
	 *
	 * @return void
	 */
	public function indexAction() {

		$messageUid = Tx_Messenger_Utility_Configuration::getInstance()->get('messageUid');
		$messageTemplate = $this->messageTemplateRepository->findByUid($messageUid);

		$records = $this->listManager->getRecords();
		$this->view->assign('recipients', $records);
		$this->view->assign('message', $messageTemplate);
	}
}
?>