<?php

// Load WP_List_Table if not present
if(!class_exists('WP_List_Table')){
	require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

if(!class_exists('WP_Posts_List_Table')){
 	require_once( ABSPATH . 'wp-admin/includes/class-wp-posts-list-table.php' );
}

class Te_Calendar_Custom_List_Table extends WP_Posts_List_Table {
    // remove search box
    public function search_box( $text, $input_id ) { return false; }

    // Your custom list table is here
    public function display() {
    	?>
    	<div class="new termine-no-show">
    	<!-- <div class="new"> -->
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

			<div id="calendar"></div>

			<br /><br />
			<h2>Alle Datensätze</h2>
		<?php

			// parent::search_box();
      parent::display();
    }
}