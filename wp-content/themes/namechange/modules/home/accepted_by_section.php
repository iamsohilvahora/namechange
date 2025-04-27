<?php
echo $title = get_sub_field('title');
echo "<br />";
$document_lists = get_sub_field('document_lists');
if($document_lists){
	foreach($document_lists as $list){
		$document_icon = $list['document_icon'];
		echo $document_icon['url'];
		echo "<br />";
		echo $list['document_title'];
		echo "<br />";
	}
}
