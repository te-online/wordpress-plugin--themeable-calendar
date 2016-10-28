<?php 
/*
Plugin Name: Termine
Plugin URI: http://te-online.net/
Description: Dieses Plugin führt eine Termindatenbank für regelmäßige und einmalige Termine. Anzeige im Frontend per Shortcode möglich.
Author: Thomas Ebert
Version: 1.1
Author URI: http://te-online.net/
*/

/* Todo */
/* WP_NONCE_FIELD to prevent multi send */
/* Feedback bei Bulk Löschen */
/* Take a detailed look at bulk actions, error messaging and forwarding after editing */


/* Installiere Tabelle in Datenbank */
/* Installiere die Tabelle in der Datenbank */
global $te_online_termine_db_version;
$te_online_termine_db_version = "1.1";

function te_online_termine_install() {
   global $wpdb;
   global $te_online_termine_db_version;

   $table_name = $wpdb->prefix . "termine";
      
   $sql = "CREATE TABLE IF NOT EXISTS `".$table_name."` (
	  `id` int(11) NOT NULL AUTO_INCREMENT,
	  `datum` datetime NOT NULL,
	  `titel` text NOT NULL,
	  `ort` text NOT NULL,
	  `info` longtext NOT NULL,
	  `keywords` text NOT NULL,
	  `ohne_uhrzeit` int(1) NOT NULL DEFAULT '0',
	  `wichtig` int(1) NOT NULL DEFAULT '0',
	  PRIMARY KEY (`id`)
	) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ;";

   require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
   dbDelta($sql);
 
   add_option("te_online_termine_db_version", $te_online_termine_db_version);
}

function te_online_termine_install_check($networkwide) {
	global $wpdb;
	if (function_exists('is_multisite') && is_multisite()) {
		if($networkwide) {
			$old_blog = $wpdb->blogid;
	        // Get all blog ids
	        $blogids = $wpdb->get_col("SELECT blog_id FROM $wpdb->blogs");
	        foreach ($blogids as $blog_id) {
	            switch_to_blog($blog_id);
	            te_online_termine_install();
	        }
	        switch_to_blog($old_blog);
	        return;
		}
    }
    te_online_termine_install();
}

function te_online_termine_install_data() {
   // Füge ggf. Beispieldaten hinzu
}

register_activation_hook(__FILE__,'te_online_termine_install_check');
register_activation_hook(__FILE__,'te_online_termine_install_data');

/* Gib Termin aus, wenn Shortcode eingesetzt wird */
function next_termine_func( $atts ) {
	extract( shortcode_atts( array(
		'max' => '9999',
		'view' => 'full',
		'mode' => 'now',
		'tags' => ''
	), $atts ) );

	$result = "";
	
	if(!is_numeric($max)) { $max = '9999'; }
	
	if($view != "full" && $view != "compact") { echo "<p><strong>Fehler beim Anzeigen der Termine</strong><br />Zur Verfügung für <em>View</em> stehen: <code>full</code> und <code>compact</code>"; }
	
	if($mode != "now" && $mode != "history" && $mode != "everything") { echo "<p><strong>Fehler beim Anzeigen der Termine</strong><br />Zur Verfügung für <em>Mode</em> stehen: <code>now</code>, <code>history</code> und <code>everything</code>"; }
	
	global $wpdb;
	
	wp_enqueue_style('terminwidget',plugins_url('terminwidget.css', __FILE__));
	
	// Suche Datensätze aus der Datenbank
	if($mode == "now") {
		$data = $wpdb->get_results('SELECT * FROM '.$wpdb->prefix.'termine WHERE datum >= CURRENT_DATE() ORDER BY datum','ARRAY_A');
	} else if($mode == "history") {
		$data = $wpdb->get_results('SELECT * FROM '.$wpdb->prefix.'termine WHERE datum < CURRENT_DATE() ORDER BY datum DESC','ARRAY_A');
	} else if($mode == "everything") {
		$data = $wpdb->get_results('SELECT * FROM '.$wpdb->prefix.'termine ORDER BY datum','ARRAY_A');
	}
	
	if($view == "compact") {
		// Kürze Array auf max
		$i = 1;
		$termine = array();
		if(count($data) > 0) {
			foreach($data as $datapackage) {
				if($i <= $max) {
					$termine[] = $datapackage;
				}
				$i++;
			} 
		}
		
		$show = false;

		if(count($termine) > 0) {
			$copy_tags = explode(",",$tags);
			foreach($termine as $termin) {
				$show = true;
				if($tags != "") {
					$show = false;
					if($termin["keywords"] != "") {
						$keys = explode(",",$termin["keywords"]);
						foreach($copy_tags as $key) {
							if(in_array($key,$keys)) {
								$show = true;
							}
						}
					}
				}
				if($show == true) {
					$datum = $termin["datum"];
					$datum = date_create($datum);
					$wochentage = explode(',','SO,MO,DI,MI,DO,FR,SA');
					$tag = $wochentage[date_format($datum,'w')];
					$ort = $termin["ort"];
					$titel = $termin["titel"];
					
					$uhrzeit = ($termin["ohne_uhrzeit"] == 0) ? '<span class="terminwidget-uhrzeit">('.date_format($datum,'H:i').' Uhr)</span>' : "";
					$important = ( $termin["wichtig"] == 1 ) ? ' important' : '';

					$result .= '<p class="widget_termin' . $important . '">';
					$result .= '<span class="terminwidget-tag"><a class="no-a-format" href="termine#'.$termin["id"].'">'.$tag.'</a></span><span class="terminwidget-datum"><a class="no-a-format" href="termine#'.$termin["id"].'">'.date_format($datum,'j.n.').'</a></span><span class="terminwidget-jahr"><a class="no-a-format" href="termine#'.$termin["id"].'">'.date_format($datum,'Y').'</a></span><span class="terminwidget-titel"><a class="no-a-format" href="termine#'.$termin["id"].'">'.$titel.'</a>'.$uhrzeit.'<span class="terminwidget-ort">'.$ort.'</span></span></p>';
					$result .= "<p class='terminwidget-clear'></p>";
				}
			} 
		}
		
		if($show == true) {
			$result .= "<p class='terminwidget-clear' style='margin-bottom: 10px;'></p>";
		}

	}
	else if($view == "full") {
		// Kürze Array auf max
		$i = 1;
		$termine = array();
		if(count($data) > 0) {
			$monate = array('Januar','Februar','März','April','Mai','Juni','Juli','August','September','Oktober','November','Dezember');
			foreach($data as $datapackage) {
				if($i <= $max || $max == "" || $max == "all") {
					$date = date_create($datapackage["datum"]);
					$termine[$monate[date_format($date,'n')-1]." ".date_format($date,'Y')][] = $datapackage;
				}
				$i++;
			} 
		}

		if(count($termine) > 0) {
			$monate = array_keys($termine);
			
			foreach($monate as $monat) {
				if($tags == "") { $result .= "<h2 class='tfull-h2'>".$monat."</h2>"; }
				$copy_tags = explode(",",$tags);
				foreach($termine[$monat] as $termin) {
					$show = true;
					if($tags != "") {
						$show = false;
						if($termin["keywords"] != "") {
							$keys = explode(",",$termin["keywords"]);
							foreach($copy_tags as $key) {
								if(in_array($key,$keys)) {
									$show = true;
								}
							}
						}
					}
					if($show == true) {
						$datum = $termin["datum"];
						$datum = date_create($datum);
						$wochentage = explode(',','SO,MO,DI,MI,DO,FR,SA');
						$tag = $wochentage[date_format($datum,'w')];
						$ort = $termin["ort"];
						$titel = $termin["titel"];
						$info = $termin["info"];
						$keywords = $termin["keywords"];
						$keywords = explode(',',$keywords);

						// Filter keywords for tags that don't exist – we don't want to display them
						if( count( $keywords ) > 0 ) {
							$newkeywords = array();
							foreach( $keywords as $keyword ) {
								if( term_exists( $keyword, 'post_tag') ) {
									$newkeywords[] = $keyword;
								}
							}
						}
						$keywords = $newkeywords;

						// $keywords[0] is often "", we have to check this
						$has_keywords = !( count($keywords) == 1 && empty( $keywords[0] ) ) && count( $keywords ) > 0;

						if($info != "") {
							$info = strip_tags($info)."<br />";
						}
						
						$extra = "";
						if( $has_keywords ) {
							$extra .= '<em>Artikel zum Thema ';
							$i = 0;
							foreach($keywords as $keyword) {
								$extra .= '<a href="'.get_bloginfo("url").'/tag/'.str_replace(' ','-',$keyword).'">'.$keyword.'</a>';
								if($i < (count($keywords)-1)) {
									$extra .= ', ';
								}
								$i++;
							}
							$extra .= '</em>';
						}
						
						$uhrzeit = ($termin["ohne_uhrzeit"] == 0) ? date_format($datum,'H:i') : "&nbsp;";
						$important = ( $termin["wichtig"] == 1 ) ? ' important' : '';

						$result .= '<a name="'.$termin["id"].'"></a>';
						$result .= '<div class="tfull_termin">';
						$result .= '<p class="tfull-tag">'.$tag.'</p><p class="tfull-datum">'.date_format($datum,'j.n.').'</p><p class="tfull-jahr">'.date_format($datum,'Y').'</p><p class="tfull-uhrzeit">'.$uhrzeit.'</p><p class="tfull-titel' . $important . '">'.$titel.'</p><p class="tfull-ort">'.$ort.'</p>';
						if($info != "") { 
							$result .= '<div class="tfull-special">'.wpautop($info).'</div>';
						}
						if( $has_keywords ) {
							$result .= '<p class="tfull-readon">'.$extra.'</p>';
						}
						$result .= '</div>';
						$result .= "<div class='tfull-clear'></div>";
						$result .= "<div class='tfull-separator'></div>";
					}
				}
			}
		}
	}
	
	if($result != "") return $result;
	return __("Keine Termine demnächst.");
}
add_shortcode( 'termine', 'next_termine_func' );


