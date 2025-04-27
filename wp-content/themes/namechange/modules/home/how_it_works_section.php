<?php
echo $title = get_sub_field('title');
echo "<br />";
echo $description = get_sub_field('description');
echo "<br />";
$packages = get_sub_field('packages');
if($packages){
	foreach($packages as $list){
		echo $package_title = $list['package_title'];
		echo "<br />";
		echo $package_description = $list['package_description'];
		echo "<br />";
	}
}
