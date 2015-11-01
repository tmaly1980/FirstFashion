<div class="members search">
<h2><?php __('Member Search');?></h2>
<? if (empty($this->data)) {
	?>
	Please enter some search criteria using the search bar on the right.
	<?
} else {
	# Show results.
	echo $this->element("member_search_results"); 
}
?>
</div>
