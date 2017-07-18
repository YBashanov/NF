<?php if ( ! defined('andromed')) exit(''); 

if ($dbTrue && $openboundary) {
	if ($db_adverse_table) {
		$db->create_tables_FromOutside($db_adverse_table);
	}
}
?>