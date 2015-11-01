<?php 
/**
 * Paypal Direct Payment API Component class file.
 *
 * @filesource
 * @copyright        Mariano Iglesias - mariano@cricava.com
 * @link            http://www.marianoiglesias.com.ar Mariano Iglesias
 * @package            cake
 * @subpackage        cake.controllers.components
 */

#App::import('Vendor','pear',array('file'=>'pear.inc.php'));

define ('CAKE_COMPONENT_PAYPAL_ENVIRONMENT_LIVE', 'live');
define ('CAKE_COMPONENT_PAYPAL_ENVIRONMENT_SANDBOX', 'sandbox');
define ('CAKE_COMPONENT_PAYPAL_ENVIRONMENT_SANDBOX_BETA', 'beta-sandbox');
define ('CAKE_COMPONENT_PAYPAL_ORDER_TYPE_SALE', 'Sale');

define ('CAKE_COMPONENT_PAYPAL_ORDER_RECURRING_DAILY', 'Day');
define ('CAKE_COMPONENT_PAYPAL_ORDER_RECURRING_WEEKLY', 'Week');
define ('CAKE_COMPONENT_PAYPAL_ORDER_RECURRING_SEMIMONTHLY', 'SemiMonth'); # 1st and 15th
define ('CAKE_COMPONENT_PAYPAL_ORDER_RECURRING_MONTHLY', 'Month');
define ('CAKE_COMPONENT_PAYPAL_ORDER_RECURRING_YEARLY', 'Year');


define ('CAKE_COMPONENT_PAYPAL_RECURRING_STATUS_CANCEL', 'Cancel');
define ('CAKE_COMPONENT_PAYPAL_RECURRING_STATUS_SUSPEND', 'Suspend');
define ('CAKE_COMPONENT_PAYPAL_RECURRING_STATUS_REACTIVATE', 'Reactivate');


define ('CAKE_COMPONENT_PAYPAL_CURRENCY', 'USD');
define ('CAKE_COMPONENT_PAYPAL_CHARSET_DEFAULT', 'iso-8859-1');
define ('CAKE_COMPONENT_PAYPAL_ACK_SUCCESS', 'Success');
define ('CAKE_COMPONENT_PAYPAL_ACK_SUCCESS_WITH_WARNING', 'SuccessWithWarning');
define ('CAKE_COMPONENT_PAYPAL_SESSION_SAVE_PATH', ROOT . DS . APP_DIR . DS . 'tmp' . DS . 'sessions'); // No trailing slash!
define ('CAKE_COMPONENT_PAYPAL_EXPRESS_CHECKOUT_URL', 'https://www.{$environment}.paypal.com/cgi-bin/webscr?cmd=_express-checkout&useraction=commit&token={$token}');

define ('CAKE_COMPONENT_PAYPAL_ERROR_CANT_CREATE_CALLER', 1);
define ('CAKE_COMPONENT_PAYPAL_ERROR_INVALID_ORDER', 2);
define ('CAKE_COMPONENT_PAYPAL_ERROR_INVALID_BUYER', 3);
define ('CAKE_COMPONENT_PAYPAL_ERROR_CANT_GET_AMOUNT_TYPE', 4);
define ('CAKE_COMPONENT_PAYPAL_ERROR_INVALID_CREDIT_CARD_EXPIRATION_DATE', 5);
define ('CAKE_COMPONENT_PAYPAL_ERROR_CREDIT_CARD_NOT_SET', 6);
define ('CAKE_COMPONENT_PAYPAL_ERROR_INVALID_REQUEST', 7);
define ('CAKE_COMPONENT_PAYPAL_ERROR_INVALID_CVV2', 10504);
define ('CAKE_COMPONENT_PAYPAL_ERROR_INVALID_CREDIT_CARD', 10527);

/**
 * Provides a wrapper for Paypal Direct Payment API.
 * 
 * @author        Mariano Iglesias - mariano@cricava.com
 * @package        cake
 * @subpackage    cake.controllers.components
 */
class PaypalComponent extends Object
{
    /**#@+
     * @access protected
     */
    /**
     * Name of this component.
     *
     * @since 1.0
     * @var string
     */
    var $name = 'Paypal';
    /**
     * Components that will be used.
     * 
     * @since 1.0
     * @var array
     */
    var $components = array('Session');
    /**#@-*/
    /**#@+
     * @access private
     */
    /**
     * Settings.
     *
     * @since 1.0
     * @var array
     */
    var $settings = array(
        'api.environment' => CAKE_COMPONENT_PAYPAL_ENVIRONMENT_SANDBOX,
        'api.username' => null,
        'api.password' => null,
        'api.certificate' => null,
        'api.signature' => null,
        'api.charset' => CAKE_COMPONENT_PAYPAL_CHARSET_DEFAULT
    );
    /**
     * Paypal API caller.
     *
     * @since 1.0
     * @var CallerServices
     */
    var $caller;
    /**
     * Last Paypal error code.
     *
     * @since 1.0
     * @var int
     */
    var $errorCode;
    /**
     * Last Paypal error.
     *
     * @since 1.0
     * @var string
     */
    var $error;
    /**#@-*/
    
    /**
     * Startup the component.
     *
     * @param AppController $controller    The controller using the component
     * 
     * @access public
     * @since 1.0
     */
     var $already_restored = array(); # so can call restoreSession multiple times without failing....

    function startup(&$controller)
    {
App::import('Vendor','pear',array('file'=>'pear.inc.php'));
require_once('PayPal.php');
require_once('PayPal/Profile/API.php');
require_once('PayPal/Profile/Handler.php');
require_once('PayPal/Profile/Handler/Array.php');
require_once('PayPal/Type/AbstractResponseType.php');
require_once('PayPal/Type/AddressType.php');
require_once('PayPal/Type/BasicAmountType.php');
require_once('PayPal/Type/CreditCardDetailsType.php');
require_once('PayPal/Type/DoCaptureResponseDetailsType.php');
require_once('PayPal/Type/DoCaptureResponseType.php');
require_once('PayPal/Type/DoDirectPaymentRequestType.php');
require_once('PayPal/Type/DoDirectPaymentRequestDetailsType.php');
require_once('PayPal/Type/DoDirectPaymentResponseType.php');
require_once('PayPal/Type/DoExpressCheckoutPaymentRequestType.php');
require_once('PayPal/Type/DoExpressCheckoutPaymentRequestDetailsType.php');
require_once('PayPal/Type/DoExpressCheckoutPaymentResponseType.php');
require_once('PayPal/Type/DoVoidResponseType.php');
require_once('PayPal/Type/ErrorType.php');
require_once('PayPal/Type/GetExpressCheckoutDetailsRequestType.php');
require_once('PayPal/Type/GetExpressCheckoutDetailsResponseDetailsType.php');
require_once('PayPal/Type/GetExpressCheckoutDetailsResponseType.php');
require_once('PayPal/Type/GetTransactionDetailsResponseType.php');
require_once('PayPal/Type/PayerInfoType.php');
require_once('PayPal/Type/PaymentDetailsType.php');
require_once('PayPal/Type/PersonNameType.php');
require_once('PayPal/Type/RefundTransactionResponseType.php');
require_once('PayPal/Type/SetExpressCheckoutRequestType.php');
require_once('PayPal/Type/SetExpressCheckoutRequestDetailsType.php');
require_once('PayPal/Type/SetExpressCheckoutResponseType.php');
require_once('PayPal/Type/TransactionSearchResponseType.php');

# Mass payment classes....
require_once 'Multi.php';
require_once 'PayPal/Type/MassPayRequestType.php';
require_once 'PayPal/Type/MassPayRequestItemType.php';
require_once 'PayPal/Type/MassPayResponseType.php';
require_once "Log.php";
    }
    
