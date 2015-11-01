<div id="member_featured_models_manage">
<h2>Manage Featured Listing</h2>

<p>General introduction to what about. Listed on home page.

<p>To accommodate all featured members, the active list is rotated and randomized to allow each member equal and fair view time.

<p>Keep in mind that you may cancel at any time PRIOR to the start date. However, once the start date commences, you are no longer eligible for a refund.


<?
if ($existing_featured_list)
{
?>
<h3>Featured Listings:</h3>
<table>
	<tr>
		<th>Start Date</th>
		<th>End Date</th>
		<th>Status</th>
		<th>&nbsp;</th>
	</tr>
<?
	# If can cancel, provide link to do so.
	foreach ($existing_featured_list as $existing_featured_entry)
	{
		$featured_model_id = $existing_featured_entry['MemberFeaturedModel']['featured_model_id'];

		$start_time = strtotime($existing_featured_entry['MemberFeaturedModel']['start_date']);
		$end_time = strtotime($existing_featured_entry['MemberFeaturedModel']['end_date']);

		$pretty_start = $time->format('m/d/Y', $existing_featured_entry['MemberFeaturedModel']['start_date']);
		$pretty_end = $time->format('m/d/Y', $existing_featured_entry['MemberFeaturedModel']['end_date']);

		$now = time();

		$status = 'Pending'; # Default.

		if ($start_time > $now) # in the future, allowed to cancel.
		{
			$status = 'Pending';
			$cancel_link = $html->link("Cancel", "/member_featured_models/cancel/$featured_model_id");
		} else {
			# Start time < now time
			if ($end_time > $now) # Active.
			{
				$status = 'Active';
			} else { # $end_time < $now, expired.
				$status = 'Expired';
			}
			$cancel_link = "";
		}

		echo $html->tableCells(
			array(
				$pretty_start,
				$pretty_end,
				$status,
				$cancel_link
			)
		);
	}
	?>
	</table>
	<?
}
?>
</table>

<?
if ($enable_signup)
{
?>
<p>Further details for signing up for a limited-time 'Featured Member' listing:

<p>Price: $<?= $featured_price ?> (one-time fee)
<p>Featured Days: <?= $featured_days ?> days
<p>Estimated Start Date: <?= $time->format('m/d/Y', $start_date); ?>
<p>Estimated End Date: <?= $time->format('m/d/Y', $end_date); ?>

<p>Actual dates may vary.

<p>If you would like to signup for a limited-time 'Featured Member' listing,
<a href="/member_featured_models/checkout">Signup Now</a>! (PayPal Required)

<?
} else {
?>

<p>Please note: You may only sign up for one 'Featured Member' listing at a time. If you have any 'Active' or 'Pending' listings, you may not sign up again until they are all listed as 'Expired' or 'Cancelled'.

<? } ?>

</div>
