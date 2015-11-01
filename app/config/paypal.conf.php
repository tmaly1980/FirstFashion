<?

return array(
	'test_paypal'=>1, # In test (1) or production (0) mode.
	'auth' => array(
			'test' => array(
			    	# Test buyer account: (for paypal login), for purchase simulation
				# testbuyer@betterthanthebookstore.com
				# testbuyer1
				
				# general sandbox access:
				#	firstfashion@215media.com
				#	testing215
			
				# Sandbox email login for seller (US):
				#	fftestseller@215media.com
				#	testing215
				# API USERNAME:
				#	firstf_1221695235_biz_api1.215media.com
				#	1221695239
				#
				# test buyer account:
				#	NOT testmodel@215media.com
				#	NOT testmo_1221695296_per@215media.com
				#	* testbu_1221847211_per@215media.com / testing215
				#
				#	testmodel215
				#
			
				# developer.paypal.com login:
			    	# test account:
			    	#firstfashion@215media.com
				#testing215
			
				# API USERNAME FOR SELLER:
				'user'=>'fftest_1221846191_biz_api1.215media.com',
				'pass'=>'1221846212',
				'signature'=>'AuJaPO3nfiXyOzJbW-bjI1bb0GPTA7TZVn2PIqRaa68ZdtNUXwyeLk5m',
			
			),
			'prod' => array(
				    # Production account:
				    # Payments@firstfashionsite.com
				    # Welcomeff215
			
				'user'=>'payments_api1.firstfashionsite.com',
				'pass'=>'HD6X67FKKNSS3CS9',
				'signature'=>'An5ns1Kso7MWUdW4ErQKJJJ4qi4-AsEHHPeJVU6nRDgfqQVongCBhq33', 
			),
			
	),

);

?>