    /**
     * Stores the current session to a temporary file for later retrieval.
     * 
     * @return bool    true if stored, false otherwise
     * 
     * @access public
     * @since 1.0
     */
    function storeSession()
    {
	$file = CAKE_COMPONENT_PAYPAL_SESSION_SAVE_PATH . DS . session_id() . '.ser.tmp';
	#error_log("STORING SESSION=$file");
        $sessionFileHandle = fopen($file, 'w');
                        
        if ($sessionFileHandle !== false)
        {
            fwrite($sessionFileHandle, serialize($_SESSION));
            fclose($sessionFileHandle);

	    #error_log("SAVED SES?=".file_exists($file));
            
            return true;
        }
        
        return false;
    }
    
    /**
     * Restores the specified session.
     * 
     * @param string    Session ID
     * 
     * @return bool    true if able to restore, false otherwise
     * 
     * @access public
     * @since 1.0
     */
    function restoreSession($session_id)
    {
    	if (isset($this->already_restored[$session_id])) { return true; }

    	#error_log("RESTORE_SESS_ID=$session_id");
	# is this being called TWICE?????
        if (preg_match('/^[A-Za-z0-9]*$/', $session_id))
        {
            $sessionFile = CAKE_COMPONENT_PAYPAL_SESSION_SAVE_PATH . DS . $session_id . '.ser.tmp';
	    #error_log("FILE=$sessionFile");

            
            if (@file_exists($sessionFile) && @is_file($sessionFile) && @is_readable($sessionFile) && @filesize($sessionFile) > 0)
            {
                $contents = file_get_contents($sessionFile);
		#error_log("CONTENTS=".print_r($contents,true));
                $oldSession = @unserialize($contents);
                
                if (is_array($oldSession) && count($oldSession) > 0)
                {
                    foreach($oldSession as $id => $value)
                    {
                        $this->Session->write($id, $value);
                    }
                }

    		$this->already_restored[$session_id] = true; # So future calls don't foobar...
                
               @unlink($sessionFile);
                
                return true;
            }
        }
        
        return false;
    }
    
    /**
     * Sets the API environment.
     * 
     * @param string $environment    API environment.
     * 
     * @access public
     * @since 1.0
     */
    function setEnvironment($environment)
    {
        if (in_array($environment, array(CAKE_COMPONENT_PAYPAL_ENVIRONMENT_LIVE, CAKE_COMPONENT_PAYPAL_ENVIRONMENT_SANDBOX, CAKE_COMPONENT_PAYPAL_ENVIRONMENT_SANDBOX_BETA)))
        {
            $this->settings['api.environment'] = $environment;
        }
    }
    
    /**
     * Sets the full path to the certificate file.
     * 
     * @param string $certificate    Path to Certificate file.
     * 
     * @access public
     * @since 1.0
     */
    function setCertificate($certificate)
    {
        $this->settings['api.certificate'] = $certificate;
    }
    
    /**
     * Sets the API signature.
     * 
     * @param string $signature    API signature.
     * 
     * @access public
     * @since 1.0
     */
    function setSignature($signature)
    {
        $this->settings['api.signature'] = $signature;
    }
    
    /**
     * Sets the API user.
     * 
     * @param string $user    API user.
     * 
     * @access public
     * @since 1.0
     */
    function setUser($user)
    {
        $this->settings['api.username'] = $user;
    }
    
    /**
     * Sets the API password.
     * 
     * @param string $password    API password.
     * 
     * @access public
     * @since 1.0
     */
    function setPassword($password)
    {
        $this->settings['api.password'] = $password;
    }
    
    /**
     * Sets the default charset.
     * 
     * @param string $charset    charset (example: iso-8859-1)
     * 
     * @access public
     * @since 1.0
     */
    function setCharset($charset)
    {
        $this->settings['api.charset'] = $charset;
    }
    
    /**
     * Sets the URL to which PayPal Express Checkout will redirect when setting the token.
     * 
     * @param string $uri    The URI (for example, $this->here from a controller)
     * 
     * @access public
     * @since 1.0
     */
    function setTokenUrl($uri)
    {
        $this->settings['express.token_uri'] = $uri;
    }
    
    /**
     * Sets the URL to which PayPal Express Checkout will redirect when order cancelled.
     * 
     * @param string $uri    The URI (for example, $this->here from a controller)
     * 
     * @access public
     * @since 1.0
     */
    function setCancelUrl($uri)
    {
        $this->settings['express.cancel_uri'] = $uri;
    }
    
    /**
     * Sets the order to be processed. The order is an indexed array.
     * Example:
     * 
     * Array
     * (
     *     [action] => Sale
     *     [description] => ORDER_DESCRIPTION
     *     [id] => INVOICE_ID
     *     [total] => 200
     *     [buyer] => Array
     *         (
     *             [first] => FIRST_NAME
     *             [last] => LAST_NAME
     *             [address1] => ADDRESS_1
     *             [address2] => ADDRESS_2
     *             [city] => CITY
     *             [state] => STATE (two letter for US)
     *             [zip] => ZIP
     *             [country] => us
     *         )
     *     [cc] => Array
     *         (
     *             [type] => Visa/MasterCard/Discovery/Amex
     *             [number] => CC_NUMBER
     *             [expiration] => 1/2010
     *             [cvv2] => 123
     *             [owner] => Array
     *                 (
     *                     [first] => HOLDER_FIRST_NAME
     *                     [last] => HOLDER_LAST_NAME
     *                 )
     *         )
     * )
     * 
     * @param array $order    Order to be processed.
     * 
     * @access public
     * @since 1.0
     */
    function setOrder($order)
    {
        $this->settings['order'] = $order;
    }
    
    /**
     * Gets the latest error message. If more than one error message was
     * reported by PayPal, each message is separated by a new line.
     * 
     * @return string    Latest error message (null if none).
     * 
     * @access public
     * @since 1.0
     */
    function getError()
    {
        return $this->error;
    }
    
    /**
     * Gets the latest error code.
     * 
     * @return int    latest error code (0 if no error).
     * 
     * @access public
     * @since 1.0
     */
    function getErrorCode()
    {
        return $this->errorCode;
    }
    
