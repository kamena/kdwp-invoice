<?php
/**
 * Invoice settings
 */

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;
?>
<div class="wrap"><div id="icon-tools" class="icon32"></div>
    <h2>Настройки</h2>
</div>
<form  method="post" action="options.php">	
<?php
	settings_fields( 'invoice_plugin_options' );
	$kdwp_invoice_options 			 = get_option( 'kdwp_invoice_options' );
	/*	Company Detail		*/
	$kdwp_company_person 			 = isset($kdwp_invoice_options['kdwp_company_person']) ? $kdwp_invoice_options['kdwp_company_person'] : "";
	$kdwp_company_name 				 = isset($kdwp_invoice_options['kdwp_company_name']) ? $kdwp_invoice_options['kdwp_company_name'] : "";
	$kdwp_company_email 			 = isset($kdwp_invoice_options['kdwp_company_email']) ? $kdwp_invoice_options['kdwp_company_email'] : "";
	$kdwp_company_website			 = isset($kdwp_invoice_options['kdwp_company_website']) ? $kdwp_invoice_options['kdwp_company_website'] : "";
	$kdwp_company_city 			 	 = isset($kdwp_invoice_options['kdwp_company_city']) ? $kdwp_invoice_options['kdwp_company_city'] : "";
	$kdwp_company_address 			 = isset($kdwp_invoice_options['kdwp_company_address']) ? $kdwp_invoice_options['kdwp_company_address'] : "";
	

	$kdwp_company_unique_number		 = isset($kdwp_invoice_options['kdwp_company_unique_number']) ? $kdwp_invoice_options['kdwp_company_unique_number'] : "";
	$kdwp_company_responsible_person = isset($kdwp_invoice_options['kdwp_company_responsible_person']) ? $kdwp_invoice_options['kdwp_company_responsible_person'] : "";

	$kdwp_bank_name 				 = isset($kdwp_invoice_options['kdwp_bank_name']) ? $kdwp_invoice_options['kdwp_bank_name'] : "";
	$kdwp_company_iban 				 = isset($kdwp_invoice_options['kdwp_company_iban']) ? $kdwp_invoice_options['kdwp_company_iban'] : "";
	$kdwp_company_bic				 = isset($kdwp_invoice_options['kdwp_company_bic']) ? $kdwp_invoice_options['kdwp_company_bic'] : "";

	// Invoice Details	
	$kdwp_serial_number				 = isset($kdwp_invoice_options['kdwp_serial_number']) ? $kdwp_invoice_options['kdwp_serial_number'] : "1";
	$kdwp_invoice_note				 = isset($kdwp_invoice_options['kdwp_invoice_note']) ? $kdwp_invoice_options['kdwp_invoice_note'] : "Съгласно чл.7, ал.1 от Закона за счетоводството, печатът не е сред задължителните реквизити на фактурата. Този документ е издаден чрез система за онлайн фактуриране - KDWP-Invoice";