/* Adding Contextual help to explain certain things */
add_filter('contextual_help', 'termine_help', 10, 3);
function termine_help($contextual_help, $screen_id, $screen) {

	global $termine_hook;
	if ($screen_id == $termine_hook) {
		
		$contextual_help = '';
		
		$screen->add_help_tab( array( 
		   'id' => 'starting',            //unique id for the tab
		   'title' => 'Start',      //unique visible title for the tab
		   'content' => '<h2>Hilfe für Termine</h2><p>Hier findest Du Hilfe zur Bedienung des Termine-Plugins.</p>'  //actual help text
		) );
		
		$shortcodes_help = '<h2>Anzeige der Termine auf der Webseite mit Hilfe eines Shortcodes</h2>
		<h3>Der Shortcode</h3>
		<p>Die Anzeige der Termine auf der Webseite erfolgt mit Hilfe von so genannten Shortcodes, einer in WordPress integrierten Erweiterungfunktion. Auf diese Art und Weise ist es einfach, dynamische Inhalte an nahezu jeder Stelle der Webseite einzufügen.</p>
		<p>Du kannst den folgenden Shortcode bspw. in einem Beitrag, auf einer Seite oder auch in einem Widget verwenden.</p>
		<p><strong>Der Shortcode für die Anzeige der Termine lautet:</strong><br /><code>[termine]</code></p>
		<p>In diesem Modus werden die Standardparameter angewendet, sodass die Terminliste im Modus <em>Full</em> (vollständig) und <em>Now</em> (Gegenwart) mit einem Maximum von <em>9999</em> Terminen und ohne Einschränkung durch <em>Tags</em> angezeigt wird. Mehr zu den Parametern im nächsten Abschnitt.</p>
		<h3>Parameter</h3>
		<p>Weiterhin lässt sich die Anzeige durch verschiedene Parameter des Shortcodes für die Nutzung an verschiedenen Ausgabeorten konfigurieren.</p>
		<h4>View</h4>
		<p><em>Beispiel:</em> <code>[termine view="compact"]</code></p>
		<p><em>View</em> beschreibt die Art der Anzeige. Hierbei wird unterschieden zwischen <code>compact</code> und <code>full</code> <em>(Standard)</em>. <code>compact</code> zeigt eine schmale Liste, etwa für die Anzeige in Widgets, <code>full</code> zeigt die Liste in voller Breite des Artikels und ist damit für Inhaltsseiten geeignet.</p>
		<h4>Mode</h4>
		<p><em>Beispiel:</em> <code>[termine mode="history"]</code></p>
		<p><em>Mode</em> konfiguriert den Zeitraum der angezeigten Termine. Unterschieden wird zwischen <code>now</code> <em>(Standard)</em>, <code>history</code> und <code>everything</code>. <code>now</code> zeigt anstehende Termine und die Termine des gegenwärtigen Tages, <code>history</code> zeigt alle vergangenen Termine und <code>everything</code> zeigt alle Termine.</p>
		<h4>Max</h4>
		<p><em>Beispiel:</em> <code>[termine max="6"]</code></p>
		<p><em>Max</em> beschreibt, wie viele Termine (maximal - je nach Verfügbarkeit) angezeigt werden. Trage bitte nur numerische Werte ein.</p>
		<h4>Tags</h4>
		<p><em>Beispiel:</em> <code>[termine tags="Haus"] oder [termine tags="Thema,Zweites Thema,Hund"]</code></p>
		<p><em>Tags</em> lässt sich dafür nutzen, die Termine nach angegebenen Tags zu filtern. Wenn Du Termine zu einem bestimmten Thema mit einem bestimmten Tag versehen hast, kannst du diesen unter <em>Tags</em> angeben und es werden nur die Termine angezeigt, welche mit diesem Tag ausgestattet sind. Das geht auch mit mehreren Tags, diese einfach mit einem Komma trennen.</p>
		<h3>Anwendung der Parameter</h3>
		<p>Selbstverständlich lassen sich alle Parameter auch beliebig kombinieren.<br />
		<em>Beispiel:</em> <code>Diese wichtigen Termine hast du verpasst: [termine view="compact" max="10" mode="history" tags="wichtig"]</code></p>';
		
		$screen->add_help_tab( array( 
		   'id' => 'shortcodes',            //unique id for the tab
		   'title' => 'Anzeige der Termine',      //unique visible title for the tab
		   'content' => $shortcodes_help //actual help text
		) );
		
		$hinzu_help = '<h2>Hinzufügen von Terminen und Folgeterminen</h2>';
		
		$screen->add_help_tab( array( 
		   'id' => 'hinzu',            //unique id for the tab
		   'title' => 'Hinzufügen von Terminen',      //unique visible title for the tab
		   'content' => $hinzu_help //actual help text
		) );
		
		$verwalten_help = '<h2>Verwalten der Termine mit Hilfe der Liste</h2>';
		
		$screen->add_help_tab( array( 
		   'id' => 'verwalten',            //unique id for the tab
		   'title' => 'Verwalten mit Liste',      //unique visible title for the tab
		   'content' => $verwalten_help //actual help text
		) );
		
		$verwalten_helpB = '<h2>Verwalten der Termine mit Hilfe des Kalenders</h2>';
		
		$screen->add_help_tab( array( 
		   'id' => 'verwaltenB',            //unique id for the tab
		   'title' => 'Verwalten mit Kalender',      //unique visible title for the tab
		   'content' => $verwalten_helpB //actual help text
		) );
		
	}
	return false;
}