    /**
     * Performs a direct payment with the specified order. If order was processed,
     * returns an indexed array with:
     * 
     * - transaction: Paypal Transaction id
     * - ack: success code
     * - avs: AVS response code for US credit cards (see https://www.paypal.com/IntegrationCenter/ic_direct-payment.html)
     * - cvv2: CVV response code for US credit cards (see - avs: AVS response code for US credit cards (see https://www.paypal.com/IntegrationCenter/ic_direct-payment.html)
     * - amount: processed amount
     * 
     * In case of error it returns false and sets error code and error message.
     * 
     * @return mixed    array if success, false otherwise.
     * 
     * @since 1.0
     * @access public
     */
    function directPayment()
    {
        $this->_initialize();
        
        if (!isset($this->caller))
        {
            return false;
        }
        
        if (!isset($this->settings['order']) || !isset($this->settings['order']['total']) || !isset($this->settings['order']['action']))
        {
            $this->errorCode = CAKE_COMPONENT_PAYPAL_ERROR_INVALID_ORDER;
            $this->error = 'Order was not set up properly';
            return false;
        }
        
        $this->errorCode = 0;
        $this->error = null;
        
        // Set up buyer's information
        
        $shipTo =& PayPal::getType('AddressType');
        
        if (isset($this->settings['order']['buyer']))
        {
            $this->settings['order']['buyer']['country'] = strtoupper($this->settings['order']['buyer']['country']);
            
            if ($this->settings['order']['buyer']['country'] == 'US')
            {
                $this->settings['order']['buyer']['state'] = strtoupper($this->settings['order']['buyer']['state']);
            }
            
            $shipTo->setName($this->settings['order']['buyer']['first'] . ' ' . $this->settings['order']['buyer']['last']);
            $shipTo->setStreet1($this->settings['order']['buyer']['address1']);
            
            if (isset($this->settings['order']['buyer']['address2']))
            {
                $shipTo->setStreet2($this->settings['order']['buyer']['address2']);
            }
            
            $shipTo->setCityName($this->settings['order']['buyer']['city']);
            $shipTo->setStateOrProvince($this->settings['order']['buyer']['state']);
            $shipTo->setPostalCode($this->settings['order']['buyer']['zip']);
            $shipTo->setCountry($this->settings['order']['buyer']['country']);
        }
        else
        {
            $this->errorCode = CAKE_COMPONENT_PAYPAL_ERROR_INVALID_BUYER;
            $this->error = 'Buyer was not set in order';
            return false;
        }
        
        // Set up total $ for order
        
        $orderTotal =& PayPal::getType('BasicAmountType');
        
        if (PayPal::isError($orderTotal)) 
        {
            $this->errorCode = CAKE_COMPONENT_PAYPAL_ERROR_CANT_GET_AMOUNT_TYPE;
            $this->error = $orderTotal->getMessage();
            return false;
        }
        
        $orderTotal->setattr('currencyID', CAKE_COMPONENT_PAYPAL_CURRENCY);
        $orderTotal->setval($this->settings['order']['total'], $this->settings['api.charset']);
        
        // Set up payment details
        
        $paymentDetails =& PayPal::getType('PaymentDetailsType');
        
        $paymentDetails->setOrderTotal($orderTotal);
        $paymentDetails->setShipToAddress($shipTo);
        
        if (isset($this->settings['order']['description']))
        {
            $paymentDetails->setOrderDescription($this->settings['order']['description'], $this->settings['api.charset']);
        }
        
        if (isset($this->settings['order']['id']))
        {
            $paymentDetails->setInvoiceId($this->settings['order']['id'], $this->settings['api.charset']);
        }
        
        // Set up credit card information
        
        $cardDetails =& PayPal::getType('CreditCardDetailsType');
        
        if (isset($this->settings['order']['cc']))
        {
            $personDetails =& PayPal::getType('PersonNameType');
            
            if (isset($this->settings['order']['cc']['owner']))
            {
                $personDetails->setFirstName($this->settings['order']['cc']['owner']['first']);
                $personDetails->setLastName($this->settings['order']['cc']['owner']['last']);
            }
            else
            {
                $personDetails->setFirstName($this->settings['order']['buyer']['first']);
                $personDetails->setLastName($this->settings['order']['buyer']['last']);
            }
            
            $payerDetails =& PayPal::getType('PayerInfoType');
            
            $payerDetails->setPayerName($personDetails);
            $payerDetails->setPayerCountry($this->settings['order']['buyer']['country']);
            $payerDetails->setAddress($shipTo);
            
            $cardDetailsExpiration = explode('/', $this->settings['order']['cc']['expiration']);
            
            if (count($cardDetailsExpiration) != 2 || $cardDetailsExpiration[0] < 1 || $cardDetailsExpiration[0] > 12 || $cardDetailsExpiration[1] < date('Y'))
            {
                $this->errorCode = CAKE_COMPONENT_PAYPAL_ERROR_INVALID_CREDIT_CARD_EXPIRATION_DATE;
                $this->error = 'Credit Card Expiration date seems to be wrong (format: month/year)';
                return false;
            }
            
            $cardDetailsExpiration[0] = str_pad($cardDetailsExpiration[0], 2, '0', STR_PAD_LEFT);
            
            $cardDetails->setCreditCardType($this->settings['order']['cc']['type']);
            $cardDetails->setCreditCardNumber($this->settings['order']['cc']['number']);
            $cardDetails->setCVV2($this->settings['order']['cc']['cvv2']);
            $cardDetails->setExpMonth($cardDetailsExpiration[0]);
            $cardDetails->setExpYear($cardDetailsExpiration[1]);
            $cardDetails->setCardOwner($payerDetails);
        }
        else
        {
            $this->errorCode = CAKE_COMPONENT_PAYPAL_ERROR_CREDIT_CARD_NOT_SET;
            $this->error = 'Credit Card was not set in order';
            return false;
        }
        
        // Set up request details
        
        $requestDetails =& PayPal::getType('DoDirectPaymentRequestDetailsType');
        
        $requestDetails->setPaymentDetails($paymentDetails);
        $requestDetails->setCreditCard($cardDetails);
        $requestDetails->setPaymentAction($this->settings['order']['action']);
        $requestDetails->setIPAddress($_SERVER['SERVER_ADDR']);
        
        // Set up request
        
        $request =& PayPal::getType('DoDirectPaymentRequestType');
        
        $request->setDoDirectPaymentRequestDetails($requestDetails);
        
        // Execute request
        
        $response = $this->caller->DoDirectPayment($request);
        
        if (PayPal::isError($response))
        {
            $this->errorCode = CAKE_COMPONENT_PAYPAL_ERROR_INVALID_REQUEST;
            $this->error = $response->getMessage();
            
            return false;
        }
        
        $response_ack = $response->getAck();
        
        if ($response_ack == CAKE_COMPONENT_PAYPAL_ACK_SUCCESS || $response_ack == CAKE_COMPONENT_PAYPAL_ACK_SUCCESS_WITH_WARNING)
        {
            $response_amount = $response->getAmount();
            
            $result = array (
                'transaction' => $response->getTransactionID(),
                'ack' => $response_ack,
                'avs' => $response->getAVSCode(),
                'cvv2' => $response->getCVV2Code(),
                'amount' => $response_amount->_value
            );
            
            return $result;
        }
        else
        {
            $errorList = $response->getErrors();
            
            if(!is_array($errorList))
            {
                $this->errorCode = $errorList->getErrorCode();
                $this->error = '#' . $errorList->getErrorCode() . ': ' . $errorList->getShortMessage() . ' [' . $errorList->getLongMessage() . ']';
            }
            else
            {
                $this->error = '';
                
                foreach($errorList as $error)
                {
                    if (!empty($this->error))
                    {
                        $this->error .= "\n";
                    }
                    
                    $this->errorCode = $error->getErrorCode();
                    $this->error .= '#' . $error->getErrorCode() . ': ' . $error->getShortMessage() . ' [' . $error->getLongMessage() . ']';
                }
            }
        }
        
        return false;
    }

