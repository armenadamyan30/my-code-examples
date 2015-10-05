<?php
$data_id = $data["data_id"];
$inp_txt = $data["input_text"];
$inp_com = $data["common"];
$inp_ch = $data["input_check"];
$i = 0;
$custom_inputs = isset($data['inputs'])?$data['inputs']:array();
?>

<form method="post" action = "<?php echo BASE_URL; ?>ajax" id="main_form">
    <input type="hidden" name="data_id" value="<?php echo $data_id; ?>" />
    <input type="hidden" name="action" value="form" />
    <input type="hidden" name="form_name" value="setup_checklist" />
	<input type="hidden" name="filled" value="0" />
    <!--<input type="submit" value="SAVE & VIEW" class="print_submit" id="form_submit_button"/>-->

    <!--<div class="header">The <img src="<?php echo BASE_URL; ?>images/choice-c.png"/>hoice Promise</div>
    <div class="after_header">Merchant Solutions</div>-->



	<table style="width:100%;border:none;">
		<tr style="border:none;">
			<td style="font-weight:bold;border:none;font-size:22px;width:300px;">BailSwipe<br/> Setup Checklist</td>
			<td style="border:none;" align="right"><img src="<?php echo BASE_URL; ?>images/bail_swipe_logo.png" /></td>
		</tr>
	</table>
	<table style="width:100%" border="0">
		<tr style="background-color:#DBE5F1;">
			<td style="height:25px;" colspan="2"><h3>COMPANY INFORMATION </h3></td>
		</tr>
		<tr>
			<td style="width:50%;">
				Date:
				<input type="text" name="input[]" class="indispensable" value="<?php echo isset($inp_txt[$i]) ? $inp_txt[$i++] : date("Y-m-d"); ?>" style="width: 40%;" />
			</td>
			<td>Office Number: 4838 </td>
		</tr>
		<tr>
			<td colspan="2">
				<table border="0" style="width:100%;border:0;">
					<tr>
						<td style="width:15%">Corporate name: </td>
						<td style="border-right:none;"><input type="text" name="input1" class="indispensable" value="<?php echo (isset($inp_com["merchantName"]))?$inp_com["merchantName"]: $custom_inputs['input1']; ?>" /></td>
					</tr>
					<tr>
						<td>DBA:</td>
						<td style="border-right:none;"><input type="text" name="input14" class="indispensable" value="<?php echo (empty($custom_inputs['input14']) && isset($_COOKIE[$data_id.'_input14'])) ?$_COOKIE[$data_id.'_input14']: $custom_inputs['input14']; ?>" /></td>
					</tr>
					<tr>
						<td>Email:</td>
						<td style="border-right:none;"><input type="text" name="input7" class="indispensable" value="<?php echo (empty($custom_inputs['input7']) && isset($_COOKIE[$data_id.'_input7'])) ?$_COOKIE[$data_id.'_input7']: $custom_inputs['input7']; ?>" /></td>
					</tr>
					<tr>
						<td>Sales Rep:</td>
                        <td style="border-right:none;"><input type="text" name="input[]" value="Online Application" disabled="disabled" /></td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
	<table style="width:100%;margin-top:30px;">
		<tr style="background-color:#DBE5F1;">
			<td colspan="2"><h3>PRIMARY DOCUMENTS</h3></td>
			<td><h3>MOBILE INFORMATION</h3></td>
		</tr>
		<tr>
			<td style="width:30px;">

					<input type="checkbox" name="checkbox1" value="1" checked="checked" disabled="disabled" />

			</td>
			<td style="width:47%;">Setup Checklist</td>
			<td>Phone Manufacturer: <input type="text" name="input[]" class="indispensable" value="<?php echo isset($inp_txt[$i]) ? $inp_txt[$i++] : ""; ?>" /></td>
		</tr>
		<tr>
			<td>

					<input type="checkbox" name="checkbox2"checked="checked" disabled="disabled" />

			</td>
			<td>Application</td>
			<td>Model: <input type="text" name="input[]" class="indispensable" value="<?php echo isset($inp_txt[$i]) ? $inp_txt[$i++] : ""; ?>" /></td>
		</tr>
		<tr>
			<td><input type="checkbox" name="checkbox3"checked="checked" disabled="disabled" /></td>
			<td>Disclosure Page</td>
			<td>Cell #: <input type="text" name="input[]" class="indispensable" value="<?php echo isset($inp_txt[$i]) ? $inp_txt[$i++] : ""; ?>" /></td>
		</tr>
		<tr>
			<td><input type="checkbox" name="checkbox4"checked="checked" disabled="disabled" /></td>
			<td>W-9</td>
			<td>Provider: <input type="text" class="indispensable" name="input[]" value="<?php echo isset($inp_txt[$i]) ? $inp_txt[$i++] : ""; ?>" /></td>
		</tr>
		<tr>
			<td><input type="checkbox" name="checkbox5"checked="checked" disabled="disabled" /></td>
			<td>CB Dispute Agreement</td>
			<td>
				<table style="width:100%;">
					<tr>
						<td style="width:30px;border-left:none;"><input type="checkbox" class="indispensable" name="checkbox6" checked="checked" disabled="disabled" /></td>
						<td style="border-right:none;">USAePay</td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
	<table style="width:100%;margin-top:30px;">
		<tr style="background-color:#DBE5F1;">
			<td colspan="3"><h3>SUPPORTING DOCUMENTS</h3></td>
		</tr>
		<tr>
			<td style="width:30px;">
				<span class="ch_group1 required_radio">
                    <input id="checkbox7" type="checkbox" name="checkbox7" value="1" <?php echo isset($inp_ch["checkbox7"]) ? "checked": ""; ?> />
				</span>
			</td>

			<td style="width:47%;"><label for="checkbox7">Copy of Signer's Driver's License</label></td>
			<td> </td>
		</tr>
		<tr>
			<td>
				<span class="ch_group1 required_radio">
					<input id="checkbox8" type="checkbox" name="checkbox8" value="1" <?php echo isset($inp_ch["checkbox8"]) ? "checked": ""; ?>  />
				</span>
			</td>
			<td><label for="checkbox8">Application</label></td>
			<td></td>
		</tr>
		<tr>
			<td>
				<span class="ch_group1 required_radio">
					<input id="checkbox9" type="checkbox" name="checkbox9" value="1" <?php echo isset($inp_ch["checkbox9"]) ? "checked": ""; ?> />
				</span>
			</td>
			<td><label for="checkbox9">Disclosure Page</label></td>
			<td>If currently processing</td>
		</tr>
		<tr>
			<td>
				<span class="ch_group1 required_radio">
					<input id="checkbox10" type="checkbox" name="checkbox10" value="1" <?php echo isset($inp_ch["checkbox10"])? "checked": ""; ?> />
				</span>
			</td>
			<td><label for="checkbox10">W-9</label></td>
			<td>If no processing history </td>
		</tr>
		<tr>
			<td>
				<span class="ch_group1 required_radio">
					<input id="checkbox11" type="checkbox" name="checkbox11" value="1" <?php echo isset($inp_ch["checkbox11"])? "checked": ""; ?> />
				</span>
			</td>
			<td><label for="checkbox11">CB Dispute Agreement</label></td>
			<td>If new bank account</td>
		</tr>
	</table>
	<table style="width:100%;margin-top:30px;">
		<tr style="background-color:#DBE5F1;">
			<td colspan="3"><h3>BONDSMAN INFORMATION </h3></td>
		</tr>
		<tr>
			<td style="width:50%;">Bondsmen Name:</td>
			<td><input type="text" name="input4_1" class="indispensable" value="<?php echo (empty($custom_inputs['input4_1']) && isset($_COOKIE[$data_id.'_input4_1'])) ?$_COOKIE[$data_id.'_input4_1']: $custom_inputs['input4_1']; ?>" /></td>
		</tr>
		<tr>
			<td>Bondsmen Number:</td>
			<td><input type="text" name="input[]" class="indispensable" value="<?php echo isset($inp_txt[$i]) ? $inp_txt[$i++] : ""; ?>" /></td>
		</tr>
		<tr>
			<td>Bail Bond Company of Employment:</td>
			<td><input type="text" name="input[]" class="indispensable" value="<?php echo isset($inp_txt[$i]) ? $inp_txt[$i++] : ""; ?>" /></td>
		</tr>
		<tr>
			<td>General Agent Name:</td>
			<td><input type="text" name="input[]" class="indispensable" value="<?php echo isset($inp_txt[$i]) ? $inp_txt[$i++] : ""; ?>" /></td>
		</tr>
		<tr>
			<td>State Bondsman License #:</td>
			<td><input type="text" name="input[]" class="indispensable" value="<?php echo isset($inp_txt[$i]) ? $inp_txt[$i++] : ""; ?>" /></td>
		</tr>
	</table>
</form>
