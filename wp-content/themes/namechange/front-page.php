<?php
get_header();

/* *** Check flexible content field exists or not *** */
if(have_rows('home_page_sections')):
    /* *** Loop through rows *** */
    while(have_rows('home_page_sections')) : the_row();
        /* *** layout *** */
        get_template_part('modules/home/'. get_row_layout());
    /* *** End loop *** */
    endwhile;
/* *** No value *** */
else:
    echo "Homepage content not found.";
endif;

get_footer();
