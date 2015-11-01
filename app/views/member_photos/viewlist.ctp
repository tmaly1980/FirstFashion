
	<table id="member_album_item_list" border=0 cellpadding=0 cellspacing=0>
	<?
	$rows = 2;
	$cols_per_row = 3;
	
	if (isset($photos) && count($photos))
	{
		$count = count($photos);
	
		$i = 0;
		for ($row = 0; $row < $rows && $i < $count; $row++)
		{
			echo "<tr>\n";
			for ($col = 0; $col < $cols_per_row && $i < $count; $col++)
			{
				$member_photo = $photos[$i]['MemberPhoto'];

				$photo_title = $member_photo["title"];
				$photo_id = $member_photo["photo_id"];
				$photo_ext = $member_photo["ext"];
				if (!$photo_ext) { $photo_ext = 'jpg'; } # DEFAULT! in case not there... cuz that's how it's named on disk too...

				$is_primary = $member_photo["is_primary"] ? "primary_photo" : "";
				/*
				# <a rel="member_album_<?= $member_id ?>" class='member_album_photo thickbox' href='/member_photos/view/large/<?=$photo_id?><?= $photo_ext ? ".$photo_ext" : "" ?>' title="<?= $photo_title ?>">
				#<td style="zwidth: <?= sprintf("%d", 100 / $cols_per_row); ?>%;">
				*/
			?>
				<td class="photocell">
				<!--<a class="photolink" rel="shadowbox[<?= $member['Member']['firstname'] . ' ' . $member['Member']['lastname'] ?>];slideshowDelay=20" class='member_album_photo' href='/member_photos/view/large/<?=$photo_id?><?= $photo_ext ? ".$photo_ext" : "" ?>' title="<?= $photo_title ?>">-->
				<a class="photolink" rel="shadowbox;options={overlayOpacity: 0.95}" class='member_album_photo' href='/member_photos/view/large/<?=$photo_id?><?= $photo_ext ? ".$photo_ext" : "" ?>' title="<?= $photo_title ?>">
					<img src="/member_photos/view/small/<?=$photo_id?>">
				</a>
				<!--
				<a class="flaglink" href="/member_photos/flag/<?=$photo_id?>">Flag</a>
				-->
				</td>
			<?
				$i++;
			}
			echo "</tr>"; 
		}
	} else {
		echo "<tr><td>This member has no photos.</td></tr>";

	}
        $paginator->options(array('update' => 'photo_list','url'=>$this->passedArgs));
	?>
	</table>

	<div class="pager">
	<?
		# not removing the old paginator....
		if (count($photos) > 0)
		{
			echo $paginator->prev('« ', null, null, array('class' => 'disabled'));
			echo $paginator->counter();
			echo $paginator->next(' »', null, null, array('class' => 'disabled'));
		}
	?>
	</div>

	<? 
		#echo $javascript->codeBlock("tb_init('a.thickbox, area.thickbox, input.thickbox');"); 
		echo $javascript->codeBlock("Shadowbox.setup(null, {slideshowDelay: 5, continuous: true});");
	?>
