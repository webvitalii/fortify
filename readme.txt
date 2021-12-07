=== Fortify ===
Contributors: webvitaly
Donate link: http://web-profile.net/donate/
Tags: spam, spammer, comment, comments, comment-spam, antispam, anti-spam, block-spam, spam-free, spambot, spam-bot, bot
Requires at least: 5.0
Tested up to: 5.8.2
Stable tag: 1.0
License: GPLv3
License URI: http://www.gnu.org/licenses/gpl.html

No spam in comments. No captcha.

== Description ==

* **[Fortify](http://web-profile.net/wordpress/plugins/fortify/ "Plugin page")**
* **[Donate](http://web-profile.net/donate/ "Support the development")**
* **[GitHub](https://github.com/webvitalii/fortify "Fork")**


Fortify plugin blocks automatic spam in comments section. No captcha.

Plugin is easy to use: just install it and it just works.

Blocked comments can be stored in the Spam area if needed. This can be enabled/disabled via Settings page. This is useful for testing and debug purpose. Blocked spam comments can be easily converted to regular comments if needed.

Fortify plugin is GDPR compliant and does not store any other user data except of the behavior mentioned above.

**Plugin blocks spam only in comments section**.



After installing the Fortify plugin **try to submit a comment on your site being logged out**.
If you get an error - you may check the solution in the [Support section](http://wordpress.org/support/plugin/fortify ) or submit a new topic with detailed description of your problem.


== Installation ==

1. Install and activate the plugin on the Plugins page
2. Enjoy life without spam in comments

== Frequently Asked Questions ==

= How to test what spam comments were blocked? =

You can visit Fortify settings page and enable saving blocked comments as spam in the spam section.
To enabled that you need to go to: WordPress admin dashboard => Settings section => Fortify
Saving blocked comments into spam section is disabled by default.
Saving spam comments can help you to keep all the comments saved and review them in future if needed. You can easily mark comment as "not spam" if some of the comments have been blocked by mistake.

= What is the percentage of spam blocked? =

Fortify plugin blocks 100% of automatic spam messages (sent by spam-bots via post requests).
Plugin does not block manual spam (submitted by spammers manually via browser).

= Incompatible with: =

* Disqus
* Jetpack Comments
* AJAX Comment Form
* bbPress

= How does Fortify plugin work? =

The blocking algorithm is based on "honeypot technique" and the fact that spam bots does not have JavaScript enabled.
Fortify plugin adds invisible to regular users input fields.
And spam bots fill in those fields which helps to identify that as spam.

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
You may read more about the [difference between trackbacks and pingbacks](http://web-profile.net/web/trackback-vs-pingback/)

= What browsers are supported? =

All modern browsers and IE8+ are supported.

= Not enough information about the plugin? =

You may check out the [source code of the plugin](http://plugins.trac.wordpress.org/browser/fortify/trunk/fortify.php).


== Changelog ==

= 1.0 =
* initial release
