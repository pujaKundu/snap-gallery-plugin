<?php
if ( have_comments() ) :
    wp_list_comments();
else :
    echo '<p>No comments yet.</p>';
endif;

comment_form();
?>
