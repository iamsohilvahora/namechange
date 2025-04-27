<?php
echo $title = get_sub_field('title');
echo "<br />";
$testimonial_posts = get_sub_field('select_testimonial');
if(!empty($testimonial_posts)):
    foreach($testimonial_posts as $post_detail):
        echo $post_title = $post_detail->post_title;
        echo "<br />";
        echo $post_content = $post_detail->post_content;
        echo "<br />";
        if(has_post_thumbnail($post_detail->ID)): 
            $image = wp_get_attachment_image_src(get_post_thumbnail_id($post_detail->ID), 'medium');
        endif;
        
        if(!empty($image[0])): ?>
        <div class="img-block">
            <img src="<?php echo $image[0]; ?>" alt="<?php echo $post_title; ?>" width="540px" height="580px">
        </div>
        <?php endif; ?>
    <?php endforeach; ?>
<?php endif; ?>
