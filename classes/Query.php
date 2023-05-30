<?php

	class Query implements Iterator {
		var $_result = null;
		var $_row_index = -1;
		var $_row = null;

		function __construct($connection, $query, $params = null) {			if ($params == null) {
				$this->_result = $connection->query($query);
			} else {
				$this->_result = $connection->prepare($query);
				$this->_result->execute($params);
			}

			$error = ($this->_result) ? $this->_result->errorInfo() : $connection->errorInfo();

			if (isset($error) and isset($error[2])) {				throw new DatabaseException(IS_DEVELOPMENT ? mb_convert_encoding($error[2], 'UTF-8', 'WINDOWS-1251')."\r\n".$query : __('Query to database failed'));
			}
	        }

		function __destruct() {			unset($this->_result);
		}

		public function rewind() {			$this->_row = $this->_result->fetch(PDO::FETCH_OBJ);
			if ($this->_row) $this->_row_index++;

			return $this->_row;
		}

		public function current() {
			return $this->_row;
		}

		public function key()  {			return $this->_row ? $this->_row->id : 0;
		}

		public function next()  {			$this->_row = $this->_result->fetch(PDO::FETCH_OBJ);
			if ($this->_row) $this->_row_index++;

			return $this->_row;
		}

		public function valid() {			return $this->_row != null;
		}

		function toArray($firstPropertyAsIndex = false) {
			$result = array();
			$fields = null;
			$cnt = 0;

			foreach ($this as $row) {				if ($fields == null) {					$fields = array_keys((array)$row);
					$cnt = count($fields);
				}

				if (!$firstPropertyAsIndex) {					$result[] = ($cnt > 1) ? $row : reset($row);
				} else
				if ($cnt <= 2) {					$result[reset($row)] = next($row);
				} else {					$result[reset($row)] = $row;				}			}

			return $result;
		}
	}
