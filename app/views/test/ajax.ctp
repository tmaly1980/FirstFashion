<?
	echo $ajax->link('Link Name', array('action'=>'ajax_query','junk'), array('update'=>'results','position'=>'bottom'));
	echo $ajax->form('ajax_query', 'post', array('update'=>'results', 'position'=>'bottom'));
	echo "<select name=''><option value='1'>One</option><option value='2'>Two</option></select>";

	echo "<input type=submit>";

	echo "</form>";

?>
	<div id="results">RESULTS...</div>
