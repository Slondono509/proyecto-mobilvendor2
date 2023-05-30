<?php

	class Query implements Iterator {
		var $_result = null;
		var $_row_index = -1;
		var $_row = null;

		function __construct($connection, $query, $params = null) {
				$this->_result = $connection->query($query);
			} else {
				$this->_result = $connection->prepare($query);
				$this->_result->execute($params);
			}

			$error = ($this->_result) ? $this->_result->errorInfo() : $connection->errorInfo();

			if (isset($error) and isset($error[2])) {
			}
	        }

		function __destruct() {
		}

		public function rewind() {
			if ($this->_row) $this->_row_index++;

			return $this->_row;
		}

		public function current() {
			return $this->_row;
		}

		public function key()  {
		}

		public function next()  {
			if ($this->_row) $this->_row_index++;

			return $this->_row;
		}

		public function valid() {
		}

		function toArray($firstPropertyAsIndex = false) {
			$result = array();
			$fields = null;
			$cnt = 0;

			foreach ($this as $row) {
					$cnt = count($fields);
				}

				if (!$firstPropertyAsIndex) {
				} else
				if ($cnt <= 2) {
				} else {

			return $result;
		}
	}