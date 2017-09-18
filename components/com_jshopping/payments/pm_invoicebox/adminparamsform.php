<?php
/*
* @package JoomShopping for Joomla!
* @subpackage payment
* @author beagler.ru
* @copyright Copyright (C) 2017 beagler.ru. All rights reserved.
* @license GNU General Public License version 2 or later
*/

defined('_JEXEC') or die();


?>
<div class="col100">
	<fieldset class="adminform">
		<table class="admintable" width="100%">
			<tr>
				<td class="key" width="300">
					<?php echo _JSHOP_CFG_INVOICEBOX_ITRANSFER_PARTICIPANT_ID; ?></td>
				<td>
					<input type="text" name="pm_params[itransfer_participant_id]" class="inputbox" value="<?php echo $params['itransfer_participant_id']; ?>" />
					
				</td>
			</tr>
			<tr>
				<td class="key">
					<?php echo _JSHOP_CFG_INVOICEBOX_ITRANSFER_PARTICIPANT_IDENT; ?>
				</td>
				<td>
					<input type="text" name="pm_params[itransfer_participant_ident]" class="inputbox" value="<?php echo $params['itransfer_participant_ident'];?>" />
					
				</td>
			</tr>
			<tr>
				<td class="key">
					<?php echo _JSHOP_CFG_INVOICEBOX_INVOICEBOX_API_KEY; ?>
				</td>
				<td>
					<input type="text" name="pm_params[invoicebox_api_key]" class="inputbox" value="<?php echo $params['invoicebox_api_key'];?>" />
					
				</td>
			</tr>
			<tr>
				<td class="key">
					<?php echo _JSHOP_CFG_INVOICEBOX_TESTMODE; ?>
				</td>
				<td>
					<?php
						if (!isset($params['itransfer_testmode'])) {
							$val = 1;
						} else {
							$val = $params['itransfer_testmode'];	
						}
						
						echo JHTML::_('select.booleanlist', 'pm_params[itransfer_testmode]', 'class="inputbox" size="1"', $val, 'JYES', 'JNO', false);
						
					?>
				</td>
			</tr>
			<tr>
				<td class="key">
					<?php echo _JSHOP_TRANSACTION_END; ?>
				</td>
				<td>
				<?php              
					echo JHTML::_('select.genericlist', $orders->getAllOrderStatus(), 'pm_params[transaction_end_status]', 'class="inputbox" size="1"', 'status_id', 'name', $params['transaction_end_status']);
					
				?>
				</td>
			</tr>
			
		</table>
	</fieldset>
</div>
<div class="clr"></div>