/* Adding Content to the contextual help */


/* Adding an admin options page to control events */
add_action('admin_menu', 'termine_menu');
function termine_menu() {
	global $termine_hook;
	$termine_hook = add_menu_page('Termine', 'Termine', 'edit_posts', 'termine', 'te_online_termine_options_page', plugin_dir_url( __FILE__ ).'icon.png');
}

function te_online_termine_options_page() {
	// Speichere wenn gefordert
	if(!empty($_POST["action"]) && $_POST["action"] == "speichern") {
		global $wpdb;
		$numbers = $_POST["numbers"];
		
		$i = 0;
		$newdata[$i]["titel"] = $_POST["titel"];
		$newdata[$i]["ort"] = $_POST["ort"];
		$newdata[$i]["infos"] = wpautop($_POST["infos"]);
		$newdata[$i]["keywords"] = $_POST["keywords"];
		$newdata[$i]["datum"] = $_POST["datum"];
		$newdata[$i]["ohneuhrzeit"] = (!empty($_POST["ohne_uhrzeit"]) && $_POST["ohne_uhrzeit"] == true) ? "1" : "0";
		
		/* Lädt die Daten aus den generierten Folgeterminen */
		if($numbers != "") {
			$numbers = explode(',',$numbers);
			foreach($numbers as $number) {
				$i = $number;
				$newdata[$i]["titel"] = $newdata[0]["titel"];
				$newdata[$i]["ort"] = (empty($_POST["ort-".$i])) ? $newdata[0]["ort"] : $_POST["ort-".$i];
				$newdata[$i]["infos"] = (empty($_POST["infos-".$i])) ? $newdata[0]["infos"] : wpautop($_POST["infos-".$i]);
				$newdata[$i]["keywords"] = $newdata[0]["keywords"];
				$newdata[$i]["datum"] = $_POST["datum-".$i];
				$newdata[$i]["ohneuhrzeit"] = ($_POST["ohne_uhrzeit-".$i] == true) ? "1" : "0";
			}
		}
		
		foreach($newdata as $data) {
			$wpdb->insert($wpdb->prefix.'termine', 
				array(
					"datum" 		=> $data["datum"], 
					"ort" 			=> stripslashes( $data["ort"] ), 
					"info" 			=> wpautop( stripslashes( $data["infos"] ) ), 
					"keywords" 		=> stripslashes( $data["keywords"] ), 
					"titel"			=> stripslashes( $data["titel"] ), 
					"ohne_uhrzeit" 	=> $data["ohneuhrzeit"]
				)
			);
		}
		
	}
	
	// Inline-edit-speichern
	if(!empty($_POST["action"]) && $_POST["action"] == "inline-edit") {
		global $wpdb;
		$wpdb->update($wpdb->prefix.'termine', 
			array( 
				"datum" 		=> $_POST["datum"], 
				"titel" 		=> stripslashes( $_POST["titel"] ), 
				"ort" 			=> stripslashes( $_POST["ort"] ), 
				"info"			=> wpautop( stripslashes( $_POST["info"] ) ), 
				"keywords" 		=> stripslashes( $_POST["keywords"] ), 
				"ohne_uhrzeit" 	=> $_POST["ohneuhrzeit"], 
				"wichtig" 		=> $_POST["wichtig"] 
			), 
			array( 
				"id" => $_POST["id"]
			)
		);
	}
	
	// Inline-duplicate speichern
	if(!empty($_POST["action"]) && $_POST["action"] == "inline-save") {
		global $wpdb;
		$wpdb->insert($wpdb->prefix.'termine', 
			array( 
				"datum" 		=> $_POST["datum"], 
				"titel" 		=> stripslashes( $_POST["titel"] ), 
				"ort" 			=> stripslashes( $_POST["ort"] ), 
				"info" 			=> wpautop( stripslashes( $_POST["info"] ) ), 
				"keywords" 		=> stripslashes( $_POST["keywords"] ), 
				"ohne_uhrzeit" 	=> $_POST["ohneuhrzeit"], 
				"wichtig" 		=> $_POST["wichtig"] 
			) 
		);
	}

	// Löschen von Einträgen
	if( !empty( $_GET["action"] ) && $_GET["action"] == "delete" ) {
		global $wpdb;
		$wpdb->query( $wpdb->prepare( 'DELETE FROM ' . $wpdb->prefix . 'termine WHERE id = "%d"', $_GET["item_id"] ) );
	}
	
	// Binde jQuery ein
	wp_enqueue_script('jQuery');
	
	// Datepicker Script and Style
	wp_enqueue_script( 'jquery-ui-datepicker' );
	wp_enqueue_style( 'jquery-ui-datepicker', 'http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.18/themes/smoothness/jquery-ui.css' );
	
	// DateTimePicker Extension
	wp_enqueue_script( 'jquery-ui-datetimepicker', plugins_url('jquery-ui-timepicker-addon.js', __FILE__) );
	
	// Plugin Stylesheet
	wp_enqueue_style('terminadmin',plugins_url('terminadmin.css', __FILE__));
	
	// Calendar Extension
	wp_enqueue_script('jquery-calendar', plugins_url('fullcalendar.min.js', __FILE__));
	wp_enqueue_style('jquery-calendar',plugins_url('fullcalendar.css', __FILE__));
	
	// We want dragable Calendar events
	wp_enqueue_script( 'jquery-ui-draggable' );
	?>		
	
	<script>
	jQuery(document).ready(function($) {
		// Fahre die "Neu" area zunächst ein
		$('.add').toggle(function() {
			$('.new').slideDown();
		},
		function() {
			$('.new').slideUp();
		});
		
		$.datepicker.regional['de'] = {clearText: 'löschen', clearStatus: 'aktuelles Datum löschen',
                closeText: 'schließen', closeStatus: 'ohne Änderungen schließen',
                prevText: '<zurück', prevStatus: 'letzten Monat zeigen',
                nextText: 'Vor>', nextStatus: 'nächsten Monat zeigen',
                currentText: 'heute', currentStatus: '',
                monthNames: ['Januar','Februar','März','April','Mai','Juni','Juli','August','September','Oktober','November','Dezember'],
                monthNamesShort: ['Jan','Feb','Mär','Apr','Mai','Jun','Jul','Aug','Sep','Okt','Nov','Dez'],
                monthStatus: 'anderen Monat anzeigen', yearStatus: 'anderes Jahr anzeigen',
                weekHeader: 'Wo', weekStatus: 'Woche des Monats',
                dayNames: ['Sonntag','Montag','Dienstag','Mittwoch','Donnerstag','Freitag','Samstag'],
                dayNamesShort: ['So','Mo','Di','Mi','Do','Fr','Sa'],
                dayNamesMin: ['So','Mo','Di','Mi','Do','Fr','Sa'],
                dayStatus: 'Setze DD als ersten Wochentag', dateStatus: 'Wähle D, M d',
                dateFormat: 'dd.mm.yy', firstDay: 1, 
                initStatus: 'Wähle ein Datum', isRTL: false};
        $.datepicker.setDefaults($.datepicker.regional['de']);
		
		$.timepicker.regional['de'] = {
		  timeOnlyTitle: 'Uhrzeit auswählen',
		  timeText: 'Zeit',
		  hourText: 'Stunde',
		  minuteText: 'Minute',
		  secondText: 'Sekunde',
		  currentText: 'Jetzt',
		  closeText: 'Auswählen',
		  ampm: false
		};
		$.timepicker.setDefaults($.timepicker.regional['de']);
		
		$('.datum').datetimepicker();
		$('.datum').datetimepicker("option", "dateFormat", "yy-mm-dd");
		$('.datum').datepicker("setDate", new Date());
		
		// Zeige das Datum schon beim Laden, damit es keine Überraschungen gibt, 
		// wenn man erst die Checkbox ohne_uhrzeit anklickt und dann das Datum auswählt
		var oDate = $('.datum').datetimepicker("getDate"); 
		renderDate(oDate,'#ohne_uhrzeit','#datum-lesbar',false);
		
		$('.datum').on("change", function() {
			if($(this).val() !=  "") {
				// Gib den Wochentag an
				var oDate = $(this).datetimepicker("getDate"); 
				var Wochentag = new Array("Sonntag", "Montag", "Dienstag", "Mittwoch", "Donnerstag", "Freitag", "Samstag");
				var Monat = new Array("Januar", "Februar", "März", "April", "Mai", "Juni", "Juli", "August", "September", "Oktober", "November","Dezember");
				$('.repeat-tag').html(Wochentag[oDate.getDay()]);
				$('.duplicate-termine').show();
				renderDate(oDate,'#ohne_uhrzeit','#datum-lesbar',false);
			}
		});
		
		// Zeige das Datum in einem lesbaren Format im Container "#datum-lesbar" an
		// Erwartet als Eingabe ("getDate") von DateTimePicker
		function renderDate(date,checkfield,destinationfield,expected) {
			var Wochentag = new Array("Sonntag", "Montag", "Dienstag", "Mittwoch",
						  "Donnerstag", "Freitag", "Samstag");
			var Monat = new Array("Januar", "Februar", "März", "April", "Mai", "Juni", 
							"Juli", "August", "September", "Oktober", "November","Dezember");
			var readable = Wochentag[date.getDay()]+', '+date.getDate()+'. '+Monat[date.getMonth()]+' '+date.getFullYear();
			if($(checkfield).prop('checked') == expected) {
				var hours = (date.getHours() < 10 ? '0' + date.getHours() : date.getHours());
				var minutes = (date.getMinutes() < 10 ? '0' + date.getMinutes() : date.getMinutes());
				readable += ' / '+hours+'.'+minutes+' Uhr';
			}
			$(destinationfield).html(readable);
		}
		
		$('#ohne_uhrzeit').on("change", function() {
			var oDate = $('.datum').datetimepicker("getDate"); 
			renderDate(oDate,'#ohne_uhrzeit','#datum-lesbar',false);
		});
		
		
		$('.delete-termin').live("click", function() {
			$(this).parent().parent().parent().parent().parent().fadeOut( function() {
				$(this).remove();
			});
		});
		
		$('#delete-all-button').on("click", function() {
			$('.duplicated-termine').html('');
			$(this).fadeOut();
		});
		
		function registerRendering(number) {
			$('#ohne_uhrzeit-'+number+', #datum-'+number).live("change", function() {
				renderDate($('#datum-'+number).datetimepicker("getDate"),'#ohne_uhrzeit-'+number,'#datum-lesbar-'+number,false);
			});
		}
		
		function strip_tags(input, allowed) {
		  allowed = (((allowed || "") + "").toLowerCase().match(/<[a-z][a-z0-9]*>/g) || []).join(''); // making sure the allowed arg is a string containing only tags in lowercase (<a><b><c>)
		  var tags = /<\/?([a-z][a-z0-9]*)\b[^>]*>/gi,
			commentsAndPhpTags = /<!--[\s\S]*?-->|<\?(?:php)?[\s\S]*?\?>/gi;
		  return input.replace(commentsAndPhpTags, '').replace(tags, function ($0, $1) {
			return allowed.indexOf('<' + $1.toLowerCase() + '>') > -1 ? $0 : '';
		  });
		}
		
		$('#duplicate-termine-button').on("click", function() {
			if($('#repeat-each-day-true').prop("checked") || $('#repeat-each-true').prop("checked")) {
				$('#ajax-loading-duplicate').css("visibility","visible");
				$('#delete-all-button').show();
				
				// Prepare Dates to add
				var oDate = $("#datum").datetimepicker("getDate");
				
				var times = parseInt($('#repeat-times').val());
				
				var dates = Array();
				
				if($('#repeat-each-true').prop("checked")) {
					// {Jede Woche} wiederholen
					var value = parseInt($('#repeat-each').val());
					var current = oDate;
					for(A = 0; A <= times-1; A++) {
						New = new Date(current.getFullYear(), current.getMonth(), (current.getDate() + (7 * (value))), current.getHours(), current.getMinutes());
						dates.push(New);
						current = New;
					}
				}
				
				if($('#repeat-each-day-true').prop("checked")) {
					// {Jeden 1.} wiederholen
					var value = parseInt($('#repeat-each-day').val());
					var current = oDate;
					for(A = 0; A <= times-1; A++) {
						var New = current;
						// Solange der Monat gleich ist, gehe eine Woche vor.
						while(current.getMonth() == New.getMonth()) {
							New = new Date(New.getFullYear(), New.getMonth(), (New.getDate() + 7), New.getHours(), New.getMinutes());
						}
						// Im neuen Monat suche den 1., 2., 3. oder 4. Tag raus
						if(value > 1) {
							New = new Date(New.getFullYear(), New.getMonth(), (New.getDate() + (7 * (value-1))), New.getHours(), New.getMinutes());
						}
						dates.push(New);
						current = New;
					}
				}
				
				$('.keep').live("click", function() {
					var itemid = $(this).attr("item-id");
					var shown = $(this).attr("shown");
					if(shown == "true") {
						$("#"+itemid).fadeOut();
						$(this).html("ändern");
						$(this).attr("shown","false");
						$("#"+itemid).val($("#"+itemid).attr("ocontent"));
					} else {
						$("#"+itemid).fadeIn();
						$(this).html("beibehalten");
						$(this).attr("shown","true");
					}
				});
				
				//dates.push(oDate);
				if(dates.length > 0) {
					var ort = $('#ort').val();
					var infos = $('#infos').val();
					for(I = 0; I <= dates.length-1; I++){
						var number = parseInt($('#lastnumber').val())+parseInt(1+I);
						$element = '<div class="duplicated-date" id="'+number+'"><table><tbody>';
						$element += '<tr>';
						$element += '		<td><label for="datum-'+number+'" class="termine-label">Datum</label></td>';
						var checked = ($('#ohne_uhrzeit').prop("checked") == true) ? "checked='checked' " : "";
						$element += '		<td><span id="datum-lesbar-'+number+'" style="font-weight: bold;"></span><br /><input type="checkbox" id="ohne_uhrzeit-'+number+'" name="ohne_uhrzeit-'+number+'" '+checked+'/> <label style="font-style: italic" for="ohne_uhrzeit-'+number+'">Uhrzeit nicht beachten</label><br /><input type="text" id="datum-'+number+'" autocomplete="off" value="" size="30" name="datum-'+number+'" class="datum" /> <a href="#/" class="delete-termin">Löschen</a></td>';
						$element += '	</tr>';
						$element += '	<tr>';
						$element += '		<td><label for="ort-'+number+'" class="termine-label">Ort</label></td>';
						$element += '		<td><a href="#/" class="keep" item-id="ort-'+number+'" shown="false">ändern</a> <input ocontent="'+strip_tags(ort)+'" class="termine-no-show termine-input" type="text" id="ort-'+number+'" value="'+ort+'" size="30" name="ort-'+number+'" /></td>';
						$element += '	</tr>';
						$element += '	<tr>';
						$element += '		<td><label for="infos-'+number+'" class="termine-label">Zusatzinformationen</label></td>';
						$element += '		<td><a href="#/" class="keep" item-id="infos-'+number+'" shown="false">ändern</a> <textarea ocontent="'+strip_tags(infos)+'" id="infos-'+number+'" name="infos-'+number+'" class="termine-no-show infos">'+infos+'</textarea></td>';
						$element += '	</tr>';
						$element += '</tbody></table>';
						$element += '</div>';
						$('.duplicated-termine').append($($element));
						$('#datum-'+number).datetimepicker();	
						$('#datum-'+number).datetimepicker("option", "dateFormat", "yy-mm-dd");
						$('#datum-'+number).datepicker("setDate", dates[I]);
						renderDate($('#datum-'+number).datetimepicker("getDate"),'#ohne_uhrzeit-'+number,'#datum-lesbar-'+number,false);
						registerRendering(number);
					}
					$('#lastnumber').val(number);
				}
				
				$('#speichern').on("click", function() {
					// Übergebe Ids der Inputs an HTML Form
					var entries = new Array();
					$('.duplicated-date').each(function(i,item) {
						entries.push($(this).attr("id"));
					});
					entries = entries.join(',');
					$('#numbers').val(entries);
					return true;
				});
				
				$('#ajax-loading-duplicate').fadeOut();
			}
		});
		
		$('.edit-button').live("click", function() {
			
			// Find the objects for this editing
			var id = $(this).attr("item-id");
			$datum = $(this).parent().parent().parent().parent().find('.col_term_datum #datum-anzeige-'+id);
			$rowactions = $datum.find('.row-actions');
			$rowactions.hide();
			$titel = $(this).parent().parent().parent().parent().find('.col_term_titel');
			$ort = $(this).parent().parent().parent().parent().find('.col_term_ort');
			$info = $(this).parent().parent().parent().parent().find('.col_term_info');
			$keywords = $(this).parent().parent().parent().parent().find('.col_term_keywords');
			$ohneuhrzeit = $(this).parent().parent().parent().parent().find('.col_term_ohne_uhrzeit');
			$wichtig = $(this).parent().parent().parent().parent().find('.col_term_wichtig');

			// Copy old contents if someone decides to undo
			var datum_alt = $datum.html();
			var titel_alt = $titel.html();
			var ort_alt = $ort.html();
			var info_alt = $info.html();
			var keywords_alt = $keywords.html();
			var ohneuhrzeit_alt = $ohneuhrzeit.html();
			var wichtig_alt = $wichtig.html();
			
			// Add inline-editing fields
			$datum.html('<input type="text" id="datum-edit-'+id+'" value="'+$(this).attr('date-value')+'" class="date-input" /><br /><input type="button" mode="normal" name="save" value="Speichern" class="button-primary inline-edit-save" save-id="'+id+'" /> <input type="button" name="cancel" value="Abbrechen" class="button-secondary inline-edit-cancel" cancel-id="'+id+'" /> <img alt="" id="ajax-loading-'+id+'" class="ajax-loading" src="<?php bloginfo('url'); ?>/wp-admin/images/wpspin_light.gif" style="vertical-align: middle;">');
			$datum.append($rowactions);
			$('#datum-edit-'+id).datetimepicker();
			$('#datum-edit-'+id).datetimepicker("option", "dateFormat", "yy-mm-dd");
			$('#datum-edit-'+id).datetimepicker("option", "timeFormat", "hh:mm");
			var datum = $(this).attr('date-value');
			$('#datum-edit-'+id).datetimepicker("setDate", datum.substr(0,datum.length-3));
			$titel.html($('<input type="text" id="titel-edit-'+id+'" class="termin-inline-input" />').val($titel.html())); 
			$ort.html($('<input type="text" id="ort-edit-'+id+'" class="termin-inline-input" />').val($ort.html()));
			$info.html($('<textarea id="info-edit-'+id+'" class="termin-inline-input"></textarea>').text($info.html()));
			$keywords.html($('<input type="text" id="keywords-edit-'+id+'" class="termin-inline-input" />').val($keywords.html()));
			var checked = ($ohneuhrzeit.html() == "Ja") ? 'checked="checked" ' : '';
			$ohneuhrzeit.html('<input type="checkbox" id="ohne_uhrzeit-edit-'+id+'" '+checked+'/>');
			checked = ( $wichtig.html() == "Ja" ) ? 'checked="checked" ' : '';
			$wichtig.html( '<input type="checkbox" id="wichtig-edit-' + id + '" ' + checked + '/>' );

			// Make it possible to undo
			$('.inline-edit-cancel').live("click", function() {
				$('#datum-edit-'+id).parent().html(datum_alt);
				$('#titel-edit-'+id).parent().html(titel_alt);
				$('#ort-edit-'+id).parent().html(ort_alt);
				$('#info-edit-'+id).parent().html(info_alt);
				$('#keywords-edit-'+id).parent().html(keywords_alt);
				$('#ohne_uhrzeit-edit-'+id).parent().html(ohneuhrzeit_alt);
				$('#wichtig-edit-'+id).parent().html(wichtig_alt);
			});
			
		});
		
		$('.inline-edit-save').live("click", function() {
			var id = $(this).attr("save-id");
			$('#ajax-loading-'+id).css("visibility",'visible');
			var newdatum = $('#datum-edit-'+id).val();
			var newtitel = $('#titel-edit-'+id).val();
			var newort = $('#ort-edit-'+id).val();
			var newinfo = $('#info-edit-'+id).val();
			var newkeywords = $('#keywords-edit-'+id).val();
			var newohneuhrzeit = ($('#ohne_uhrzeit-edit-'+id).prop("checked") == true) ? "0" : "1"
			var newwichtig = ($('#wichtig-edit-'+id).prop('checked') == true) ? "1" : "0";
			$editbutton = $(this);
			
			var action = ($(this).attr("mode") == "duplicate") ? "inline-save" : "inline-edit";
			
			$.ajax({
			type: "POST",
			url: "#",
			data: { action: action, id: id, datum : newdatum, ort: newort, titel: newtitel, info: newinfo, keywords: newkeywords, ohneuhrzeit: newohneuhrzeit, wichtig: newwichtig }
			}).done(function( msg ) {
				// If there is the edit field from the calendar view we want just saving and updating and so on
				if($('#calendar-edit').length) {
					$('#calendar-edit').slideUp(function() {
						$(this).html('');
					});
					$('#calendar').fullCalendar('refetchEvents');
				//  else if there is the list view we want to bring the normal display of the entry back
				} else {
					$editbutton.parent().find('.row-actions').show();
					$('#datum-edit-'+id).hide();
					$editbutton.hide();
					var oDate = $("#datum-edit-"+id).datetimepicker("getDate");
					renderDate(oDate,'#ohne_uhrzeit-edit-'+id,'#datum-anzeige-'+id,true);
					$('.edit-button[item-id='+id+']').attr("date-value",convertDate(oDate));
					$('#datum-edit-'+id).parent().html(newdatum+$('#datum-edit-'+id).parent().html());
					$('#titel-edit-'+id).parent().html(newtitel);
					$('#ort-edit-'+id).parent().html(newort);
					$('#info-edit-'+id).parent().html(newinfo);
					$('#keywords-edit-'+id).parent().html(newkeywords);
					newohneuhrzeit = (newohneuhrzeit == 1) ? "Nein" : "Ja";
					$('#ohne_uhrzeit-edit-'+id).parent().html(newohneuhrzeit);
					newwichtig = (newwichtig == 1) ? "Ja" : "Nein";
					$('#wichtig-edit-'+id).parent().html(newwichtig);
					$('#ajax-loading-'+id).hide();
				}
			});
		});
		
		<?php if(!empty($_GET["action"])) { ?>
			// Wenn per GET gelöscht wurde, wollen wir das nicht der URL haben.
			function getCleanUrl(tag,key,newtag,newkey) {
				var vars = [], hash;
				var hashes = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');
				var old = window.location.href.slice(0,window.location.href.indexOf('?')+1);
				var did_it = 0;
				for(var i = 0; i < hashes.length; i++)
				{
					hash = hashes[i].split('=');
					if(hash[0] == tag && hash[1] == key) {
						hash[0] = newtag;
						hash[1] = newkey;
						did_it = 1;
					}
					if(i != 0) { old += "&"; }
					old += hash[0]+"="+hash[1];
				}
				arr = new Array();
				arr.push(did_it);
				arr.push(old);
				return arr;
			}
			var recycled = getCleanUrl("action","delete","recycled","1");
			if(recycled[0] == 1) {
				window.location.replace(recycled[1]);
			}
		<?php } ?>

		var date = new Date();
		var d = date.getDate();
		var m = date.getMonth();
		var y = date.getFullYear();
		
		var localOptions = {
		buttonText: {
			today: 'Heute',
			month: 'Monat',
			day: 'Tag',
			week: 'Woche'
		},
		monthNames: ['Januar','Februar','März','April','Mai','Juni','Juli','August','September','Oktober','November','Dezember'],
		monthNamesShort: ['Jan','Feb','Mär','Apr','Mai','Jun','Jul','Aug','Sept','Okt','Nov','Dez'],
		dayNames: ['Sonntag','Montag','Dienstag','Mittwoch','Donnerstag','Freitag','Samstag'],
		dayNamesShort: ['So','Mo','Di','Mi','Do','Fr','Sa']
		}
		
		// If the calendar div exists we will add a refresh-update-message
		if($('.fc-header-left').length) {
			$('.fc-header-left').append('<span id="refreshing-calendar"><img alt="Loading" id="ajax-loading" class="ajax-loading" src="<?php bloginfo('url'); ?>/wp-admin/images/wpspin_light.gif" style="vertical-align: middle; visibility: visible;"> Aktualisiere...</span>');
		}
		
		// Add the functionality of the cancel-button
		$('.calendar-edit-cancel').live("click", function() {
			$('#calendar-edit').slideUp( function() {
				// disable editing
				$(this).html('');
				// Activating calendar
				changeCalendarState(true);
			});
		});
		
		// Refresh the human readable date
		$('.datum-edit, .ohne_uhrzeit-edit').live("change", function() {
			renderDate($('.datum-edit').datetimepicker("getDate"),".ohne_uhrzeit-edit",".calendar-edit-uhrzeit-lesbar",true);
		});
		
		// Deleting an event
		$('#calendar-delete-event').live("click", function() {
			var item_id = $(this).attr("item_id");
			$('#ajax-loading-'+item_id).css("visibility",'visible');
			$.ajax({
			type: "GET",
			url: "admin.php?page=termine",
			data: { action: "delete", item_id: item_id }
			}).done(function( msg ) {
				// Close editing and refresh calendar
				$('#calendar-edit').slideUp(function() {
						$(this).html('');
					});
				$('#calendar').fullCalendar('refetchEvents');
			});
		});
		
		$('#calendar').fullCalendar($.extend({
			editable: true,
			firstDay: 1,
			// Loading real events here will be the next challenge
			eventSources: [
				{
					url: 'admin.php?page=termine&feed-me=true',
					className: 'event-object'
				}
			],
			timeFormat: "H:mm",
			weekNumbers: false,
			eventClick: function(calEvent, jsEvent, view) {
				if($('#calendar-active').attr("active") == "true") {
					// Doing stuff if an event is clicked
					// We disable the calendar for editing
					changeCalendarState(false);
					// Sliding the Editing up and (newly filled) down againg with the neccessary fields
					$('#calendar-edit').slideUp(function() {
						$(this).html('');
						$(this).slideDown();
						var id = calEvent.id;
						var $edit = $('#calendar-edit'); 
						$edit.append('<h3>Termin bearbeiten</h3>');
						$edit.append('<p class="calendar-edit-container" style="width: 250px;"><span class="calendar-edit-uhrzeit-lesbar"></span><br /><label class="calendar-edit-label" for="datum-edit-'+id+'">Datum</label><br /><input type="text" id="datum-edit-'+id+'" value="'+calEvent.dateTime+'" class="date-input datum-edit" /></p>');
						$('#datum-edit-'+id).datetimepicker();
						$('#datum-edit-'+id).datetimepicker("option", "dateFormat", "yy-mm-dd");
						$('#datum-edit-'+id).datetimepicker("option", "timeFormat", "hh:mm");
						var datum = calEvent.dateTime;
						$('#datum-edit-'+id).datetimepicker("setDate", datum.substr(0,datum.length-3));
						var checked = (calEvent.ohneuhrzeit == "0") ? 'checked="checked" ' : '';
						$edit.append('<p class="calendar-edit-container"><label class="calendar-edit-label" for="ohne_uhrzeit-edit-'+id+'">Uhrzeit beachten?</label><br /><input type="checkbox" class="ohne_uhrzeit-edit" id="ohne_uhrzeit-edit-'+id+'" '+checked+'/></p>');
						checked = (calEvent.wichtig == "1") ? 'checked="checked" ' : '';

						// Format-Dummies
						var $container = $('<p></p>').addClass('calendar-edit-container');
						var $label = $('<label></label>').addClass('calendar-edit-label');

						$edit.append($container.clone()
							.append($label.clone().attr('for', 'wichtig-edit-'+id).text('Hervorheben?'))
							.append('<br />')
							.append($('<input type="checkbox" '+checked+'/>').addClass('wichtig-edit').attr('id', 'wichtig-edit-'+id))
						);

						$edit.append($container.clone()
							.append($label.clone().attr('for', 'titel-edit-'+id).text('Titel'))
							.append('<br />')
							.append($('<input type="text" />').addClass('termin-calendar-input').attr('id', 'titel-edit-'+id).val(calEvent.title))
						);

						$edit.append($container.clone()
							.append($label.clone().attr('for', 'ort-edit-'+id).text('Ort'))
							.append('<br />')
							.append($('<input type="text" />').addClass('termin-calendar-input').attr('id', 'ort-edit-'+id).val(calEvent.ort))
						);

						$edit.append($container.clone()
							.append($label.clone().attr('for', 'info-edit-'+id).text('Info'))
							.append('<br />')
							.append($('<textarea></textarea>').addClass('termin-calendar-input').attr('id', 'info-edit-'+id).text(calEvent.info))
						);
						
						$edit.append($container.clone()
							.append($label.clone().attr('for', 'keywords-edit-'+id).text('Keywords'))
							.append('<br />')
							.append($('<input type="text" />').addClass('termin-calendar-input').attr('id', 'keywords-edit-'+id).val(calEvent.keywords))
						);

						$edit.append('<p style="clear: both; height: 5px;"></p><input type="button" name="save" value="Speichern" mode="normal" class="button-primary inline-edit-save" save-id="'+id+'" /> <input type="button" name="save" value="Kopie speichern" class="button-primary inline-edit-save" mode="duplicate" save-id="'+id+'" /> <input type="button" name="cancel" value="Abbrechen" class="button-secondary calendar-edit-cancel" cancel-id="'+id+'" /> <img alt="" id="ajax-loading-'+id+'" class="ajax-loading" src="<?php bloginfo('url'); ?>/wp-admin/images/wpspin_light.gif" style="vertical-align: middle;"> <span class="delete"><a id="calendar-delete-event" item_id="'+id+'" href="#">Termin löschen</a></span>');
						
						$("html, body").animate({ scrollTop: 0 }, "slow");
						
						// Show date in human readable format
						renderDate($('#datum-edit-'+id).datetimepicker("getDate"),"#ohne_uhrzeit-edit-"+id,".calendar-edit-uhrzeit-lesbar",true);
					});
				} else {
					// Disabling the editing for the time of editing an event
					return false;
				}
						
			},
			eventDrop: function( calEvent, dayDelta, minuteDelta, allDay, revertFunc, jsEvent, ui, view ) { 
				// If the calendar is active
				if($('#calendar-active').attr("active") == "true") {
					// Disabling calendar for saving
					changeCalendarState(false);
					// Converting the date
					var newdatum = convertDate(calEvent.start);
					// Saving the delay after drop
					$.ajax({
					type: "POST",
					url: "#",
					data: { action: "inline-edit", id: calEvent.id, datum: newdatum, ort: calEvent.ort, titel: calEvent.title, info: calEvent.info, keywords: calEvent.keywords, ohneuhrzeit: calEvent.ohneuhrzeit }
					}).done(function( msg ) {
						// Make the calendar work again
						changeCalendarState(true);
					});
				} else {
					revertFunc();
				}
			},
			loading: function(bool) {
				if(bool) {
					// Wenn der Kalender geladen wird... (nichts für Ungeduldige...)
					$('.fc-header-left').append('<span id="refreshing-calendar"><img alt="Loading" id="ajax-loading" class="ajax-loading" src="<?php bloginfo('url'); ?>/wp-admin/images/wpspin_light.gif" style="vertical-align: middle; visibility: visible;"> Aktualisiere...</span>');
					changeCalendarState(false);
				} else {
					// Wenn alles fertig ist
					changeCalendarState(true);
					$('#refreshing-calendar').fadeOut(function(){$(this).remove();});
				}
			},
			initialRender: function() {
				$('.fc-header-left').append('<span id="refreshing-calendar"><img alt="Loading" id="ajax-loading" class="ajax-loading" src="<?php bloginfo('url'); ?>/wp-admin/images/wpspin_light.gif" style="vertical-align: middle; visibility: visible;"> Aktualisiere...</span>');
			},
			dayClick: function( date, allDay, jsEvent, view ) { 
				if(!$('.new').is(":visible")) {
					// Adding the current time
					var nowDate = new Date();
					date.setHours(nowDate.getHours());
					date.setMinutes(nowDate.getMinutes());
					newDatum = convertDate(date);
					// adding the date to the field
					$('#datum').val(newDatum);
					// Refreshing the human-readable date
					renderDate(date,"#ohne_uhrzeit","#datum-lesbar",false);
					// Showing the add-form
					$('.add').click();
				} else {
					return false;
				}
			},
			disableDragging: false,
			disableResizing: true,
			height: 700
		},localOptions));
		
		function changeCalendarState(onoff) {
			//onoff = onoff || true;
			if(onoff == true) {
				// We append the cursor
				$('.event-object').removeClass('event-object-disabled');
				// We make it look like it was working ;-)
				$('#calendar').removeClass('opaque');
				// Trying to make clear that the calender should work
				$('#calendar-active').attr("active", "true");
			} else {
				// We remove the cursor
				$('.event-object').addClass('event-object-disabled');
				// We make it look like it was not working
				$('#calendar').addClass('opaque');
				// Trying to make clear that the calender should not work
				$('#calendar-active').attr("active", "false");
			}
		}
		
		function convertDate(oDate) {
			oDate = oDate || new Date();
			var monate = Array("01","02","03","04","05","06","07","08","09","10","11","12");
			var date = (oDate.getDate() < 10) ? "0"+oDate.getDate() : oDate.getDate();
			var hours = (oDate.getHours() < 10) ? "0"+oDate.getHours() : oDate.getHours();
			var minutes = (oDate.getMinutes() < 10) ? "0"+oDate.getMinutes() : oDate.getMinutes();
			var newDate = oDate.getFullYear()+"-"+monate[oDate.getMonth()]+"-"+date+" "+hours+":"+minutes+":00";
			return newDate;
		}
		
	});
	</script>
	
	<div class="wrap">
		<h2><?php _e("Termine"); ?> <a class="add-new-h2 add" href="#/">Hinzufügen</a></h2>
		<?php if(!empty($_GET["recycled"])) { ?>
			<div class="updated below-h2" id="message"><p> <?php echo $_GET["recycled"]; ?> Element<?php if($_GET["recycled"] != 1) { echo "e"; } ?> gelöscht.</p></div>
		<?php } ?>
		<div class="new termine-no-show">
			<h3>Neuer Termin</h3>
			<form action="" method="post">
			<table class="new-termin"><tbody>
			<tr>
				<td><label for="titel" class="termine-label">Titel</label></td>
				<td><input type="text" autocomplete="off" id="titel" value="" name="titel" class="termine-input" /></td>
			</tr>
			<tr>
				<td><label for="ort" class="termine-label">Ort</label></td>
				<td><input type="text" id="ort" autocomplete="off" value="" name="ort" class="termine-input" /></td>
			</tr>
			<tr>
				<td><label for="infos" class="termine-label">Zusatzinformationen</label></td>
				<td><textarea id="infos" class="infos" autocomplete="off" name="infos"></textarea></td>
			</tr>
			<tr>
				<td><label for="keywords" class="termine-label">Stichwörter</label></td>
				<td><input type="text" autocomplete="off" id="keywords" value="" class="termine-input" name="keywords" /></td>
			</tr>
			<tr>
				<td><label for="datum" class="termine-label">Datum</label></td>
				<td><input type="text" id="datum" autocomplete="off" value="" name="datum" class="datum" /> <input type="checkbox" id="ohne_uhrzeit" name="ohne_uhrzeit" /> <label for="ohne_uhrzeit" class="termine-label">Uhrzeit nicht beachten</label></td>
			</tr>
			<tr>
				<td></td>
				<td><span id="datum-lesbar"></span></td>
			</tr>
			</tbody></table><br />
			<div class="duplicate-termine termine-no-show">
				<h3>Wiederholungen des Termins</h3>
				<input type="checkbox" name="repeat-each-true" id="repeat-each-true" />
				<select name="repeat-each" id="repeat-each">
					<option value="1">Jede Woche</option>
					<option value="2">Alle 2 Wochen</option>
					<option value="3">Alle 3 Wochen</option>
					<option value="4">Alle 4 Wochen</option>
					<option value="8">Alle 8 Wochen</option>
					<option value="12">Alle 12 Wochen</option>
				</select> wiederholen<br /><br />
				<input type="checkbox" name="repeat-each-day-true" id="repeat-each-day-true" />
				Jeden
				<select name="repeat-each-day" id="repeat-each-day">
					<option value="1">1.</option>
					<option value="2">2.</option>
					<option value="3">3.</option>
					<option value="4">4.</option>
				</select> <span class="repeat-tag"></span> im Monat wiederholen<br /><br />
				und zwar <input type="text" name="repeat-times" value="5" size="1" id="repeat-times" /> mal.<br /><br />
				<input type="button" value="Folgetermine hinzufügen" class="button" id="duplicate-termine-button" name="dupliacte-termine-button"> <input type="button" value="Alle löschen" class="button" id="delete-all-button" name="delete-all-button" style="display: none !important;"> <img alt="" id="ajax-loading-duplicate" class="ajax-loading" src="<?php bloginfo('url'); ?>/wp-admin/images/wpspin_light.gif" style="vertical-align: middle;">
				<div class="duplicated-termine"></div>
			</div>
			
            <?php wp_nonce_field(); ?>
            
			<input type="hidden" name="action" value="speichern" />
			<input type="hidden" name="lastnumber" value="0" id="lastnumber" />
			<input type="hidden" name="numbers" value="" id="numbers" />
			
			<br /><input type="submit" name="save" value="Speichern" class="button-primary" id="speichern"><br /><br />
			</form>
		</div>
	
	<?php 	$current_calendar = ( ( !empty($_GET["tab"]) && $_GET["tab"] == "calendar" ) || empty($_GET["tab"])) ? " nav-tab-active" : "";
			$current_list = ( !empty($_GET["tab"]) && $_GET["tab"] == "list" ) ? " nav-tab-active" : "";
	?>
	<h2 class="nav-tab-wrapper"><a class='nav-tab<?php echo $current_list; ?>' href='?page=termine&tab=list'>Liste</a><a class='nav-tab<?php echo $current_calendar; ?>' href='?page=termine&tab=calendar'>Kalender</a></h2>
	
	<?php if( !empty( $_GET["tab"] ) && $_GET["tab"] == "list" ) { ?>
		<form method="post" style="margin-top: 10px;">
        
        <input type="hidden" name="page" value="<?php echo $_REQUEST['page'] ?>" />
		<?php
		
		if(!class_exists('WP_List_Table')) {
			require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
		}
		
		require_once( plugin_basename('termine_list.php', __FILE__) );
							
		// Erstelle eine Instanz von Fragen_List_Table
		$wp_list_table = new Termine_List_Table();
		// Erledige Bulk-Actions
		$wp_list_table->process_bulk_action();
		// Bereite Elemente vor
		$wp_list_table->prepare_items();
		// Zeige Views
		$wp_list_table->views();
		// Füge Search-Box hinzu
		$wp_list_table->search_box('Suchen', 'termine-suche');
		// Zeige die Liste an
		$wp_list_table->display();
		?>         
          
        </form>
		
	<?php } else { ?>
	
    	<span id="calendar-active" active="true"></span>
    	<div id="calendar-edit"></div>
		<div id="calendar"></div>
	
	<?php } ?>
	
	</div>
	<?php
}


