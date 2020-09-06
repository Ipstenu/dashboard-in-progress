# Dashboard: In Progress

Widget for the WordPress dashboard to display posts pending review (i.e draft and pending).

## Available filters

* `dashboard_in_progress_post_type` - change the post type shown (default 'post')
* `dashboard_in_progress_posts_shown` - change the number of posts shown (default 5)

###  Usage

To change the displayed post type to pages, instead of posts, you can filter like this:

```
add_filter( 'dashboard_in_progress_post_type', 'function_to_change_dashboard_in_progress_post_type', 10, 2 );

function function_to_change_dashboard_in_progress_post_type( $post_type ) {
	return 'page';
}
```

## About This Project

This plugin was forked from "Dashboard: Pending Review" written by Alex Mills.
