<?php
/*
* @package JoomShopping for Joomla!
* @subpackage payment
* @author beagler.ru
* @copyright Copyright (C) 2017 beagler.ru. All rights reserved.
* @license GNU General Public License version 2 or later
*/

//защита от прямого доступа
defined('_JEXEC') or die(); 
if(is_array($params) && isset($params['itransfer_participant_id']) && !empty($params['itransfer_participant_id'])&& isset($params['itransfer_participant_ident']) && !empty($params['itransfer_participant_ident'])&& isset($params['invoicebox_api_key']) && !empty($params['invoicebox_api_key'])){
?>
<script type="text/javascript">
	function check_pm_invoicebox() {
		jQuery('#payment_form').submit();
	}
</script>
<?php }else{
	
echo '<div style="color:red;">Внимание! Способ оплаты ИнвойсБокс не настроен!</div>';
}
?>