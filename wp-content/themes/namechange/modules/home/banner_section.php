<?php
echo $heading = get_sub_field('heading');
echo "<br />";
echo $sub_heading = get_sub_field('sub_heading');
echo "<br />";
$buy_now_button = get_sub_field('buy_now');
echo $buy_now_button_label = $buy_now_button['button_label'];
echo "<br />";
echo $buy_now_button_link = button_group($buy_now_button);
echo "<br />";
echo $price_from = get_sub_field('price_from');
echo "<br />";
$namechange_list = get_sub_field('namechange_list');
if($namechange_list){
	foreach($namechange_list as $list){
		echo $list['title'];
		echo "<br />";
	}
}
echo "<br />";
$congratulation_image = get_sub_field('congratulation_image');
echo $congratulation_image['url'];
echo "<br />";
$right_arrow_image = get_sub_field('right_arrow_image');
echo $right_arrow_image['url'];
echo "<br />";
$left_arrow_image = get_sub_field('left_arrow_image');
echo $left_arrow_image['url'];
echo "<br />";
$namechange_image = get_sub_field('namechange_image');
echo $namechange_image['url'];
echo "<br />";
