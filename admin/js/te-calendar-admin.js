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

	$(function() {

		/**
		/*	Takes actions necessary when “has end” checkbox was selected.
		/*
		**/
		if(!document.querySelector('#calendar')) {
			return;
		}

		// var localOptions = {
		// 	buttonText: {
		// 		today: 'Heute',
		// 		month: 'Monat',
		// 		day: 'Tag',
		// 		week: 'Woche'
		// 	},
		// 	monthNames: ['Januar','Februar','März','April','Mai','Juni','Juli','August','September','Oktober','November','Dezember'],
		// 	monthNamesShort: ['Jan','Feb','Mär','Apr','Mai','Jun','Jul','Aug','Sept','Okt','Nov','Dez'],
		// 	dayNames: ['Sonntag','Montag','Dienstag','Mittwoch','Donnerstag','Freitag','Samstag'],
		// 	dayNamesShort: ['So','Mo','Di','Mi','Do','Fr','Sa']
		// }

		// If the calendar div exists we will add a refresh-update-message
		if($('.fc-header-left').length) {
			$('.fc-header-left').append('<span id="refreshing-calendar"><img alt="Loading" id="ajax-loading" class="ajax-loading" src="' + WPURLS.siteurl + '/wp-admin/images/wpspin_light.gif" style="vertical-align: middle; visibility: visible;"> Aktualisiere...</span>');
		}

		// Fetch all calendars first.
		var eventSources = [];
		var data = {
			action: 'te_calendar_fetch_calendars'
		};

		$.post(ajaxurl, data, function( response ) {
			response = JSON.parse( response );
			// Prepare event sources for each calendar.
			response.forEach( function( calendar ) {
				var eventSource = {
					url: ajaxurl,
					type: 'POST',
					data: {
						action: 'te_calendar_fetch_events',
						calendar: calendar
					},
					className: 'event-object'
				};

				eventSources.push( eventSource );
			});
			initCalendar();
		} );

		/**
		/*	Initializes the full calendar.
		/*
		**/
		function initCalendar() {

			$('#calendar').fullCalendar($.extend({
				editable: true,
				firstDay: 1,
				// Loading real events here will be the next challenge
				eventSources: eventSources,
				//events: [{"id":"534","title":"Frauenhilfe Ochtrup","start":"2016-11-09T15:00:00","allDay":false,"ort":"","info":"","keywords":"","ohneuhrzeit":"0","dateTime":"2016-11-09 15:00:00","wichtig":"0"},{"id":"540","title":"Frauenhilfe Metelen","start":"2016-11-02T15:00:00","allDay":false,"ort":"","info":"","keywords":"","ohneuhrzeit":"0","dateTime":"2016-11-02 15:00:00","wichtig":"0"},{"id":"541","title":"Frauenhilfe Metelen","start":"2016-12-07T15:00:00","allDay":false,"ort":"","info":"Adventsfeier\n","keywords":"","ohneuhrzeit":"0","dateTime":"2016-12-07 15:00:00","wichtig":"0"},{"id":"567","title":"11-Uhr-Gottesdienst-Vorbereitung","start":"2016-11-23T20:00:00","allDay":false,"ort":"Ev. Gemeindehaus","info":"","keywords":"","ohneuhrzeit":"0","dateTime":"2016-11-23 20:00:00","wichtig":"0"},{"id":"546","title":"Vorbereitung 11-Uhr-Gottesdienst","start":"2016-11-02T20:00:00","allDay":false,"ort":"Gemeindehaus Ochtrup","info":"","keywords":"","ohneuhrzeit":"0","dateTime":"2016-11-02 20:00:00","wichtig":"0"},{"id":"547","title":"Konzert","start":"2016-11-13T17:00:00","allDay":false,"ort":"Ev. Kirche Ochtrup","info":"Motetten von J.S. Bach, Capella Enschede\n","keywords":"","ohneuhrzeit":"0","dateTime":"2016-11-13 17:00:00","wichtig":"0"},{"id":"549","title":"Konfi-Kids","start":"2016-11-03T15:30:00","allDay":false,"ort":"","info":"","keywords":"","ohneuhrzeit":"0","dateTime":"2016-11-03 15:30:00","wichtig":"0"},{"id":"550","title":"Konfi-Kids","start":"2016-11-10T15:30:00","allDay":false,"ort":"Ev. Gemeindehaus Ochtrup","info":"","keywords":"","ohneuhrzeit":"0","dateTime":"2016-11-10 15:30:00","wichtig":"0"},{"id":"554","title":"Presbyterium","start":"2016-11-03T19:30:00","allDay":false,"ort":"Ochtrup","info":"","keywords":"","ohneuhrzeit":"0","dateTime":"2016-11-03 19:30:00","wichtig":"0"},{"id":"557","title":"Presbyterium","start":"2016-12-05T19:30:00","allDay":false,"ort":"Ochtrup","info":"","keywords":"","ohneuhrzeit":"0","dateTime":"2016-12-05 19:30:00","wichtig":"0"},{"id":"564","title":"Trauercaf\u00e9 Hoffnungs-schimmer","start":"2016-12-07T15:00:00","allDay":false,"ort":"Villa Winkel","info":"","keywords":"","ohneuhrzeit":"0","dateTime":"2016-12-07 15:00:00","wichtig":"0"},{"id":"570","title":"Offener Trauertreff \"Innehalten\"","start":"2016-11-12T10:30:00","allDay":false,"ort":"Kommunalfriedhof","info":"Mitglieder des Hospizvereins laden auf dem Kommunalfriedhof bei einem Stehkaffee zu Begegnung, Innehalten, Verweilen ein. \n","keywords":"","ohneuhrzeit":"0","dateTime":"2016-11-12 10:30:00","wichtig":"0"},{"id":"569","title":"Offener Trauertreff \"Innehalten\"","start":"2016-11-05T10:30:00","allDay":false,"ort":"Kommunalfriedhof","info":"Mitglieder des Hospizvereins laden auf dem Kommunalfriedhof bei einem Stehkaffee zu Begegnung, Innehalten, Verweilen ein. \n","keywords":"","ohneuhrzeit":"0","dateTime":"2016-11-05 10:30:00","wichtig":"0"},{"id":"571","title":"\u00d6ffentlicher Hospizstammtisch","start":"2016-11-14T19:30:00","allDay":false,"ort":"Paddy's Irish Pub","info":"Markus B\u00fcnseler von der Buchhandlung Steffers stellt Buchneuheiten vor.\n","keywords":"","ohneuhrzeit":"0","dateTime":"2016-11-14 19:30:00","wichtig":"0"},{"id":"572","title":"Probe Junger Chor","start":"2016-11-12T11:00:00","allDay":false,"ort":"Ev. Kirche","info":"","keywords":"","ohneuhrzeit":"0","dateTime":"2016-11-12 11:00:00","wichtig":"0"},{"id":"573","title":"Vortragsabend \"Der Islam und die Muslime in Deutschland\"","start":"2016-11-08T19:30:00","allDay":false,"ort":"Clemens-August-Heim","info":"","keywords":"","ohneuhrzeit":"0","dateTime":"2016-11-08 19:30:00","wichtig":"0"},{"id":"574","title":"Adventsbasar Eine Welt Gruppe","start":"2016-11-27T11:00:00","allDay":false,"ort":"Ev. Gemeindehaus Ochtrup","info":"","keywords":"","ohneuhrzeit":"0","dateTime":"2016-11-27 11:00:00","wichtig":"0"}],
				timeFormat: "H:mm",
				weekNumbers: false,
				eventClick: function(calEvent, jsEvent, view) {
					tecal_showModal( calEvent, null, null, jsEvent, view, 'edit' );
				},
				eventDrop: function( calEvent, dayDelta, minuteDelta, allDay, revertFunc, jsEvent, ui, view ) {
					// If the calendar is active
					// if($('#calendar-active').attr("active") == "true") {
						// Disabling calendar for saving
						changeCalendarState(false);

						console.log( "has end", ( calEvent.hasEnd === "1" ) ? "true" : "false" );

						var data = {
							tecal_events_post_id: calEvent.id,
							tecal_events_begin: moment(calEvent.start).format('YYYY-MM-DD'),
							tecal_events_begin_time: moment(calEvent.start).format('HH:mm'),
							tecal_events_end: ( calEvent.hasEnd === "1" ) ? moment(calEvent.end).format('YYYY-MM-DD') : moment(calEvent.start).format('YYYY-MM-DD'),
							tecal_events_end_time: ( calEvent.hasEnd === "1" ) ? moment(calEvent.end).format('HH:mm') : moment(calEvent.start).format('HH:mm'),
							action: 'te_calendar_move_event'
						};

						$.post(ajaxurl, data, function() {
							$( '#calendar' ).fullCalendar( 'refetchEvents' );
							// Make the calendar work again
							// changeCalendarState(true);
						});
					// }
				},
				loading: function(bool) {
					if(bool) {
						// When the calendar is loading.
						$('.fc-header-left').append('<span id="refreshing-calendar"><img alt="Loading" id="ajax-loading" class="ajax-loading" src="' + WPURLS.siteurl + '/wp-admin/images/wpspin_light.gif" style="vertical-align: middle; visibility: visible;"> Aktualisiere...</span>');
						changeCalendarState(false);
					} else {
						// When everything is ready.
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
			}));
		}

		/**
		/*	Displays the edit modal. This modal is used for creating and editing events.
		/*
		**/
		function tecal_showModal( calEvent, date, allDay, jsEvent, view, mode ) {
			var modal = document.querySelector('.tecal__edit-modal__container');
			var cancel_button = document.querySelector('[name="tecal_edit-modal_cancel"]');
			var save_button = document.querySelector('[name="tecal_edit-modal_save"]');
			var delete_button = document.querySelector('[name="tecal_edit-modal_delete"]');

			modal.classList.add('is-visible');

			cancel_button.addEventListener('click', tecal_modalCancelEvent, true);

			var title_input = document.querySelector('[name="tecal_events_title"]');
			var location_input = document.querySelector('[name="tecal_events_location"]');
			var begin_date_input = document.querySelector('[name="tecal_events_begin"]');
			var begin_time_input = document.querySelector('[name="tecal_events_begin_time"]');
			var allday_input = document.querySelector('[name="tecal_events_allday"]');
			var end_date_input = document.querySelector('[name="tecal_events_end"]');
			var end_time_input = document.querySelector('[name="tecal_events_end_time"]');
			var has_end_input = document.querySelector('[name="tecal_events_has_end"]');
			var description_input = document.querySelector('[name="tecal_events_description"]');
			var calendar_input = document.querySelector('[name="tecal_events_calendar"]');

			var edit_id_hidden = document.querySelector('[name="tecal_events_edit_id"]');

			allday_input.addEventListener('click', tecal_alldayClicked, true);
			has_end_input.addEventListener('click', tecal_hasEndClicked, true);

			var now = moment();

			if( mode == 'edit' ) {
				modal.classList.add('is-edit');
				save_button.addEventListener('click', tecal_modalSaveEditEvent, true);
				delete_button.addEventListener('click', tecal_modalDeleteEvent, true);

				var begin = calEvent.start;
				var begin_time = '';
				if( begin.hasHours ) {
					begin_time = (calEvent.start).format('HH:mm');
				} else {
					begin_time = now.format('HH:00');
				}
				end = calEvent.end;
				var end_time = '';
				if( moment.isMoment( end ) ) {
					if( calEvent.allDay ) {
						end_time = now.add('1', 'hours').format('HH:00');
					} else {
						end_time = (calEvent.end).format('HH:mm');
					}
				} else {
					end = begin.add('1', 'hours');
					end_time = end.format('HH:00')
				}

				title_input.value = calEvent.title;
				location_input.value = calEvent.location;
				begin_date_input.value = begin.format('YYYY-MM-DD');
				has_end_input.checked = ( calEvent.hasEnd === true || calEvent.hasEnd === "1" ) ? true : false;
				has_end_input.disabled = false;
				end_date_input.value = end.format('YYYY-MM-DD');
				end_time_input.value = end_time
				begin_time_input.value = begin_time;
				if( calEvent.hasEnd === true || calEvent.hasEnd === "1" ) {
					end_date_input.disabled = false;
					end_time_input.disabled = false;
				} else {
					end_date_input.disabled = true;
					end_time_input.disabled = true;
				}
				allday_input.checked = ( calEvent.allDay === true || calEvent.allDay === "1" ) ? true : false;
				if( calEvent.allDay === false || calEvent.allDay === "0" ) {
					begin_time_input.disabled = false;
				} else {
					end_time_input.disabled = true;
					begin_time_input.disabled = true;
				}
				description_input.value = calEvent.description;
				calendar_input.value = calEvent.calendar;
				edit_id_hidden.value = calEvent.id;
				delete_button.disabled = false;
			} else {
				modal.classList.add('is-new');
				save_button.addEventListener('click', tecal_modalSaveNewEvent, true);

				date.hour(now.get('hour'));
				var this_hour = date.format('HH:00');
				var end = date.add('1', 'hours');
				var next_hour = end.format('HH:00');
				var end_date = end.format('YYYY-MM-DD');

				// Reset all inputs.
				title_input.value = "";
				location_input.value = "";
				begin_date_input.value = date.format('YYYY-MM-DD');
				begin_time_input.value = this_hour;
				begin_time_input.disabled = true;
				allday_input.checked = true;
				end_date_input.value = end_date;
				end_time_input.value = next_hour;
				end_date_input.disabled = true;
				end_time_input.disabled = true;
				has_end_input.checked = false;
				has_end_input.disabled = false;
				description_input.value = "";
				calendar_input.value = "";
				delete_button.disabled = true;
			}

			rome(begin_date_input, { time: false, initialValue: begin_date_input.value, dateValidator: rome.val.beforeEq( end_date_input) });
			rome(begin_time_input, { date: false, initialValue: begin_time_input.value });
			rome(end_date_input, { time: false, initialValue: end_date_input.value, dateValidator: rome.val.afterEq( begin_date_input ) });
			rome(end_time_input, { date: false, initialValue: end_time_input.value });
		}


		/**
		/*	Unregisters event listeners when closing the modal.
		/*
		**/
		function tecal_modalUnregisterEventListener() {
			var modal = document.querySelector('.tecal__edit-modal__container');
			// var cancel_button = document.querySelector('[name="tecal_edit-modal_cancel"]');
			var save_button = document.querySelector('[name="tecal_edit-modal_save"]');
			var delete_button = document.querySelector('[name="tecal_edit-modal_delete"]');
			// var has_end_input = document.querySelector('[name="tecal_events_has_end"]');
			// var allday_input = document.querySelector('[name="tecal_events_allday"]');

			modal.classList.remove('is-visible');
			// Incorporate that an animation is taking place.
			setTimeout(function() {
				modal.classList.remove('is-edit');
				modal.classList.remove('is-new');
			}, 300);

			// cancel_button.removeEventListener('click', tecal_modalCancelEvent);
			// save_button.removeEventListener('click', tecal_modalSaveEditEvent);
			var save_clone = save_button.cloneNode( true );
			save_button.parentNode.replaceChild( save_clone, save_button );
			// save_button.removeEventListener('click', tecal_modalSaveNewEvent);
			// delete_button.removeEventListener('click', tecal_modalDeleteEvent);
			var delete_clone = delete_button.cloneNode( true );
			delete_button.parentNode.replaceChild( delete_clone, delete_button );
			// allday_input.removeEventListener('click', tecal_alldayClicked);
			// has_end_input.removeEventListener('click', tecal_hasEndClicked);
		}

		/**
		/*	Takes actions necessary when “all day” checkbox was selected.
		/*
		**/
		var tecal_alldayClicked = function(e) {
			var begin_time_input = document.querySelector('[name="tecal_events_begin_time"]');
			var end_time_input = document.querySelector('[name="tecal_events_end_time"]');
			var has_end_input = document.querySelector('[name="tecal_events_has_end"]');

			if( e.target.checked ) {
				begin_time_input.disabled = true;
				end_time_input.disabled = true;
			} else {
				begin_time_input.disabled = false;
				if( has_end_input.checked ) {
					end_time_input.disabled = false;
				}
			}
		}

		/**
		/*	Takes actions necessary when “has end” checkbox was selected.
		/*
		**/
		var tecal_hasEndClicked = function(e) {
			var end_date_input = document.querySelector('[name="tecal_events_end"]');
			var end_time_input = document.querySelector('[name="tecal_events_end_time"]');
			// var has_end_input = document.querySelector('[name="tecal_events_has_end"]');
			var allday_input = document.querySelector('[name="tecal_events_allday"]');

			if( e.target.checked ) {
				end_date_input.disabled = false;
				if( allday_input.checked !== true ) {
					end_time_input.disabled = false;
				}
			} else {
				end_date_input.disabled = true;
				end_time_input.disabled = true;
			}
		}

		/**
		/*	Cancels editing an event. Closes the modal.
		/*
		**/
		var tecal_modalCancelEvent = function(e) {
			tecal_modalUnregisterEventListener();

			e.preventDefault();
			return false;
		}

		/**
		/*	Saves an edited event.
		/*
		**/
		var tecal_modalSaveEditEvent = function(e) {
			console.log("Editing...");
			modalActionButtonPressed('edit', e);
		}

		/**
		/*	Saves a new event from data from the edit modal.
		/*
		**/
		var tecal_modalSaveNewEvent = function(e) {
			console.log("Saving new...");
			modalActionButtonPressed('new', e);
		}

		/**
		/*	Reloads the calendar when submit button of
		/*	edit modal was pressed.
		/*
		**/
		function modalActionButtonPressed(modalCase, e) {
			var data = prepareInputData(modalCase);

			e.target.value = e.target.getAttribute('data-busycaption');

			$.post(ajaxurl, data, function() {
				e.target.value = e.target.getAttribute('data-defaultcaption');
				tecal_modalUnregisterEventListener();
				$( '#calendar' ).fullCalendar( 'refetchEvents' );
			});

			e.preventDefault();
			return false;
		}

		/**
		/*	Deletes the event that is currently viewed in the edit modal.
		/*
		**/
		var tecal_modalDeleteEvent = function(e) {
			var edit_id_hidden = document.querySelector('[name="tecal_events_edit_id"]');

			var data = {
				tecal_events_post_id: edit_id_hidden.value,
				action: 'te_calendar_delete_event'
			};

			e.target.value = e.target.getAttribute('data-busycaption');

			$.post(ajaxurl, data, function() {
				e.target.value = e.target.getAttribute('data-defaultcaption');
				tecal_modalUnregisterEventListener();
				$( '#calendar' ).fullCalendar( 'refetchEvents' );
			});
		}

		/**
		/*	Toggles calendar active state for loading scenarios.
		/*
		**/
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

		/**
		/*	Prepares data to be sent to the storage API.
		/* 	@return object 	contains all input data and config
		/*
		**/
		function prepareInputData(modalCase) {
			var title_input = document.querySelector('[name="tecal_events_title"]');
			var location_input = document.querySelector('[name="tecal_events_location"]');
			var begin_date_input = document.querySelector('[name="tecal_events_begin"]');
			var begin_time_input = document.querySelector('[name="tecal_events_begin_time"]');
			var allday_input = document.querySelector('[name="tecal_events_allday"]');
			var end_date_input = document.querySelector('[name="tecal_events_end"]');
			var end_time_input = document.querySelector('[name="tecal_events_end_time"]');
			var has_end_input = document.querySelector('[name="tecal_events_has_end"]');
			var description_input = document.querySelector('[name="tecal_events_description"]');
			var calendar_input = document.querySelector('[name="tecal_events_calendar"]');
			if( modalCase === 'edit' ) {
				var edit_id_hidden = document.querySelector('[name="tecal_events_edit_id"]');
			}

			var data = {
				tecal_events_title: title_input.value,
				tecal_events_location: location_input.value,
				tecal_events_begin: begin_date_input.value,
				tecal_events_begin_time: begin_time_input.value,
				tecal_events_allday: allday_input.checked,
				tecal_events_end: ( !has_end_input.checked ) ? begin_date_input.value : end_date_input.value,
				tecal_events_end_time: ( !has_end_input.checked ) ? begin_time_input.value : end_time_input.value,
				tecal_events_has_end: has_end_input.checked,
				tecal_events_description: description_input.value,
				tecal_events_calendar: ( calendar_input.options[calendar_input.selectedIndex] ) ? calendar_input.options[calendar_input.selectedIndex].value : 'calendar'
			};

			if( modalCase === 'edit' ) {
				data.tecal_events_post_id = edit_id_hidden.value;
				data.action = 'te_calendar_save_edited_event';
			} else if( modalCase === 'new' ) {
				data.action = 'te_calendar_save_new_event';
			}

			return data;
		}

		$('.tecal_list_table').hide();

		document.querySelector('.page-title-action').addEventListener('click', function(e) {
			var today = $('td.fc-today');
			var down = new $.Event("mousedown");
			var up = new $.Event("mouseup");
			down.which = up.which = 1;
			down.pageX = up.pageX = today.offset().left;
			down.pageY = up.pageY = today.offset().top;
			today.trigger(down);
			today.trigger(up);

			e.preventDefault();
			return false;
		});

	});

})( jQuery );