<?php

namespace AppFoundations\CommunicationsBundle\Service;

use SendGrid;

class SendGridFactory {

	static $mockSendGrid;

	static function setMock($m){
		self::$mockSendGrid = $m;
	}

	static function createSendGrid($key) {
		if (!empty(self::$mockSendGrid)) {
			return self::$mockSendGrid;
		}
		return new SendGrid($key);
	}

}
