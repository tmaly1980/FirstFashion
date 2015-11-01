<?
/*
	For consolidating payment processing, etc all into one location.

*/

class PaymentComponent extends Object
{
	var $name = 'Payment';
	var $components = array('Paypal');
	var $config = array();
	var $controller = null;

	var $test_paypal = false; # Test transactions...

	function startup(&$controller)
	{
		# Specify site-specific details....
		$this->config = require_once(dirname(__FILE__)."/../../config/paypal.conf.php");
		$this->test_paypal = file_exists(dirname(__FILE__)."/../../config/PAYPAL_TESTMODE.TXT");
		error_log("TESTMODE_PAYPAL=".$this->test_paypal);
	#	$this->config["test_paypal"];
		$this->controller = $controller;
	}

	function setAuthentication()
	{
	    if ($this->test_paypal)
	    {
	    	error_log("TESTING PAYPAL SANDBOX....");
	    	$this->Paypal->setEnvironment(CAKE_COMPONENT_PAYPAL_ENVIRONMENT_SANDBOX);
	    	$this->Paypal->setUser($this->config["auth"]["test"]["user"]);
		$this->Paypal->setPassword($this->config["auth"]["test"]["pass"]);

		if (isset($this->config["auth"]["test"]["signature"]))
		{
			$this->Paypal->setSignature($this->config["auth"]["test"]["signature"]);
		}
		# ASSUMES app/paypal_sandbox.pem
		else if (isset($this->config["auth"]["test"]["cert"]))
		{
	    		$this->Paypal->setCertificate(dirname(__FILE__).'/../../' .
				$this->config["auth"]["test"]["cert"]);
		}
		# May need to snoop around for sandbox account info.... (if this doesnt work)
	    } else {
	    	$this->Paypal->setEnvironment(CAKE_COMPONENT_PAYPAL_ENVIRONMENT_LIVE);
	    	$this->Paypal->setUser($this->config["auth"]["prod"]["user"]);
		$this->Paypal->setPassword($this->config["auth"]["prod"]["pass"]);
		
		# ASSUMES app/paypal_live.pem
		if (isset($this->config["auth"]["prod"]["signature"]))
		{
			$this->Paypal->setSignature($this->config["auth"]["prod"]["signature"]);
		}
		else if (isset($this->config["auth"]["prod"]["cert"]))
		{
	    		$this->Paypal->setCertificate(dirname(__FILE__).'/../../' .
				$this->config["auth"]["prod"]["cert"]);
		}

	    }
	}

	function setOrder($order, $desc = '', $url = '')
	{
		if (!is_array($order)) # IN case something special...
		{
			if (!$desc) { $desc = 'Online Order'; }
			$total = $order;
			$order = array(
			'action' => CAKE_COMPONENT_PAYPAL_ORDER_TYPE_SALE,
			'description' => $desc,
			'total' => $total,
			);
		}

		$this->Paypal->setOrder($order);

		if (!$url) { $url = $this->controller->get_url(); }

		if ($url != '') # NO real need to set, if at url already!, paypal lib will figure out this url...
		{
            		$this->Paypal->setTokenUrl("$url/pay?csid=" . session_id());
            		$this->Paypal->setCancelUrl("$url/cancel?csid=" . session_id());
		}
	}

	function submitSubscriptionCheckout($level, $total)
	{
		$this->setAuthentication();

    	    	$path = "http://".$_SERVER['HTTP_HOST']."/members/upgrade/$level";
	}

	function submitCheckout()
	{
	    	$this->setAuthentication();
            	// Save current session
            	if (!$this->Paypal->storeSession())
		{
			$this->setError("Cannot store session"); 
			error_log("CANNOT STORE SESSION");
			return false;
		}
            	$result = $this->Paypal->expressCheckout();

		return $result;
	}

	function refundTransaction($tran_id,$memo = 'Refund')
	{
		$this->setAuthentication();
		# Eventually allow partial, etc.
		return $this->Paypal->refundTransaction($tran_id,$memo);
	}

	function recurringUpdateCheckout()
	{
	    	$this->setAuthentication();
            	// Save current session
            	$this->Paypal->storeSession();
            	$result = $this->Paypal->recurringUpdateCheckout();

		return $result;
	}

	function submitRecurringCheckout()
	{
	    	$this->setAuthentication();
            	// Save current session
            	$this->Paypal->storeSession();
            	$result = $this->Paypal->recurringCheckout();
		error_log("SUBMIT RES=".print_r($result,true));

		return $result;
	}

	function getCheckoutResponse()
	{
	    	$this->setAuthentication();
	        $result = $this->Paypal->expressCheckout();
		return $result;
	}

	function getRecurringCheckoutResponse()
	{
	    	$this->setAuthentication();
	        $result = $this->Paypal->recurringCheckout();
		return $result;
	}

	function restoreSession($csid)
	{
	        $rv = $this->Paypal->restoreSession($csid);
		error_log("RESTORE RC=$rv");
		return $rv;
	}

	function setError($errormsg)
	{
		$this->Paypal->error = $errormsg;
		$this->Paypal->errorCode = 1;
	}

	function getError()
	{
		return $this->Paypal->getError();
	}

	function submitMassPayment($sales)
	{
		# Assumes we have Book, Sales, Seller
		# Need email, note (bttb, book & isbn), amount, uniqueId, 
		$order = array();
		foreach ($sales as $sale)
		{
			$sale_total = $sale['Sales']['sale_total'];
			$bttb_profit = 0.15 * $sale_total;
			if ($bttb_profit < 3) { $bttb_profit = 3; }
			$amount = $sale_total - $bttb_profit;
			# We get 15% or $3, whichever is more.

			$order[] = array(
				'email'=>$sale['Seller']['email'],
				'note'=>"BetterThanTheBookstore.com, " . $sale['Book']['title'] . ", " . $sale['Book']['isbn13'],
				'amount'=>$amount,
				'uniqueId'=>$sale['Sales']['sale_id'],
			);
		}

		print_r($order);
		#exit(0);
		$this->Paypal->setOrder($order);

		return $this->Paypal->massPayment();
		# If failure (false), calling code can call 'getError()'
	}


}

?>
