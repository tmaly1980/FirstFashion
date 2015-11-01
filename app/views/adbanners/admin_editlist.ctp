<div id="adbanner_editlist">

<fieldset>
<legend>Edit Ad Banner - <?= ucfirst($section) ?></legend>

<p>To re-organize the ads, simply drag them to where you want them to go.</p>
<div id="adbannerX" class="adbanner_editor">
<div id="adbanner_manager">
		<div class="editlink">
			[<a href="/admin/adbanners/add/<?=$section?>">Add Banner</a>]
		</div>
		<div class="clear"></div>
		<div id="adbanner_sortable">
		<?
		$n = 0;
		foreach($adbanners as $entry)
		{
			$adbanner = $entry['Adbanner'];
			$ext = $adbanner["ext"];
			$adbanner_id = $adbanner["adbanner_id"];
		?>
			<div id="adbanner_item_<?=$adbanner_id?>" class="adbanner_item">
			[<a class='adbanner_editlink' href='/admin/adbanners/edit/<?=$adbanner_id?>'>Edit Ad</a>]<br/>
			<img src="/adbanners/view/<?=$adbanner_id?>">
			</div>
		<?
			$n++;
		}
		?>
		</div>
</div>

<?

echo $ajax->sortable("adbanner_sortable", array('tag'=>'div', 'only' => 'adbanner_item', 'url'=>"/admin/adbanners/resort/$section"));

?>

</fieldset>
</div>
