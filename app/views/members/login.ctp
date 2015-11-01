<?php

#echo __("You must be logged in to view this page.", true);

#echo $this->element('header', array());
# layout will force, since at login page....

# background for 'main content' messed up......
# we want to FORCE a background for whatever is AFTER 'header'

if ($session->check('Message.auth')) $session->flash('auth');

?>