    function massPayment()
    {
        $this->_initialize();
        
        if (!isset($this->caller))
        {
            return false;
        }
        
        if (!isset($this->settings['order']) || count($this->settings['order']) == 0 || !isset($this->settings['order'][0]['amount']))
        {
            $this->errorCode = CAKE_COMPONENT_PAYPAL_ERROR_INVALID_ORDER;
            $this->error = 'Order was not set up properly';
            return false;
        }
        
        $this->errorCode = 0;
        $this->error = null;

	echo "OK....1\n";

        $masspay_request =& PayPal::getType('MassPayRequestType');
	echo "OK....3\n";
	if (PayPal::isError($masspay_request)) 
	{
		error_log("ERROR IN REQUEST...");
		return false;
	}
	echo "OK....2\n";

        // Set up request details
	# Assumes 'order' has keys 'email', 'note', 'uniqueID', 'amount'
	$massPayItems = array();
	$i = 0;
	foreach ($this->settings['order'] as $item)
	{
		$massPayItems[$i] =& PayPal::getType('MassPayRequestItemType');
		$massPayItems[$i]->setReceiverEmail($item['email']);
		$massPayItems[$i]->setNote($item['note']);
		$massPayItems[$i]->setUniqueId($item['uniqueId']);
		$amtType =& PayPal::getType('BasicAmountType');
		$amtType->setattr('currencyID','USD');
		$amtType->setval($item['amount'],'iso-8859-1');
		$massPayItems[$i]->setAmount($amtType);
		$i++;
	}


	$emailSubject = 'BetterThanTheBookstore.com Payment';
	$masspay_request->setEmailSubject($emailSubject);
	$receiverType = 'EmailAddress';
	$masspay_request->setReceiverType($receiverType);

	echo "OK....4\n";

	//creating multiple occurences of MassPayRequestItem
	$multiItems =&new MultiOccurs($masspay_request, 'MassPayRequestItem');
	$multiItems->setChildren($massPayItems);
	$masspay_request->setMassPayItem($multiItems);

	echo "OK....5\n";

	$response =$this->caller->MassPay($masspay_request);

	echo "OK....6\n";
	#exit(0);

        if (PayPal::isError($response))
        {
            $this->errorCode = CAKE_COMPONENT_PAYPAL_ERROR_INVALID_REQUEST;
            $this->error = $response->getMessage();
            
            return false;
        }
        
	echo "OK....7\n";
        $response_ack = $response->getAck();
	echo "OK....8 $response_ack\n";
    
        if ($response_ack == CAKE_COMPONENT_PAYPAL_ACK_SUCCESS || $response_ack == CAKE_COMPONENT_PAYPAL_ACK_SUCCESS_WITH_WARNING)
        {
		echo "OK....9a\n";
	#exit(0);
        
		return true;
        }
        else
        {
		echo "OK....9b\n";
            $errorList = $response->getErrors();
            
		echo "OK....10\n";
            if(!is_array($errorList))
            {
                $this->errorCode = $errorList->getErrorCode();
                $this->error = '#' . $errorList->getErrorCode() . ': ' . $errorList->getShortMessage() . ' [' . $errorList->getLongMessage() . ']';
            }
            else
            {
                $this->error = '';
                
                foreach($errorList as $error)
                {
                    if (!empty($this->error))
                    {
                        $this->error .= "\n";
                    }
                    
                    $this->errorCode = $error->getErrorCode();
                    $this->error .= '#' . $error->getErrorCode() . ': ' . $error->getShortMessage() . ' [' . $error->getLongMessage() . ']';
                }
                
                return false;
            }
        }
        
        return false;
    }
    
