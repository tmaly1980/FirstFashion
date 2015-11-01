<p>Dear <?= $user['first'] ?>,

<p>Your sale details are listed below!

<p>To complete your sale, you will need to either:

<p>1.Bring your book to House of Our Own Bookstore (3920 Spruce St) by <?= $timestamp ?>
<p>2.Click here to pick a time before  <?= $timestamp ?> for a BTTB messenger to pick your book up from you 

<p>You’ll need to have your Student ID card with you in both scenarios.

<p>If your book is not dropped off at House of Our Own or picked up by a BTTB messenger by <?= $timestamp ?>, this sale will be canceled and the buyer will be refunded.  We all have classes and need our books quickly.

<?  echo $this->element("email_book_info", array('listings'=>$listings)); ?>



<p><u>Payment information</u>


<p>Your payment will be processed right after the buyer receives the book.  You will receive an email from PayPal with easy instructions on how to retrieve your money (it takes about 2 minutes).  Read our <a href="http://<?= $_SERVER['HTTP_HOST'] ?>/pages/payment">Payment FAQ</a>


<p>Thanks for listing your book with BetterThanTheBookstore.com! 


<p><a href="http://<?= $_SERVER['HTTP_HOST'] ?>/sold_listings/refund/<?= $listing['listing_id'] ?>">Click here to cancel this sale and issue a refund</a>
