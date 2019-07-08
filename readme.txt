=== Anti-spam ===
Contributors: webvitaly
Donate link: http://web-profile.net/donate/
Tags: spam, spammer, comment, comments, comment-spam, antispam, anti-spam, block-spam, spam-free, spambot, spam-bot, bot
Requires at least: 3.3
Tested up to: 5.2
Stable tag: 5.5
License: GPLv3
License URI: http://www.gnu.org/licenses/gpl.html

No spam in comments. No captcha.

== Description ==

* **[Anti-spam Pro](http://codecanyon.net/item/antispam-pro/6491169?ref=webvitalii "Upgrade to Pro")**
* **[Anti-spam](http://web-profile.net/wordpress/plugins/anti-spam/ "Plugin page")**
* **[Donate](http://web-profile.net/donate/ "Support the development")**
* **[GitHub](https://github.com/webvitalii/anti-spam "Fork")**


Anti-spam plugin blocks automatic spam in comments section. No captcha. 

Plugin is easy to use: just install it and it just works.

Blocked comments can be stored in the Spam area if needed. This can be enabled/disabled via Settings page. This is useful for testing and debug purpose. Blocked spam comments can be easily converted to regular comments if needed.

Anti-spam plugin is GDPR compliant and does not store any other user data except of the behavior mentioned above.

**Plugin blocks spam only in comments section**.



After installing the Anti-spam plugin **try to submit a comment on your site being logged out**.
If you get an error - you may check the solution in the [Support section](http://wordpress.org/support/plugin/anti-spam) or submit a new topic with detailed description of your problem.


== Installation ==

1. Install and activate the plugin on the Plugins page
2. Enjoy life without spam in comments

== Frequently Asked Questions ==

= How to test what spam comments were blocked? =

You can visit Anti-spam settings page and enable saving blocked comments as spam in the spam section.
To enabled that you need to go to: WordPress admin dashboard => Settings section => Anti-spam
Saving blocked comments into spam section is disabled by default.
Saving spam comments can help you to keep all the comments saved and review them in future if needed. You can easily mark comment as "not spam" if some of the comments were blocked by mistake.

= What is the percentage of spam blocked? =

Anti-spam plugin blocks 100% of automatic spam messages (sent by spam-bots via post requests).
Plugin does not block manual spam (submitted by spammers manually via browser).
You can use [Anti-spam Pro](http://codecanyon.net/item/antispam-pro/6491169?ref=webvitalii "Upgrade to Pro") plugin if you need to block manual spam.

= Incompatible with: =

* Disqus
* Jetpack Comments
* AJAX Comment Form
* bbPress

= How does Anti-spam plugin work? =

The blocking algorithm is based on 2 methods: 'invisible js-captcha' and 'invisible input trap' (aka honeypot technique).

= How does 'invisible js-captcha' method (aka honeypot) work? =

The 'invisible js-captcha' method is based on fact that bots does not have javascript on their user-agents.
Extra hidden field is added to comments form.
It is the question about the current year.
If the user visits site, than this field is answered automatically with javascript, is hidden by javascript and css and invisible for the user.
If the spammer will fill year-field incorrectly - the comment will be blocked because it is spam.

= How does 'invisible input trap' (aka honeypot technique) method work? =

The 'invisible input trap' method is based on fact that almost all the bots will fill inputs with name 'email' or 'url'.
Extra hidden field is added to comments form.
This field is hidden for the user and user will not fill it.
But this field is visible for the spammer.
If the spammer will fill this trap-field with anything - the comment will be blocked because it is spam.

= How to know the counter of blocked spam comments? =

You can find the info block with total spam blocked counter in the admin comments section.
You can hide or show this info block in the "Screen Options" section.
The visibility option for this info block is saved per user.

= Does plugin block spam from Contact or other forms? =

Plugin blocks spam only in comments form section and does not block spam from any other forms on site.
If you installed and activated the plugin and you still receiving spam - probably this could be because of some other forms on your site (for example feedback form).

= What about trackback spam? =

Users rarely use trackbacks because it is manual and requires extra input. Spammers uses trackbacks because it is easy to cheat here.
Users use pingbacks very often because they work automatically. Spammers does not use pingbacks because backlinks are checked.
So trackbacks are blocked but pingbacks are enabled. 
You may use [Anti-spam Pro](http://codecanyon.net/item/antispam-pro/6491169?ref=webvitalii "Upgrade to Pro") plugin if you need to enable trackbacks.
You may read more about the [difference between trackbacks and pingbacks](http://web-profile.net/web/trackback-vs-pingback/)

= What browsers are supported? =

All modern browsers and IE8+ are supported.

= Unobtrusive JavaScript =

Anti-spam plugin works with disabled JavaScript. JavaScript is disabled on less than 1% of devices.
Users with disabled JavaScript should manually fill catcha-like input before submitting the comment.

= And one more extra note... =

If site has caching plugin enabled and cache is not cleared or if theme does not use 'comment_form' action
and there is no plugin inputs in comments form - plugin tries to add hidden fields automatically using JavaScript.

= Not enough information about the plugin? =

You may check out the [source code of the plugin](http://plugins.trac.wordpress.org/browser/anti-spam/trunk/anti-spam.php).
The plugin is pretty small and easy to read.


== Changelog ==

= 5.5 =
* Code cleanup
* Removed dismissible notice

= 5.4 =
* Updated dismissible notice

= 5.3 =
* Fixed the typo in the readme
* Readme cleanup
* Code cleanup
* Added dismissible notice

= 5.2 =
* Disable trackbacks

= 5.1 =
* Disable check for comments from logged in users

= 5.0 =
* Rewriting/refactoring a lot of the code
* Adding Settings page
* Storing blocked comments into the Spam section
* Working on GDPR compliance


= 4.4 - 2017-08-30 =
* Fixed issue with showing comments on every page. Thanks to [johnh10](https://wordpress.org/support/topic/shows-the-captcha-on-archive-pages/)

= 4.3 - 2016-11-22 =
* fixed notices

= 4.2 - 2016-01-30 =
* removed XSS vulnerability - thanks to Kenan from [tbmnull.com](http://tbmnull.com/)

= 4.1 - 2015-10-25 =
* added log spam to file feature - huge thanks to [Guti](http://www.javiergutierrezchamorro.com/ "Javier Gutiérrez Chamorro")
* prevent full path disclosure
* added empty index.php file
* publish plugin to GitHub
* added Text Domain for translation.wordpress.org

= 4.0 - 2015-10-11 =
* dropped jQuery dependency (huge thanks to [Guti](http://www.javiergutierrezchamorro.com/ "Javier Gutiérrez Chamorro") for rewriting javascript code from scratch. Força Barça! )
* fixed issue with empty blocked spam counter (showing zero instead of nothing)

= 3.5 - 2015-01-17 =
* removed function_exists check because each function has unique prefix
* removed add_option()
* added autocomplete="off" for inputs (thanks to Feriman)

= 3.4 - 2014-12-20 =
* added the ability to hide or show info block in the "Screen Options" section

= 3.3 - 2014-12-15 =
* refactor code structure
* added blocked spam counter in the comments section
* clean up the docs

= 3.2 - 2014-12-05 =
* added ANTISPAM_VERSION constant (thanks to jumbo)
* removed new spam-block algorithm because it is not needed

= 3.1 - 2014-12-04 =
* remove log notices

= 3.0 - 2014-12-02 =
* added new spam-block algorithm
* bugfixing
* enqueue script only for pages with comments form and in the footer (thanks to dougvdotcom)
* refactor code structure

= 2.6 - 2014-11-30 =
* reverting to ver.2.2 state (enqueue script using 'init' hook and into the header) because users start receiving spam messages

= 2.5 - 2014-11-26 =
* update input names

= 2.4 - 2014-11-25 =
* update input names

= 2.3 - 2014-11-23 =
* enqueue script only for pages with comments form and in the footer (thanks to dougvdotcom)
* clean up code

= 2.2 - 2014-08-03 =
* clear value of the empty input because some themes are adding some value for all inputs
* updated FAQ section

= 2.1 - 2014-02-15 =
* add support for comments forms loaded via ajax

= 2.0 - 2014-01-04 =
* bug fixing
* updating info

= 1.9 - 2013-10-23 =
* change the html structure

= 1.8 - 2013-07-19 =
* removed labels from plugin markup because some themes try to get text from labels and insert it into inputs like placeholders (what cause an error)
* added info to FAQ section that Anti-spam plugin does not work with Jetpack Comments

= 1.7 - 2013-05-31 =
* if site has caching plugin enabled and cache is not cleared or if theme does not use 'comment_form' action - Anti-spam plugin does not worked; so now whole input added via javascript if it does not exist in html

= 1.6 - 2013-05-05 =
* add some more debug info in errors text

= 1.5 - 2013-04-15 =
* disable trackbacks because of spam (pingbacks are enabled)

= 1.4 - 2013-04-13 =
* code refactor
* renaming empty field to "*-email-url" to trap more spam

= 1.3 - 2013-04-10 =
* changing the input names and add some more traps because some spammers are passing the plugin

= 1.2 - 2012-10-28 =
* minor changes

= 1.1 - 2012-10-14 =
* sending answer from server to client into hidden field (because client year and server year could mismatch)

= 1.0 - 2012-09-06 =
* initial release