    /**
     * Performs an express checkout payment with the specified order. If order was processed,
     * returns an indexed array with:
     * 
     * - transaction: Paypal Transaction id
     * - ack: success code
     * - amount: processed amount
     * 
     * In case of error it returns false and sets error code and error message.
     * 
     * Please note that this function should be called TWICE to make an actual payment. First call
     * sets environment and paypal token up and tells paypal to redirect back to the URL specified
     * by the function setTokenUrl(). Once this URL is reached (meaning paypal has sent us the
     * generated token) then this function should be called again to perform the actual payment.
     * 
     * @return mixed    array if success, false otherwise.
     * 
     * @since 1.0
     * @access public
     */
    function expressCheckout()
    {
        $this->_initialize();
        
        if (!isset($this->caller))
        {
            return false;
        }
        
        if (!isset($this->settings['order']) || !isset($this->settings['order']['total']) || !isset($this->settings['order']['action']))
        {
            $this->errorCode = CAKE_COMPONENT_PAYPAL_ERROR_INVALID_ORDER;
            $this->error = 'Order was not set up properly';
            return false;
        }

        
        $this->errorCode = 0;
        $this->error = null;

	try {
        
        if (!isset($_REQUEST['token']))
        {
            if (isset($this->settings['express.token_uri']))
            {
                $tokenUrl = $this->settings['express.token_uri'];
            }
            else
            {
                $serverName = $_SERVER['SERVER_NAME'];
                $serverPort = $_SERVER['SERVER_PORT'];
                
                $pathParts = pathinfo($_SERVER['SCRIPT_NAME']);
                $pathInfo = $pathParts['dirname'];
            
                $tokenUrl = 'http://';
               
                if (isset($_SERVER['HTTPS']) && strcasecmp($_SERVER['HTTPS'], 'on') == 0)
                {
                    $tokenUrl = 'https://';
                }
               
                $tokenUrl .= $serverName . ($serverPort != 80 ? ':' . $serverPort : '');
                $tokenUrl .= $pathInfo;
                $tokenUrl .= '/' . $_SERVER['SCRIPT_NAME'];
                
                if (!empty($_SERVER['QUERY_STRING']))
                {
                    $tokenUrl .= '?' . $_SERVER['QUERY_STRING'];
                }
            }
            
            if (isset($this->settings['express.cancel_uri']))
            {
                $cancelUrl = $this->settings['express.cancel_uri'];
            }
            
            // Set up total $ for order
            
            $orderTotal =& PayPal::getType('BasicAmountType');
            
            if (PayPal::isError($orderTotal)) 
            {
                $this->errorCode = CAKE_COMPONENT_PAYPAL_ERROR_CANT_GET_AMOUNT_TYPE;
                $this->error = $orderTotal->getMessage();
                return false;
            }
            
            $orderTotal->setattr('currencyID', CAKE_COMPONENT_PAYPAL_CURRENCY);
            $orderTotal->setval($this->settings['order']['total'], $this->settings['api.charset']);
            
            // Set up request details
            
            $requestDetails =& PayPal::getType('SetExpressCheckoutRequestDetailsType');
            
            $requestDetails->setReturnURL($tokenUrl);
            $requestDetails->setCancelURL($cancelUrl);
            $requestDetails->setPaymentAction($this->settings['order']['action']);
            $requestDetails->setOrderTotal($orderTotal);
            
            // Set up request
	    $request =& PayPal::getType('SetExpressCheckoutRequestType');
	    $request->setSetExpressCheckoutRequestDetails($requestDetails);
            
            // Execute request
            
            $response = $this->caller->SetExpressCheckout($request);
            
            if (PayPal::isError($response))
            {
                $this->errorCode = CAKE_COMPONENT_PAYPAL_ERROR_INVALID_REQUEST;
                $this->error = $response->getMessage();
                
                return false;
            }
            
            $response_ack = $response->getAck();
        
            if ($response_ack == CAKE_COMPONENT_PAYPAL_ACK_SUCCESS || $response_ack == CAKE_COMPONENT_PAYPAL_ACK_SUCCESS_WITH_WARNING)
            {
                $token = $response->getToken();
                      
                $payPalUrl = CAKE_COMPONENT_PAYPAL_EXPRESS_CHECKOUT_URL;
                      
                $payPalUrl = str_replace('{$environment}', $this->settings['api.environment'], $payPalUrl);
                $payPalUrl = str_replace('{$token}', $token, $payPalUrl);
                
                header('Location: ' . $payPalUrl);
                exit;
            }
            else
            {
                $errorList = $response->getErrors();
                
                if(!is_array($errorList))
                {
                    $this->errorCode = $errorList->getErrorCode();
                    $this->error = '#' . $errorList->getErrorCode() . ': ' . $errorList->getShortMessage() . ' [' . $errorList->getLongMessage() . ']';
                }
                else
                {
                    $this->error = '';
                    
                    foreach($errorList as $error)
                    {
                        if (!empty($this->error))
                        {
                            $this->error .= "\n";
                        }
                        
                        $this->errorCode = $error->getErrorCode();
                        $this->error .= '#' . $error->getErrorCode() . ': ' . $error->getShortMessage() . ' [' . $error->getLongMessage() . ']';
                    }
                    
                    return false;
                }
            }
        }
        else
        {
            // Set up request
            
            $request =& PayPal::getType('GetExpressCheckoutDetailsRequestType');
            
            $request->setToken($_REQUEST['token']);
            
            // Execute request
            
            $response = $this->caller->GetExpressCheckoutDetails($request);
            
            if (PayPal::isError($response))
            {
                $this->errorCode = CAKE_COMPONENT_PAYPAL_ERROR_INVALID_REQUEST;
                $this->error = $response->getMessage();
                
                return false;
            }
            
            $response_ack = $response->getAck();
        
            if ($response_ack == CAKE_COMPONENT_PAYPAL_ACK_SUCCESS || $response_ack == CAKE_COMPONENT_PAYPAL_ACK_SUCCESS_WITH_WARNING)
            {
                $responseDetails = $response->getGetExpressCheckoutDetailsResponseDetails();
                
                $payerInfo = $responseDetails->getPayerInfo();
                $payerId = $payerInfo->getPayerID();
            }
            else
            {
                $errorList = $response->getErrors();
                
                if(!is_array($errorList))
                {
                    $this->errorCode = $errorList->getErrorCode();
                    $this->error = '#' . $errorList->getErrorCode() . ': ' . $errorList->getShortMessage() . ' [' . $errorList->getLongMessage() . ']';
                }
                else
                {
                    $this->error = '';
                    
                    foreach($errorList as $error)
                    {
                        if (!empty($this->error))
                        {
                            $this->error .= "\n";
                        }
                        
                        $this->errorCode = $error->getErrorCode();
                        $this->error .= '#' . $error->getErrorCode() . ': ' . $error->getShortMessage() . ' [' . $error->getLongMessage() . ']';
                    }
                }
                
                return false;
            }
            
            // Set up total $ for order
            
            $orderTotal =& PayPal::getType('BasicAmountType');
            
            if (PayPal::isError($orderTotal)) 
            {
                $this->errorCode = CAKE_COMPONENT_PAYPAL_ERROR_CANT_GET_AMOUNT_TYPE;
                $this->error = $orderTotal->getMessage();
                
                return false;
            }
            
            $orderTotal->setattr('currencyID', CAKE_COMPONENT_PAYPAL_CURRENCY);
            $orderTotal->setval($this->settings['order']['total'], $this->settings['api.charset']);
            
            // Set up payment details
        
            $paymentDetails =& PayPal::getType('PaymentDetailsType');
            
            $paymentDetails->setOrderTotal($orderTotal);
            
            if (isset($this->settings['order']['description']))
            {
                $paymentDetails->setOrderDescription($this->settings['order']['description'], $this->settings['api.charset']);
            }
            
            if (isset($this->settings['order']['id']))
            {
                $paymentDetails->setInvoiceId($this->settings['order']['id'], $this->settings['api.charset']);
            }
            
            // Set up request details
            
            $requestDetails =& PayPal::getType('DoExpressCheckoutPaymentRequestDetailsType');
            
            $requestDetails->setToken($_REQUEST['token']);
            $requestDetails->setPayerID($payerId);
            $requestDetails->setPaymentAction($this->settings['order']['action']);
            $requestDetails->setPaymentDetails($paymentDetails);
            
            // Set up request
            
            $request =& PayPal::getType('DoExpressCheckoutPaymentRequestType');
            
            $request->setDoExpressCheckoutPaymentRequestDetails($requestDetails);
            
            // Execute request
            
            $response = $this->caller->DoExpressCheckoutPayment($request);
            
            if (PayPal::isError($response))
            {
                $this->errorCode = CAKE_COMPONENT_PAYPAL_ERROR_INVALID_REQUEST;
                $this->error = $response->getMessage();
                
                return false;
            }
            
            $response_ack = $response->getAck();
            
            if ($response_ack == CAKE_COMPONENT_PAYPAL_ACK_SUCCESS || $response_ack == CAKE_COMPONENT_PAYPAL_ACK_SUCCESS_WITH_WARNING)
            {
                $responseDetails = $response->getDoExpressCheckoutPaymentResponseDetails();
                $responsePaymentInfo = $responseDetails->getPaymentInfo();
                
                $response_amount = $responsePaymentInfo->getGrossAmount();
                
                $result = array (
                    'transaction' => $responsePaymentInfo->getTransactionID(),
                    'ack' => $response_ack,
                    'amount' => $response_amount->_value
                );
                
                return $result;
            }
            else
            {
                $errorList = $response->getErrors();
                
                if(!is_array($errorList))
                {
                    $this->errorCode = $errorList->getErrorCode();
                    $this->error = '#' . $errorList->getErrorCode() . ': ' . $errorList->getShortMessage() . ' [' . $errorList->getLongMessage() . ']';
                }
                else
                {
                    $this->error = '';
                    
                    foreach($errorList as $error)
                    {
                        if (!empty($this->error))
                        {
                            $this->error .= "\n";
                        }
                        
                        $this->errorCode = $error->getErrorCode();
                        $this->error .= '#' . $error->getErrorCode() . ': ' . $error->getShortMessage() . ' [' . $error->getLongMessage() . ']';
                    }
                }
                
                return false;
            }
        }

	} catch (Exception $e) { 
		error_log($e->getMessage());
            	$this->errorCode = CAKE_COMPONENT_PAYPAL_ERROR_INVALID_ORDER;
            	$this->error = $e->getMessage();
		return false;
	}
        
        return false;
    }

