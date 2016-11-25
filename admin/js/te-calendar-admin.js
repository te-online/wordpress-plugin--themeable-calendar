(function( $ ) {
	'use strict';

	/**
	 * All of the code for your admin-facing JavaScript source
	 * should reside in this file.
	 *
	 * Note: It has been assumed you will write jQuery code here, so the
	 * $ function reference has been prepared for usage within the scope
	 * of this function.
	 *
	 * This enables you to define handlers, for when the DOM is ready:
	 *
	 * $(function() {
	 *
	 * });
	 *
	 * When the window is loaded:
	 *
	 * $( window ).load(function() {
	 *
	 * });
	 *
	 * ...and/or other possibilities.
	 *
	 * Ideally, it is not considered best practise to attach more than a
	 * single DOM-ready or window-load handler for a particular page.
	 * Although scripts in the WordPress core, Plugins and Themes may be
	 * practising this, we should strive to set a better example in our own work.
	 */

	 jQuery(document).ready(function($) {
		// // Fahre die "Neu" area zunächst ein
		// $('.add').toggle(function() {
		// 	$('.new').slideDown();
		// },
		// function() {
		// 	$('.new').slideUp();
		// });

		// $.datepicker.regional['de'] = {clearText: 'löschen', clearStatus: 'aktuelles Datum löschen',
  //               closeText: 'schließen', closeStatus: 'ohne Änderungen schließen',
  //               prevText: '<zurück', prevStatus: 'letzten Monat zeigen',
  //               nextText: 'Vor>', nextStatus: 'nächsten Monat zeigen',
  //               currentText: 'heute', currentStatus: '',
  //               monthNames: ['Januar','Februar','März','April','Mai','Juni','Juli','August','September','Oktober','November','Dezember'],
  //               monthNamesShort: ['Jan','Feb','Mär','Apr','Mai','Jun','Jul','Aug','Sep','Okt','Nov','Dez'],
  //               monthStatus: 'anderen Monat anzeigen', yearStatus: 'anderes Jahr anzeigen',
  //               weekHeader: 'Wo', weekStatus: 'Woche des Monats',
  //               dayNames: ['Sonntag','Montag','Dienstag','Mittwoch','Donnerstag','Freitag','Samstag'],
  //               dayNamesShort: ['So','Mo','Di','Mi','Do','Fr','Sa'],
  //               dayNamesMin: ['So','Mo','Di','Mi','Do','Fr','Sa'],
  //               dayStatus: 'Setze DD als ersten Wochentag', dateStatus: 'Wähle D, M d',
  //               dateFormat: 'dd.mm.yy', firstDay: 1,
  //               initStatus: 'Wähle ein Datum', isRTL: false};
  //       $.datepicker.setDefaults($.datepicker.regional['de']);

		// $.timepicker.regional['de'] = {
		//   timeOnlyTitle: 'Uhrzeit auswählen',
		//   timeText: 'Zeit',
		//   hourText: 'Stunde',
		//   minuteText: 'Minute',
		//   secondText: 'Sekunde',
		//   currentText: 'Jetzt',
		//   closeText: 'Auswählen',
		//   ampm: false
		// };
		// $.timepicker.setDefaults($.timepicker.regional['de']);

		// $('.datum').datetimepicker();
		// $('.datum').datetimepicker("option", "dateFormat", "yy-mm-dd");
		// $('.datum').datepicker("setDate", new Date());

		// // Zeige das Datum schon beim Laden, damit es keine Überraschungen gibt,
		// // wenn man erst die Checkbox ohne_uhrzeit anklickt und dann das Datum auswählt
		// var oDate = $('.datum').datetimepicker("getDate");
		// renderDate(oDate,'#ohne_uhrzeit','#datum-lesbar',false);

		// $('.datum').on("change", function() {
		// 	if($(this).val() !=  "") {
		// 		// Gib den Wochentag an
		// 		var oDate = $(this).datetimepicker("getDate");
		// 		var Wochentag = new Array("Sonntag", "Montag", "Dienstag", "Mittwoch", "Donnerstag", "Freitag", "Samstag");
		// 		var Monat = new Array("Januar", "Februar", "März", "April", "Mai", "Juni", "Juli", "August", "September", "Oktober", "November","Dezember");
		// 		$('.repeat-tag').html(Wochentag[oDate.getDay()]);
		// 		$('.duplicate-termine').show();
		// 		renderDate(oDate,'#ohne_uhrzeit','#datum-lesbar',false);
		// 	}
		// });

		// Zeige das Datum in einem lesbaren Format im Container "#datum-lesbar" an
		// Erwartet als Eingabe ("getDate") von DateTimePicker
		// function renderDate(date,checkfield,destinationfield,expected) {
		// 	var Wochentag = new Array("Sonntag", "Montag", "Dienstag", "Mittwoch",
		// 				  "Donnerstag", "Freitag", "Samstag");
		// 	var Monat = new Array("Januar", "Februar", "März", "April", "Mai", "Juni",
		// 					"Juli", "August", "September", "Oktober", "November","Dezember");
		// 	var readable = Wochentag[date.getDay()]+', '+date.getDate()+'. '+Monat[date.getMonth()]+' '+date.getFullYear();
		// 	if($(checkfield).prop('checked') == expected) {
		// 		var hours = (date.getHours() < 10 ? '0' + date.getHours() : date.getHours());
		// 		var minutes = (date.getMinutes() < 10 ? '0' + date.getMinutes() : date.getMinutes());
		// 		readable += ' / '+hours+'.'+minutes+' Uhr';
		// 	}
		// 	$(destinationfield).html(readable);
		// }

		// $('#ohne_uhrzeit').on("change", function() {
		// 	var oDate = $('.datum').datetimepicker("getDate");
		// 	renderDate(oDate,'#ohne_uhrzeit','#datum-lesbar',false);
		// });


		// $('.delete-termin').live("click", function() {
		// 	$(this).parent().parent().parent().parent().parent().fadeOut( function() {
		// 		$(this).remove();
		// 	});
		// });

		// $('#delete-all-button').on("click", function() {
		// 	$('.duplicated-termine').html('');
		// 	$(this).fadeOut();
		// });

		// function registerRendering(number) {
		// 	$('#ohne_uhrzeit-'+number+', #datum-'+number).live("change", function() {
		// 		renderDate($('#datum-'+number).datetimepicker("getDate"),'#ohne_uhrzeit-'+number,'#datum-lesbar-'+number,false);
		// 	});
		// }

		// function strip_tags(input, allowed) {
		//   allowed = (((allowed || "") + "").toLowerCase().match(/<[a-z][a-z0-9]*>/g) || []).join(''); // making sure the allowed arg is a string containing only tags in lowercase (<a><b><c>)
		//   var tags = /<\/?([a-z][a-z0-9]*)\b[^>]*>/gi,
		// 	commentsAndPhpTags = /<!--[\s\S]*?-->|<\?(?:php)?[\s\S]*?\?>/gi;
		//   return input.replace(commentsAndPhpTags, '').replace(tags, function ($0, $1) {
		// 	return allowed.indexOf('<' + $1.toLowerCase() + '>') > -1 ? $0 : '';
		//   });
		// }

		// $('#duplicate-termine-button').on("click", function() {
		// 	if($('#repeat-each-day-true').prop("checked") || $('#repeat-each-true').prop("checked")) {
		// 		$('#ajax-loading-duplicate').css("visibility","visible");
		// 		$('#delete-all-button').show();

		// 		// Prepare Dates to add
		// 		var oDate = $("#datum").datetimepicker("getDate");

		// 		var times = parseInt($('#repeat-times').val());

		// 		var dates = Array();

		// 		if($('#repeat-each-true').prop("checked")) {
		// 			// {Jede Woche} wiederholen
		// 			var value = parseInt($('#repeat-each').val());
		// 			var current = oDate;
		// 			for(A = 0; A <= times-1; A++) {
		// 				New = new Date(current.getFullYear(), current.getMonth(), (current.getDate() + (7 * (value))), current.getHours(), current.getMinutes());
		// 				dates.push(New);
		// 				current = New;
		// 			}
		// 		}

		// 		if($('#repeat-each-day-true').prop("checked")) {
		// 			// {Jeden 1.} wiederholen
		// 			var value = parseInt($('#repeat-each-day').val());
		// 			var current = oDate;
		// 			for(A = 0; A <= times-1; A++) {
		// 				var New = current;
		// 				// Solange der Monat gleich ist, gehe eine Woche vor.
		// 				while(current.getMonth() == New.getMonth()) {
		// 					New = new Date(New.getFullYear(), New.getMonth(), (New.getDate() + 7), New.getHours(), New.getMinutes());
		// 				}
		// 				// Im neuen Monat suche den 1., 2., 3. oder 4. Tag raus
		// 				if(value > 1) {
		// 					New = new Date(New.getFullYear(), New.getMonth(), (New.getDate() + (7 * (value-1))), New.getHours(), New.getMinutes());
		// 				}
		// 				dates.push(New);
		// 				current = New;
		// 			}
		// 		}

		// 		$('.keep').live("click", function() {
		// 			var itemid = $(this).attr("item-id");
		// 			var shown = $(this).attr("shown");
		// 			if(shown == "true") {
		// 				$("#"+itemid).fadeOut();
		// 				$(this).html("ändern");
		// 				$(this).attr("shown","false");
		// 				$("#"+itemid).val($("#"+itemid).attr("ocontent"));
		// 			} else {
		// 				$("#"+itemid).fadeIn();
		// 				$(this).html("beibehalten");
		// 				$(this).attr("shown","true");
		// 			}
		// 		});

		// 		//dates.push(oDate);
		// 		if(dates.length > 0) {
		// 			var ort = $('#ort').val();
		// 			var infos = $('#infos').val();
		// 			for(I = 0; I <= dates.length-1; I++){
		// 				var number = parseInt($('#lastnumber').val())+parseInt(1+I);
		// 				$element = '<div class="duplicated-date" id="'+number+'"><table><tbody>';
		// 				$element += '<tr>';
		// 				$element += '		<td><label for="datum-'+number+'" class="termine-label">Datum</label></td>';
		// 				var checked = ($('#ohne_uhrzeit').prop("checked") == true) ? "checked='checked' " : "";
		// 				$element += '		<td><span id="datum-lesbar-'+number+'" style="font-weight: bold;"></span><br /><input type="checkbox" id="ohne_uhrzeit-'+number+'" name="ohne_uhrzeit-'+number+'" '+checked+'/> <label style="font-style: italic" for="ohne_uhrzeit-'+number+'">Uhrzeit nicht beachten</label><br /><input type="text" id="datum-'+number+'" autocomplete="off" value="" size="30" name="datum-'+number+'" class="datum" /> <a href="#/" class="delete-termin">Löschen</a></td>';
		// 				$element += '	</tr>';
		// 				$element += '	<tr>';
		// 				$element += '		<td><label for="ort-'+number+'" class="termine-label">Ort</label></td>';
		// 				$element += '		<td><a href="#/" class="keep" item-id="ort-'+number+'" shown="false">ändern</a> <input ocontent="'+strip_tags(ort)+'" class="termine-no-show termine-input" type="text" id="ort-'+number+'" value="'+ort+'" size="30" name="ort-'+number+'" /></td>';
		// 				$element += '	</tr>';
		// 				$element += '	<tr>';
		// 				$element += '		<td><label for="infos-'+number+'" class="termine-label">Zusatzinformationen</label></td>';
		// 				$element += '		<td><a href="#/" class="keep" item-id="infos-'+number+'" shown="false">ändern</a> <textarea ocontent="'+strip_tags(infos)+'" id="infos-'+number+'" name="infos-'+number+'" class="termine-no-show infos">'+infos+'</textarea></td>';
		// 				$element += '	</tr>';
		// 				$element += '</tbody></table>';
		// 				$element += '</div>';
		// 				$('.duplicated-termine').append($($element));
		// 				$('#datum-'+number).datetimepicker();
		// 				$('#datum-'+number).datetimepicker("option", "dateFormat", "yy-mm-dd");
		// 				$('#datum-'+number).datepicker("setDate", dates[I]);
		// 				renderDate($('#datum-'+number).datetimepicker("getDate"),'#ohne_uhrzeit-'+number,'#datum-lesbar-'+number,false);
		// 				registerRendering(number);
		// 			}
		// 			$('#lastnumber').val(number);
		// 		}

		// 		$('#speichern').on("click", function() {
		// 			// Übergebe Ids der Inputs an HTML Form
		// 			var entries = new Array();
		// 			$('.duplicated-date').each(function(i,item) {
		// 				entries.push($(this).attr("id"));
		// 			});
		// 			entries = entries.join(',');
		// 			$('#numbers').val(entries);
		// 			return true;
		// 		});

		// 		$('#ajax-loading-duplicate').fadeOut();
		// 	}
		// });

		// var date = new Date();
		// var d = date.getDate();
		// var m = date.getMonth();
		// var y = date.getFullYear();

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
			$('.fc-header-left').append('<span id="refreshing-calendar"><img alt="Loading" id="ajax-loading" class="ajax-loading" src="' + WPURLS.siteurl + '/wp-admin/images/wpspin_light.gif" style="vertical-align: middle; visibility: visible;"> Aktualisiere...</span>');
		}

		// Add the functionality of the cancel-button
		// $('.calendar-edit-cancel').live("click", function() {
		// 	$('#calendar-edit').slideUp( function() {
		// 		// disable editing
		// 		$(this).html('');
		// 		// Activating calendar
		// 		changeCalendarState(true);
		// 	});
		// });

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
					url: ajaxurl,
					type: 'POST',
					data: {
						action: 'te_calendar_fetch_events'
					},
					className: 'event-object'
				}
			],
			//events: [{"id":"534","title":"Frauenhilfe Ochtrup","start":"2016-11-09T15:00:00","allDay":false,"ort":"","info":"","keywords":"","ohneuhrzeit":"0","dateTime":"2016-11-09 15:00:00","wichtig":"0"},{"id":"540","title":"Frauenhilfe Metelen","start":"2016-11-02T15:00:00","allDay":false,"ort":"","info":"","keywords":"","ohneuhrzeit":"0","dateTime":"2016-11-02 15:00:00","wichtig":"0"},{"id":"541","title":"Frauenhilfe Metelen","start":"2016-12-07T15:00:00","allDay":false,"ort":"","info":"Adventsfeier\n","keywords":"","ohneuhrzeit":"0","dateTime":"2016-12-07 15:00:00","wichtig":"0"},{"id":"567","title":"11-Uhr-Gottesdienst-Vorbereitung","start":"2016-11-23T20:00:00","allDay":false,"ort":"Ev. Gemeindehaus","info":"","keywords":"","ohneuhrzeit":"0","dateTime":"2016-11-23 20:00:00","wichtig":"0"},{"id":"546","title":"Vorbereitung 11-Uhr-Gottesdienst","start":"2016-11-02T20:00:00","allDay":false,"ort":"Gemeindehaus Ochtrup","info":"","keywords":"","ohneuhrzeit":"0","dateTime":"2016-11-02 20:00:00","wichtig":"0"},{"id":"547","title":"Konzert","start":"2016-11-13T17:00:00","allDay":false,"ort":"Ev. Kirche Ochtrup","info":"Motetten von J.S. Bach, Capella Enschede\n","keywords":"","ohneuhrzeit":"0","dateTime":"2016-11-13 17:00:00","wichtig":"0"},{"id":"549","title":"Konfi-Kids","start":"2016-11-03T15:30:00","allDay":false,"ort":"","info":"","keywords":"","ohneuhrzeit":"0","dateTime":"2016-11-03 15:30:00","wichtig":"0"},{"id":"550","title":"Konfi-Kids","start":"2016-11-10T15:30:00","allDay":false,"ort":"Ev. Gemeindehaus Ochtrup","info":"","keywords":"","ohneuhrzeit":"0","dateTime":"2016-11-10 15:30:00","wichtig":"0"},{"id":"554","title":"Presbyterium","start":"2016-11-03T19:30:00","allDay":false,"ort":"Ochtrup","info":"","keywords":"","ohneuhrzeit":"0","dateTime":"2016-11-03 19:30:00","wichtig":"0"},{"id":"557","title":"Presbyterium","start":"2016-12-05T19:30:00","allDay":false,"ort":"Ochtrup","info":"","keywords":"","ohneuhrzeit":"0","dateTime":"2016-12-05 19:30:00","wichtig":"0"},{"id":"564","title":"Trauercaf\u00e9 Hoffnungs-schimmer","start":"2016-12-07T15:00:00","allDay":false,"ort":"Villa Winkel","info":"","keywords":"","ohneuhrzeit":"0","dateTime":"2016-12-07 15:00:00","wichtig":"0"},{"id":"570","title":"Offener Trauertreff \"Innehalten\"","start":"2016-11-12T10:30:00","allDay":false,"ort":"Kommunalfriedhof","info":"Mitglieder des Hospizvereins laden auf dem Kommunalfriedhof bei einem Stehkaffee zu Begegnung, Innehalten, Verweilen ein. \n","keywords":"","ohneuhrzeit":"0","dateTime":"2016-11-12 10:30:00","wichtig":"0"},{"id":"569","title":"Offener Trauertreff \"Innehalten\"","start":"2016-11-05T10:30:00","allDay":false,"ort":"Kommunalfriedhof","info":"Mitglieder des Hospizvereins laden auf dem Kommunalfriedhof bei einem Stehkaffee zu Begegnung, Innehalten, Verweilen ein. \n","keywords":"","ohneuhrzeit":"0","dateTime":"2016-11-05 10:30:00","wichtig":"0"},{"id":"571","title":"\u00d6ffentlicher Hospizstammtisch","start":"2016-11-14T19:30:00","allDay":false,"ort":"Paddy's Irish Pub","info":"Markus B\u00fcnseler von der Buchhandlung Steffers stellt Buchneuheiten vor.\n","keywords":"","ohneuhrzeit":"0","dateTime":"2016-11-14 19:30:00","wichtig":"0"},{"id":"572","title":"Probe Junger Chor","start":"2016-11-12T11:00:00","allDay":false,"ort":"Ev. Kirche","info":"","keywords":"","ohneuhrzeit":"0","dateTime":"2016-11-12 11:00:00","wichtig":"0"},{"id":"573","title":"Vortragsabend \"Der Islam und die Muslime in Deutschland\"","start":"2016-11-08T19:30:00","allDay":false,"ort":"Clemens-August-Heim","info":"","keywords":"","ohneuhrzeit":"0","dateTime":"2016-11-08 19:30:00","wichtig":"0"},{"id":"574","title":"Adventsbasar Eine Welt Gruppe","start":"2016-11-27T11:00:00","allDay":false,"ort":"Ev. Gemeindehaus Ochtrup","info":"","keywords":"","ohneuhrzeit":"0","dateTime":"2016-11-27 11:00:00","wichtig":"0"}],
			timeFormat: "H:mm",
			weekNumbers: false,
			eventClick: function(calEvent, jsEvent, view) {
				tecal_showModal( calEvent, null, null, jsEvent, view, 'edit' );
			},
			eventDrop: function( calEvent, dayDelta, minuteDelta, allDay, revertFunc, jsEvent, ui, view ) {
				return false;

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
					$('.fc-header-left').append('<span id="refreshing-calendar"><img alt="Loading" id="ajax-loading" class="ajax-loading" src="' + WPURLS.siteurl + '/wp-admin/images/wpspin_light.gif" style="vertical-align: middle; visibility: visible;"> Aktualisiere...</span>');
					changeCalendarState(false);
				} else {
					// Wenn alles fertig ist
					changeCalendarState(true);
					$('#refreshing-calendar').fadeOut(function(){$(this).remove();});
				}
			},
			initialRender: function() {
				$('.fc-header-left').append('<span id="refreshing-calendar"><img alt="Loading" id="ajax-loading" class="ajax-loading" src="' + WPURLS.siteurl + '/wp-admin/images/wpspin_light.gif" style="vertical-align: middle; visibility: visible;"> Aktualisiere...</span>');
			},
			dayClick: function( date, allDay, jsEvent, view ) {
				tecal_showModal( null, date, allDay, jsEvent, view, 'new' );
			},
			disableDragging: false,
			disableResizing: true,
			height: 700
		}, localOptions));

		function tecal_showModal( calEvent, date, allDay, jsEvent, view, mode ) {
			var modal = document.querySelector('.tecal__edit-modal__container');
			var cancel_button = document.querySelector('[name="tecal_edit-modal_cancel"]');
			var save_button = document.querySelector('[name="tecal_edit-modal_save"]');
			var delete_button = document.querySelector('[name="tecal_edit-modal_delete"]');

			modal.classList.add('is-visible');

			cancel_button.addEventListener('click', tecal_modalCancelEvent, true);

			console.log(calEvent);

			var title_input = document.querySelector('[name="tecal_events_title"]');
			var location_input = document.querySelector('[name="tecal_events_location"]');
			var begin_date_input = document.querySelector('[name="tecal_events_begin"]');
			var begin_time_input = document.querySelector('[name="tecal_events_begin_time"]');
			var allday_input = document.querySelector('[name="tecal_events_allday"]');
			var end_date_input = document.querySelector('[name="tecal_events_end"]');
			var end_time_input = document.querySelector('[name="tecal_events_end_time"]');
			var has_end_input = document.querySelector('[name="tecal_events_has_end"]');
			var description_input = document.querySelector('[name="tecal_events_description"]');
			var edit_id_hidden = document.querySelector('[name="tecal_events_edit_id"]');

			if( mode == 'edit' ) {
				modal.classList.add('is-edit');
				save_button.addEventListener('click', tecal_modalSaveEditEvent, true);
				delete_button.addEventListener('click', tecal_modalDeleteEvent, true);

				title_input.value = calEvent.title;
				location_input.value = calEvent.location;
				begin_date_input.value = calEvent.start.format('YYYY-MM-DD');
				allday_input.checked = calEvent.allDay;
				if( calEvent.allDay === false || calEvent.allDay === "0" ) {
					end_date_input.value = moment(calEvent.end).format('YYYY-MM-DD');
					end_time_input.value = moment(calEvent.end).format('HH:mm');
					begin_time_input.value = moment(calEvent.start).format('HH:mm');
					has_end_input.checked = calEvent.hasEnd;
				} else {
					end_date_input.disabled = true;
					end_time_input.disabled = true;
					has_end_input.disabled = true;
					begin_time_input.disabled = true;
				}
				description_input.value = calEvent.description;
				edit_id_hidden.value = calEvent.id;
			} else {
				modal.classList.add('is-new');
				save_button.addEventListener('click', tecal_modalSaveNewEvent, true);

				console.log("date", date);

				// Reset all inputs.
				title_input.value = "";
				location_input.value = "";
				begin_date_input.value = date.format('YYYY-MM-DD');
				begin_time_input.value = "";
				begin_time_input.disabled = true;
				allday_input.checked = true;
				end_date_input.value = "";
				end_time_input.value = "";
				end_date_input.disabled = true;
				end_time_input.disabled = true;
				has_end_input.checked = false;
				has_end_input.disabled = false;
				description_input.value = "";
			}
		}


		function tecal_modalUnregisterEventListener() {
			var modal = document.querySelector('.tecal__edit-modal__container');
			var cancel_button = document.querySelector('[name="tecal_edit-modal_cancel"]');
			var save_button = document.querySelector('[name="tecal_edit-modal_save"]');
			var delete_button = document.querySelector('[name="tecal_edit-modal_delete"]');

			modal.classList.remove('is-visible');
			// Incorporate that an animation is taking place.
			setTimeout(function() {
				modal.classList.remove('is-edit');
				modal.classList.remove('is-new');
			}, 300);

			cancel_button.removeEventListener('click', tecal_modalCancelEvent);
			save_button.removeEventListener('click', tecal_modalSaveEditEvent);
			save_button.removeEventListener('click', tecal_modalSaveNewEvent);
			delete_button.removeEventListener('click', tecal_modalDeleteEvent);
		}

		var tecal_modalCancelEvent = function(e) {
			tecal_modalUnregisterEventListener();

			e.preventDefault();
			return false;
		}

		var tecal_modalSaveEditEvent = function(e) {
			var title_input = document.querySelector('[name="tecal_events_title"]');
			var location_input = document.querySelector('[name="tecal_events_location"]');
			var begin_date_input = document.querySelector('[name="tecal_events_begin"]');
			var begin_time_input = document.querySelector('[name="tecal_events_begin_time"]');
			var allday_input = document.querySelector('[name="tecal_events_allday"]');
			var end_date_input = document.querySelector('[name="tecal_events_end"]');
			var end_time_input = document.querySelector('[name="tecal_events_end_time"]');
			var has_end_input = document.querySelector('[name="tecal_events_has_end"]');
			var description_input = document.querySelector('[name="tecal_events_description"]');
			var edit_id_hidden = document.querySelector('[name="tecal_events_edit_id"]');

			console.log("is checked", allday_input.checked);
			console.log("is date", begin_date_input.value);

			var data = {
				tecal_events_title: title_input.value,
				tecal_events_location: location_input.value,
				tecal_events_begin: begin_date_input.value,
				tecal_events_begin_time: begin_time_input.value,
				tecal_events_allday: allday_input.checked,
				tecal_events_end: end_date_input.value,
				tecal_events_end_time: end_time_input.value,
				tecal_events_has_end: has_end_input.checked,
				tecal_events_description: description_input.value,
				tecal_events_post_id: edit_id_hidden.value,
				action: 'te_calendar_save_edited_event'
			};

			e.target.value = e.target.getAttribute('data-busycaption');

			$.post(ajaxurl, data, function(response) {
				tecal_modalUnregisterEventListener();
				e.target.value = e.target.getAttribute('data-defaultcaption');
				$( '#calendar' ).fullCalendar( 'refetchEvents' );
			});

			e.preventDefault();
			return false;
		}

		var tecal_modalSaveNewEvent = function(e) {
			var title_input = document.querySelector('[name="tecal_events_title"]');
			var location_input = document.querySelector('[name="tecal_events_location"]');
			var begin_date_input = document.querySelector('[name="tecal_events_begin"]');
			var begin_time_input = document.querySelector('[name="tecal_events_begin_time"]');
			var allday_input = document.querySelector('[name="tecal_events_allday"]');
			var end_date_input = document.querySelector('[name="tecal_events_end"]');
			var end_time_input = document.querySelector('[name="tecal_events_end_time"]');
			var has_end_input = document.querySelector('[name="tecal_events_has_end"]');
			var description_input = document.querySelector('[name="tecal_events_description"]');

			var data = {
				tecal_events_title: title_input.value,
				tecal_events_location: location_input.value,
				tecal_events_begin: begin_date_input.value,
				tecal_events_begin_time: begin_time_input.value,
				tecal_events_allday: allday_input.checked,
				tecal_events_end: end_date_input.value,
				tecal_events_end_time: end_time_input.value,
				tecal_events_has_end: has_end_input.checked,
				tecal_events_description: description_input.value,
				action: 'te_calendar_save_new_event'
			};

			e.target.value = e.target.getAttribute('data-busycaption');

			$.post(ajaxurl, data, function(response) {
				tecal_modalUnregisterEventListener();
				e.target.value = e.target.getAttribute('data-defaultcaption');
				$( '#calendar' ).fullCalendar( 'refetchEvents' );
			});

			e.preventDefault();
			return false;
		}

		var tecal_modalDeleteEvent = function(e) {
			var edit_id_hidden = document.querySelector('[name="tecal_events_edit_id"]');

			var data = {
				tecal_events_post_id: edit_id_hidden.value,
				action: 'te_calendar_delete_event'
			};

			e.target.value = e.target.getAttribute('data-busycaption');

			$.post(ajaxurl, data, function(response) {
				tecal_modalUnregisterEventListener();
				e.target.value = e.target.getAttribute('data-defaultcaption');
				$( '#calendar' ).fullCalendar( 'refetchEvents' );
			});
		}

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

})( jQuery );
