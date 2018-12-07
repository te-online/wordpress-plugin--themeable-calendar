(function( $ ) {
	'use strict';

	$(function() {

		/**
		/*	Initializes the full calendar.
		/*
		**/
		function initCalendar() {

			moment.locale(TE_CAL.locale.split('_')[0]);
			rome.use(moment);

			$('#tecal_calendar').fullCalendar($.extend({
				locale: TE_CAL.locale.split('_')[0],
				editable: true,
				firstDay: 1,
				eventSources: eventSources,
				timeFormat: "H:mm",
				weekNumbers: false,
				eventClick: function(calEvent, jsEvent, view) {
					if( calEvent.editable ) {
						tecal_showModal( calEvent, null, null, jsEvent, view, 'edit' );
					}
				},
				eventDrop: function( calEvent /*, dayDelta, minuteDelta, allDay, revertFunc, jsEvent, ui , view */ ) {
					// Disabling calendar for saving
					changeCalendarState(false);

					var data = {
						tecal_events_post_id: calEvent.id,
						tecal_events_begin: moment(calEvent.start).format('YYYY-MM-DD'),
						tecal_events_begin_time: moment(calEvent.start).format('HH:mm'),
						tecal_events_end: ( calEvent.hasEnd === true || calEvent.hasEnd === "1" ) ? moment(calEvent.end).format('YYYY-MM-DD') : moment(calEvent.start).format('YYYY-MM-DD'),
						tecal_events_end_time: ( calEvent.hasEnd === true || calEvent.hasEnd === "1" ) ? moment(calEvent.end).format('HH:mm') : moment(calEvent.start).format('HH:mm'),
						action: 'te_calendar_move_event'
					};

					$.post(ajaxurl, data, function() {
						$( '#tecal_calendar' ).fullCalendar( 'refetchEvents' );
					});
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
				eventDataTransform: function( eventData ) {
					// Full calendar has a problem, when an all day event's end
					// is not timed at 00:00 hours.
					if(eventData.allDay) {
						// Make a copy of the actual end time for our edit dialog.
						eventData.realEnd = moment(eventData.end);
						// Replace given time on end date with end of day (midnight + 1 day).
						var end = moment(eventData.end).add(1, 'days').hours(0).minutes(0);
						eventData.end = end.format();
					}

					// Convert HTML entities in fields.
					eventData.title = convertHTMLEntity(eventData.title);
					eventData.location = convertHTMLEntity(eventData.location);
					eventData.description = convertHTMLEntity(eventData.description);

					return eventData;
				},
				disableDragging: false,
				disableResizing: true,
				height: Math.min((window.innerHeight - 200), 900),
				header: {
					left:   'title',
					center: '',
					right:  'today prev,next'
				},
				titleFormat: 'MMMM YYYY'
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

			// Show modal.
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
			var end = null;

			if( mode == 'edit' ) {
				// Set modal mode to EDIT.
				modal.classList.add('is-edit');

				// Add event listeners for buttons.
				save_button.addEventListener('click', tecal_modalSaveEditEvent, true);
				delete_button.addEventListener('click', tecal_modalDeleteEvent, true);

				// Prepare begin time.
				var begin = calEvent.start;
				var begin_time = '';
				// When the event is not set to allday, we will display hours.
				if( !calEvent.allDay ) {
					begin_time = begin.format('HH:mm');
				} else {
					// Let's just use the current hour with 0 minutes.
					begin_time = now.format('HH:00');
				}
				// Prepare end time. Use backup of endtime when event is all day.
				end = (calEvent.allDay) ? calEvent.realEnd : calEvent.end;
				var end_time = '';
				if( moment.isMoment( end ) ) {
					end_time = end.format('HH:mm');
				} else {
					end = begin.clone().add('1', 'hours');
					end_time = end.format('HH:00')
				}

				// Configure input fields to show edit values.
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
				// Set modal mode to NEW.
				modal.classList.add('is-new');
				save_button.addEventListener('click', tecal_modalSaveNewEvent, true);

				date.hour(now.get('hour'));
				var this_hour = date.format('HH:00');
				end = date.clone().add('1', 'hours');
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
				// Select default calendar by default.
				calendar_input.value = "calendar";
				delete_button.disabled = true;
			}

			rome(begin_date_input, { time: false, initialValue: begin_date_input.value });
			rome(begin_time_input, { date: false, initialValue: begin_time_input.value });
			rome(end_date_input, { time: false, initialValue: end_date_input.value });
			rome(end_time_input, { date: false, initialValue: end_time_input.value });
		}

		/**
		/*	Enhances the event input fields in legacy edit mode.
		/*
		**/
		function tecal_enhanceMetaBox() {
			var begin_date_input = document.querySelector('[name="tecal_events_begin"]');
			var begin_time_input = document.querySelector('[name="tecal_events_begin_time"]');
			var allday_input = document.querySelector('[name="tecal_events_allday"]');
			var end_date_input = document.querySelector('[name="tecal_events_end"]');
			var end_time_input = document.querySelector('[name="tecal_events_end_time"]');
			var has_end_input = document.querySelector('[name="tecal_events_has_end"]');

			allday_input.addEventListener('change', tecal_alldayClicked);
			has_end_input.addEventListener('click', tecal_hasEndClicked);

			var now = moment();
			var end = null;

			// Prepare begin time.
			var begin = moment(begin_date_input.value + 'T' + begin_time_input.value);
			var begin_time = '';
			// When the event is not set to allday, we will display hours.
			if( !allday_input.checked  ) {
				begin_time = begin.format('HH:mm');
			} else {
				// Let's just use the current hour with 0 minutes.
				begin_time = now.format('HH:00');
			}
			// Prepare end time. Use backup of endtime when event is all day.
			end = moment(end_date_input.value + 'T' + end_time_input.value);
			var end_time = '';
			if( moment.isMoment( end ) ) {
				end_time = end.format('HH:mm');
			} else {
				end = begin.clone().add('1', 'hours');
				end_time = end.format('HH:00')
			}

			// Configure input fields to show edit values.
			begin_date_input.value = begin.format('YYYY-MM-DD');
			end_date_input.value = end.format('YYYY-MM-DD');
			end_time_input.value = end_time
			begin_time_input.value = begin_time;
			if( has_end_input.checked ) {
				end_date_input.disabled = false;
				end_time_input.disabled = false;
			} else {
				end_date_input.disabled = true;
				end_time_input.disabled = true;
			}
			if( !allday_input.checked ) {
				begin_time_input.disabled = false;
			} else {
				end_time_input.disabled = true;
				begin_time_input.disabled = true;
			}

			rome(begin_date_input, { time: false, initialValue: begin_date_input.value });
			rome(begin_time_input, { date: false, initialValue: begin_time_input.value });
			rome(end_date_input, { time: false, initialValue: end_date_input.value });
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
			modalActionButtonPressed('edit', e);
		}

		/**
		/*	Saves a new event from data from the edit modal.
		/*
		**/
		var tecal_modalSaveNewEvent = function(e) {
			modalActionButtonPressed('new', e);
		}

		/**
		/*	Reloads the calendar when submit button of
		/*	edit modal was pressed.
		/*
		**/
		function modalActionButtonPressed(modalCase, e) {
			// Hide errors.
			tecal_hideModalErrors();
			// Validate inputs.
			var validationResult = tecal_validateModalInput();
			// Show errors if not valid.
			if(!validationResult.valid) {
				tecal_displayModalErrors(validationResult.message);
				e.preventDefault();
				return false;
			}
			// Prepare data.
			var data = prepareInputData(modalCase);

			// Show within the button caption that we are working on it.
			e.target.value = e.target.getAttribute('data-busycaption');

			// Post data.
			$.post(ajaxurl, data, function() {
				e.target.value = e.target.getAttribute('data-defaultcaption');
				tecal_modalUnregisterEventListener();
				$( '#tecal_calendar' ).fullCalendar( 'refetchEvents' );
			});

			e.preventDefault();
			return false;
		}

		/**
		/*	Validates user input from edit modal.
		/*
		**/
		var tecal_validateModalInput = function() {
			var begin_date_input = document.querySelector('[name="tecal_events_begin"]');
			var begin_time_input = document.querySelector('[name="tecal_events_begin_time"]');
			var end_date_input = document.querySelector('[name="tecal_events_end"]');
			var end_time_input = document.querySelector('[name="tecal_events_end_time"]');
			var has_end_input = document.querySelector('[name="tecal_events_has_end"]');
			var allday_input = document.querySelector('[name="tecal_events_allday"]');

			// Check if all dates are valid.
			if(!moment(begin_date_input.value).isValid()) {
				return {
					valid: false,
					message: TE_CAL.translations.validate_begin_date_message
				};
			}

			if(!moment(begin_date_input.value + 'T' + begin_time_input.value).isValid()) {
				return {
					valid: false,
					message: TE_CAL.translations.validate_begin_time_message
				};
			}

			if(!moment(end_date_input.value).isValid()) {
				return {
					valid: false,
					message: TE_CAL.translations.validate_end_date_message
				};
			}

			if(!moment(end_date_input.value + 'T' + end_time_input.value).isValid()) {
				return {
					valid: false,
					message: TE_CAL.translations.validate_end_time_message
				};
			}

			// Check if end date is after begin date. (Ignore time.)
			if(has_end_input.checked && moment(end_date_input.value).isSameOrBefore(moment(begin_date_input.value))) {
				return {
					valid: false,
					message: TE_CAL.translations.validate_date_range_message
				};
			}

			// Check if end time is after begin time. (Only if time is relevant.)
			if(has_end_input.checked && !allday_input.checked && moment(end_date_input.value + 'T' + end_time_input.value).isBefore(
				moment(begin_date_input.value + 'T' + begin_time_input.value)
			)) {
				return {
					valid: false,
					message: TE_CAL.translations.validate_time_range_message
				};
			}

			return {
				valid: true
			};

		}

		/**
		/*	Show error message in modal.
		/*
		**/
		var tecal_displayModalErrors = function(message) {
			var errorContainer = document.querySelector('.tecal__edit-modal__error');
			errorContainer.innerText = message;
			errorContainer.classList.add('is-visible');
		}

		/**
		/*	Hide error message in modal.
		/*
		**/
		var tecal_hideModalErrors = function() {
			var errorContainer = document.querySelector('.tecal__edit-modal__error');
			errorContainer.innerText = '';
			errorContainer.classList.remove('is-visible');
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
				$( '#tecal_calendar' ).fullCalendar( 'refetchEvents' );
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
				$('#tecal_calendar').removeClass('opaque');
				// Trying to make clear that the calender should work
				$('#tecal_calendar-active').attr("active", "true");
			} else {
				// We remove the cursor
				$('.event-object').addClass('event-object-disabled');
				// We make it look like it was not working
				$('#tecal_calendar').addClass('opaque');
				// Trying to make clear that the calender should not work
				$('#tecal_calendar-active').attr("active", "false");
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
				// If event has no end, we set the end date to the same date as the begin date.
				tecal_events_end: ( !has_end_input.checked ) ? begin_date_input.value : end_date_input.value,
				// If event has no end or is all day, we set the end time to the same time as the begin time.
				tecal_events_end_time: ( !has_end_input.checked || allday_input.checked ) ? begin_time_input.value : end_time_input.value,
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

		/**
		/*	Decodes HTML entities.
		/*	See https://stackoverflow.com/questions/5796718/html-entity-decode
		/*
		**/
		function convertHTMLEntity(text) {
			if(!text) {
				return text
			}

			var span = document.createElement('span');

			return text
			.replace(/&[#A-Za-z0-9]+;/gi, function(entity) {
					span.innerHTML = entity;
					return span.innerText;
			});
		}

		/**
		/*	Enable calendar if we have a container for it.
		/*
		**/
		if(document.querySelector('#tecal_calendar')) {
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
							calendar: calendar.slug
						},
						className: 'event-object',
						color: calendar.color
					};

					eventSources.push( eventSource );
				});
				initCalendar();
			} );
		}

		/**
		/*	Enable enhancements for legacy edit view.
		/*
		**/
		if(document.querySelector('#tecal_legacy_event_edit')) {
			tecal_enhanceMetaBox();
		}

		/**
		/*	Add event listener to clicks on the “add” button at the top of the screen.
		/* 	On click this opens the create modal with the current date and hour.
		/*
		**/
		if(document.querySelector('.page-title-action')) {
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
		}

	});

})( jQuery );