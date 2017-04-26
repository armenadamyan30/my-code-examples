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
    <input type="hidden" name="form_name" value="promise" />
	<input type="hidden" name="filled" value="0" />
    <!--<input type="submit" value="SAVE & VIEW" class="print_submit" id="form_submit_button"/>-->

    <div class="header">The <img src="<?php echo BASE_URL; ?>images/choice-c.png"/>hoice Promise</div>
    <div class="after_header">Merchant Solutions</div>

    <table>
        <tr>
            <td rowspan="2" class="alcent"><img src="<?php echo BASE_URL; ?>images/bail_swipe_logo.png" /></td>
            <td class="alcent vert_bot">1010 Wethersfield Ave <b>&middot;</b> Hartford, CT 06114 Ph. 860-296-1300 Fax 860-296-1303</td>
            <td rowspan="4" class="stamp_wrapper"><img src="<?php echo BASE_URL; ?>images/stamp.png" /></td>
        </tr>
        <tr>
            <td class="noterm">No termination or cancellation fee</td>
        </tr>

        <tr>
            <td class="mnaddr" width="200px">Merchant Name</td>
            <td class="border_bottom bord_col bord_left"><input type="text" name="merchantName" value="<?php echo isset($inp_com["merchantName"]) ? $inp_com["merchantName"] : ""; ?>" style="width: 90%" class="indispensable" placeholder="Your Company, LLC"/></td>
        </tr>
        <tr>
            <td class="mnaddr">Merchant Address</td>
            <td class="border_bottom bord_col bord_left">
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
            </td>
        </tr>
    </table>

    <table style="margin-top: 50px; font-size: 15px;">
        <tr>
            <td width="150" style="text-align: right;">Merchant Signature:</td>
            <td width="530" class="border_bottom bord_col">
                <input type="text" name="input[]" value="<?php echo isset($inp_txt[$i]) ? $inp_txt[$i++] : ""; ?>" style="width: 90%" class="indispensable signature"/>
            </td>
            <td width="70" style="text-align: center;">Date:</td>
            <td width="250" class="border_bottom bord_col">
                <input type="text" name="input[]" value="<?php echo isset($inp_txt[$i]) ? $inp_txt[$i++] : date("Y-m-d"); ?>" style="width: 90%" class="indispensable"/>
            </td>
        </tr>
        <tr>
            <td width="150" style="text-align: right;">Account Exec. Name:</td>
            <td width="530" class="border_bottom bord_col">
                <input type="text" name="input[]" value="" disabled="disabled" style="width: 90%" />
            </td>
            <td width="70" style="text-align: center;">Rep #:</td>
            <td width="250" class="border_bottom bord_col">
                <input type="text" name="input[]" disabled="disabled" value="Online Application" style="width: 90%" />
            </td>
        </tr>
    </table>
</form>
