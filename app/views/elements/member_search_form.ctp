<form method="POST">
<div id="member_search_refine">
	<?= $this->element("member_search_refine", $this->viewVars); ?>
</div>
<div id="member_type_search_refine">
	<?
	if ($default_member_type == 'model')
	{
		echo $this->element("member_search_refine_model", $this->viewVars);
	}
	?>
</div>
<div class="left">
	<input type="submit" name="action" value="Search">
</div>
</form>

<br/>
<br/>

