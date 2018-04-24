# 📅 Calendar With Custom Themes BETA
- Contributors: thomas.ebert
- Donate link: https://paypal.me/teonline
- Tags: calendar, events, theme, template
- Requires at least: 4.7.2
- Tested up to: 4.7.2
- Stable tag: trunk
- License: GPLv2 or later
- License URI: http://www.gnu.org/licenses/gpl-2.0.html

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

### 💭 Currently Planned Features

- Have a UI for specifying which template to use (dropdowns and display names for templates)
- Use external calendars for displaying (ical/caldav)
- Event features
	- Custom fields
	- Repeatable events (just like in every normal calendar)

### Usage

#### Widget

#### Shortcode

Use the shortcode like this: `[calendar num_events="5" template="my_template_file_in_theme_directory.php" archive="false" calendar="calendar's slug"]`

All options are optional.

`num_events` – the number of events to display.
`template` – the name of the template file in your theme's directory. Defaults to `default` which is the template shipped with this plugin.
`archive` – whether or not to display *only* events from the past.
`calendar` – the calendar to use for displaying events. Multiple comma separated.

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
