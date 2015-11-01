
	<table id="member_video_item_list" border=0 cellpadding=0 cellspacing=0>
	<?
	$rows = 2;
	$cols_per_row = 3;
	
	if (isset($videos) && count($videos))
	{
		$count = count($videos);
	
		$i = 0;
		for ($row = 0; $row < $rows && $i < $count; $row++)
		{
			echo "<tr>\n";
			for ($col = 0; $col < $cols_per_row && $i < $count; $col++)
			{
				$member_video = $videos[$i]['MemberVideo'];
				$member_id = $member_video['member_id'];
				$member_name = $videos[$i]['Member']['firstname'] . ' ' . $videos[$i]['Member']['lastname'];

				$video_title = $member_video["title"];
			?>
				<td class="member_video_cell">
				<a class="member_video" rel="shadowbox;width=450;height=390;player=iframe" href="/member_videos/view/<?= $member_id ?>?member_link=1">
					<img class="member_video_thumb" src="<?= $member_video['video_thumbnail_url'] ?>">
				</a>
				<!-- SOMEHOW BOTTOM ROW LINKS ARE NOT SHOWING UP RIGHT, BLOCKED AND CANT CLICK ON...
				<br/>
				<a class='member_link' href='/members/view/<?=$member_id?>' title="<?= $member_name ?>: <?= $video_title ?>">
					View Member &raquo;
				</a>
				-->
				</td>
			<?
				$i++;
			}
			echo "</tr>"; 
		}
	}
        $paginator->options(array('update' => 'latest_videos_list','url'=>$this->passedArgs));
	?>
	</table>

	<div class="pager">
	<?
		# not removing the old paginator....
		if (count($videos) > 0)
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
