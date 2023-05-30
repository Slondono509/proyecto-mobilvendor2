<?php

	class Connection {

		const HOST	= '127.0.0.1';
		const DATABASE	= 'furniture';
		const USER	= 'root';
		const PASSWORD	= '';

		private static $pdo = null;

		private static function pdoInstance() {
			if (!self::$pdo) {
				try {
					self::$pdo = new PDO('mysql:host='.self::HOST.';dbname='.self::DATABASE.';charset=utf8', self::USER, self::PASSWORD);
				} catch (Exception $ex) {
					if (IS_DEVELOPMENT) {
						throw new DatabaseException(mb_convert_encoding($ex->getMessage(), 'UTF-8', 'WINDOWS-1251'));
					} else {
						throw new DatabaseException(__('Database connection failed'));
					}
				}
			}

			return self::$pdo;
		}

		static function getParams($sourceParams, $table = '') {
			if ($sourceParams !== null and !is_array($sourceParams)) $sourceParams = array('id' => $sourceParams);

			$where = '';
			$params = array();

			$index = 0;

			if (is_array($sourceParams)) {
				foreach ($sourceParams as $field => $value) {
					if ($where) $where .= ' and ';

					if ($value === null) {
						$where .= $field.' is null';
					} else
					if (is_array($value)) {
						$operator = $value[1];
						$value = $value[0];

						if ($value === null) {
							$where .= $field.' '.$operator.' null';
						} else
						if ($operator == 'in' or $operator == 'not in') {
							$where .= $field.' '.$operator.' '.$value;
						} else {
							$where .= $field.' '.$operator.' :p'.$index;
							$params['p'.$index] = $value;
							$index++;
						}
					} else {
						$where .= $field.'=:p'.$index;
						$params['p'.$index] = $value;
						$index++;
					}
				}
			}

			return array($where, $params);
		}

		static function getQuery($sql, $params = null, $limit = 0, $offset = 0) {
			if ($limit) $sql .= ' limit '.$limit;
			if ($offset) $sql .= ' offset '.$offset;

			return new Query(self::pdoInstance(), $sql, $params);
		}

		static function getRecord($tableOrSql, $params = null) {
			if (stripos($tableOrSql, ' ') === false) {
				$sql = 'select * from '.$tableOrSql;

				list($where, $params) = self::getParams($params, $tableOrSql);
				if ($where) $sql .= ' where '.$where;

				$sql .= ' limit 1';
			} else {
				$sql = $tableOrSql;

				if (strpos($sql, 'limit 1') === false) $sql .= ' limit 1';
			}

			$query = self::getQuery($sql, $params);

			return $query->rewind();
		}
	}
