<p>Dear <?= $user['first'] ?>,

<? 
if (count($listings) > 1) { 
	echo "<p>The following book(s) are ready for pickup:";
} else { 
	echo "<p>The following book is ready for pickup:";
} 

echo $this->element("email_book_info", array('listings'=>$listings));
?>

<p>
<a href="mailto:sales@betterthanthebookstore.com">If you have a moment, we would love to have your feedback so we can continue to make BTTB better</a>


<h5>This virtual receipt is emailed to you in place of a paper receipt in order to save paper.</h5>


