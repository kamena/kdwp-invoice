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
	$kdwp_invoice_options 		= get_option( 'kdwp_invoice_options' );
	/*	Company Detail		*/
	$kdwp_company_person 			 = isset($kdwp_invoice_options['kdwp_company_person']) ? $kdwp_invoice_options['kdwp_company_person'] : "";
	$kdwp_company_name 				 = isset($kdwp_invoice_options['kdwp_company_name']) ? $kdwp_invoice_options['kdwp_company_name'] : "";
	$kdwp_company_email 			 = isset($kdwp_invoice_options['kdwp_company_email']) ? $kdwp_invoice_options['kdwp_company_email'] : "";
	$kdwp_company_website			 = isset($kdwp_invoice_options['kdwp_company_website']) ? $kdwp_invoice_options['kdwp_company_website'] : "";
	$kdwp_company_address 			 = isset($kdwp_invoice_options['kdwp_company_address']) ? $kdwp_invoice_options['kdwp_company_address'] : "";
	$kdwp_company_unique_number		 = isset($kdwp_invoice_options['kdwp_company_unique_number']) ? $kdwp_invoice_options['kdwp_company_unique_number'] : "";
	$kdwp_company_responsible_person = isset($kdwp_invoice_options['kdwp_company_responsible_person']) ? $kdwp_invoice_options['kdwp_company_responsible_person'] : "";
	$kdwp_company_bank_ac_number 	 = isset($kdwp_invoice_options['kdwp_company_bank_ac_number']) ? $kdwp_invoice_options['kdwp_company_bank_ac_number'] : "";

?>	

		<div id="kdwp-company-settings" class="post-box-container">		
			<div class="metabox-holder">			
				<div class="meta-box-sortables ui-sortable">
					<div id="settings" class="postbox">			
						<div class="handlediv" title="<?php echo __( 'Click to toggle', 'kdwp-invoice' ) ?>"><br /></div>			
							<h3 class="hndle">					
								<span style="vertical-align: top;"><?php echo __( 'Company Settings', 'kdwp-invoice' ) ?></span>					
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
												<label for="kdwp-company-address"><strong><?php echo __( 'Официален сайт на фирмата', 'kdwp-invoice' ) ?></strong></label>
											</th>
											<td><input type="text" id="kdwp-company-name"  name="kdwp_invoice_options[kdwp_company_website]" value="<?php echo $kdwp_company_website; ?>" size="63" /><br />
												<span class="description"><?php echo __( 'Enter Company Website Address', 'kdwp-invoice' ) ?></span>
											</td>
										 </tr>

										 <tr>
											<th scope="row">
												<label for="kdwp-company-address"><strong><?php echo __( 'E-mail на фирмата', 'kdwp-invoice' ) ?></strong></label>
											</th>
											<td><input type="text" id="kdwp-company-name"  name="kdwp_invoice_options[kdwp_company_email]" value="<?php echo $kdwp_company_email; ?>" size="63" /><br />
												<span class="description"><?php echo __( 'Enter Company Email Address', 'kdwp-invoice' ) ?></span>
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
			</div>		
		</div>	
</form>
