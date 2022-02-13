<?php

namespace ErrorReport2;

use SPFW\system\storage\SuperStorage;


/**
 * ErrorReport2 Model
 *
 * @package ErrorReport2
 * @version 2.1.2
 */
final class ErrorReport2 extends SuperStorage
{
	public static function databaseTableVersion() : ?int
	{
		$storage_engine = self::storageEngine();
		$database = $storage_engine->database();
		$database_name = $storage_engine->database()->getDatabaseConfig()->getDatabase();
		$table_name = $storage_engine->tableName();
		$query = 'SHOW TABLE STATUS FROM `' . $database_name . '` LIKE "' . $table_name . '"';

		$results = $database->query($query);
		if ($results = mysqli_fetch_object($results)) {
			$table_comment = $results->Comment;
			$comments = preg_split('/^\\\\,/', $table_comment);
			foreach ($comments as $comment) {
				if (strncmp($comment, 'er2-db-vers:', 12) === 0) {
					return (int)substr($comment, 12);
				}
			}
		}

		return null;
	}
}


?>