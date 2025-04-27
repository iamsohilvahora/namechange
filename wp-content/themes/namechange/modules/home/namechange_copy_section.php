<?php
echo $title = get_sub_field('title');
echo "<br />";
$namechange_copies = get_sub_field('namechange_copies');
if($namechange_copies){
	foreach($namechange_copies as $list){
		echo $copy_title = $list['copy_title'];
		echo "<br />";
		echo $copy_price = $list['copy_price'];
		echo "<br />";
		$copy_points = $list['copy_points'];
		if($copy_points){
			foreach($copy_points as $point){
				echo $point['copy_point'];
				echo "<br />";
			}
		}
		echo "<br />";
		$copy_button = get_sub_field('copy_button');
		echo $copy_button_label = $copy_button['button_label'];
		echo "<br />";
		echo $copy_button_link = button_group($copy_button);
	}
}
