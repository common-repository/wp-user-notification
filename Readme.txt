=== WP User Notifications ===
Contributors: Muhammad Rehman
Tags: wp user notification, wp user notifications, wp notification, wordpress user notification, wordpress notification plugin, send notification to user plugin, send notification, send notifications, send notificaiton to any user, wp fb notifications
Requires at least: 4.0
Tested up to: 4.7
Stable tag: 1.2.6
License: GPLv2
License URI: GPLv2

== Description ==
Send notifications to any users by using shortcodes, place the shortcode where you want to send notification. You can send notification to multiple users by their role.

* [Live Demo and Sample Code](https://goo.gl/lG0dFz)

  = Send Notification to Single User =
= [wpun_notification title="New Notification" user_id="5" link="http://muhammadrehman.com/plugins/wp-user-notification/"]=
  Just place this shortcode in pages, posts, widgets or php file.
  
  = Send Notification to Multiple Users By Role =
= [wpun_notification title="New Notification" role="subscriber" link="http://muhammadrehman.com/plugins/wp-user-notification/"]=
  Just place this shortcode in pages, posts, widgets or php file.

  = Receive Notification =  
= [wpun_view_notification]=
  Just place this shortcode for view notification, you can place it menus, or any where in the pages, posts, widgets or php file.
  
  = Attributes =
  Here are some Attributes
  
  - title

  Title of your Notifications

  - user_id

  Reciver user id , comma seprate value for multiple users

  - link

  Notification Redirect Link
  
  - Role

  User Role (role="subscriber",role="student" or role="all" - send notification for all users)

  Notification link to redirect specific page

== Installation ==
To add a WordPress Plugin using the built-in plugin installer:

Go to Plugins > Add New.

1. Type in the name \"WP User Notifications\" in Search Plugins box
2. Find the \"WP User Notifications\" Plugin you wish to install.
3. Click Install Now to install the WordPress Plugin.
4. The resulting installation screen will list the installation as successful or note any problems during the install.
If successful, click Activate Plugin to activate it, or Return to Plugin Installer for further actions.

== Frequently Asked Questions ==
= How to use this plugin? =
Just after installing WP User Notifications, You can use any of the provided shortcodes by WP User Notifications

== Screenshots ==
1. Notification Preview

== Changelog ==
= 1.2.5 =
- Send notification to multiple users at a time

= 1.2.4 =
- Add Option to send notification to multiple users by their role

= 1.2.3 =
- Fixed jQuery issues

= 1.2.2 =
- Fixed Ajax issue

= 1.2.1 =
- Fixed Bugs, and optimize speed

= 1.1.0 =
- Fixed jQuery bugs

= 1.0.0 =
- Initial release.

== Upgrade Notice ==
Always try to keep your plugin update so that you can get the improved and additional features added to this plugin till the moment.