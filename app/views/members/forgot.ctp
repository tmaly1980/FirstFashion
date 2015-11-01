<?php 
echo "<h2>".__('Send new password via email', true)."</h2>";

echo $form->create('Member', array('action'=>'forgot')); 
echo $form->input('email', array('value'=>''));
echo $form->end('Submit');
?>
