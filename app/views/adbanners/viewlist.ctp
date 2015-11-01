<div>
	<?
	if (isset($adbanners) && count($adbanners))
	{
		$ix = 0;
		foreach($adbanners as $ad)
		{
			?>
			<div class="adbanner_item adbanner_item_id_<?= $ad['Adbanner']['adbanner_id'] ?> adbanner_item_ix_<?= $ix ?>" id="<?= $section ?>_adbanner_item__<?= $ix ?>"><a href="<?= $ad['Adbanner']['link_url'] ?>"><img src="/adbanners/view/<?= $ad['Adbanner']['adbanner_id'] ?>" /></a></div>
			<?
			$ix++;
		}
	}
	?>
</div>
