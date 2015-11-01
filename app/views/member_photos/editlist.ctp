<?


?>
<div class="memberPhotos index">

<fieldset>
<legend>Photo Album</legend>

<p><b>Double Click</b> on a photo to set it as your primary image, change its information or to replace it with a different photo.</p>
<p>To re-organize your photos, simply drag them to where you want them to go.</p>
<div id="member_albumX" class="member_album_editor">
<div id="member_album_manager">
		<div class="editlink">
			[<a href="/member_photos/add">Add Photo</a>]
		</div>
		<div class="clear"></div>
		<div id="member_album_sortable">
		<?
		$n = 0;
		foreach($memberPhotos as $entry)
		{
			$member_photo = $entry['MemberPhoto'];
			$photo_title = $member_photo["title"];
			$photo_id = $member_photo["photo_id"];
			$is_primary = $member_photo["is_primary"] ? "primary_photo" : "";
		?>
			<!--
			<a class='left padded member_album_photo <?=$is_primary?>' href='/member_photos/edit/<?=$photo_id?>' title="<?= $photo_title ?>">
				<img src="/member_photos/view/small/<?=$photo_id?>">
			</a>
			-->
				<img class="left padded <?= $is_primary ?>" onDblClick="document.location.href='/member_photos/edit/<?=$photo_id?>'; " id="photo_<?= $photo_id ?>" src="/member_photos/view/small/<?=$photo_id?>">
		<?
			$n++;
		}
		?>
		</div>
</div>

<?
# Since list not loaded yet, we need to put code HERE...

echo $ajax->sortable("member_album_sortable", array('tag'=>'img','url'=>"/member_photos/resort/$member_id"));

?>

</fieldset>
</div>
