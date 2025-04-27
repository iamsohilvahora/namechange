<?php
echo $title = get_sub_field('title');
echo "<br />";
echo $description = get_sub_field('description');
echo "<br />";
$kit_lists = get_sub_field('kit_lists');
if($kit_lists){
	foreach($kit_lists as $list){
		echo $kit_list_title = $list['kit_list_title'];
		echo "<br />";
	}
}
