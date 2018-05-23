# Social Link Widget
Contributors: gratrockstar  
Plugin Site: http://damemedia.com  
Tags: Social Media, Widgets  
Requires at least: 3.0  
Tested up to: 4.9.6  
Requires PHP: 5.6  
Stable tag: 1.0.0  
License: GPLv3  
License URI: https://www.gnu.org/licenses/gpl-3.0.txt  

Simple configurable Wordpress Widget for showing icon links to social media services.

## Description
This plugin adds a simple configurable widget that shows a list of icon links to social media services.  There is no css provided, so you can customize the look of the widgets, however some basic Bootstrap classes are provided as defaults.  Includes filters for customizing services, icons,  icon dimensions, and much more:
* social_links_widget_services(array): Alter the array of services available.
* social_links_widget_service_image_dimensions(array): Alter the height and width applied to icons
* social_links_widget_service_icons(array): Alter the array of icons used for each service.  Defaults to an svg, keyed by service.  These icons come from https://github.com/simple-icons/simple-icons
* social_links_widget_title(string): Alter the widget title (defaults to title set in widget settings)
* social_links_widget_list_classes(array): Alter the classes of the list parent (ul).
* social_links_widget_list_item_classes(array): Alter the classes for each list item.
* social_links_widget_html(string, $args, $instance): Alter the final HTML output of the widget.  Filter receives html string, passed $args, and passed $instance.

## Installation
Simple to install, just like other plugins.

1. Download and unzip the plugin social-links-widget.zip.
2. Copy the unzipped folder in your Plugins directory (wp-content/plugins)
3. Activate the plugin through the plugin window in the admin panel
4. Configure the widget settings through Appearance->Widgets in the admin panel

## Frequently Asked Questions
For Issues/Feature Requests, submit an issue here.

## Changelog
### 1.0
* Initial Release