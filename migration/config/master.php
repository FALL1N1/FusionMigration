<?php

/* YOUR LICENCE KEY HERE */
$config['licencekey']					 = "0000000000"; // Your Unique licence Key
/* YOUR LICENCE KEY HERE */

$config['335alink'] 					 = "https://mega.co.nz/#!NxE1BKTR!MBaUDAL_HvbSBFlfNYq1vD552GOpMzIpBHgNOfASwUc"; // Download Link addon Wotlk
$config['fname335a'] 					 = "HysteriaGaming_h4$h3d_dump.lua"; // Can change this if you customize addon
$config['baseurl'] 						 = "/"; // IMPORTANT!  (example: if your fusion url is: www.domain.com/web, baseurl is /web/   | else if youy fusion is only www.domain.com , baseurl is /)

$config['mail_title']					 = 'Hysteria Migrations'; // mailBox Player
$config['mail_content']					 = 'Thank You for using our migration services!'; // mailBox Player
$config['MaxMoney']    					 = 2000000000;        // Max Money, if more then it, then only this. put values in copper coins
$config['Playtime']              		 = 2;           	// 2 days Minimum Playtime. Counted as: last archievment date - first archievment date
$config['playtime_minlvl']				 = 80; 				// Min level to check playTime...
$config['MaxHP']    				     = 75000;            // Max Honor Points, if more then it, then only this.
$config['MaxAP']       					 = 3000;             // Max Arena Points, if more then it, then only this.



					/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
					/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
					/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

$config['ignore_items'] = array( //  ITEM DROP OUR FROM DELIVERY LIST
								 // You can keep adding infinite realms, need not be consecutive.
							/*'1' => array( // Realm ID
									'17',
									'17',
									'17',
								),
							'2' => array( // Realm ID 2
									'17',
									'17',
									'17',
								), 
							'4' => array( // Realm ID 4
									'17',
									'17',
									'17',
								), */
							);



$config['replace_items'] = array( // ITEM WILL BE CHANGED IN DELIVERY LIST
								  // You can keep adding infinite realms, need not be consecutive.
							/*'1' => array( // Realm id
										 // ITEM RULES
									'12345' => array('replace' => '54321'),
									'12345' => array('replace' => '54321'),
								),*/
							);

/* No change here */
$config['force_code_editor'] = true;