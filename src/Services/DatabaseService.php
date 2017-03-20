<?php

namespace Beeldvoerders\Reset\Services;

use Illuminate\Support\Facades\DB;
use Spatie\DbDumper\Databases\MySql;

class DatabaseService
{
	/**
	 * Remove all data and tables from a database
	 *
	 * @return void
	 */
	public static function empty()
	{
		$tables = DB::select('SHOW TABLES');
        $tables = implode(',', array_map(function($table)
        {
            return array_first($table);
        }, $tables));

        if(empty($tables))
        {
            DB::beginTransaction();
            DB::statement('SET FOREIGN_KEY_CHECKS = 0');
            DB::statement("DROP TABLE $tables");
            DB::statement('SET FOREIGN_KEY_CHECKS = 1');
            DB::commit();
        }
	}

	/**
	 * Import a sql file to the database
	 *
	 * @param  string $file
	 * @return void
	 */
	public static function import( $file )
	{
		DB::unprepared(file_get_contents(storage_path($file)));
	}

	/**
	 * Create a database dump file
	 *
	 * @return string
	 */
	public static function export()
	{
		$credentials = array_get(config('database.connections'), config('database.default'));

		MySql::create()
		    ->setDbName($credentials['database'])
		    ->setUserName($credentials['username'])
		    ->setPassword($credentials['password'])
		    ->dumpToFile(storage_path('mysqldump.sql'));

		return storage_path('mysqldump.sql');
	}
}