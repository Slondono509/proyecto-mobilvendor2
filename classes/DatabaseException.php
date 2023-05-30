<?php

	class DatabaseException extends Exception {
		const ERROR_UNIQUE_KEY  = 1001;
		const ERROR_FOREIGN_KEY = 1002;

		var $errorCode;

		function __construct($message, $errorCode = 0) {			parent::__construct($message);

			$this->errorCode = $errorCode;
		}

		function getErrorCode() {			return $this->errorCode;		}
	}
