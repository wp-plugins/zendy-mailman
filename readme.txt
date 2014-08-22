=== Zendy Mailman ===
Contributors: ZendyLabs
Donate link: http://hq.zendy.net/wordpress/plugins/mailman/donate/
Tags: email, smtp
Requires at least: 3.9.1
Tested up to: 3.9.1
Stable tag: 1.0.6
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Zendy Mailman improves deliverabilty of emails sent by your website by using SMTP rather than the built-in Wordpress PHP mailer.

== Description ==

Zendy Mailman: the friendly email delivery system for Wordpress

Zendy Mailman makes sure all the email messages sent by your Wordpress site are delivered successfully by using SMTP rather than the built-in Wordpress PHP mailer.

== Installation ==

1. Upload the `zendy-mailman` folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Enter your SMTP info in the plugin settings; from your Wordpress dashboard menu, go to `Settings` > `Zendy Mailman`

== Frequently Asked Questions ==

= Where do I find my SMTP settings? =

Your web host (or email provider) will provide that for you. However you can visit our plugin website to see settings for the most popular email providers.

= What does Zendy Mailman do? =

Zendy Mailman increases the delivery rate of emails sent by your Wordpress site (like contact form notifications and new subscriber alerts). In other words, the emails sent by your Wordpress website are less likely to be flagged as spam.

= How does Zendy Mailman work? =

On shared hosting servers (most Wordpress sites are on shared hosting servers) the Wordpress mailer often gets flagged as spam or even blacklisted. Zendy Mailman uses SMTP mail instead of relying on the Wordpress mailer.

= What use cases and third-party plugins have been tested? =

* Wordpress email notification: new user registration, comment notification, etc.
* Gravity Forms plugin

== Screenshots ==

1. The settings page
2. The testing & troubleshooting page
3. The FAQ, including settings for some of the most popular email providers 

== Changelog ==

= 1.0.6 =
* Fix: fixed errors on activation on some nginx installs
* Meta: Removed change.log file; all changelog info is now in readme.txt

= 1.0.5 =
* Meta: changed stable tag
* Fix: changed end of file to attempt to fix a bug on activation on some nginx installs

= 1.0.4 =
* Meta: removed screenshots from plugin files; they are now in the Wordpress plugin subversion repository, in the /assets folder

= 1.0.3 =
* Meta: changed stable tag number

= 1.0.2 =
* Meta: changed description of Wordpress plugin for Wordpress.org plugin directory

= 1.0.1 =
* Meta: added screenshots for Wordpress.org plugin directory

= 1.0 =
* Initial release
* Feature: all emails sent via built-in Wordpress mailer will use SMTP instead of PHP mail.
* Feature: test your plugin SMTP settings by sending email via the test form.
* Feature: FAQ, including settings for some of the most popular email providers




