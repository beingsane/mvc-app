<?php

class MySQL_PDOWrapper extends PDOWrapper
{
	protected function formatDsn($config)
	{
		return "mysql:host=".$config['host'].";dbname=".$config['name'];
	}
}	