    function refundTransaction($transaction_id, $memo = 'Refund')
    # Eventually allow partial, etc. by settings['order'], etc...
    {
    	$this->_initialize();

	$request =& PayPal::getType('RefundTransactionRequestType');
	$request->setTransactionID($transaction_id);
	$request->setRefundType('Full');
	$request->setMemo($memo);

        $response = $this->caller->RefundTransaction($request);

	$response_ack = $response->getAck();

        if ($response_ack != CAKE_COMPONENT_PAYPAL_ACK_SUCCESS && $response_ack != CAKE_COMPONENT_PAYPAL_ACK_SUCCESS_WITH_WARNING)
        {
		$errorList = $response->getErrors();
                if(!is_array($errorList))
                {
                    $this->errorCode = $errorList->getErrorCode();
                    $this->error = '#' . $errorList->getErrorCode() . ': ' . $errorList->getShortMessage() . ' [' . $errorList->getLongMessage() . ']';
                }
                else
                {
                    $this->error = '';
                    
                    foreach($errorList as $error)
                    {
                        if (!empty($this->error))
                        {
                            $this->error .= "\n";
                        }
                        
                        $this->errorCode = $error->getErrorCode();
                        $this->error .= '#' . $error->getErrorCode() . ': ' . $error->getShortMessage() . ' [' . $error->getLongMessage() . ']';
                    }
                }
            
            return false;
        }
	return true;

    }

    function recurringUpdateCheckout() # For updating payments, cancelation, suspension, etc.
    {
        $this->_initialize();
        
        if (!isset($this->caller))
        {
            return false;
        }
        
        if (!isset($this->settings['order']) || !isset($this->settings['order']['profile_id']))
        {
            $this->errorCode = CAKE_COMPONENT_PAYPAL_ERROR_INVALID_ORDER;
            $this->error = 'Order was not set up properly';
            return false;
        }
        
        $this->errorCode = 0;
        $this->error = null;
        
        // Set up request
            
	$request_details =& PayPal::getType('ManageRecurringPaymentsProfileStatusRequestDetailsType');
	$request_details->setProfileID($this->settings['order']['profile_id']);
	$request_details->setAction($this->settings['order']['action']);
	$request_details->setNote($this->settings['order']['note']);

	$request =& PayPal::getType('ManageRecurringPaymentsProfileStatusRequestType');
	$request->setManageRecurringPaymentsProfileStatusRequestDetails($request_details);

	#error_log("REQUEST=".print_r($request,true));

        $response = $this->caller->ManageRecurringPaymentsProfileStatus($request);
	$response_details = $response->getManageRecurringPaymentsProfileStatusResponseDetails();

	#error_log("RESPONSE=".print_r($response,true));

	$response_ack = $response->getAck();

	#error_log("ISERR=".PayPal::isError($response_details));
	#error_log("ACK=".print_r($response_ack,true));

	#$response_ack = $response_details->getAck();
        
        if ($response_ack != CAKE_COMPONENT_PAYPAL_ACK_SUCCESS && $response_ack != CAKE_COMPONENT_PAYPAL_ACK_SUCCESS_WITH_WARNING)
	#PayPal::isError($response_details->getAck() != '))
        {
		$errorList = $response->getErrors();
                if(!is_array($errorList))
                {
                    $this->errorCode = $errorList->getErrorCode();
                    $this->error = '#' . $errorList->getErrorCode() . ': ' . $errorList->getShortMessage() . ' [' . $errorList->getLongMessage() . ']';
                }
                else
                {
                    $this->error = '';
                    
                    foreach($errorList as $error)
                    {
                        if (!empty($this->error))
                        {
                            $this->error .= "\n";
                        }
                        
                        $this->errorCode = $error->getErrorCode();
                        $this->error .= '#' . $error->getErrorCode() . ': ' . $error->getShortMessage() . ' [' . $error->getLongMessage() . ']';
                    }
                }
            #$this->errorCode = CAKE_COMPONENT_PAYPAL_ERROR_INVALID_REQUEST;
            #$this->error = $response_details->getMessage();
            
            return false;
        }

    }

    function getTokenUrl()
    {
    	$tokenUrl = '';
            if (isset($this->settings['express.token_uri']))
            {
                $tokenUrl = $this->settings['express.token_uri'];
            }
            else
            {
                $serverName = $_SERVER['SERVER_NAME'];
                $serverPort = $_SERVER['SERVER_PORT'];
                
                $pathParts = pathinfo($_SERVER['SCRIPT_NAME']);
                $pathInfo = $pathParts['dirname'];
            
                $tokenUrl = 'http://';
               
                if (isset($_SERVER['HTTPS']) && strcasecmp($_SERVER['HTTPS'], 'on') == 0)
                {
                    $tokenUrl = 'https://';
                }
               
                $tokenUrl .= $serverName . ($serverPort != 80 ? ':' . $serverPort : '');
                $tokenUrl .= $pathInfo;
                $tokenUrl .= '/' . $_SERVER['SCRIPT_NAME'];
                
                if (!empty($_SERVER['QUERY_STRING']))
                {
                    $tokenUrl .= '?' . $_SERVER['QUERY_STRING'];
                }
            }
	    return $tokenUrl;
    }

    function getCancelUrl()
    {
	    $cancelUrl = ''; # What happens if not specified???
            if (isset($this->settings['express.cancel_uri']))
            {
                $cancelUrl = $this->settings['express.cancel_uri'];
            }
	    return $cancelUrl;
    }

    function createOrderTotal()
    {
            $orderTotal =& PayPal::getType('BasicAmountType');
            
            if (PayPal::isError($orderTotal)) 
            {
                $this->errorCode = CAKE_COMPONENT_PAYPAL_ERROR_CANT_GET_AMOUNT_TYPE;
                $this->error = $orderTotal->getMessage();
		#throw new Exception($this->error);
                return false;
            }

	    #error_log("ORDER=".print_r($this->settings['order'],true));
            
            $orderTotal->setattr('currencyID', CAKE_COMPONENT_PAYPAL_CURRENCY);
            $orderTotal->setval($this->settings['order']['total'], $this->settings['api.charset']);
	    return $orderTotal;
    }

	function createBillingAgreementDetails()
	{
	    $billingDetails =& PayPal::getType('BillingAgreementDetailsType');

	    $billingDetails->setBillingType('RecurringPayments');
	    $billingDetails->setBillingAgreementDescription($this->settings['order']['billing_description']);

	    return $billingDetails;
	}

	function createSetExpressCheckoutRequest($billingDetails = null)
	{
		$requestDetails = $this->createSetExpressCheckoutRequestDetails($billingDetails);

            	$request =& PayPal::getType('SetExpressCheckoutRequestType');
            	$request->setSetExpressCheckoutRequestDetails($requestDetails);

		return $request;
	}

