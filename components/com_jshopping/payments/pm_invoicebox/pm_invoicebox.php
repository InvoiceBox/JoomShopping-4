<?php
/*
* @package JoomShopping for Joomla!
* @subpackage payment
* @author beagler.ru
* @copyright Copyright (C) 2017 beagler.ru. All rights reserved.
* @license GNU General Public License version 2 or later
*/

defined('_JEXEC') or die();

class pm_invoicebox extends PaymentRoot
{
	function showPaymentForm($params, $pmconfigs)
	{
		include(dirname(__FILE__).'/paymentform.php');
	}
	
	
	
	function showAdminFormParams($params)
	{
	
	$jmlThisDocument = JFactory::getDocument();
        switch ($jmlThisDocument->language) {
            case 'en-gb':
                include(JPATH_SITE . '/administrator/components/com_jshopping/lang/en-GB_invoicebox.php');
                $language = 'en';
                break;
            case 'ru-ru':
                include(JPATH_SITE . '/administrator/components/com_jshopping/lang/ru-RU_invoicebox.php');
                $language = 'ru';
                break;
            default:
                include(JPATH_SITE . '/administrator/components/com_jshopping/lang/ru-RU_invoicebox.php');
        }
		$array_params = array('itransfer_participant_id', 'itransfer_participant_ident', 'invoicebox_api_key', 'itransfer_testmode', 'transaction_end_status');
		
		foreach ($array_params as $key) {
			if (!isset($params[$key])) {
				$params[$key] = '';
			}
		}

		$orders = JSFactory::getModel('orders', 'JshoppingModel');
		
		include(dirname(__FILE__).'/adminparamsform.php');
	}
	

	function checkTransaction($pmconfigs, $order, $act)
	{
		$order->order_total = $this->fixOrderTotal($order); 
		
		$this->rezult = '';
		$error = '';

	
		$order_amount = $this->fixOrderTotal($post['amount']);

		$order_currency = $post['order_currency'];
		
		$jmlThisDocument = JFactory::getDocument();
        switch ($jmlThisDocument->language) {
            case 'en-gb':
                include(JPATH_SITE . '/administrator/components/com_jshopping/lang/en-GB_invoicebox.php');
                $language = 'en';
                break;
            case 'ru-ru':
                include(JPATH_SITE . '/administrator/components/com_jshopping/lang/ru-RU_invoicebox.php');
                $language = 'ru';
                break;
            default:
                include(JPATH_SITE . '/administrator/components/com_jshopping/lang/ru-RU_invoicebox.php');
        }
		
			$participantId 		= IntVal(JRequest::getVar("participantId"));
			$participantOrderId 	= IntVal(JRequest::getVar("participantOrderId")) ;
			if ( !($participantId && $participantOrderId )){
				$error .= _JSHOP_ERROR_INVOICEBOX_ID.'<br>';
			}
			$ucode 		= JRequest::getVar("ucode");
			$timetype 	= JRequest::getVar("timetype");
			$time 		= str_replace(' ','+',JRequest::getVar("time"));
			$amount 	= JRequest::getVar("amount");
			$currency 	= JRequest::getVar("currency");
			$agentName 	= JRequest::getVar("agentName");
			$agentPointName = JRequest::getVar("agentPointName");
			$testMode 	= JRequest::getVar("testMode");
			$sign	 	= JRequest::getVar("sign");
            $orderid = $participantOrderId;
			
			$sign_strA = md5(
			$participantId .
			$participantOrderId .
			$ucode .
			$timetype .
			$time .
			$amount .
			$currency .
			$agentName .
			$agentPointName .
			$testMode .
			$pmconfigs['invoicebox_api_key']);
			
			if ($sign != $sign_strA) {
				$error .= _JSHOP_ERROR_INVOICEBOX_MD5.'<br>';
			}
		
            if($order->order_total != $amount){
				$error .= _JSHOP_ERROR_INVOICEBOX_SUM.'<br>';
			}
			
			if (!$error) { 
				 
						$this->rezult = 'OK';
						
						 echo 'OK';
						
						return array(1, '');
					
			} else {
				
				$this->rezult = $error;
				
						 echo _JSHOP_ERROR_INVOICEBOX_PARAM.': '.$error;
				
				return array(0, _JSHOP_ERROR_INVOICEBOX_PARAM.': '.$error);
			} 
		
	
		return array(0, _JSHOP_ERROR_INVOICEBOX_RESP.' '.$order->order_id);                    
	}


