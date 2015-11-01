<table cellpadding="0" cellspacing="0">
<?
	echo $html->tableCells(array(
		array(
			$this->element('advanced_search_range_field', array('name'=>'MemberModelProfile.height','options'=>$height_options,'label'=>'Height:')),
			$this->element('advanced_search_range_field', array('name'=>'MemberModelProfile.weight','label'=>'Weight:','after'=>' lbs.')),
			$this->element('advanced_search_multiselect_field', array('name'=>'MemberModelProfile.gender','options'=>$gender_options)),
			$this->element('advanced_search_range_field', array('name'=>'MemberModelProfile.since_experience','label'=>'Experience:')),
		),
		array(
			$this->element('advanced_search_multiselect_field', array('name'=>'MemberModelProfile.eye_color','options'=>$eye_colors)),
			$this->element('advanced_search_multiselect_field', array('name'=>'MemberModelProfile.hair_color','options'=>$hair_colors)),
			$this->element('advanced_search_multiselect_field', array('name'=>'MemberModelProfile.ethnicity','options'=>$ethnicity_options)),
			$this->element('advanced_search_multiselect_field', array('name'=>'MemberModelProfile.skintone','options'=>$skin_tones)),
		),
	));
?>
</table>
