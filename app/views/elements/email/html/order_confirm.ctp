<p>Dear <?= $user["first"] ?>,
<p>We have received the following order from you:

<?  echo $this->element("email_book_info", array('listings'=>$listings)); ?>

<p>Retrieval method: pickup<br/>
Total: $<?= sprintf("%.02f", $total) ?>

<?
if (count($listings) > 1) {
?> 
<p>Your books will be available at House of Our Own Bookstore (3920 Spruce St) after you receive emails from pickup@betterthanthebookstore.com confirming their arrival.  
<? 
} else {
?>
<p><b><i>Your book will be available at House of Our Own Bookstore (3920 Spruce St) <u>after</u> you receive an email from pickup@betterthanthebookstore.com confirming its arrival.</i></b>  
<?
}
?>

<p>Thank you for your purchase!


<p>BetterThanTheBookstore.com

