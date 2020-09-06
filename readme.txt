=== Dashboard: In Progress ===
Contributors: Viper007Bond, Ipstenu
Donate link: https://ko-fi.com/A236CEN/
Tags: dashboard, widgets, dashboard widget
Requires at least: 2.7
Tested up to: 5.5
Stable tag: 1.1

Display a list of all unpublished (draft and pending) content on your dashboard.

== Description ==

This plugin creates a new widget for that dashboard that lists all unpublished and unscheduled content, be they drafts or pending review.

= Available filters =

* `easy_dashboard_status_post_type` - change the post type shown (default 'post')
* `easy_dashboard_status_posts_shown` - change the number of posts shown (default 5)

=== Privacy Notes ===

This plugin does not track any user data and makes no external calls.

== Installation ==

No Special Instructions.

== Frequently Asked Questions ==

= Can I change the post type from 'post'? =

You can filter `easy_dashboard_status_post_type` and change the post type to any valid post type or `any`, which will show all pending posts in all post types.

= Can I change the default number of posts shown? =

You can use the filter `dashboard_in_progress_posts_shown` to change the number. If you somehow make it 0, the default will be 5.

= Can I show different post types for draft and pending? =

Not at this time.

= Can I show multiple post types? =

Not at this time.

= Where can I contribute? =

[Github! Pull requests welcome.](https://github.com/Ipstenu/dashboard-in-progress)

== Screenshots ==

1. Example of a list of posts.

== ChangeLog ==

= 1.1 =
* Improvements on code for newer WP
* Updated CSS

= 1.0 =
* By Mika Epstein
* Forked from Dashboard: Pending Review (by Alex Mills)
* Brought up to 2019 coding standards
* Fixed deprecations
* Moved CSS to a file
* Matching style to the rest of post MP6 WordPress Dashboard
* Add filter to post type
* Props to [ov3rfly](https://wordpress.org/support/users/ov3rfly/) for all bug fixes from the forums.
