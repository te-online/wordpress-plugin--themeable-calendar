<?php	
    /* Erstelle eine Instanz von WP_List_Table */
		class Termine_List_Table extends WP_List_Table {
			
			/* Constructor */
			function __construct() {
				parent::__construct(array(
				'singular' => 'wp_list_termine',
				'plural' => 'wp_list_termine',
				'ajax' => false
				));
				date_default_timezone_set( "Europe/Berlin" );
			}
			
			/* Spalte mit Checkboxen hinzufügen */
			function column_cb($item){
				return sprintf(
					'<input type="checkbox" name="%1$s[]" value="%2$s" />',
					/*$1%s*/ $this->_args['singular'],  //Let's simply repurpose the table's singular label ("movie")
					/*$2%s*/ $item->id                //The value of the checkbox should be the record's id
				);
			}
			
			/* Suche die Spalten für die Tabelle aus */
			function get_columns() {
				return $columns = array (
					'cb'        => '<input type="checkbox" />',
					'col_term_datum' => __('Datum'),
					'col_term_ohne_uhrzeit' => __('Uhrzeit beachten?'),
					'col_term_wichtig' => __('Hervorheben?'),
					'col_term_titel' => __('Termintitel'),
					'col_term_ort' => __('Ort'),
					'col_term_info' => __('Zusatzinformationen'),
					'col_term_keywords' => __('Stichwörter')
				);
			}
			
			/* Verknüpfe die Spalten mit den Datenbankspalten und mache sie sortierbar */
			function get_sortable_columns() {
				return $sortable = array (
					'col_term_datum' => array('datum', true),
					'col_term_ort' => array('ort', false),
					'col_term_titel' => array('titel', false),
					'col_term_info' => array('info', false),
					'col_term_keywords' => array('keywords', false),
					'col_term_ohne_uhrzeit' => array('ohne_uhrzeit', false),
					'col_term_wichtig' => array('wichtig', false)
				);				
			}
			
			/* Fügt die Massenbearbeitung hinzu */
			function get_bulk_actions() {
				return array('delete' => 'Löschen');
			}
			
			/* Für die Views */
			function get_views() {
				global $wpdb;
				
				// Füge "Alle" Menüpunkt hinzu
				$class = (!empty($_GET["year"]) && $_GET["year"] == "all") ? ' class="current"' : ''; 
				$url = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'];
				$copy = "";
				if(!empty($_GET["year"])) $copy = $_GET["year"];
				$_GET["year"] = "all";
				$url = $url . '?' . http_build_query ($_GET);
				$_GET["year"] = $copy;
				$numberof = $wpdb->get_results("SELECT COUNT(*) FROM ".$wpdb->prefix."termine", ARRAY_A);
				$viewarray["all"] = '<a href="'.$url.'"'.$class.'>Alle <span class="count">('.$numberof[0]["COUNT(*)"].')</span></a>';
				
				// Füge Jahre hinzu				
				$results = $wpdb->get_results("SELECT DISTINCT(YEAR(datum)) FROM ".$wpdb->prefix."termine ORDER BY datum DESC", ARRAY_A);
				$numberof = $wpdb->get_results("SELECT COUNT(datum) FROM ".$wpdb->prefix."termine GROUP BY YEAR(datum) ORDER BY datum DESC", ARRAY_A);
				$i = 0;
				foreach($results as $result) {
					$year = $result["(YEAR(datum))"];
					if(!empty($_GET["year"]) && $_GET["year"] == $year) {
						$class = ' class="current"';
					//} else if(empty($_GET["year"]) && $i == 0) {
					} else if(empty($_GET["year"]) && $year == date('Y')) {
						$class = ' class="current"';
					} else {
						$class = "";
					}
					$url = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'];
					$copy = $_GET["year"];
					$_GET["year"] = $year;
					$url = $url . '?' . http_build_query ($_GET);
					$_GET["year"] = $copy;  
					$viewarray[$year] = '<a href="'.$url.'"'.$class.'>'.$year.' <span class="count">('.$numberof[$i]["COUNT(datum)"].')</span></a>';
					$i++;
				}
								
				return $viewarray;
			}
			
			function process_bulk_action() {

				/* Massenbearbeitungfunktionen
					Wenn "Löschen" gewählt wurde, ist der Rückgabewert 0,
				*/
				if("delete" === $this->current_action()) {
					// Verarbeite Löschaktionen
					if(0 != $_POST["wp_list_termine"]) {
						$i = 0;	
						foreach($_POST["wp_list_termine"] as $deletees) {
							global $wpdb;
							$wpdb->query($wpdb->prepare('DELETE FROM '.$wpdb->prefix.'termine WHERE id = "%d"',$deletees));	
							$i++;	
						}
					}
					$_GET["recycled"] = $i;
				}		
			}
			
			/* Füge Elemente der Tabelle hinzu */
			function prepare_items() {

				// YEAR SELECTION DOESN'T WORK - PLEASE FIX!
				
				global $wpdb, $_wp_column_headers;

				$screen = get_current_screen();
				$search = "";
				if(!empty($_POST["s"])) $search = esc_sql(trim($_POST["s"]));
				
				/* Abfrage vorbereiten */
				$query = "SELECT * FROM " . $wpdb->prefix . "termine";
				
				/* Sortierparameter */
				$where = (!empty($_GET["year"]) && $_GET["year"] != "all") ? mysql_real_escape_string($_GET["year"]) : '';
				$orderby = !empty($_GET["orderby"]) ? mysql_real_escape_string($_GET["orderby"]) : 'datum';
				$order = !empty($_GET["order"]) ? mysql_real_escape_string($_GET["order"]) : 'DESC';
				if(!empty($where)) {
					$query .= ' WHERE YEAR(datum) = '.$where;
					if(!empty($search)) {
						$query .= ' AND titel LIKE "%%'.$search.'%%" OR ort LIKE "%%'.$search.'%%" OR info LIKE "%%'.$search.'%%" OR keywords LIKE "%%'.$search.'%%"';
					}
				}
				
				// Setze Standardview auf neuestes Jahr
				if(empty($_GET["year"])) {
					$results = $wpdb->get_results("SELECT DISTINCT(YEAR(datum)) FROM ".$wpdb->prefix."termine ORDER BY datum DESC", ARRAY_A);
					if(count($results) > 0) {
						//$where = $results[0]["(YEAR(datum))"];
						$where = date('Y');
						$query .= ' WHERE YEAR(datum) = '.$where;
						//$where = $results[0]["(YEAR(datum))"];
						if(!empty($search)) {
							$query .= ' AND titel LIKE "%%'.$search.'%%" OR ort LIKE "%%'.$search.'%%" OR info LIKE "%%'.$search.'%%" OR keywords LIKE "%%'.$search.'%%"';
						}
					} else {
						if(!empty($search)) {
							$query .= ' WHERE titel LIKE "%%'.$search.'%%" OR ort LIKE "%%'.$search.'%%" OR info LIKE "%%'.$search.'%%" OR keywords LIKE "%%'.$search.'%%"';
						}
					}
				}
				
				if(!empty($_GET["year"]) && empty($where)) {
					if(!empty($search)) {
						$query .= ' WHERE titel LIKE "%%'.$search.'%%" OR ort LIKE "%%'.$search.'%%" OR info LIKE "%%'.$search.'%%" OR keywords LIKE "%%'.$search.'%%"';
					}
				}
				
				if(!empty($orderby) && !empty($order)) {
					$query .= ' ORDER BY '.$orderby.' '.$order; 
				}
				
				/* Seitennavigation (Pagination) */
					// Elemente in der Tabelle 
					$totalitems = $wpdb->query($query); // liefert die Anzahl der Reihen zurück
					// Wie viele Elemente wollen wir pro Seite anzeigen?
					$perpage = 45;
					// Welche Seite ist dies?
					$paged = !empty($_GET["paged"]) ? mysql_real_escape_string($_GET["paged"]) : '';
					// Seitennnummer
					if(empty($paged) || !is_numeric($paged) || $paged<=0) {
						$paged = 1;
					}
					// Wie viele Seiten gibt es?
					$totalpages = ceil($totalitems/$perpage);
					// Abfrage manipulieren, sodass sie die Navigation miteinbezieht
					if(!empty($paged) && !empty($perpage)) {
						$offset = ($paged-1)*$perpage;
						$query .= ' LIMIT '.(int)$offset.','.(int)$perpage;
					}
					
				/* Die Seitennavigation bekannt machen */
					$this->set_pagination_args( array(
						'total_items' => $totalitems,
						'total_pages' => $totalpages,
						'per_page' => $perpage
					));
					
				/* Die Spalten bekannt machen */
					$columns = $this->get_columns();
					$hidden = array();
					$sortable = $this->get_sortable_columns();
					$this->_column_headers = array($columns,$hidden,$sortable);
					//$_wp_column_headers[$screen->id] = $columns;
					
					//wp_die($query);
					
				/* Die Elemente einsammeln */
					$this->items = $wpdb->get_results($query);
			}
						
			/* Spaltenfunktion für Spalte Erstellt */
			function column_col_term_datum($item) {
				$actions = array(
				'edit' => sprintf('<a href="#/" class="edit-button" item-id="'.$item->id.'" date-value="'.$item->datum.'" rel="nofollow">Bearbeiten</a>','edit'),
				'delete' => sprintf('<a href="?page=termine&amp;action=delete&amp;item_id='.$item->id.'" rel="nofollow">L&ouml;schen</a>',$_REQUEST['page'],'delete',$item->id)
				);
				
				$date = date_create_from_format('Y-m-d H:i:s',$item->datum);
				$monate = array("","Januar", "Februar", "März", "April", "Mai", "Juni", "Juli", "August", "September", "Oktober", "November","Dezember");
				$tage = array("Sonntag", "Montag", "Dienstag", "Mittwoch", "Donnerstag", "Freitag", "Samstag");
				$uhrzeit = ($item->{'ohne_uhrzeit'} == 0) ? date_format($date,' / H:i') : "";
				return "<span id='datum-anzeige-".$item->id."'>".$tage[date_format($date,'w')].", ".date_format($date,'j. ').$monate[date_format($date,'n')].date_format($date,' Y'). $uhrzeit . "</span>" . $this->row_actions($actions);
			}
			
			/* Spaltenfunktion für Spalte Ohne-Uhrzeit */
			function column_col_term_ohne_uhrzeit($item) {
				if($item->{'ohne_uhrzeit'} == 0) {
					return "Ja";
				} else {
					return "Nein";
				}
			}

			/* Spaltenfunktion für Spalte Wichtig */
			function column_col_term_wichtig($item) {
				if($item->wichtig == 1) {
					return "Ja";
				} else {
					return "Nein";
				}
			}
			
			/* Spaltenfunktion für Spalte Beginnt */
			function column_col_term_ort($item) {
				return $item->ort;
			}
			
			/* Spaltenfunktion für Spalte Läuft ab */
			function column_col_term_titel($item) {
				return $item->titel;
			}
			
			/* Spaltenfunktion für Spalte Läuft ab */
			function column_col_term_info($item) {
				return strip_tags($item->info);
			}
			
			/* Spaltenfunktion für Spalte Läuft ab */
			function column_col_term_keywords($item) {
				return $item->keywords;
			}
			
			/* Anzeige, wenn keine Elemente verfügbar sind */
			function no_items() {
				_e( 'Keine Termine vorhanden.' );
			}
	}
?>