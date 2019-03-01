# Dashboard: Pending Review

Widget for the WordPress dashboard to display posts pending review.

## Available filters

* `dashboard_pending_review_post_type` - change the post type shown (default 'post')
* `dashboard_pending_review_posts_shown` - change the number of posts shown (default 5)

###  Usage

To change the pending post type to pages, instead of posts, you can filter like this:

```
add_filter( 'dashboard_pending_review_post_type', 'function_to_change_dashboard_pending_review_post_type', 10, 2 );

function function_to_change_dashboard_pending_review_post_type( $post_type ) {
	return 'page';
}
```

## About This Project

This plugin was originally written by Alex Mills.
