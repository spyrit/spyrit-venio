=== Venio ===
Contributors: spyrit, paulkarampournis
Tags: venio, events
Stable tag: 1.1.0
Requires at least: 5.7
Requires PHP: 5.6
Tested up to: 5.9
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Display Venio events directly on your WordPress website.

== Description ==

= Display your events on your website =
This plugin allows you to display [Venio](https://venio.fr/) events on your website.
Your visitors can search among these events thanks to a shortcode integrated on your website.

[Follow plugin development on Github.](https://github.com/spyrit/spyrit-venio/)

= Event single page =
A URL is provided for each event for you to share easily.
We find on this single page the pictures, the description and the packages of your event.

= Optimized performances =
Caching system is regulating the connection with Venio API calls.
Caching lifetime is set to 1 hour (3600 seconds), every hours, your website make a new API call to Venio to get and display all your public events.
Anytime, you can force the events retrieval clicking on the "Force update" button.

== Installation ==

= Automatic installation =

Log in to your WordPress dashboard, navigate to the Plugins menu and click "Add New".
In the search field type "Venio" and click Search Plugins. Once you have found the plugin, click "Install Now".

= Manual Installation =

1. Upload the entire `venio` directory to the `/wp-content/plugins/` directory.
2. Activate Venio through the 'Plugins' menu in WordPress.

== Changelog ==

### 1.1.0 - 2022-03-10
- Dashboard: changing layout of options page
- Dashboard: adding 2 new fields -> Back button URL and Back button label
- Dashboard: display of the list of events directly on the options page
- Front: adding a back button in event single page
- Front: fix layout when selecting an institution

### 1.0.1 - 2021-12-07
- API: fix infinite loop when institution option is empty
- Front: adding a grey background for the search field
- Frond: adding a cursor style for the slider buttons

### 1.0.0 - 2021-11-04
- Dashboard: adding a new screen to save your organizations
- API: events are now saved in the database
- Front: adding an event list feature (shortcode)
- Front: adding a single page for your events