   	function createSetExpressCheckoutRequestDetails($billingDetails = null)
	# We can reuse this elsewhere, too...
   	{
	    $tokenUrl = $this->getTokenUrl();
	    $cancelUrl = $this->getCancelUrl();

	    if (!$cancelUrl)
	    {
	    	error_log("No cancelUrl specified...");
	    	return false;
	    }

            // Set up total $ for order
	    $orderTotal = $this->createOrderTotal();
	    if (!$orderTotal) { return false; }

            $requestDetails =& PayPal::getType('SetExpressCheckoutRequestDetailsType');
            $requestDetails->setReturnURL($tokenUrl);
            $requestDetails->setCancelURL($cancelUrl);
            $requestDetails->setPaymentAction($this->settings['order']['action']);
            $requestDetails->setOrderTotal($orderTotal);

	    if ($billingDetails)
	    {
            	$requestDetails->setBillingAgreementDetails($billingDetails);
	    }

	    return $requestDetails;
    	}

	function processErrors($response)
	{

        	$errorList = $response->getErrors();
                
                if(!is_array($errorList))
                {
                    $this->errorCode = $errorList->getErrorCode();
                    $this->error = '#' . $errorList->getErrorCode() . ': ' . $errorList->getShortMessage() . ' [' . $errorList->getLongMessage() . ']';
                }
                else
                {
                   $this->error = '';
                    
                   foreach($errorList as $error)
                   {
                       	if (!empty($this->error))
                       	{
                       		$this->error .= "\n";
                       	}
                       		 
                       	$this->errorCode = $error->getErrorCode();
                       	$this->error .= '#' . $error->getErrorCode() . ': ' . $error->getShortMessage() . ' [' . $error->getLongMessage() . ']';
                    }
                    
                    return false;
                }

	}

	function createPaymentDetails($orderTotal = '')
	{
        
       		$paymentDetails =& PayPal::getType('PaymentDetailsType');
           
       		$paymentDetails->setOrderTotal($orderTotal);
            
       		if (isset($this->settings['order']['description']))
            {
                $paymentDetails->setOrderDescription($this->settings['order']['description'], $this->settings['api.charset']);
            }
            
            if (isset($this->settings['order']['id']))
            {
                $paymentDetails->setInvoiceId($this->settings['order']['id'], $this->settings['api.charset']);
            }
	    	return $paymentDetails;
	}

	function createBillingPeriodDetails($orderTotal = '')
	{
		if (!$orderTotal) { $orderTotal = $this->createOrderTotal(); }
		if (!$orderTotal) { return false; }

	    $billingPeriod = $this->settings['order']['frequency'];
	    $billingFrequency = isset($this->settings['order']['times_per_year']) ? 
	    		# How many times per year ('cycle')
	    		$this->settings['order']['times_per_year'] : null; 

	    # Let specify, such as if 6 months free out of year
	    if (!$billingPeriod)
	    {
	    	$billingPeriod = CAKE_COMPONENT_PAYPAL_ORDER_RECURRING_MONTHLY;
	    }

	    if (!$billingFrequency) # Calculate!
	    {
	    	if ($billingPeriod === CAKE_COMPONENT_PAYPAL_ORDER_RECURRING_DAILY)
		{
			$billingFrequency = 365;
		}
	    	else if ($billingPeriod === CAKE_COMPONENT_PAYPAL_ORDER_RECURRING_WEEKLY)
		{
			$billingFrequency = 52;
		}
	    	else if ($billingPeriod === CAKE_COMPONENT_PAYPAL_ORDER_RECURRING_SEMIMONTHLY)
		{
			$billingFrequency = 1; # Odd requirement.
		}
	    	else if ($billingPeriod === CAKE_COMPONENT_PAYPAL_ORDER_RECURRING_MONTHLY)
		{
			$billingFrequency = 12;
		}
	    	else if ($billingPeriod === CAKE_COMPONENT_PAYPAL_ORDER_RECURRING_YEARLY)
		{
			$billingFrequency = 1;
		}
		$billingFrequency = 1; # I think this means 'every Xth' PERIOD (ie every 1 month)
	    }

	    $billingPeriodDetails =& PayPal::getType('BillingPeriodDetailsType');
	    $billingPeriodDetails->setBillingPeriod($billingPeriod);
	    $billingPeriodDetails->setBillingFrequency($billingFrequency);
	    $totalBillingCycles = 0; # infinite, until cancel.
	    $billingPeriodDetails->setTotalBillingCycles($totalBillingCycles); # finite or infinite
	    $billingPeriodDetails->setAmount($orderTotal);

	    #error_log("BILLING_PERIOD_DETAILS=".print_r($billingPeriodDetails,true));

	    return $billingPeriodDetails;
	}

	function createScheduleDetails($orderTotal = '')
	{
	    $periodDetails = $this->createBillingPeriodDetails($orderTotal);
            $scheduleDetails =& PayPal::getType('ScheduleDetailsType');
	    $scheduleDetails->setDescription($this->settings['order']['billing_description']);
	    $scheduleDetails->setPaymentPeriod($periodDetails);
	    return $scheduleDetails;
	}

	function createRecurringPaymentsProfileDetails()
	{
            	$recurringPaymentsProfileDetails =& PayPal::getType('RecurringPaymentsProfileDetailsType');

	    	# MUST BE IN UTC FORMAT! date('c', $time)
		if (isset($this->settings['order']['billing_startdate']))
		{
			$billingStartdate = $this->settings['order']['billing_startdate'];
		} else {
	    		# Today.
			#$billingStartdate = date('Y-m-d H:i:s');
			$billingStartdate = date('c');
	    	}

            	$recurringPaymentsProfileDetails->setBillingStartdate($billingStartdate);

            	return $recurringPaymentsProfileDetails;
	}

	function createRecurringPaymentsProfileRequestDetails($token = '')
	{
           $requestDetails =& PayPal::getType('CreateRecurringPaymentsProfileRequestDetailsType');
           $requestDetails->setToken($token);

	   $recurringPaymentsProfileDetails = $this->createRecurringPaymentsProfileDetails();
	   $requestDetails->setRecurringPaymentsProfileDetails($recurringPaymentsProfileDetails);
	   $scheduleDetails = $this->createScheduleDetails();
	   $requestDetails->setScheduleDetails($scheduleDetails);

	   return $requestDetails;
	}