	function showEndForm($pmconfigs, $order)
	{
		$itransfer_participant_id = $pmconfigs['itransfer_participant_id'];
		$itransfer_participant_ident = $pmconfigs['itransfer_participant_ident'];
		$invoicebox_api_key = $pmconfigs['invoicebox_api_key'];
		$testmode = $pmconfigs['itransfer_testmode'] ? '1' : '0';
		$pm_method = $this->getPmMethod();
		$order_id = $order->order_id;
		$currency = $order->currency_code_iso;
		$email = $order->email;
		$total = $order->order_total;
		$signatureValue = md5(
			$itransfer_participant_id.
			$order_id.
			$total.
			$currency.
			$invoicebox_api_key
			); 
		
		
		$params = array(
                "itransfer_participant_id" => $itransfer_participant_id,
				"itransfer_participant_ident" => $itransfer_participant_ident,
				"itransfer_order_id" => $order_id,
                "itransfer_order_amount" => $total,
                "itransfer_order_currency_ident" => $currency,
                "itransfer_testmode" => $testmode,
                "itransfer_body_type" => "PRIVATE",
                "itransfer_participant_sign" => $signatureValue,
                "CMS" => 'JOOMSHOPPING',
                "itransfer_order_description" => 'Оплата заказа ' . $order_id,
				"itransfer_person_name" => $order->d_f_name.' '.$order->d_l_name,
				"itransfer_person_email" => $email,
                "itransfer_url_notify" => SEFLink(JURI::root()."index.php?option=com_jshopping&controller=checkout&task=step7&act=notify&js_paymentclass=".$pm_method->payment_class."&no_lang=1&tmpl=component"),
				"itransfer_url_return" => SEFLink(JURI::root()."index.php?option=com_jshopping&controller=checkout&task=step7&act=return&js_paymentclass=".$pm_method->payment_class)
            );
				$params['itransfer_person_phone'] = $order->d_phone;
			

	 $vatrate = 0;
	
	$orderitem = $order->getAllItems();
       
    $product_quantity = $i = 0;
    foreach ($orderitem as $product) { 
		$i++;
		$product_quantity += $product->product_quantity;
		 $params['itransfer_item'.$i.'_name'] = $product->product_name;
		 $params['itransfer_item'.$i.'_quantity'] = $product->product_quantity;
		 $params['itransfer_item'.$i.'_price'] = round($product->product_item_price*$product->product_quantity,2);
		 $params['itransfer_item'.$i.'_vatrate'] = round($product->product_tax,2);
		 $params['itransfer_item'.$i.'_measure'] = 'шт.';
		
	}	
	$params['itransfer_order_quantity'] = $product_quantity;
	if($order->order_shipping > 0){
		$ships = $order->getShippingTaxExt();
		foreach($ships as $shi){
			$ship = $shi;
			break;
		}
		$i++;
		 $params['itransfer_item'.$i.'_name'] = 'Доставка';
		 $params['itransfer_item'.$i.'_quantity'] = 1;
		 $params['itransfer_item'.$i.'_price'] = round($order->order_shipping,2);
		 $params['itransfer_item'.$i.'_vatrate'] = round($order->shipping_tax);
		 $params['itransfer_item'.$i.'_vat'] = round($ship, 2);
		 $params['itransfer_item'.$i.'_measure'] = 'шт.';
	}
	
           
            
            $html = '<html>
			<head>
				<meta http-equiv="content-type" content="text/html; charset=utf-8" />           
			</head>
			<body>
			<form method="post" action="https://go.invoicebox.ru/module_inbox_auto.u" accept-charset="UTF-8" id="paymentform" name="paymentform">';
            foreach ($params as $key => $param) {
                $html.= '<input type="hidden" name="' . $key . '" value="' . $param . '">';
            }
            $html.= '<input type="submit" value="Оплатить"/></form>';
            $html.= _JSHOP_REDIRECT_TO_PAYMENT_PAGE.'
			<br>
			<script type="text/javascript">document.getElementById("paymentform").submit();</script>
			</body>
		</html>';
			echo $html;
		die();
	}

	
	function getUrlParams($pmconfigs)
	{      
		$order_id = JFactory::getApplication()->input->getInt("participantOrderId");

        $params = array();
        $params['order_id'] = $order_id;
        $params['hash'] = "";
        $params['checkHash'] = 0;
        $params['checkReturnParams'] = 0;

        return $params; 
	}
	
	function fixOrderTotal($order){
        $total = $order->order_total;
        if ($order->currency_code_iso=='HUF'){
            $total = round($total);
        }else{
            $total = number_format($total, 2, '.', '');
        }
    return $total;
    }
}
?>