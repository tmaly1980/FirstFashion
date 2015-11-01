<div class="member_searchbar">
<?php
	echo $form->create('Member',array('action'=>'search'));
	echo $form->input("keyword", array('label'=>'Talent Search', 'class'=>'searchbar_field'));
	echo $form->end();
?>
</div>