    	function recurringCheckout() # Subscription fees, etc as done via express checkout.
    	{
    		# MUST set VERSION to 50.0, according to docs.

		# BUT docs could be out of date!

		#define('PAYPAL_WSDL_VERSION', '50.0');

        	$this->_initialize();

        	if (!isset($this->caller))
        	{
            		return false;
        	}
        
        	if (!isset($this->settings['order']) || !isset($this->settings['order']['total']) || !isset($this->settings['order']['action']))
        	{
            		$this->errorCode = CAKE_COMPONENT_PAYPAL_ERROR_INVALID_ORDER;
            		$this->error = 'Order was not set up properly';
            		return false;
        	}
        
        	$this->errorCode = 0;
        	$this->error = null;

		try {
        
        		if (!isset($_REQUEST['token'])) # First time being called, initialize payment.
        		{
				# create billingAgreementDetails
				$billingAgreementDetails = $this->createBillingAgreementDetails();

	    			$setExpressCheckoutRequest = $this->createSetExpressCheckoutRequest($billingAgreementDetails);
	    			if (!$setExpressCheckoutRequest) { return false; }

            			// Execute request
            			$setExpressCheckoutResponse = $this->caller->SetExpressCheckout($setExpressCheckoutRequest);

				$response = $setExpressCheckoutResponse;

				#return $this->processResponse

            			if (PayPal::isError($response))
            			{
                			$this->errorCode = CAKE_COMPONENT_PAYPAL_ERROR_INVALID_REQUEST;
                			$this->error = $response->getMessage();
                
                			return false;
            			}

				if ($this->successfulResponse($response))
				{
					$payPalUrl = $this->createExpressCheckoutUrl($response);
					#error_log("REDIR TO $payPalUrl");
                
                			header('Location: ' . $payPalUrl);
                			exit;
            			}
            			else
            			{
					$this->processErrors();
            			}
        		}
        		else # Finalize payment processing (after they ok), create payment profile.
        		{
				$response = $this->createRecurringPaymentsProfile($_REQUEST['token']);


            			// Set up payment details

				# XXXX CONFUSED AFTER THIS POINT...jumbled....
				# TOMAS_MALY

            



            // Set up request
            
            #$request =& PayPal::getType('CreateRecurringPaymentsProfileRequestType');
            #$request->setCreateRecurringPaymentsProfileRequestDetails($requestDetails);
            
            // Execute request
            
            #$response = $this->caller->CreateRecurringPaymentsProfile($request);
            
            if (PayPal::isError($response))
            {
                $this->errorCode = CAKE_COMPONENT_PAYPAL_ERROR_INVALID_REQUEST;
                $this->error = $response->getMessage();
                
                return false;
            }
            
            $response_ack = $response->getAck();
            
            if ($response_ack == CAKE_COMPONENT_PAYPAL_ACK_SUCCESS || $response_ack == CAKE_COMPONENT_PAYPAL_ACK_SUCCESS_WITH_WARNING)
            {
                $responseDetails = $response->getCreateRecurringPaymentsProfileResponseDetails();
                
                $result = array (
                    'transaction' => $responseDetails->getProfileID(),
                    'ack' => $response_ack,
                );
                
                return $result;
            }
            else
            {
                $errorList = $response->getErrors();
                
                if(!is_array($errorList))
                {
                    $this->errorCode = $errorList->getErrorCode();
                    $this->error = '#' . $errorList->getErrorCode() . ': ' . $errorList->getShortMessage() . ' [' . $errorList->getLongMessage() . ']';
                }
                else
                {
                    $this->error = '';
                    
                    foreach($errorList as $error)
                    {
                        if (!empty($this->error))
                        {
                            $this->error .= "\n";
                        }
                        
                        $this->errorCode = $error->getErrorCode();
                        $this->error .= '#' . $error->getErrorCode() . ': ' . $error->getShortMessage() . ' [' . $error->getLongMessage() . ']';
                    }
                }
                
                return false;
            }
        }

	} catch (Exception $e) { 
		error_log($e->getMessage());
            	$this->errorCode = CAKE_COMPONENT_PAYPAL_ERROR_INVALID_ORDER;
            	$this->error = $e->getMessage();
		return false;
	}
        
        return false;
    }

    function createRecurringPaymentsProfile($token)
    {
        $request =& PayPal::getType('CreateRecurringPaymentsProfileRequestType');

    	$recurringPaymentsProfileRequestDetails = $this->createRecurringPaymentsProfileRequestDetails($token);
        $request->setCreateRecurringPaymentsProfileRequestDetails($recurringPaymentsProfileRequestDetails);
    	

        $response = $this->caller->CreateRecurringPaymentsProfile($request);

	return $response;
    }
    
    #function createRecurringPaymentsProfileRequestDetails()
    #{
#
#	$orderTotal = $this->createOrderTotal();
#	if (!$orderTotal) { return false; }
#	$billingAgreementDetails = $this->createBillingAgreementDetails($orderTotal);
#	$paymentDetails = $this->createPaymentDetails($orderTotal);
#	$billingPeriodDetails = $this->createBillingPeriodDetails();
#	$recurringPaymentsProfileRequestDetails = $this->createRecurringPaymentsProfileRequestDetails();
#
#	return $recurringPaymentsProfileRequestDetails;
#    }

    function successfulResponse($response)
    {
            	$response_ack = $response->getAck();
            	return ($response_ack == CAKE_COMPONENT_PAYPAL_ACK_SUCCESS || $response_ack == CAKE_COMPONENT_PAYPAL_ACK_SUCCESS_WITH_WARNING);
    }

    function createExpressCheckoutUrl($response)
    {
                $token = $response->getToken();
               	$payPalUrl = CAKE_COMPONENT_PAYPAL_EXPRESS_CHECKOUT_URL;
               	$payPalUrl = str_replace('{$environment}', $this->settings['api.environment'], $payPalUrl);
                $payPalUrl = str_replace('{$token}', $token, $payPalUrl);
		return $payPalUrl;
    }
    
    /**
     * Initializes the PayPal API caller handler.
     * 
     * @return bool    true on success, false otherwise.
     * 
     * @access private
     * @since 1.0
     */
    function _initialize()
    {
        $this->errorCode = 0;
        $this->error = null;
        
        $handler =& ProfileHandler_Array::getInstance(array(
            'username' => $this->settings['api.username'],
            'certificateFile' => null,
            'subject' => null,
            'environment' => $this->settings['api.environment'] ));
                 
        $pid = ProfileHandler::generateID();
        
        $profile =& new APIProfile($pid, $handler);
        
        $profile->setAPIUsername($this->settings['api.username']);
        $profile->setAPIPassword($this->settings['api.password']); 
        
        if(isset($this->settings['api.certificate']))
        {
            $profile->setCertificateFile($this->settings['api.certificate']); 
        }
        
        $profile->setSignature($this->settings['api.signature']);
        $profile->setEnvironment($this->settings['api.environment']);
        
        $this->caller =& PayPal::getCallerServices($profile);
	#error_log("CALLER IS=".get_class($this->caller));

	# MUST PUT FIRST IF ERRORS...
        if (PayPal::isError($this->caller))
        {
            $this->errorCode = CAKE_COMPONENT_PAYPAL_ERROR_CANT_CREATE_CALLER;
            $this->error = $this->caller->getMessage();
            
            unset($this->caller);
            
            return false;
        }

	# Damned writing log files from both web server and command line only use one filename per day no matter the users.
	# So stupid....

	# Change log file so unique per UID.
	$uid = posix_getuid();
	$file = '/tmp/' . date('Ymd') . '-' . $uid . '.PayPal.log';

	$logger = &Log::singleton('file', $file, $this->settings['api.username'], array('append' => true));

	$this->caller->setLogger($logger);
        
        
        return true;
    }
}
?>