?>	

		<div class="metabox-holder">			
			<div id="settings" class="postbox">			
				<div class="handlediv" title="<?php echo __( 'Click to toggle', 'kdwp-invoice' ) ?>"><br /></div>			
				<h3 class="hndle">					
					<span style="vertical-align: top;"><?php echo __( 'Настройки за компанията', 'kdwp-invoice' ) ?></span>					
				</h3>

				<div class="inside">			
					<table class="form-table kdwp-customer-settings-box"> 
						<tbody>
							<tr>
								<th scope="row">
									<label for="kdwp-person-name"><strong><?php echo __( 'Име на получател', 'kdwp-invoice' ) ?></strong></label>
								</th>
								<td><input type="text" id="kdwp-person-name"  name="kdwp_invoice_options[kdwp_company_person]" value="<?php echo $kdwp_company_person; ?>" size="63" /><br />
									<span class="description"><?php echo __( 'Enter Person Name', 'kdwp-invoice' ) ?></span>
								</td>
							 </tr>
							<tr>
								<th scope="row">
									<label for="kdwp-company-name"><strong><?php echo __( 'Име на фирмата', 'kdwp-invoice' ) ?></strong></label>
								</th>
								<td><input type="text" id="kdwp-company-name"  name="kdwp_invoice_options[kdwp_company_name]" value="<?php echo $kdwp_company_name; ?>" size="63" /><br />
									<span class="description"><?php echo __( 'Enter Company Name', 'kdwp-invoice' ) ?></span>
								</td>
							 </tr>
							 <tr>
								<th scope="row">
									<label for="kdwp-company-web"><strong><?php echo __( 'Официален сайт на фирмата', 'kdwp-invoice' ) ?></strong></label>
								</th>
								<td><input type="text" id="kdwp-company-web"  name="kdwp_invoice_options[kdwp_company_website]" value="<?php echo $kdwp_company_website; ?>" size="63" /><br />
									<span class="description"><?php echo __( 'Enter Company Website Address', 'kdwp-invoice' ) ?></span>
								</td>
							 </tr>

							 <tr>
								<th scope="row">
									<label for="kdwp-company-email"><strong><?php echo __( 'E-mail на фирмата', 'kdwp-invoice' ) ?></strong></label>
								</th>
								<td><input type="text" id="kdwp-company-email"  name="kdwp_invoice_options[kdwp_company_email]" value="<?php echo $kdwp_company_email; ?>" size="63" /><br />
									<span class="description"><?php echo __( 'Enter Company Email Address', 'kdwp-invoice' ) ?></span>
								</td>
							 </tr>
							 <tr>
								<th scope="row">
									<label for="kdwp-company-city"><strong><?php echo __( 'Град на фирмата', 'kdwp-invoice' ) ?></strong></label>
								</th>
								<td><input type="text" id="kdwp-company-name"  name="kdwp_invoice_options[kdwp_company_city]" value="<?php echo $kdwp_company_city; ?>" size="63" /><br>
									<span class="description"><?php echo __( 'Enter Company City', 'kdwp-invoice' ) ?></span>
								</td>
							 </tr>
							 <tr>
								<th scope="row">
									<label for="kdwp-company-address"><strong><?php echo __( 'Адрес на фирмата', 'kdwp-invoice' ) ?></strong></label>
								</th>
								<td><textarea rows="5" cols="60" name="kdwp_invoice_options[kdwp_company_address]"><?php echo $kdwp_company_address; ?></textarea><br>
									<span class="description"><?php echo __( 'Enter Company Address', 'kdwp-invoice' ) ?></span>
								</td>
							 </tr>
							 <tr>
								<th scope="row">
									<label for="kdwp-company-unique-number"><strong><?php echo __( 'ЕИК/Булстат', 'kdwp-invoice' ) ?></strong></label>
								</th>
								<td><input type="text" id="kdwp-company-unique-number" name="kdwp_invoice_options[kdwp_company_unique_number]" value="<?php echo $kdwp_company_unique_number; ?>" size="63" /><br />
									<span class="description"><?php echo __( 'Enter Company Unique Number', 'kdwp-invoice' ) ?></span>
								</td>
							 </tr>
							 <tr>
								<th scope="row">
									<label for="kdwp-company-responsible-person"><strong><?php echo __( 'МОЛ', 'kdwp-invoice' ) ?></strong></label>
								</th>
								<td><input type="text" id="kdwp-company-responsible-person" name="kdwp_invoice_options[kdwp_company_responsible_person]" value="<?php echo $kdwp_company_responsible_person; ?>" size="63" /><br />
									<span class="description"><?php echo __( 'Enter Company Unique Number', 'kdwp-invoice' ) ?></span>
								</td>
							 </tr>
							 <tr><th><h3>Разплащателна сметка</h3></th></tr>
							  <tr>
								<th scope="row">
									<label for="kdwp-company-responsible-person"><strong><?php echo __( 'Банка', 'kdwp-invoice' ) ?></strong></label>
								</th>
								<td><input type="text" id="kdwp-company-responsible-person" name="kdwp_invoice_options[kdwp_bank_name]" value="<?php echo $kdwp_bank_name; ?>" size="63" /><br />
									<span class="description"><?php echo __( 'Enter Bank Name', 'kdwp-invoice' ) ?></span>
								</td>
							 </tr>
							 <tr>
								<th scope="row">
									<label for="kdwp-company-responsible-person"><strong><?php echo __( 'IBAN', 'kdwp-invoice' ) ?></strong></label>
								</th>
								<td><input type="text" id="kdwp-company-responsible-person" name="kdwp_invoice_options[kdwp_company_iban]" value="<?php echo $kdwp_company_iban; ?>" size="63" /><br />
									<span class="description"><?php echo __( 'Enter Company IBAN', 'kdwp-invoice' ) ?></span>
								</td>
							 </tr>
							 <tr>
								<th scope="row">
									<label for="kdwp-company-responsible-person"><strong><?php echo __( 'BIC', 'kdwp-invoice' ) ?></strong></label>
								</th>
								<td><input type="text" id="kdwp-company-responsible-person" name="kdwp_invoice_options[kdwp_company_bic]" value="<?php echo $kdwp_company_bic; ?>" size="63" /><br />
									<span class="description"><?php echo __( 'Enter Company BIC', 'kdwp-invoice' ) ?></span>
								</td>
							 </tr>
							<tr>
								<td colspan="2">
									<input type="submit" class="button-primary kdwp-company-settings-save" name="kdwp_company_settings_save" class="" value="<?php echo __( 'Save Changes', 'kdwp-invoice' ) ?>" />
								</td>
							</tr>
						</tbody>
					</table>				
				</div>	
			</div>	
		</div>		
		<div class="metabox-holder">			
			<div id="settings" class="postbox">			
				<div class="handlediv" title="<?php echo __( 'Click to toggle', 'kdwp-invoice' ) ?>"><br /></div>			
				<h3 class="hndle">					
					<span style="vertical-align: top;"><?php echo __( 'Настройки за фактурите', 'kdwp-invoice' ) ?></span>					
				</h3>

				<div class="inside">
					<table class="form-table kdwp-customer-settings-box"> 
						<tr>
							<th scope="row">
								<label for="kdwp-company-responsible-person"><strong><?php echo __( 'Сериен номер', 'kdwp-invoice' ) ?></strong></label>
							</th>							
							<td><input type="text" id="kdwp-serial-number" name="kdwp_invoice_options[kdwp_serial_number]" value="<?php echo $kdwp_serial_number; ?>" size="63" /><br />
								<span class="description"><?php echo __( 'Въведи през колко да се променя серийния номер на фактурите', 'kdwp-invoice' ) ?></span>
							</td>
						</tr>	
						<tr>
							<th scope="row">
								<label for="kdwp-company-responsible-person"><strong><?php echo __( 'Бележка към фактурите', 'kdwp-invoice' ) ?></strong></label>
							</th>							
							<td><textarea rows="5" cols="60" name="kdwp_invoice_options[kdwp_invoice_note]"><?php echo $kdwp_invoice_note; ?></textarea><br />
								<span class="description"><?php echo __( 'Кратка бележка за фактурите', 'kdwp-invoice' ) ?></span>
							</td>
						</tr>
						<tr>
							<td colspan="2">
								<input type="submit" class="button-primary kdwp-company-settings-save" name="kdwp_company_settings_save" class="" value="<?php echo __( 'Save Changes', 'kdwp-invoice' ) ?>" />
							</td>
						</tr>
					</table>							
				</div>			
			</div>	
		</div>		
</form>