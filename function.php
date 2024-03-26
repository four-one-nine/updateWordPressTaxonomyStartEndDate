<?php
// Include functions.php (replace with the actual path if necessary)
require_once('wp-load.php');
// Function to update series term meta with first and last post dates
function update_series_term_dates($term_id) {

  // Get arguments for retrieving messages posts within the term
  $args = array(
    'post_type' => 'message', // Replace with your actual post type name
    'post_status' => 'publish', // Only retrieve published posts
    'tax_query' => array(
      array(
        'taxonomy' => 'series', // Replace with your actual term name
        'field' => 'term_id',
        'terms' => $term_id,
      ),
    ),
    'orderby' => 'post_date', // Order by post date
    'order' => 'ASC', // Ascending order (get first post)
    'posts_per_page' => 1, // Limit to 1 post
  );

  // Get the first post
  $first_post_query = new WP_Query($args);

  if ($first_post_query->have_posts()) {
    $first_post = $first_post_query->the_post();
    $first_post_date = get_the_date('F j, Y' ); // Get date in YYYY-MM-DD format
  } else {
    echo 'no first post found \\';
    $first_post_date = null; // No first post found
  }

  wp_reset_postdata(); // Reset post data after first query

  // Modify arguments to get the last post (descending order)
  $args['order'] = 'DESC';

  // Get the last post
  $last_post_query = new WP_Query($args);

  if ($last_post_query->have_posts()) {
    $last_post = $last_post_query->the_post();
    $last_post_date = get_the_date('F j, Y') ; // Get date in YYYY-MM-DD format
  } else {
    echo 'no last post found \\';
    $last_post_date = null; // No last post found
  }

  echo $last_post_date;
  wp_reset_postdata(); // Reset post data after last query

  // Update term meta fields with retrieved dates
  update_term_meta($term_id, 'series_date_range', $last_post_date . ' - ' . $first_post_date);
}

echo 'test \\';

$args = array(
    'orderby'    => 'ID', 
    'order'      => 'DESC',
    'hide_empty' => true,
);      

echo 'test2 \\';
$terms = get_terms('series' , $args); //Fetches all the current series taxonomies

// Loop through each term and update its meta fields
if ($terms) {
  foreach ($terms as $term) {
    echo 'ok';
    echo $term->name;
    update_series_term_dates($term->term_id);
  }
}

echo "Series term dates updated successfully!";

?>