/* Creating a JSON Feed for the FullCalendar to read */
add_action('admin_init', 'termine_json_feed_admin_head');
function termine_json_feed_admin_head() {
	if(!empty($_GET["feed-me"]) && $_GET["feed-me"] == "true") {
		// Fetching the start and end time from the given url
		$start = $_GET["start"];
		$end = $_GET["end"];
		// Creating DateTime Objects from the timestamps
		$start = date_create('@'.$start);
		$end = date_create('@'.$end);
		// Establishing WordPress database object and fetching the data we need
		global $wpdb;
		$termine = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."termine WHERE datum >= '".date_format($start,'Y-m-d H:i:s')."' AND datum <= '".date_format($end,'Y-m-d H:i:s')."'");
		$i = 1;
		//echo "[\n";
		if(count($termine) > 0) {
			foreach($termine as $termin) {
				$date = date_create_from_format('Y-m-d H:i:s',$termin->datum);
				$allday = ($termin->{'ohne_uhrzeit'} == "0") ? false : true;
				$event_array[] = array(
					"id" => $termin->id,
					"title" => strip_tags($termin->titel),
					"start" => date_format($date,'Y-m-d')."T".date_format($date,'H:i:s'),
					"allDay" => $allday,
					"ort" => $termin->ort,
					"info" => strip_tags($termin->info),
					"keywords" => $termin->keywords,
					"ohneuhrzeit" => $termin->{'ohne_uhrzeit'},
					"dateTime" => $termin->datum,
					"wichtig" => $termin->wichtig
				);
			}
		}
		echo json_encode($event_array);
		exit;
	}
}

?>