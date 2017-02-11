=== Calendar With Custom Themes BETA ===
Contributors: thomas.ebert
Donate link: https://thomas-ebert.design
Tags: calendar, events, theme, template
Requires at least: 4.7.2
Tested up to: 4.7.2
Stable tag: 4.7.2
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Note: This a BETA plugin. A Really Simple™ Calendar with beautiful default templates and a way to create your own themes for your events.

== Description ==

**Note: This is a BETA plugin. This plugin is *not* ready for production websites. If you don't know what “beta” means, you should consider looking for a different plugin for the time beeing. If you want to test this plugin and give feedback on the currently implemented functionality, you're more than welcome in the support forum!**

When finished, this is supposed to be a really simple calendar plugin with beautiful default templates and the possibility for developers to create themes for the eventlists that can be shipped with WordPress themes.

### Implemented Features

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

### Currently Planned Features

- Have a UI for specifying which template to use (dropdowns and display names for templates)
- Use external calendars for displaying (ical/caldav)
- Event features
	- Custom fields
	- Repeatable events (just like in every normal calendar)
- Translation to German

### Template Tags you can use today!

For an example of how to create a template see the files in the `templates` folder of the plugin. *Or just trust your intuition and do it the same way you would do it for normal posts.*

Detailed descriptions for the functions will follow.
All functions can be used with echo (the_...) or silently (get_...).

#### get_event_begin( $format )
Get the event begin in a given format.

#### get_event_begin_day
Get the weekday of the event begin.

#### get_event_begin_date
Get the short form date of the event begin.

#### get_event_begin_year
Get the year of the event begin.

#### get_event_begin_time
Get the time of the event begin.

#### get_event_end( $format )
Get the event end in a given format.

#### get_event_end_day
Get the weekday of the event end.

#### get_event_end_date
Get the short form date of the event end.

#### get_event_end_year
Get the year of the event end.

#### get_event_end_time
Get the time of the event end.

#### get_event_location
Get the location of the event.

#### get_event_is_allday
Know if the event is an allday-event.

#### get_event_has_end
Know if the event has an end specified.

### External libraries

This plugin currently uses the following external libraries:

- FullCalendar by xxx, link
- Rome by xxx, link

== Installation ==

Honestly: Use the plugin directory to install the plugin.

For unconvincibles: Download the zip and put the content into your wp-content/plugins folder.

== Frequently Asked Questions ==

No frequently asked questions, yet. *Go ahead...*

== Screenshots ==

Screenshots are to follow.

== Changelog ==

= 0.1 =
* Initial version.
