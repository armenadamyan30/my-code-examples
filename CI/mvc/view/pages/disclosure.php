<?php
$data_id = $data["data_id"];
$inp_txt = $data["input_text"];
$inp_com = $data["common"];
$i = 0;
$custom_inputs = isset($data['inputs'])?$data['inputs']:array();
?>
<form method="post" action = "<?php echo BASE_URL; ?>ajax" id="main_form">
    <input type="hidden" name="data_id" value="<?php echo $data_id; ?>" />
    <input type="hidden" name="action" value="form" />
    <input type="hidden" name="form_name" value="disclosure" />
	<input type="hidden" name="filled" value="0" />
    <!--<input type="submit" value="SAVE & VIEW" class="print_submit"/>-->



    <h1 style="font-size: 32px;">DISCLOSURE PAGE</h1>
    <h1 style="font-size: 26px;">MEMBER BANK (ACQUIRER) INFORMATION</h1>

    <div style="width: 1000px; margin: 0 auto; overflow: hidden;">
        <div style="float: left; width: 470px; border: 1px solid #000; margin: 14px;">
            <table class="top_divs">
                <tr>
                    <td width="50%">Acquirer Name:</td>
                    <td>Merrick Bank</td>
                </tr>
                <tr>
                    <td>Acquirer Address:</td>
                    <td>Merchant Services Dept.<br/>
                        135 Crossways Park Drive North<br/>
                        Suite A
                    </td>
                </tr>
                <tr>
                    <td>Acquirer Phone:</td>
                    <td>Woodbury, NY 11797<br/>
                        800-267-2256
                    </td>
                </tr>
            </table>
        </div>
        <div style="float: left; width: 470px; border: 1px solid #000; margin: 14px;">
            <table class="top_divs">
                <tr>
                    <td width="50%">Acquirer Name:</td>
                    <td>Merrick Bank</td>
                </tr>
                <tr>
                    <td>Acquirer Address:</td>
                    <td>Merchant Services Dept.<br/>
                        135 Crossways Park Drive North<br/>
                        Suite A
                    </td>
                </tr>
                <tr>
                    <td>Acquirer Phone:</td>
                    <td>Woodbury, NY 11797<br/>
                        800-267-2256
                    </td>
                </tr>
            </table>
        </div>
    </div>
    <div class="clear"></div>

    <h1 style="font-size: 26px;">IMPORTANT MEMBER BANK (ACQUIRER) RESPONSIBILITIES</h1>
    <div class="form_div">
        <ol style="margin-left: 20px;">
            <li>
                A Visa Member is the <span style="text-decoration: underline;">only entity</span> approved
                to extend acceptance of Visa products directly to a Merchant.
            </li>
            <li>
                A Visa Member must be a principal (signer) to the Merchant Agreement.
            </li>
            <li>
                The Visa Member is responsible for educating Merchants on pertinent Visa Operating
                Regulations with which Merchants must comply.
            </li>
            <li>
                The Visa Member is responsible for and must settle with funds with the merchant.
            </li>
            <li>
                The Visa Member is responsible for all funds held in reserve that are derived from settlement.
            </li>
        </ol>
    </div>
    <div class="form_div merch_inform">
        <h3 style="float: left; text-decoration: underline;">MERCHANT INFORMATION</h3>
        <div class="clear"></div>
        <p><span>Merchant Name:</span><input type="text" name="merchantName" value="<?php echo isset($inp_com["merchantName"]) ? $inp_com["merchantName"] : ""; ?>" size="44" class="indispensable"/></p>
        <div>
            <span class="merchant_address_label">Merchant Address:</span>
            <table class="merchant_address">
                <?php if(isset($appId_read_only)){ ?>
				<tr>
                    <td colspan="4">
					<?php echo (empty($custom_inputs['input2_1']) && isset($_COOKIE[$data_id.'_input2_1'])) ?$_COOKIE[$data_id.'_input2_1']: $custom_inputs['input2_1']; ?>,
                    <?php echo (empty($custom_inputs['input11_1']) && isset($_COOKIE[$data_id.'_input11_1'])) ?$_COOKIE[$data_id.'_input11_1']: $custom_inputs['input11_1']; ?>,
                    <?php echo (empty($custom_inputs['input12_1']) && isset($_COOKIE[$data_id.'_input12_1'])) ?$_COOKIE[$data_id.'_input12_1']: $custom_inputs['input12_1']; ?>,
                    <?php echo (empty($custom_inputs['input13_1']) && isset($_COOKIE[$data_id.'_input13_1'])) ?$_COOKIE[$data_id.'_input13_1']: $custom_inputs['input13_1']; ?>
					</td>
                </tr>
				<?php }else{ ?>
				<tr>
                    <td style='width: 35%;'>Address</td>
                    <td style='width: 25%;'>City</td>
                    <td style='width: 25%;'>State</td>
                    <td style='width: 15%;'>Zip</td>
                </tr>
                <tr>
                    <td><input type="text" name="input2_1" value="<?php echo (empty($custom_inputs['input2_1']) && isset($_COOKIE[$data_id.'_input2_1'])) ?$_COOKIE[$data_id.'_input2_1']: $custom_inputs['input2_1']; ?>" style="width: 95%;" class="indispensable"/></td>
                    <td><input type="text" name="input11_1" value="<?php echo (empty($custom_inputs['input11_1']) && isset($_COOKIE[$data_id.'_input11_1'])) ?$_COOKIE[$data_id.'_input11_1']: $custom_inputs['input11_1']; ?>" style="width: 95%;" class="indispensable"/></td>
                    <td><input type="text" name="input12_1" value="<?php echo (empty($custom_inputs['input12_1']) && isset($_COOKIE[$data_id.'_input12_1'])) ?$_COOKIE[$data_id.'_input12_1']: $custom_inputs['input12_1']; ?>" style="width: 95%;" class="indispensable"/></td>
                    <td><input type="text" name="input13_1" value="<?php echo (empty($custom_inputs['input13_1']) && isset($_COOKIE[$data_id.'_input13_1'])) ?$_COOKIE[$data_id.'_input13_1']: $custom_inputs['input13_1']; ?>" style="width: 95%;" class="indispensable"/></td>
                </tr>
				<?php } ?>
            </table>
            <input type="hidden" name="input[]" value="<?php echo isset($inp_txt[$i]) ? $inp_txt[$i++] : "United States"; ?>" />
        </div>
        <p><span>Merchant Phone:</span><input type="text" name="input3" value="<?php echo (empty($custom_inputs['input3']) && isset($_COOKIE[$data_id.'_input3'])) ?$_COOKIE[$data_id.'_input3']: $custom_inputs['input3']; ?>" size="44" class="indispensable"/></p>

	</div>
    <div class="form_div">
        <h3 style="float: left; text-decoration: underline;">IMPORTANT MERCHANT RESPONSIBILITIES </h3>
        <div class="clear"></div>
        <ol style="margin-left: 50px;">
            <li>
                Ensure compliance with cardholder data security and storage requirements.<br/>
                <b>(Go to <span class="underl">www.Visa.com</span>, select Small Business and Merchants, select Operations and
                    Risk Management, select Cardholder Information Security Program)</b>
            </li>
            <li>
                Maintain fraud and chargeback below thresholds.
            </li>
            <li>
                Review and understand the terms of the Merchant Agreement.
            </li>
            <li>
                Comply with Visa Operating Regulations.<br/>
                <b>(Go to www.Visa.com, select Small Business and Merchants, select Operations and
                    Risk Management, select Rules for Visa Merchants)</b>
            </li>
        </ol>
        The responsibilities listed above do not supersede terms of the Merchant Agreement and are provided to
        ensure the merchant understands some important obligations of each party and that the Visa
        Member (Acquirer) is the ultimate authority should the merchant have any problems.
    </div>
    <div class="form_div" style="margin: 50px auto;">
		<table style="width: 100%;">
            <tbody>
				<tr class="border_bottom" style="border-color: #C0C0C0;">
					<td width="50%"><input type="text" name="input[]" value="<?php echo isset($inp_txt[$i]) ? $inp_txt[$i++] : ""; ?>" class="indispensable signature"></td>
					<td width="50%"><input type="text" name="input6" value="<?php echo (empty($custom_inputs['input6'])) ? date("Y-m-d"): $custom_inputs['input6']; ?>" class="indispensable"></td>
				</tr>
				<tr>
					<td width="50%">Merchant's Signature</td>
					<td width="50%">Date</td>
				</tr>
				<tr style="height: 30px;"><td colspan="2"></td></tr>
				<tr class="border_bottom" style="border-color: #C0C0C0;">
					<td><input type="text" name="input4_1" value="<?php echo (empty($custom_inputs['input4_1']) && isset($_COOKIE[$data_id.'_input4_1'])) ?$_COOKIE[$data_id.'_input4_1']: $custom_inputs['input4_1']; ?>" class="indispensable"></td>
					<td><input type="text" name="input5" value="<?php echo (empty($custom_inputs['input5']) && isset($_COOKIE[$data_id.'_input5'])) ?$_COOKIE[$data_id.'_input5']: $custom_inputs['input5']; ?>" class="indispensable"></td>
				</tr>
				<tr>
					<td>Merchant's Printed Name</td>
					<td>Merchant's Printed Title</td>
				</tr>
				<tr style="height: 30px;"><td colspan="2"></td></tr>
				<tr>
					<td style="padding-right: 25px; text-align: right; width: 50%;">White Copy - Choice</td>
					<td style="padding-left: 25px;">Yellow Copy - Merchan</td>
				</tr>
			</tbody>
		</table>

    </div>
</form>