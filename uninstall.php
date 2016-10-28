<?php
	delete_option("te_online_termine_db_version");
	
	dropTables();
	
	function dropTables() {
		global $wpdb;
		$query = "DROP TABLE ".$wpdb->prefix."termine";
		$wpdb->query($query);
	}
?>