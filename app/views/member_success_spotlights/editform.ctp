<div id="member_success_spotlights_form">
	<?= $ajax->form("/member_success_spotlights/editform/<?= $member_id ?>"); 
	echo $form->hidden("

	# XXX MAYBE CAN UPDATE in other container????

	# MAYBE BETTER WAY THAN WITHOUT INTENSE CALENDAR CRAZINESS....

	# maybe can manage using simple list.....
	# and simple ajax sort for list....

	# and maybe all we need is to set 'start' date. esp using dropdowns....????
	# with defaults of adding based when last one in system shown.... (default to first open spot)

	# list to ajax sort will only be those in future or active.....

	# Should be able to have list of WEEKS and can drag between weeks, and have gaps and will 'just know' that 
	# SHOULD just use most recent one....
	# so can decide to have one each week, or one each month or feature one guy longer than others, etc....
	# and will DEFAULT to 'UPCOMING/NEXT WEEK' (so won't mess up active site)
		
	?>
	<table width="100%">
		<?
			echo $html->tableCells(array(
				array(
					$form->input('Member.username', array('disabled'=>'disabled'));
					#"<label>Member:</label>" . ($member ? $member['Member']['username'] : ""),
					# FIX USING disabled forms.
				),
				array(
					$form->input('start_date'),
					"BUTTON CRAP TO GET AJAX CAL-FORM CLICKS TO AFFECT HERE",
				),
				array(
					$form->input('end_date'),
					"BUTTON CRAP TO GET AJAX CAL-FORM CLICKS TO AFFECT HERE",
				),
				array(
					$form->input('comment'),
				),

			));

		?>
	</table>
	<!-- ajax submit??? -->
	<input type=submit name="submit" value="Update">
	<input type=submit name="submit" value="Delete">
	</form>
</div>

	<?
	  # ALWAYS refresh upon load, since changes may affect calendar.
	   echo $ajax->Javascript->event('window','load',
	        $ajax->remoteFunction(
	                array('url'=>"/member_success_spotlights/calendar", 'update'=>"spotlight_calendar")
	        )
	   );
	?>
