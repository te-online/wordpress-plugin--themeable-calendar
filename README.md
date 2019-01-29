# 📅 Calendar With Custom Themes BETA
- Contributors: thomas.ebert
- Tags: calendar, events, theme, template
- Requires at least: 4.7.2
- Tested up to: 4.9.8
- Stable tag: trunk
- License: GPLv2 or later
- License URI: http://www.gnu.org/licenses/gpl-2.0.html
- Donate link: https://paypal.me/teonline

Note: This a BETA plugin. A Really Simple™ Calendar with beautiful default templates and a way to create your own themes for your events.

## Description

**Note: This is a BETA plugin. This plugin is *not* ready for production websites. If you don't know what “beta” means, you should consider looking for a different plugin for the time beeing. If you want to test this plugin and give feedback on the currently implemented functionality, you're more than welcome to post in the [GitHub issue tracker](https://github.com/te-online/wordpress-plugin--themeable-calendar/issues)!**

When finished, this is supposed to be a really simple calendar plugin with beautiful default templates and the possibility for developers to create themes for the eventlists that can be shipped with WordPress themes.

### ✅ Implemented Features

- Visual calendar to create events
- Two default templates
- A widget for displaying events
- A shortcode for displaying events
- Possibility to use a custom php-file in your themes directory as a template
- Events support
	- Event title
	- Start/Begin date and time
	- End date and time
	- All day parameter that omits times
	- Specify end parameter that omits the end
	- Location
	- Description
- Use a default calendar or create as many calendars as you want (distinguished by color)
- Translations: English, German, Dutch (not checked by native-speaker)
- Use external calendars as read-only for displaying (ical)

### 💭 Currently Planned Features

- Have a UI for specifying which template to use (dropdowns and display names for templates)
- Event features
	- Custom fields
	- Repeatable events (just like in every normal calendar)

### Usage

#### Widget

Use the UI in the Widgets area to add a calendar to a sidebar and set the options. The options are similar to the options for the shortcode (see below).

#### Shortcode

Use the shortcode like this: `[calendar num_events="5" template="my_template_file_in_theme_directory.php" archive="false" calendar="calendar's slug"]`

All options are optional.

- `num_events` – the number of events to display.
- `template` – the name of the template file in your theme's directory. Defaults to `default` which is the template shipped with this plugin.
- `archive` – whether or not to display *only* events from the past.
- `calendar` – the calendar to use for displaying events. Multiple comma separated.
- `filter` – a search term to filter events with. Only matching will be shown.

#### JSON REST API
By default, REST API access to calendars and events is enabled from (including) version 0.4.0 and upwards. Standard WordPress endpoints are used. You can add custom attributes for your own purposes. Take a look at the specific chapter in the [Developer handbook](https://developer.wordpress.org/rest-api/extending-the-rest-api/modifying-responses/).

Calendars are a custom `term` and events are represented by a custom `post_type`.

All standard limitations and usages for the WordPress REST API apply, be aware of that. Example: Responses are limited to a certain number of items. Use the `X-WP-Total` header to determine how many entries exist and use the `page` query parameter to browse through the paginated entries.

Replace `https://example.org` with your own WordPress URL in the examples below.

##### Calendars
To get a list of calendars from the WordPress REST API, try the following endpoint:
(GET) `https://example.org/wp-json/wp/v2/calendars`.

**Attributes**. The field `name` is your calendar's name. You can use the field `id` to fetch events only for a specific calendar or for a list of calendars.

##### Events
After we got details about the calendars from the API, we can use this information to retrieve events for specific calendars.

Use the endpoint (GET) `https://example.org/wp-json/wp/v2/events?calendars=1,2` to fetch events for the calendars that have assigned the ids `1` and `2`. You can also just use one calendar-id.

To retrieve all events from all calendars, use this endpoint `https://example.org/wp-json/wp/v2/events`.

**Attributes**. The plugin adds one custom attribute to each event in the reponse called `event_details`.

This attribute is an object with values like `location`, `begin`, `end`, `begin_date`, `end_date`, `begin_time`, `end_time`, `all_day`, `has_end`. The `end`, `end_date` and `end_time` attributes will be `null`, if `has_end` is `false`. The `begin_time` and `end_time` attributes will be `null`, if `all_day` is `true`.

Use the `title.rendered` and `content.rendered` attributes, that are added by default to the response, to get the *title* and *description* (*content*) for the specific event.

**Time ranges**. To fetch events within certain time ranges, we can use the custom query fields `begin_after` and `end_before`, as well as the default fields `order` and `per_page`. The fields `begin_after` and `end_before` are *inclusive*, meaning that the date you specify (format: `YYYY-MM-DD`) will be included in the results on both ends of the boundary.

Examples:

- **Fetching events between two dates**

Assuming we want the events between the 1st of January 2011 and the 31st of July 2011. The start and end of our boundaries can be specified in the query attributes `begin_after` and `end_before`. To get the earliest events first instead of the latest events, we use `order`=`asc`.

(GET) `https://example.org/wp-json/wp/v2/events?begin_after=2011-01-01&end_before=2011-07-31&order=asc`

- **Fetching 3 events after specific date**

Assuming we just want the 3 first events in the year 2019. Specifying the `begin_after` will get us all events after that date (including the date itself). Assigining `3` to the `per_page` parameter, reduces our results to 3 entries.

(GET) `https://example.org/wp-json/wp/v2/events?begin_after=2019-01-01&order=asc&per_page=3`


### 💅 Template Tags you can use today!

For an example of how to create a template see the files in the `templates` folder of the plugin. *The template functions are inspired by the core WordPress functions you can use for posts and pages.*

Detailed descriptions for the functions will follow.
All functions can be used with echo (the_...) or silently (get_...).

#### `get_event_begin( $format ) / the_event_begin( $format )`
Get the event begin in a given format. Format string uses the format seen here: https://codex.wordpress.org/Formatting_Date_and_Time

#### `get_event_begin_day / the_event_begin_day`
Get the weekday of the event begin.

#### `get_event_begin_date / the_event_begin_date`
Get the short form date of the event begin.

#### `get_event_begin_year / the_event_begin_year`
Get the year of the event begin.

#### `get_event_begin_time / the_event_begin_time`
Get the time of the event begin.

#### `get_event_end( $format ) / the_event_end( $format )`
Get the event end in a given format. Format string uses the format seen here: https://codex.wordpress.org/Formatting_Date_and_Time

#### `get_event_end_day / the_event_end_day`
Get the weekday of the event end.

#### `get_event_end_date / the_event_end_date`
Get the short form date of the event end.

#### `get_event_end_year / the_event_end_year`
Get the year of the event end.

#### `get_event_end_time / the_event_end_time`
Get the time of the event end.

#### `get_event_location / the_event_location`
Get the location of the event.

#### `get_event_is_allday`
Know if the event is an allday-event.

#### `get_event_has_end`
Know if the event has an end specified.

### 👩‍💻 External libraries

This plugin currently uses the following external libraries:

- [FullCalendar](https://fullcalendar.io)
- Rome by [Nicolás Bevacqua](https://github.com/bevacqua/rome)

## 💁 Installation

### Currently recommended
Use the [GitHub Updater](https://github.com/te-online/wordpress-plugin--themeable-calendar) to install the plugin.

### Manual
Download the zip file and put the contents into your wp-content/plugins folder.

## Frequently Asked Questions
No frequently asked questions, yet. *If you have a question, open an issue on GitHub...*

## Screenshots
Screenshots are to follow.

## 💇 Changelog

### 0.4.2 Beta (29 Jan 2019)
- Add option to ignore `modified-date` field in iCal feed, to be compatible with Nextcloud.

### 0.4.1 Beta (20 Jan 2019)
- Add `filter` property to Shortcode. Enables you to only show events that contain a specific search string.

### 0.4.0 Beta (17 Dec 2018)
- REST API: Add support for the WordPress JSON API. You can now fetch calendars and events through the API, and filter and sort the result.
- Secure Ajax request with nonces.
- Add basic support for [Advanced Custom Fields](https://www.advancedcustomfields.com/) in calendar view.

### 0.3.9.2 Beta (7 Dec 2018)
- Fixes to the modal event editor
- Additions to the hotfix from 0.3.9.1

### 0.3.9.1 Beta (7 Dec 2018)
- Hotfix: Fix accidental call to `get_field` of ACF instead of `get_term_meta`.
- Enhancement: List View – Add view switch. Now we can also see and remove trashed events.

### 0.3.9 Beta (5 Dec 2018)
- ICAL – Fix recurring events apparently can also have *no* `RRULE`, but a `RECURRENCE_ID` instead. Account for that while checking uid of event.
- LIST VIEW – Enhancements to list view.
  - Fix pagination not working
  - Add sorting by column and default order
  - Fix ical field caption in calendar settings

### 0.3.8 Beta (21 Jul 2018)
- ICAL – Fix allday events could not be updated to be timed events.
- Update composer ical parser library.
- ICAL – Fix event specific timezone was used for UTC time. Fix repeating events all had the same uid.

### 0.3.7 Beta (8 Jun 2018)
- Fix allday events with set start and begin will be displayed incorrectly.

### 0.3.6 Beta (5 Jun 2018)
- Fix allday events have the next day as end day, because they are saved as 24 hours long.

### 0.3.5 Beta (27 Apr 2018)
- Always set external events to have end, because otherwise it won't be saved for multi-day allday events.

### 0.3.4 Beta (27 Apr 2018)
- Fix problem with calendar range format when fetching events.

### 0.3.3 Beta
*(skipped)*

### 0.3.2 Beta (27 Apr 2018)
- Switch WP Cron to hourly jobs fetching external events (instead of twicedaily).

### 0.3.1 Beta (25 Apr 2018)
- Fix external calendar not editable.
- Also add p tag around empty state message.
- Make default template structure a bit more concise and add translations

### 0.3
- *Breaking change*: Fixed: Times were not saved in UTC before, now they are. All your old dates will unfortunately inevitable off, if your're not using UTC anyways.
- Fixed: Error in JavaScript when event description was `null`.
- Fixed: JS Error on other WP admin pages.
- Added: The Readme is now readable and has some considerable emoji-explosion.
- Added: You can now use external iCal feeds as a read-only calendar and display events from the feed 🎉.
- Added: Updated translations and translation for DE_formal.

### 0.2
- Fixed: Shortcode display position was broken.
- Fixed: When no calendar was configured, the widget didn't show the default calendar.
- Fixed: Data inconsistencies in edit dialog. Making sure you edit the correct data and don't have to start over.
- Added: Switch between WordPress-style list of events and JavaScript based calendar view.
- Added: Nice menu icon from dashicons.
- Added: Neat JavaScript enhancements for the WordPress style event edit screen.

### 0.1.1
- Fixed: Visual improvements to add-event modal.
- Fixed: Fix calendar data not loading correctly due to too much sanitization.
- Fixed: Template can be used multiple times now.
- Added: Color setting for calendars.
- Added: Choose calendar to save event in.
- Added: Chosse calendar for display in widget.
- Added: Use multiple calendars and have one default calendar.
- Added: Enable templating with files from themes directory.

### 0.1
- Initial version.
