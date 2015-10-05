<?php
$inp_txt = $data["input_text"];

$data_id = $data["data_id"];
$inp_ch = $data["input_check"];


$inp_com = $data["common"];
$i = 0;
//echo "<pre>";
//print_r($data);
$custom_inputs = isset($data['inputs'])?$data['inputs']:array();


?>

<form method="post" action = "<?php echo BASE_URL; ?>ajax" id="main_form">
    <input type="hidden" name="data_id" value="<?php echo $data_id; ?>" />
    <input type="hidden" name="action" value="form" />
    <input type="hidden" name="form_name" value="merchantapp" />
	<input type="hidden" name="filled" value="0" />
    <!--<input type="submit" value="SAVE & VIEW" class="print_submit" id="form_submit_button"/>-->



    <table id="tbl1" class="merch_agr_tbl" border="0">
        <tr class="row1">
            <td style="width:330px;">
                <img width="160" src="<?php echo BASE_URL; ?>images/bailswipe-logo.png" />
            </td>
            <td>
                <p>MERCHANT AGREEMENT</p>
            </td>
            <td style="width:330px;">
                <p class="tar">0413c<br/><b>SPONSORED BY</b></p>
                <img src="<?php echo BASE_URL; ?>images/bank.png" style="float: right;"/>
            </td>
        </tr>
        <tr>
            <td style="padding-top: 20px;">
                <p>Choice Merchant Solutions</p>
                <p>1010 Wetherfield Avenue</p>
                <p>Hartford, CT 06114</p>
                <p>800-615-1330</p>
            </td>
            <td>
                <label style="display: inline-block; width: 40px;">MCC:</label><input type="text" name="input[]" value="<?php echo isset($inp_txt[$i]) ? $inp_txt[$i++] : ""; ?>" /><br/>
                <label style="display: inline-block; width: 40px;">MN:</label><input type="text" name="input[]" value="<?php echo isset($inp_txt[$i]) ? $inp_txt[$i++] : ""; ?>"/>
            </td>
            <td style="padding-left: 30px;">
                <p>Merrick Bank</p>
                <p>135 Crossways Park Drive North, Suite A</p>
                <p>Woodbury, NY 11797 800-267-2256</p>
            </td>
        </tr>
        <tr>
            <td style="text-align: right;"><label>Office:</label> <input  type="text" name="input[]" disabled="disabled" value="4838"/></td>
            <td style="text-align: center;"><label>Account Mgr:</label><input type="text" name="input[]" value="<?php echo isset($inp_txt[$i]) ? $inp_txt[$i++] : ""; ?>"/></td>
            <td><label>Account Rep:</label> <input type="text" name="input[]" value="Online Application" disabled="disabled" class=""></td>
        </tr>
    </table>

    <table id="tbl2">
        <tr class="row2">
            <td class="black_header" colspan="3">
                <h3 style="margin-left:10px;">BUSINESS NAME (S)</h3>
            </td>
        </tr>
        <tr class="row3">
            <td class="border_left" style="width:500px;" >
                <div style="height: 46px;width:370px;border-right:1px solid black;float:left;">
                    <label style="display: inline-block;width:90px;float: left;">Corporate or	Legal Name</label>
                    <input name="input1" class="indispensable" style="margin-top:8px;" type="text" value="<?php echo (empty($custom_inputs['input1']) && isset($_COOKIE[$data_id.'_input1'])) ?$_COOKIE[$data_id.'_input1']: $custom_inputs['input1']; ?>" />
				</div>
                <div style="width:123px;float:right">
                    <label>No. Locations</label>
                    <input name="input[]" class="indispensable" style="width:60px;margin-left: 20px;" type="text" value="<?php echo isset($inp_txt[$i]) ? $inp_txt[$i++] : ""; ?>" />
                </div>
            </td>
            <td class="border_right border_left border_bottom">
                <label>Doing Business As</label>
                <input type="text" name="input14" class="indispensable" value="<?php echo (empty($custom_inputs['input14']) && isset($_COOKIE[$data_id.'_input14'])) ?$_COOKIE[$data_id.'_input14']: $custom_inputs['input14']; ?>" />
            </td>

        </tr>
        <tr>
            <td class="border_left">
                <label style="float:left;display:inline-block;width:91px;">Corporate Address</label>
                <input style="margin-top:8px;" class="corporate_address indispensable" type="text" name="input2_1" value="<?php echo (empty($custom_inputs['input2_1']) && isset($_COOKIE[$data_id.'_input2_1'])) ?$_COOKIE[$data_id.'_input2_1']: $custom_inputs['input2_1']; ?>" />
            </td>
            <td class="border_right border_left border_bottom" style="width:500px;">
                <div style="height: 46px;width:71px;border-right:1px solid black;float:left;">
                    <label style="line-height: 14px;display: inline-block;width:90px;float: left;" for="checkbox1">Same As Corporate</label>
                    <input class="" type="checkbox" name="checkbox1" id="checkbox1" value="" checked="checked" <?php echo isset($inp_ch["checkbox1"]) ? "checked": ""; ?> />
                </div>
                <div style="width:403px;float:right">
                    <label style="float:left;display:inline-block;width:91px;">Location Address</label>
                    <input style="margin-top:8px;" class="indispensable" type="text" name="input2_2" value="<?php echo (empty($custom_inputs['input2_2']) && isset($_COOKIE[$data_id.'_input2_1'])) ?$_COOKIE[$data_id.'_input2_1']: $custom_inputs['input2_2']; ?>" />
                </div>
            </td>
        </tr>
        <tr>
            <td class="border_left" style="width:500px;">
                <div style="height: 46px;width:227px;border-right:1px solid black;float:left;">
                    <label style="float: left; margin: 13px 0 0 3px;">City</label>
                    <input class="indispensable" type="text" style="width:70%;margin:12px 0 0 10px;" name="input11_1" value="<?php echo (empty($custom_inputs['input11_1']) && isset($_COOKIE[$data_id.'_input11_1'])) ?$_COOKIE[$data_id.'_input11_1']: $custom_inputs['input11_1']; ?>" />
                </div>
                <div style="height: 46px;width:133px;float:right;">
                    <label style="float:left; margin: 13px 0 0 3px;">Zip</label>
                    <input class="indispensable" style="width:60%;margin:12px 0 0 10px;" type="text" name="input13_1" value="<?php echo (empty($custom_inputs['input13_1']) && isset($_COOKIE[$data_id.'_input13_1'])) ?$_COOKIE[$data_id.'_input13_1']: $custom_inputs['input13_1']; ?>" />
                </div>
                <div style="height:46px; width:133px;float:right;border-right:1px solid black;">
                    <label style="float:left; margin: 13px 0 0 3px;">State</label>
                    <input class="indispensable" style="width:60%;margin:12px 0 0 10px;;" type="text" name="input12_1" value="<?php echo (empty($custom_inputs['input12_1']) && isset($_COOKIE[$data_id.'_input12_1'])) ?$_COOKIE[$data_id.'_input12_1']: $custom_inputs['input12_1']; ?>" />
                </div>
            </td>
            <td class="border_right border_left border_bottom" style="width:500px;">
                <div style="height:46px;width:227px;border-right:1px solid black;float:left;">
                    <label style="float: left; margin: 13px 0 0 3px;">City</label>
                    <input class="indispensable" type="text" style="width:70%;margin:12px 0 0 10px;" name="input11_2" value="<?php echo (empty($custom_inputs['input11_2']) && isset($_COOKIE[$data_id.'_input11_1'])) ?$_COOKIE[$data_id.'_input11_1']: $custom_inputs['input11_2']; ?>" />
                </div>
                <div style="height: 46px;width:133px;float:right;">
                    <label style="float:left; margin: 13px 0 0 3px;">Zip</label>
                    <input class="indispensable" style="width:60%;margin:12px 0 0 10px;" type="text" name="input13_2" value="<?php echo (empty($custom_inputs['input13_2']) && isset($_COOKIE[$data_id.'_input13_1'])) ?$_COOKIE[$data_id.'_input13_1']: $custom_inputs['input13_2']; ?>" />
                </div>
                <div style="height:46px; width:133px;float:right;border-right:1px solid black;">
                    <label style="float:left; margin: 13px 0 0 3px;">State</label>
                    <input style="width:60%;margin:12px 0 0 10px;" class="indispensable" type="text" name="input12_2" value="<?php echo (empty($custom_inputs['input12_2']) && isset($_COOKIE[$data_id.'_input12_1'])) ?$_COOKIE[$data_id.'_input12_1']: $custom_inputs['input12_2']; ?>" />
                </div>
            </td>
        </tr>
        <tr>
            <td class="border_left" style="width:500px;">
                <div style="height: 46px;width:245px;border-right:1px solid black;float:left;">
                    <label style="float: left;display:inline-block;width:66px;">Telephone Number</label>
                    <input class="indispensable" type="text" style="margin-top:8px;" name="input[]" value="<?php echo (empty($inp_txt[$i]) && isset($_COOKIE[$data_id.'_input3'])) ?$_COOKIE[$data_id.'_input3']: $inp_txt[$i++]; ?>" />
                </div>
                <div style="height: 46px;width:245px;float:right;">
                    <label style="float:left;display:inline-block;width:66px;">Fax Number</label>
                    <input style="margin-top:8px;" type="text" name="input[]" class="fixed" value="<?php echo isset($inp_txt[$i]) ? $inp_txt[$i++] : ""; ?>" />
                </div>
            </td>
            <td class="border_right border_left border_bottom" style="width:500px;">
                <div style="height: 46px;width:245px;border-right:1px solid black;float:left;">
                    <label style="float: left;display:inline-block;width:66px;">Telephone Number</label>
                    <input class="indispensable" type="text" style="margin-top:8px;" name="input3" value="<?php echo (empty($custom_inputs['input3']) && isset($_COOKIE[$data_id.'_input3'])) ?$_COOKIE[$data_id.'_input3']: $custom_inputs['input3']; ?>" />
                </div>
                <div style="height: 46px;width:245px;float:right;">
                    <label style="float:left;display:inline-block;width:66px;">Alternate Phone</label>
                    <input style="margin-top:8px;" class="fixed" type="text" name="input[]" value="<?php echo isset($inp_txt[$i]) ? $inp_txt[$i++] : ""; ?>" />
                </div>
            </td>
        </tr>
        <tr>
            <td class="border_left" style="width:500px;">
                <div style="height: 46px;width:245px;border-right:1px solid black;float:left;">
                    <label style="float: left;display:inline-block;width:83px;">Federal Tax ID(Nine Digits)</label>
                    <input class="indispensable" type="text" style="margin-top:8px; width: 150px;" name="input[]" value="<?php echo isset($inp_txt[$i]) ? $inp_txt[$i++] : ""; ?>" />
                </div>
                <div style="height: 46px;width:245px;float:right;">
                    <label style="float:left;display:inline-block;width:66px;">Contact Person</label>
                    <input class="indispensable" style="margin-top:8px;" type="text" name="input4_1" value="<?php echo (empty($custom_inputs['input4_1']) && isset($_COOKIE[$data_id.'_input4_1'])) ?$_COOKIE[$data_id.'_input4_1']: $custom_inputs['input4_1']; ?>" />
                </div>
            </td>
            <td class="border_right border_left border_bottom" style="width:500px;">
                <div style="height: 50px;width:398px;border-right:1px solid black;float:left;">
                    <label style="float: left;display:inline-block;width:66px;">Email Address</label>
                    <input class="indispensable" type="text" style="margin-top:8px;" name="input7" value="<?php echo (empty($custom_inputs['input7']) && isset($_COOKIE[$data_id.'_input7'])) ?$_COOKIE[$data_id.'_input7']: $custom_inputs['input7']; ?>" />
                </div>
                <div style="height: 46px;width:99px;float:right;">
                    <label style="line-height:10px;"><b>Mail To:</b></label><br/>
                    <input type="checkbox" name="checkbox2" id="checkbox2" value="1" disabled="disabled" checked /><label for="checkbox2">Corporate</label><br/>
                    <input type="checkbox" name="checkbox3" id="checkbox3" value="1" disabled="disabled" /><label for="checkbox3">Location</label>
                </div>
            </td>
        </tr>
        <tr class="">
            <td class="black_header">
                <h3 style="margin-left:10px;">MERCHANT PROFILE</h3>
            </td>
            <td class="black_header">
                <h3 style="margin-left:10px;">PROCESSING HISTORY</h3>
            </td>
        </tr>
        <tr>
            <td class="border_left" style="width:500px;">
				<div style="height: 353px;">
					<div style="height: 50px">
						<div style="height: 50px;width:260px;border-right:1px solid black;float:left;">
							<label style="float: left;display:inline-block;width:65px;">Type of Ownership</label>
							<span class="required_radio"><input class="" id="radio91" type="radio" name="radio9" value="1" <?php echo (isset($inp_ch["radio9"]) && $inp_ch["radio9"] == 1)? "checked": ""; ?> /></span><label for="radio91">Sole Proprietor</label>
							<span class="required_radio"><input class="" id="radio92" type="radio" name="radio9" value="2" <?php echo (isset($inp_ch["radio9"]) && $inp_ch["radio9"] == 2)? "checked": ""; ?> /></span><label for="radio92">Corporation</label>
							<span class="required_radio"><input class="" id="radio93" type="radio" name="radio9" value="3" <?php echo (isset($inp_ch["radio9"]) && $inp_ch["radio9"] == 3)? "checked": ""; ?> /></span><label for="radio93">Partnership</label>
							<span class="required_radio"><input class="" id="radio94" type="radio" name="radio9" value="4" <?php echo (isset($inp_ch["radio9"]) && $inp_ch["radio9"] == 4)? "checked": ""; ?> /></span><label for="radio94">LLC</label>
						</div>
						<div style="height:50px; width:230px; float:right;">
							<label style="display: inline-block; width: 110px;">Type of Goods Sold</label>
							<input style="width:100px;" name="input[]" disabled="disabled" type="text" value="Bail Bonds" /><br/>
							<label style="display: inline-block; width: 110px;">SIC/MCC</label>
							<input name="input[]" style="width:100px; " type="text" value="<?php echo isset($inp_txt[$i]) ? $inp_txt[$i++] : ""; ?>" />
						</div>
					</div>
					<div>
						<div class="border_right border_top fl" style="height: 50px;width:156px;">
							<label>Length of Ownership</label><br/>
							<input type="text" name="input[]" class="indispensable" style="width:50px;" value="<?php echo isset($inp_txt[$i]) ? $inp_txt[$i++] : ""; ?>" /><label>YRS</label>
							<input type="text" name="input[]" class="indispensable" style="width:41px;" value="<?php echo isset($inp_txt[$i]) ? $inp_txt[$i++] : ""; ?>" /><label>MOS</label>
						</div>
						<div class="border_top border_right" style="height: 50px;width:169px;float:left;">
							<label>Length at Location</label><br/>
							<input type="text" name="input[]" class="indispensable" style="width:50px;"  value="<?php echo isset($inp_txt[$i]) ? $inp_txt[$i++] : ""; ?>"/><label>YRS</label>
							<input type="text" name="input[]" class="indispensable" style="width:41px;"  value="<?php echo isset($inp_txt[$i]) ? $inp_txt[$i++] : ""; ?>"/><label>MOS</label>
						</div>
						<div class="border_top fl" style="height: 50px;width:171px;">
							<label>Business Established In:</label>
							<input type="text" name="input[]" class="indispensable"  value="<?php echo isset($inp_txt[$i]) ? $inp_txt[$i++] : ""; ?>"/>
						</div>
					</div>
					<div>
						<div class="border_top fl" style="height: 50px;width: 100%;">
							<label style="width: 50px;" class="fl">Web Address</label>
							<input type="text" name="input[]"  class="indispensable" style="width:250px;" value="<?php echo isset($inp_txt[$i]) ? $inp_txt[$i++] : ""; ?>"/>
						</div>
					</div>
					<div>
						<div class="black_header fl" style="height: 20px;width: 100%;  border: 0;">
							<h3 style="margin-left:10px;" >CREDIT CARD TRANSACTION PROFILE</h3>
						</div>
					</div>
					<div>
						<div class="border_right border_top fl" style="height: 180px;width:156px;">
							<input type="checkbox" name="checkbox8" checked disabled="disabled" /><label>Retail</label><br/>
							<input type="checkbox" name="checkbox9" disabled='disabled' /><label>Restaurant w/Tip</label><br/>
							<input type="checkbox" name="checkbox10" disabled='disabled' /><label>Lodging</label><br/>
							<input type="checkbox" name="checkbox11" disabled='disabled' /><label>Trade/Craft Shows</label><br/>
							<input type="checkbox" name="checkbox12" disabled='disabled' /><label>Mail/Phone Order</label><br/>
							<input type="checkbox" name="checkbox13" disabled='disabled' /><label>Internet</label><br/>
							<input type="checkbox" name="checkbox14" disabled='disabled' /><label>Service</label><br/>
						</div>
						<div class="lbl_width90 border_top border_right" style="height: 180px;width:169px;float:left;">
							<label>On Premise Sales</label>
							<input type="text" name="input[]" style="width:50px;"  disabled="disabled" value="90%"/> <span> % </span><br/>
							<label>Off Premise Sales</label>
							<input type="text" name="input[]" style="width:50px;" disabled="disabled" value="10%"/> <span> % </span><br/>
							<label>Mail Order</label>
							<input type="text" name="input[]" style="width:50px;"  value="<?php echo isset($inp_txt[$i]) ? $inp_txt[$i++] : ""; ?>"/> <span> % </span><br/>
							<label>Telephone Order</label>
							<input type="text" name="input[]" style="width:50px;"  value="<?php echo isset($inp_txt[$i]) ? $inp_txt[$i++] : ""; ?>"/> <span> % </span><br/>
							<label>Internet</label>
							<input type="text" name="input[]" style="width:50px;" value="<?php echo isset($inp_txt[$i]) ? $inp_txt[$i++] : ""; ?>"/> <span> % </span><br/>
							<span><b>MUST TOTAL 100%</b></span>
						</div>
						<div class="border_top fl lbl_width90" style="height: 180px;width:171px;">
							<label>Sales Swiped <br/>Through <br/>POS terminal</label>
							<input type="text" name="input[]" disabled="disabled" style="width:50px;" value="10%"/> <span> % </span><br/><br/>
							<label>Sales Keyed <br/>Into <br/>POS terminal</label>
							<input type="text" name="input[]" disabled="disabled" style="width:50px;" value="10%" /> <span> % </span><br/><br/>
							<span><b>MUST TOTAL 100%</b></span>
						</div>
					</div>
				</div>

            </td>
            <td class="border_right border_left border_bottom " style="width:500px;">
                <div class="proc_history border_bottom">
                    <span>Has the business or any associated owner ever been terminated as a</span>
                    <div class="required_radio"><input style="cursor:pointer;" type="radio" name="radio1" id="radio1_yes" value="1" <?php echo (isset($inp_ch["radio1"]) && $inp_ch["radio1"] == 1)? "checked": ""; ?> /></div><label for="radio1_yes">YES</label><br/>
					<span>VISA&reg;/MasterCard&reg;/Discover&reg; merchant?</span>
                    <div class="required_radio"><input type="radio" style="cursor:pointer;" name="radio1" id="radio1_no" value="0" <?php echo (isset($inp_ch["radio1"]) && $inp_ch["radio1"] == 0)? "checked": ""; ?>  /></div><label for="radio1_no">NO</label><br/>
				</div>
                <div class="proc_history border_bottom">
                    <span>Do you currently accept VISA&reg;/MasterCard&reg;/Discover&reg;?</span>
                    <div class="required_radio"><input style="cursor:pointer;" type="radio" value="1" name="radio2" id="radio2_yes" <?php echo (isset($inp_ch["radio2"]) && $inp_ch["radio2"] == 1)? "checked": ""; ?>/></div><label for="radio2_yes">YES</label><br/>
                    <span>If YES, please submit 3 most current monthly statements</span>
                    <div class="required_radio"><input style="cursor:pointer;" type="radio" value="0" name="radio2" id="radio2_no" <?php echo (isset($inp_ch["radio2"]) && $inp_ch["radio2"] == 0) ? "checked": ""; ?>/></div><label for="radio2_no">NO</label><br/>
                </div>
                <div class="proc_history border_bottom">
                    <span>Are there third parties/payment applications involved with<br/>your payment process?</span>
                    <div class="required_radio"><input type="radio" name="radio3" id="radio3_yes" value="1" <?php echo (isset($inp_ch["radio3"]) && $inp_ch["radio3"] == 1)? "checked": ""; ?> /></div><label for="radio3_yes">YES</label><br/>
                    <span></span>
                    <div class="required_radio"><input type="radio" name="radio3" id="radio3_no" value="0" <?php echo (isset($inp_ch["radio3"]) && $inp_ch["radio3"] == 0)? "checked": ""; ?> /></div><label for="radio3_no">NO</label><br/>
                    <span style="width: 100px;">If YES, identify.</span>
                    <input type="text" name="input[]" style="width:300px;" value="<?php echo isset($inp_txt[$i]) ? $inp_txt[$i++] : ""; ?>"/>
                </div>
                <div class="proc_history border_bottom">
                    <span></span>
                    <input type="checkbox" name="checkbox21" id="checkbox21" <?php echo isset($inp_ch["checkbox21"]) ? "checked": ""; ?>/><label for="checkbox21">YES</label><br/>
                    <span>Is your business PCI compliant?</span>
                    <input type="checkbox" name="checkbox22" id="checkbox22" <?php echo isset($inp_ch["checkbox22"]) ? "checked": ""; ?>/><label for="checkbox22">NO</label><br/>
                </div>
                <div class="proc_history border_bottom">
                    <label>Has your business had any ongoing or prior data</label>
                    <input type="checkbox" name="checkbox23" id="checkbox23" <?php echo isset($inp_ch["checkbox23"]) ? "checked" : ""; ?>/><label for="checkbox23">YES</label><br/>
                    <label>compromise investigations?</label>
                    <input type="checkbox" name="checkbox24" id="checkbox24" <?php echo isset($inp_ch["checkbox24"]) ? "checked" : ""; ?>/><label for="checkbox24">NO</label><br/>
                </div>
                <div>
                    <p style="margin: 3px 0 10px 3px;">
                        <label>Additional Services</label>
                        <input type="text" name="input[]" style="width:50px;" value="<?php echo isset($inp_txt[$i]) ? $inp_txt[$i++] : ""; ?>"/>
                        <label>Merchant Number</label>
                        <input type="text" name="input[]" style="width:50px;" value="<?php echo isset($inp_txt[$i]) ? $inp_txt[$i++] : ""; ?>"/>
                    </p>
                    <p>
                        <label style="display: inline-block; width: 100px;">American Express</label>
                        <input type="text" name="input[]" style="width:300px" class="fixed" value="<?php echo isset($inp_txt[$i]) ? $inp_txt[$i++] : ""; ?>"/>
                        <input type="checkbox" name="checkbox25" id="checkbox25" <?php echo isset($inp_ch["checkbox25"]) ? "checked" : ""; ?>/><label for="checkbox25">YES</label>
                        <input type="checkbox" name="checkbox26" id="checkbox26" <?php echo isset($inp_ch["checkbox26"]) ? "checked" : ""; ?>/><label for="checkbox26">NO</label><br/>
                    </p>
                    <br/>
                    <p>
                        <label style="display: inline-block; width: 100px;">Diners Club</label>
                        <input type="text" name="input[]" style="width:300px;" value="<?php echo isset($inp_txt[$i]) ? $inp_txt[$i++] : ""; ?>"/>
                        <input type="checkbox" name="checkbox27" id="checkbox27" <?php echo isset($inp_ch["checkbox27"]) ? "checked" : ""; ?>/><label for="checkbox27">YES</label>
                        <input type="checkbox" name="checkbox28" id="checkbox28" <?php echo isset($inp_ch["checkbox28"]) ? "checked" : ""; ?>/><label for="checkbox28">NO</label><br/>
                    </p>
                    <br/>
                </div>

            </td>
        </tr>
        <tr class="">
            <td class="black_header" colspan="2" >
                <h3 style="margin-left:10px;">OWNERS AND OFFICERS</h3>
            </td>
        </tr>
        <tr class="owner_officer">
            <td colspan="2" class="border_right">
                <div class="border_right border_left  border_bottom owner_officer_first_column fl">
                    <label>Name (1)<br/><i>Please Print</i></label>
                    <input type="text" class="indispensable" name="input4_2" value="<?php echo (empty($custom_inputs['input4_2']) && isset($_COOKIE[$data_id.'_input4_1'])) ?$_COOKIE[$data_id.'_input4_1']: $custom_inputs['input4_2']; ?>" />
                </div>
                <div class="border_right border_bottom owner_officer_second_column fl">
                    <label>Title</label><br/>
                    <input type="text" name="input5" class="indispensable" value="<?php echo (empty($custom_inputs['input5']) && isset($_COOKIE[$data_id.'_input5'])) ?$_COOKIE[$data_id.'_input5']: $custom_inputs['input5']; ?>" style="width: 50px;">
                </div>
                <div class="border_bottom fl" style="width: 594px;">
                    <label>Residential Address, City, State, Zip, County</label><br/>
                    <input type="text" class="indispensable" name="input[]" value="<?php echo isset($inp_txt[$i]) ? $inp_txt[$i++] : ""; ?>" style="width: 400px;"/>
                </div>
            </td>
        </tr>
        <tr class="owner_officer">
            <td class="border_right" colspan="2">
                <div class="border_right border_left border_bottom owner_officer_first_column  fl">
                    <label>SSN</label><br/>
                    <input type="text" class="indispensable" name="input[]" value="<?php echo isset($inp_txt[$i]) ? $inp_txt[$i++] : ""; ?>">
                </div>
                <div class="border_right border_bottom fl" style="width: 100px;">
                    <label>Equity <br/> Ownership </label>
                    <input type="text" class="indispensable" name="input[]" value="<?php echo isset($inp_txt[$i]) ? $inp_txt[$i++] : ""; ?>" style="width: 20px;"> <span>%</span>
                </div>
                <div class="border_right border_bottom fl" style="width: 180px;">
                    Time at Residence<br/>
                    <div style="width: 130px; height: 5px; display: inline-block;"></div><span class="required_radio"><input type="radio" id="radio41" name="radio4" value="1" <?php echo (isset($inp_ch["radio4"]) && $inp_ch["radio4"] == 1)? "checked": ""; ?> /></span><label for="radio41">Own</label>
                    <div style="width: 130px; height: 15px; display: inline-block;">
                        <input style="width:30px;" type="text" name="input[]" value="<?php echo isset($inp_txt[$i]) ? $inp_txt[$i++] : ""; ?>"><label>YRS</label>
                        <input style="width:30px;" type="text" name="input[]" value="<?php echo isset($inp_txt[$i]) ? $inp_txt[$i++] : ""; ?>"><label>MOS</label>
                    </div>
					<span class="required_radio">
					<input type="radio" id="radio40" name="radio4" value="0" <?php echo (isset($inp_ch["radio4"]) && $inp_ch["radio4"] == 0)? "checked": ""; ?> /></span>
					<label for="radio40">Rent</label>
                </div>
                <div class="border_right border_bottom fl" style="width: 180px;">
                    Date of Birth<br/><br/>
					<select class="indispensable" style="width:60px;" name="select1" >
						<option value="">Year</option>
						<?php for($j = 1960; $j <= date('Y'); $j++ ){ ?>
						<option <?php echo (isset($inp_ch['select1']) && $inp_ch['select1'] == $j)?'selected="selected"':''; ?> value="<?php echo $j; ?>"><?php echo $j; ?></option>
						<?php } ?>
					</select><label>/</label>
					<select class="indispensable" style="width:40px;" name="select2" >
						<option value="">Month</option>
						<?php for($j = 1; $j <= 12; $j++ ){ ?>
						<option <?php echo (isset($inp_ch['select2']) && $inp_ch['select2'] == $j)?'selected="selected"':''; ?> value="<?php echo $j; ?>"><?php echo $j<10?('0'.$j):$j; ?></option>
						<?php } ?>
					</select>
					<label>/</label>
					<select class="indispensable" style="width:40px;" name="select3" >
						<option value="">Day</option>
						<?php for($j = 1; $j <= 31; $j++ ){ ?>
						<option <?php echo (isset($inp_ch['select3']) && $inp_ch['select3'] == $j)?'selected="selected"':''; ?> value="<?php echo $j; ?>"><?php echo $j<10?('0'.$j):$j; ?></option>
						<?php } ?>
					</select>
				</div>
                <div class="border_bottom fl" style="width: 232px;">
                    Residence<br/>Telephone<br/>
                    <label style="margin: 0 0 0 30px ;">(</label><input style="width:70px;" type="text" name="input[]" value="<?php echo isset($inp_txt[$i]) ? $inp_txt[$i++] : ""; ?>"><label>)</label>
                </div>
            </td>
        </tr>
        <tr class="owner_officer">
            <td class="border_right" colspan="2">
                <div class="border_right border_left border_bottom owner_officer_first_column fl">
                    <label>Name (1)<br/><i>Please Print</i></label>
                    <input type="text" name="input[]" value="<?php echo isset($inp_txt[$i]) ? $inp_txt[$i++] : ""; ?>">
                </div>
                <div class="border_right border_bottom owner_officer_second_column fl">
                    <label>Title</label><br/>
                    <input type="text" name="input[]" value="<?php echo isset($inp_txt[$i]) ? $inp_txt[$i++] : ""; ?>" style="width: 50px;">
                </div>
                <div class="border_bottom fl" style="width: 594px;">
                    <label>Residential Address, City, State, Zip, County</label><br/>
                    <input type="text" name="input[]" value="<?php echo isset($inp_txt[$i]) ? $inp_txt[$i++] : ""; ?>" style="width: 400px;">
                </div>
            </td>
        </tr>
        <tr class="owner_officer">
            <td class="border_right" colspan="2">
                <div class="border_right border_left border_bottom owner_officer_first_column  fl">
                    <label>SSN</label><br/>
                    <input type="text" name="input[]" value="<?php echo isset($inp_txt[$i]) ? $inp_txt[$i++] : ""; ?>">
                </div>
                <div class="border_right border_bottom fl" style="width: 100px;">
                    <label>Equity <br/> Ownership </label>
                    <input type="text" name="input[]" value="<?php echo isset($inp_txt[$i]) ? $inp_txt[$i++] : ""; ?>" style="width: 20px;"> <span>%</span>
                </div>
                <div class="border_right border_bottom fl" style="width: 180px;">
                    Time at Residence<br/>
                    <div style="width: 130px; height: 5px; display: inline-block;"></div>
					<input type="checkbox" name="checkbox28_4" id="checkbox28_4" value="" <?php isset($inp_ch["checkbox28_4"]) ? "checked" : ""; ?>/><label for="checkbox28_4">Own</label>
                    <div style="width: 130px; height: 15px; display: inline-block;">
                        <input style="width:30px;" type="text" name="input[]" value="<?php echo isset($inp_txt[$i]) ? $inp_txt[$i++] : ""; ?>"><label>YRS</label>
                        <input style="width:30px;" type="text" name="input[]" value="<?php echo isset($inp_txt[$i]) ? $inp_txt[$i++] : ""; ?>"><label>MOS</label>
                    </div><input type="checkbox" name="checkbox28_3" id="checkbox28_3" value="" <?php isset($inp_ch["checkbox28_3"]) ? "checked" : ""; ?>/><label for="checkbox28_3">Rent</label>
                </div>
                <div class="border_right border_bottom fl" style="width: 180px;">
                    Date of Birth<br/><br/>
					<select style="width:60px;" name="select4" >
						<option value="">Year</option>
						<?php for($j = 1960; $j <= date('Y'); $j++ ){ ?>
						<option <?php echo (isset($inp_ch['select4']) && $inp_ch['select4'] == $j)?'selected="selected"':''; ?> value="<?php echo $j; ?>"><?php echo $j; ?></option>
						<?php } ?>
					</select><label>/</label>
					<select style="width:40px;" name="select5" >
						<option value="">Month</option>
						<?php for($j = 1; $j <= 12; $j++ ){ ?>
						<option <?php echo (isset($inp_ch['select5']) && $inp_ch['select5'] == $j)?'selected="selected"':''; ?> value="<?php echo $j; ?>"><?php echo $j<10?('0'.$j):$j; ?></option>
						<?php } ?>
					</select>
					<label>/</label>
					<select style="width:40px;" name="select6" >
						<option value="">Day</option>
						<?php for($j = 1; $j <= 31; $j++ ){ ?>
						<option <?php echo (isset($inp_ch['select6']) && $inp_ch['select6'] == $j)?'selected="selected"':''; ?> value="<?php echo $j; ?>"><?php echo $j<10?('0'.$j):$j; ?></option>
						<?php } ?>
					</select>
                </div>
                <div class="border_bottom fl" style="width: 232px;">
                    Residence<br/>Telephone<br/>
                    <label style="margin: 0 0 0 30px ;">(</label>
                    <input style="width:70px;" type="text" name="input[]" value="<?php echo isset($inp_txt[$i]) ? $inp_txt[$i++] : ""; ?>">
                    <label>)</label>
                </div>
            </td>
        </tr>
        <tr class="owner_officer">
            <td class="border_right" colspan="2">
                <div class="border_left border_right border_bottom owner_officer_first_column  fl">
                    <label><strong>BANK <br/> REFERENCE</strong></label><br/>
                    <input type="text" name="input[]" class="indispensable" value="<?php echo isset($inp_txt[$i]) ? $inp_txt[$i++] : ""; ?>" />
                </div>
                <div class="border_right border_bottom fl" style="width: 231px;">
                    <label>Account #</label><br/>
                    <input type="text" name="input[]" class="indispensable" value="<?php echo isset($inp_txt[$i]) ? $inp_txt[$i++] : ""; ?>" />
                </div>
                <div class="border_right border_bottom fl" style="width: 231px;">
                    <label>Telephone<br/>Number</label><br/>
                    (<input type="text" name="input[]" value="<?php echo isset($inp_txt[$i]) ? $inp_txt[$i++] : ""; ?>" />)
                </div>
                <div class="border_bottom fl" style="width: 231px;">
                    Contact<br/>
                    <span style="margin: 12px 0 0 25px;">
                        <input type="text" name="input[]" value="<?php echo isset($inp_txt[$i]) ? $inp_txt[$i++] : ""; ?>" />
                    </span>
                </div>
            </td>
        </tr>
        <tr class="">
            <td class="black_header" colspan="2">
                <h3 style="margin-left:10px;">MERCHANT SITE INSPECTION REPORT <span style="font-size: 12px;">(must be completed by sales representative)<span></h3>
            </td>
        </tr>
        <tr class="merch_site_insertion_first_row">
            <td colspan="2">
                <span class="col"><strong>Merchant Location</strong></span>
                <span class="ch_group1 required_radio"><input type="checkbox" name="checkbox29" id="checkbox29" value="1" <?php echo isset($inp_ch["checkbox29"]) ? "checked" : ""; ?> ></span><label class="col" for="checkbox29">Shopping Center</label>
                <span class="ch_group1 required_radio"><input type="checkbox" name="checkbox30" id="checkbox30" value="1" <?php echo isset($inp_ch["checkbox30"]) ? "checked" : ""; ?> ></span><label class="col" for="checkbox30">Retail Storefront</label>
                <span class="ch_group1 required_radio"><input type="checkbox" name="checkbox31" id="checkbox31" value="1" <?php echo isset($inp_ch["checkbox31"]) ? "checked" : ""; ?> ></span><label class="col" for="checkbox31">Residence</label>
                <span class="ch_group1 required_radio"><input type="checkbox" name="checkbox32" id="checkbox32" value="1" <?php echo isset($inp_ch["checkbox32"]) ? "checked" : ""; ?> ></span><label class="col" for="checkbox32">Mobile Merchant</label>
                <span class="ch_group1 required_radio"><input type="checkbox" name="checkbox33" id="checkbox33" value="1" <?php echo isset($inp_ch["checkbox33"]) ? "checked" : ""; ?> ></span><label class="col" for="checkbox33">Office Building</label>
                <br>
                <span class="col"><strong>Area is Zoned</strong></span>
                <span class="ch_group2 required_radio"><input type="checkbox" name="checkbox34" id="checkbox34" value="1" <?php echo isset($inp_ch["checkbox34"]) ? "checked" : ""; ?> ></span><label class="col" for="checkbox34">Commercial</label>
                <span class="ch_group2 required_radio"><input type="checkbox" name="checkbox35" id="checkbox35" value="1" <?php echo isset($inp_ch["checkbox35"]) ? "checked" : ""; ?> ></span><label class="col" for="checkbox35">Residential</label>
                <span class="ch_group2 required_radio"><input type="checkbox" name="checkbox36" id="checkbox36" value="1" <?php echo isset($inp_ch["checkbox36"]) ? "checked" : ""; ?> ></span><label class="col" for="checkbox36">Industrial</label>
                <br>
                <span class="col"><strong>Square Footage</strong></span>
                <span class="ch_group3 required_radio"><input type="checkbox" name="checkbox37" id="checkbox37" value="1" <?php echo isset($inp_ch["checkbox37"]) ? "checked" : ""; ?> ></span><label class="col" for="checkbox37">0-250</label>
                <span class="ch_group3 required_radio"><input type="checkbox" name="checkbox38" id="checkbox38" value="1" <?php echo isset($inp_ch["checkbox38"]) ? "checked" : ""; ?> ></span><label class="col" for="checkbox38">251-500</label>
                <span class="ch_group3 required_radio"><input type="checkbox" name="checkbox39" id="checkbox39" value="1" <?php echo isset($inp_ch["checkbox39"]) ? "checked" : ""; ?> ></span><label class="col" for="checkbox39">501-2000</label>
				<span class="ch_group3 required_radio"><input type="checkbox" name="checkbox40" id="checkbox40" value="1" <?php echo isset($inp_ch["checkbox40"]) ? "checked" : ""; ?> ></span><label class="col" for="checkbox40">2001+</label>
				<br>
                Does the inventory, merchandise, and staff appear to be consistent with the type of business?
				<input type="checkbox" name="checkbox40_1" id="checkbox40_1" value="" <?php echo isset($inp_ch["checkbox40_1"]) ? "checked" : ""; ?>><label for="checkbox40_1">YES</label>
				<input type="checkbox" name="checkbox40_2" id="checkbox40_2" value="" <?php echo isset($inp_ch["checkbox40_2"]) ? "checked" : ""; ?>><label for="checkbox40_2">NO</label>
                If no, please explain:
            </td>
        </tr>
        <tr>
            <td class="border_right" colspan="2">
                <div class="border_left border_bottom border_right border_top fl" style="width: 100px; height: 60px;">
                    The Merchant <br/>
                    <span class="required_radio"><input type="radio" name="radio8" id="radio8_own" value="1" <?php echo (isset($inp_ch["radio8"]) && $inp_ch["radio8"] == 1)? "checked": ""; ?>></span><label class="col" for="radio8_own">Owns</label> <br/>
                    <span class="required_radio"><input type="radio" name="radio8" id="radio8_lease" value="2" <?php echo (isset($inp_ch["radio8"]) && $inp_ch["radio8"] == 2)? "checked": ""; ?> ></span><label class="col" for="radio8_lease">Leases</label>

                </div>
                <div class="border_bottom border_right border_top fl" style="width: 650px; height: 60px;">
                    Landlord's Name Or Mortgage Holder<br/>
					<input type="text" name="input[]" class="indispensable" value="<?php echo isset($inp_txt[$i]) ? $inp_txt[$i++] : ""; ?>" />
                </div>
                <div class="border_bottom border_top fl" style="width: 245px; height: 60px;">
                    <label>Telephone<br/>Number</label><br/>
                    (<input type="text" name="input[]" class="indispensable" value="<?php echo isset($inp_txt[$i]) ? $inp_txt[$i++] : ""; ?>" />)
                </div>
            </td>
        </tr>
        <tr>
            <td class="border_right" colspan="2">
                <div class="border_left border_bottom" style="height: 60px;">
                    General Comments by Inspector <br/>
                    <input type="text" name="input[]" value="<?php echo isset($inp_txt[$i]) ? $inp_txt[$i++] : ""; ?>" style="width: 70%; margin: 10px 0 0 10px;"/>
                </div>
            </td>
        </tr>
        <tr>
            <td class="border_right" colspan="2">
                <div class="border_left border_bottom border_right fl" style="width: 400px; height: 60px;">
                    I hereby verify that I
                    <input type="checkbox" name="checkbox43" id="checkbox43" value="" <?php echo isset($inp_ch["checkbox43"]) ? "checked" : ""; ?>><label for="checkbox43"><b>have</b></label>
                    <input type="checkbox" name="checkbox44" id="checkbox44" value="" <?php echo isset($inp_ch["checkbox44"]) ? "checked" : ""; ?>><label for="checkbox44"><b>have not</b></label>
                    physically inspected the business premises of the merchant at this address and the information stated above is correct to the best of my knowledge.
                </div>
                <div class="border_bottom border_right fl" style="width: 350px; height: 60px;">
                    Signature of<br/>
                    Rep/Inspector
                </div>
                <div class="border_bottom fl" style="width: 245px; height: 60px;">
                    Date<br/><br/>
                        <input style="width:30px;" name="input[]" type="text" value="<?php echo isset($inp_txt[$i]) ? $inp_txt[$i++] : ""; ?>"><label>/</label>
                        <input style="width:30px;" name="input[]" type="text" value="<?php echo isset($inp_txt[$i]) ? $inp_txt[$i++] : ""; ?>"><label>/</label>
                        <input style="width:30px;" name="input[]" type="text" value="<?php echo isset($inp_txt[$i]) ? $inp_txt[$i++] : ""; ?>">
                </div>
            </td>
        </tr>
    </table>
		<h1 style="text-align:center;">MERCHANT AGREEMENT</h1>
	<table id="tbl3">
		<tr class="row2">
			<td class="black_header">
				<h3 style="margin-left:10px;">DEBIT / CREDIT AUTHORIZATION</h3>
			</td>
		</tr>
		<tr class="row2">
			<td>
				<p>MERCHANT hereby authorizes BANK and EMS in accordance with this MERCHANT Agreement to initiate debit/credit entries to MERCHANTS' checking account as indicated below. This authority is to remain in full force and effect during the term of the Agreement. This authorization extends to such entries in said account concerning lease, rental or purchase agreement applying to POS terminal, accompanying equipment, check guarantee fees and/or gift/loyalty card fees.</p>
				<br/>
				<div style="width:146px;float:left;height:50px;">
					<div class="box_rotate">
						<p>STAPLE</p>
						<p>CHECK</p>
						<p>HERE</p>
					</div>
				</div>
				<div class="march_1" style="width:850px;float:left;padding-bottom:10px;">
					<p><b>DO NOT USE A DEPOSIT TICKET</b></p>
					<p><b>MAKE SURE CHECK IS VOIDED PROPERLY</b></p>
					<p><b>CHECK MUST BE MICR ENCODED WITH ABA ROUTING NUMBER AND ACCOUNT NUMBER</b></p>
					<p><b>MAKE SURE CHECK IS PRE-PRINTED WITH MERCHANT BUSINESS NAME</b></p>
				</div>

			</td>
		</tr>
		<tr class="row2">
			<td class="black_header">
				<h3 style="margin-left:10px;">AMERICAN EXPRESS CARD ACCEPTANCE</h3>
			</td>
		</tr>
		<tr class="row2">
			<td>
				<p>
					<span class="optional_ch"><input type="checkbox" name="checkbox45" id="checkbox45"  <?php echo isset($inp_ch["checkbox45"]) ? "checked" : ""; ?>/></span>
                    By signing below, I represent that I have read these Terms and Conditions
					for American Express Card Acceptance (including the application page, the "Card Acceptance Agreement")
					and can sign for the entity above, which agrees to be bound by the Card Acceptance Agreement, and that
					all information that I have provided herein is true, complete, and accurate. I authorize EMS and American
					Express Travel Related Services Company, Inc. ("American Express") and American Express's agents and
					Affiliates to verify the information in this application and receive and exchange information about me
					personally, including by requesting reports from consumer reporting agencies, and disclose such information
					to their agents, subcontractors, Affiliates and other parties for any purpose permitted by law. I authorize
					and direct EMS and American Express and American Express agents and Affiliates to inform me directly,
					or through the entity above, of reports about me that they have requested from consumer reporting agencies.
					Such information will include the name and address of the agency furnishing the report. If I have applied,
					on behalf of the entity above, for American Express's standard Card service program, I further understand
					that upon American Express's approval of the entity indicated above to accept the American Express Card,
					the terms and conditions for American Express&#174; Card Acceptance ("Terms and Conditions") will be sent
					to such entity along with a Welcome Letter or like Welcome Materials. By accepting the American Express Card
					for the purchases of goods and/or services, or otherwise indicating its intention to be bound, the entity agrees
					to be bound by the Terms and Conditions. <input style="width:70px;" class="fixed" name="input[]" type="text" value="<?php echo isset($inp_txt[$i]) ? $inp_txt[$i++] : ""; ?>" /> INITIALS
				</p>
			</td>
		</tr>
		<tr>
			<td class="black_header">
				<h3 style="margin-left:10px;">BUSINESS NAME (S)</h3>
			</td>
		</tr/>
		<tr>
			<td>
				<div style="width:598px;float:left;">
					<p>VISA&#174;/MasterCard&#174;/Discover&#174; Qualified Check Card
					<input type="text" name="input[]" style="width:50px;" value="2.49" disabled="disabled" /> <span>%</span> + <input type="text" name="input[]" style="width:50px;" value="0" disabled="disabled"/> <span>&#162;</span></p>
					<p>Pin Debit Card Transaction Fee <span class="line">$0.35</span></p>
					<p>Batch Header <span class="line">$0.25</span></p>
					<p>Monthly Service Fee <span class="line">$15.00</span></p>
					<p>Annual Service Fee <span class="line">$65.00</span></p>
					<p>Account Setup Fee (one time) <span class="line">$95.00</span></p>
					<p>Choice Merchant Club <span class="line">$10.00</span></p>
				</div>
				<div style="width:399px;float:left">
					<p>EBT/AMEX Transaction Fee <span class="line">$0.20</span></p>
					<p>Debit Gateway (monthly) <span class="line">$5.00</span> only applies with use of pin-pad</p>
					<p>Semi-Annual Technology Update/Compliance Fee $75.00</p>
					<p>Monthly Minimum Fee $25.00</p>
					<p>Wireless Transactions <span class="line">$25.00</span> Per Month + $0.10 Per Transaction</p>
					<p>eCommerce <input style="width:50px;" type="text" name="input[]" value="<?php echo isset($inp_txt[$i]) ? $inp_txt[$i++] : ""; ?>"/> Monthly Fee $ <input style="width:50px;" type="text" name="input[]" value="<?php echo isset($inp_txt[$i]) ? $inp_txt[$i++] : ""; ?>"/> Trans Fee $ <input style="width:50px;" type="text" name="input[]" value="<?php echo isset($inp_txt[$i]) ? $inp_txt[$i++] : ""; ?>"/></p>
					<p>Monthly 100K Data and Breach Protection <span class="line">$15.00</span></p>
					<p>Voice Authorization Fee <span class="line">$0.60</span></p>
				</div>
			</td>
		</tr>
		<tr>
			<td>
				<div style="float:left;">
					<p style="font-size:10px;">Additional charge of .40% for all credit card transactions. Additional
					charge for all cards of 1.99% and 15&#162; of sale amount for all mid qualified
					transactions and 1.99% + 15&#162; charge for all non-qualified transactions.
					Mid and non qualified check card transactions will be surcharged an
					additional .40%. All Visa, MasterCard and Discover transaction fees and
					assessments + 15&#162; will be charged to the merchant on every transaction.
					Fees of $45.00 per retrieval request, $45.00 per chargeback and $45.00 per
					returned ACH item. All rewards cards will bump to a mid qualified transaction.
					All Debit network fee's will pass thru to merchant.
					</p><br/>
					<p>
						AN INVESTIGATIVE CONSUMER REPORT MAY BE MADE IN CONNECTION WITH THE ATTACHED APPLICATION. MERCHANT AUTHORIZES BANK, EMS OR ANY CREDIT REPORTING AGENCY EMPLOYED BY BANK OR EMS TO INVESTIGATE THE REFERENCES GIVEN OR ANY OTHER STATEMENTS OR DATA OBTAINED FROM MERCHANT, OR ANY OF THE UNDERSIGNED PRINCIPALS, FOR THE PURPOSE OF THIS APPLICATION OR ANY APPLICATION FOR ACCOMPANYING POS EQUIPMENT FINANCING. THE ABOVE SCHEDULE OF FEES IS PREDICATED ON THE BUSINESS PROFILE AND THE FOLLOWING INFORMATION:
					</p>
					<p>
						<b>AVERAGE MONTHLY SALES VOLUME: $<input type="text" class="indispensable" name="input[]" value="<?php echo isset($inp_txt[$i]) ? $inp_txt[$i++] : ""; ?>" />
							AVERAGE TICKET SIZE: $<input type="text" name="input[]" class="indispensable" value="<?php echo isset($inp_txt[$i]) ? $inp_txt[$i++] : ""; ?>" />
							HIGHEST TICKET SIZE: $<input style="width:113px;" type="text" disabled="disabled" name="input[]" value="2500.00" />
						</b>
					</p>
					<p>
						OFFICERS AND OWNERS OF MERCHANT WARRANT THAT THE AVERAGE MONTHLY SALES VOLUME AND AVERAGE TICKET SIZE ARE ACCURATE AND ACKNOWLEDGE THAT ANY VARIANCE MAY RESULT IN THE DELAY OR THE WITHHOLDING OF FUNDS SETTLEMENT OR TERMINATION OF THE MERCHANT AGREEMENT.
					</p>
					<p><b>IMPORTANT NOTICE:</b> All information contained in the attached Merchant Application was completed by owners and/or authorized officers of Merchant. No spaces were left incomplete. N/A or None is to be filled in any space where applicable. MERCHANT accepts all contractual obligations of this Agreement.</p>
					<p><b>MERCHANT ACKNOWLEDGES HAVING READ AND RECEIVED A COPY OF THIS AGREEMENT, AND THAT IT SHALL NOT BE EFFECTIVE UNTIL APPROVED BY BANK AND EMS. THIS IS AN AUTOMATICALLY RENEWABLE 24 MONTH MERCHANT CONTRACT. CANCELLATION DURING THE TERM WILL RESULT IN A $595 EARLY TERMINATION FEE. MERCHANT AGREES TO BE PCI COMPLIANT WITHIN 90 DAYS AFTER SIGNING THIS AGREEMENT. FAILURE TO DO SO WILL RESULT IN AN ADDITIONAL $50.00 MONTHLY FEE UNTIL MERCHANT BECOMES COMPLIANT.</b></p>
				</div>
			</td>
		</tr>
	</table>
	<table id="tbl4">
		<tr class="row2">
			<td class="black_header">
				<h3 style="margin-left:10px;">AGREED AND ACCEPTED</h3>
			</td>
			<td class="black_header">
				<h3 style="margin-left:10px;">CORPORATE RESOLUTION</h3>
			</td>
		</tr>
		<tr class="row2">
			<td colspan="2">
				<p style="font-size:10px;">IMPORTANT INFORMATION ABOUT PROCEDURES FOR OPENING A NEW ACCOUNT. To help the government fight the funding of terrorism and money laundering activities, Federal law requires all financial institutions to obtain, verify, and record information that identifies each person who opens an account. What this means for you. When you open an account, we will ask for your name, address, date of birth, and other information that will allow us to identify you. We may also ask to see your driver's license or other identifying documents.</p>
			</td>
		</tr>
		<tr class="row2">
			<td style="width:500px;">
				<input type="text" name="input4_4" class="indispensable" value="<?php echo (empty($custom_inputs['input4_4']) && isset($_COOKIE[$data_id.'_input4_1'])) ?$_COOKIE[$data_id.'_input4_1']: $custom_inputs['input4_4']; ?>" />
				<hr style="width:450px;margin:1px 0 0 0;"  />
				<p>Print Merchant Name</p><br/>
				<p>(1) Sign X<input style="margin-left:5px;width:100px;" class="indispensable signature" name="input[]" type="text" value="<?php echo isset($inp_txt[$i]) ? $inp_txt[$i++] : ""; ?>" /><input style="margin-left:67px;width:50px;" class="indispensable" name="input5_2" type="text" value="<?php echo (empty($custom_inputs['input5_2']) && isset($_COOKIE[$data_id.'_input5'])) ?$_COOKIE[$data_id.'_input5']: $custom_inputs['input5_2']; ?>" /><input style="margin-left:60px;width:50px;" class="indispensable" name="input6" type="text" value="<?php echo (empty($custom_inputs['input6']) && isset($_COOKIE[$data_id.'_input6'])) ?$_COOKIE[$data_id.'_input6']: $custom_inputs['input6']; ?>" /> </p>
				<hr style="width:450px;margin:0;" />
				<span style="margin-left:250px;">Title</span><span style="margin-left:82px;">Date</span>
				<br/><br/>
				<p>(2) Sign X </p>
				<hr style="width:450px;margin:0;" />
				<span style="margin-left:250px;">Title</span><span style="margin-left:82px;">Date</span>
			</td>
			<td style="width:500px;">
				<p>The officers identified in #1 and #2 have the authority to execute the Merchant Agreement with BANK and EMS on behalf of the corporation.</p>
				<br/>
				<p>(1) Sign X<input style="margin-left:5px;width:100px;" class="indispensable signature" name="input[]" type="text" value="<?php echo isset($inp_txt[$i]) ? $inp_txt[$i++] : ""; ?>" /><input style="margin-left:67px;width:50px;" class="indispensable" name="input5_3" type="text" value="<?php echo (empty($custom_inputs['input5_3']) && isset($_COOKIE[$data_id.'_input5'])) ?$_COOKIE[$data_id.'_input5']: $custom_inputs['input5_3']; ?>" /><input style="margin-left:60px;width:50px;" class="indispensable" name="input6_2" type="text" value="<?php echo (empty($custom_inputs['input6_2']) && isset($_COOKIE[$data_id.'_input6'])) ?$_COOKIE[$data_id.'_input6']: $custom_inputs['input6_2']; ?>" /></p>
				<hr style="width:450px;margin:0;" />
				<span style="margin-left:250px;">Title</span><span style="margin-left:82px;">Date</span>
				<p>By my signature, I verify that I already own a manual imprinter and will provide imprinted sales drafts whenever necessary.</p>
				<br/>
				<p>(1) Sign X</p>
				<hr style="width:450px;margin:0;" />
				<span style="margin-left:250px;">Title</span><span style="margin-left:82px;">Date</span>
			</td>
		</tr>
		<tr class="row2">
			<td colspan="2" class="black_header">
				<h3 style="margin-left:10px;">PERSONAL GUARANTY FROM OWNER/OFFICER</h3>
			</td>

		</tr>
		<tr class="row2">
			<td colspan="2">
				<p style="font-size:10px;">The undersigned (jointly and severally if more than one) in consideration of BANK and EMS entering into this Merchant Agreement ("Agreement") with the above named Merchant, hereby absolutely and unconditionally guarantee the full and prompt payment of any and all amounts owed to BANK and EMS and the performance of all MERCHANT'S obligations under this Agreement as may be subsequently amended from time to time, whether before or after termination or expiration of the Agreement. The undersigned guarantor(s) agree(s) to pay or perform upon demand and waive any notice, presentment, demand, collection from others or any delay in enforcement. This Guaranty includes (i) any amount returned by the BANK and EMS after receipt due to any bankruptcy or similar law and (ii) BANK's and EMS's expenses including attorney fees and costs. Any sums owing by the MERCHANT to the undersigned shall be subordinated to sums owed to BANK. This Guaranty is continuing, binding upon heirs and successors and may not be changed except in writing and signed by BANK and EMS. Each of the undersigned hereby authorize BANK and EMS to and obtain from any credit reporting agency financial or credit information pertaining to the undersigned and give BANK and EMS continuing authority to obtain such information in connection with the maintenance, renewal or extension of the Agreement.</p>
			</td>
		</tr>
		<tr class="row2">
			<td style="width:500px;">
				<br/>
				<p>(1) Sign X <input style="margin-left:5px;width:220px;" class="indispensable signature" name="input[]" type="text" value="<?php echo isset($inp_txt[$i]) ? $inp_txt[$i++] : ""; ?>" /><input style="margin-left:67px;width:50px;" class="indispensable" name="input6_3" type="text" value="<?php echo (empty($custom_inputs['input6_3']) && isset($_COOKIE[$data_id.'_input6'])) ?$_COOKIE[$data_id.'_input6']: $custom_inputs['input6_3']; ?>" /></p>
				<hr style="width:450px;margin:0;" />
				<span style="margin-left:150px;">NO TITLE PERMITTED</span><span style="margin-left:82px;">Date</span>
				<br/><br/>
				<p>(2) Sign X</p>
				<hr style="width:450px;margin:0;" />
				<span style="margin-left:150px;">NO TITLE PERMITTED</span><span style="margin-left:82px;">Date</span>
			</td>
			<td style="width:500px;">
				<br/><br/>
				<hr style="width:450px;margin:0;" />
				<span style="margin-left:180px;">PLEASE PRINT NAME</span>

				<br/><br/><br/>
				<hr style="width:450px;margin:0;" />
				<span style="margin-left:180px;">PLEASE PRINT NAME</span>
			</td>
		</tr>
		<tr class="row2">
			<td colspan="2">
				<br/>
				<div style="height:10px;background-color:black;">

				</div>
			</td>
		</tr>
		<tr class="row2">
			<td style="width:500px;">
				<p style=""><b>EMS AND BANK USE ONLY</b></p>
				<p>EMS Approval</p>
				<hr style="width:374px;margin: 0 0 0 77px;" />
				<span style="margin-left:100px;">Signature</span><span style="margin-left:100px;">Title</span><span style="margin-left:82px;">Date</span>
				<br/><br/>
				<p>Bank Approval</p>
				<hr style="width:374px;margin: 0 0 0 77px;" />
				<span style="margin-left:100px;">Signature</span><span style="margin-left:100px;">Title</span><span style="margin-left:82px;">Date</span>
				<br/><br/>
				<span>Bank Name</span><input type="text" name="input[]" value="<?php echo isset($inp_txt[$i]) ? $inp_txt[$i++] : ""; ?>" />
				<span>Merchant Setup</span><input style="width:110px;margin-left: 10px;" type="text" name="input[]" value="<?php echo isset($inp_txt[$i]) ? $inp_txt[$i++] : ""; ?>"/><span style="font-size:8px;">(Initials)</span>
				<p style="margin:20px 0 0 338px;"><b>WHITE COPY</b> - EMS</p>
			</td>
			<td style="width:500px;">
				<br/>
				<p>Declined By</p>
				<hr style="width:374px;margin: 0 0 0 77px;" />
				<span style="margin-left:100px;">Signature</span><span style="margin-left:100px;">Title</span><span style="margin-left:82px;">Date</span>
				<br/><br/>
				<span>TERMINAL ID NUMBER</span><input type="text" style="width:300px;margin-left:10px;" name="input[]" value="<?php echo isset($inp_txt[$i]) ? $inp_txt[$i++] : ""; ?>"/>
				<br/><br/>
				<span>MERCHANT NUMBER</span><input type="text" style="width:300px;margin-left:20px;" name="input[]" value="<?php echo isset($inp_txt[$i]) ? $inp_txt[$i++] : ""; ?>"/>
				<p style="margin:24px 0 0 0px;"><b>YELLOW COPY</b> - MERCHANT</p>
			</td>
		</tr>
	</table>
	<table id="tbl5">
		<tr class="row2">
			<td class="p_align">
                <p>
                    <label><b>MERCHANT NAME</b></label><input type="text" name="merchantName" value="<?php echo isset($inp_com["merchantName"]) ? $inp_com["merchantName"] : ""; ?>" style="margin-left: 10px; width: 500px;"/>
                </p>
				<p>
					THIS MERCHANT AGREEMENT (the "Agreement"), which includes the attached Merchant Application (the "Application"), is made and entered into by Merrick Bank, a national banking association, Francis David Corporation, an Ohio corporation doing business as Electronic Merchant Systems ("EMS"), and the undersigned Merchant ("Merchant").
					WHEREAS, Bank is engaged in the business of providing settlement services to Merchants that accept a valid credit card or valid off-line debit card (hereinafter, each a "Card") of Visa, U.S.A., Inc. ("Visa"), MasterCard International, Inc. ("MasterCard") or other credit card associations (hereinafter, each an "Association", and, collectively, the "Associations") for payment for goods and/or services sold, rented or rendered by Merchant; and
					WHEREAS, EMS is registered with Visa as an Independent Sales Organization and with MasterCard as a Member Service Provider, and has agreed with the Bank to provide credit card processing, authorization and related services for Merchants that use Bank's settlement services for Card transactions (individually, a "Transaction", and, collectively, "Transactions"); and
					WHEREAS, Merchant desires to use the services of Bank and EMS to authorize, process and settle Transactions undertaken by any authorized user of a Card (collectively, the "Services") on the terms and conditions hereinafter set forth.
					NOW, THEREFORE, in consideration of the foregoing and of the representations, covenants and agreements set forth in this Agreement, and intending to be legally bound by the terms of this Agreement, the parties hereto do hereby agree as follows:
				</p>
				<p>
					<b>1. Honoring/Acceptance of Cards.</b> Merchant may not accept a Card for an unlawful Internet gambling transaction. Merchnt will pay all Card Association fines, fees, penalties and all other assessments or indebtedness levied by Card Associations and/or regulatory agencies to Bank which are attributable, at the Bank's discretion, to Merchant's transaction processing or business. Merchant agrees to honor and accept, without discrimination, all valid Cards when properly presented as payment by a cardholder or authorized user and upon obtaining authorization for each Transaction in advance from the authorization center in accordance with the terms and conditions of this Agreement. Merchant will submit all authorized Transactions for its business exclusively to Bank and EMS. Merchant agrees to fully comply with and abide by all rules, regulations, procedures and requirements imposed or adopted by Visa, MasterCard or other Association as the same may be amended from time to time (the "Rules"). Merchant acknowledges that the following Rules are requirements that are strictly enforced by Visa and MasterCard and agrees, without limitation thereto, to fully comply with and abide by the following requirements: (a) to adequately display the Visa or MasterCard service marks, and, if applicable, on promotional materials to inform the public which Cards are to be honored at Merchant's place of business; (b) not establish or implement procedures that discourage, favor, or discriminate against the use of any particular Card; (c) not establish minimum or maximum transaction amounts as a condition for accepting Cards; (d) not impose any surcharge or fee for accepting a Card (except as permitted and within limitations of certain debit card networks) or establish any special conditions for accepting a Card; (e) unless permitted under the Rules, not require a cardholder to provide any personal information, such as a home or business address or telephone number, or a driver's license or other proof of identification as a condition of honoring a Card, unless instructed by the authorization center (with exceptions for a mail/telephone order or delivery required transaction and zip code for a card-present key-entered transaction in order to obtain address verification); (f) any taxes that Merchant collects (and any other similar handling fees) must be included in the total transaction amount and not collected separately in cash nor processed as an additional transaction. Merchant is responsible for the payment of all taxes applicable to Transactions; (g) Merchant will not accept a Card for an unlawful Internet gambling transaction. Merchant agrees not to indicate that Visa or MasterCard or any other Association endorses its goods or services. Merchant understands and agrees that it shall have no right to use the proprietary name and/or symbol of Bank, Visa or MasterCard unless the materials containing such are provided to Merchant by Bank and/or are approved in advance by Bank, and, in either event, only while this Agreement is in effect or until Merchant is notified by Bank, Visa or MasterCard to cease usage.
				</p>
				<p>
                    <b>2. Authorization.</b  > Approval by, or on behalf of, a cardholder's bank or the bank that issued the Card (hereinafter, "Authorization") is required on all Transactions. Merchant understands and acknowledges that an Authorization only confirms the availability of the cardholder's credit at the time of the Authorization; it does not warrant that the person presenting the Card is the rightful cardholder, nor is it an unconditional promise, guarantee or representation by Bank or EMS that a Transaction is or will be deemed valid and not subject to dispute, debit or Chargeback (as hereinafter defined). Merchant acknowledges and understands that its floor limit shall be Zero and that all Transactions must be authorized in advance through the authorization center. Merchant shall request Authorization for the exact amount of the Transaction on the date the Transaction takes place by swiping the Card through the terminal or keying the account number, expiration date, amount and address verification ("AVS") information into the terminal. If the electronic Card terminal is not functioning, Merchant may obtain Authorization by telephone, calling the voice authorization phone number provided by Bank or EMS. Merchant must provide the voice authorization center the Card account number, expiration date and the amount of the sale. In order to process any approved voice authorization, when the electronic credit card terminal is functioning, the transaction must be key entered utilizing the "force", "offline", or "post authorization" function. Merchant agrees that in connection with the acceptance of Cards that (in addition to, and not in lieu of, other applicable procedures and Rules) it will comply with the following procedures and Rules: (a) use due diligence to verify that a Cardholder is authorized to use the Card presented and that at the point of sale (i) carefully examine the signature on every Card presented and carefully compare the signature on the Card to the sales record, (ii) check the date on which the Card becomes valid and the date on which the Card expires. Merchant agrees that it shall not accept any Card that is not yet valid or has expired, and must verify that such Card is not stolen, fraudulent or counterfeit; (b) for Card present Transactions when the signature panel for a Visa Card is not signed Merchant shall in addition to requesting an Authorization (i) review positive identification bearing the Cardholder's signature to validate the cardholder's identity, (ii) indicate the positive identification, including any serial number and expiration date, on the Transaction receipt, and (iii) require the cardholder to sign the signature panel of the Card prior to completing the Transaction; (c) when the signature panel for a MasterCard Card is not signed and the Cardholder refuses to sign the Card, Merchant shall not accept it for a Transaction. If the cardholder is willing to sign the Card in the presence of Merchant, Merchant shall request two pieces of valid and current identification; and (d) for Visa and MasterCard, a signature panel bearing the words "See I.D." or equivalent language shall be deemed to be blank. In either case, if such identification is uncertain, or if Merchant otherwise questions or has suspicions regarding the validity of the Card, Merchant shall contact Bank's designated authorization center for instructions. If Authorization is denied, the Transaction shall not be completed and Merchant shall follow instructions from the authorization center, including recovery of Cards by reasonable and peaceful means. Merchant shall retain or retrieve Cards, as required by the Rules, which are expired or for which reasonable grounds exist to believe that such Cards are counterfeit, fraudulent or stolen.
				</p>
				<p>
					3. Sales Drafts. Merchant agrees to use a Point of Sale ("POS") device, computer, telephone and related equipment approved by Bank and EMS for transmission of all Transaction data and to record each Transaction by "swiping" the Card through the POS device whenever a Card is present, or if a Card cannot be electronically read, to enter the Card number and expiration date into the POS device manually. Merchant shall prepare a sales draft in legible form for each Transaction. All items, goods and services purchased in a single transaction shall be included in the total amount on a single sales draft. Merchant shall legibly type, print or imprint the following information on each sales draft: (a) the cardholder's name or name of authorized user; (b) the cardholder's account number and expiration date; (c) Merchant's correct name and address of business; (d) the date of the Transaction; (e) the total cash price of the sale (including all applicable state, federal or local surcharges and taxes); (f) the amount to be charged if a partial payment is made in cash or by check, or the amount to be charged if a partial payment is made as a deposit or as the balance owing after a deposit has been accepted; (g) a brief description of the goods or services; (h) the words "deposit" or "balance" if full payment is to be made in this manner at different times on different sales drafts; (i) the authorization approval code from the authorization center: and (j) for telephone orders transactions, the designation "TO", for mail order transactions, the designation "MO", for preauthorized transactions, the designation "PO", and for recurring transactions, the phrase "Recurring Transaction" in each instance shall be typed or printed on the signature line. Merchant will take reasonable steps to verify card information in accordance with the Rules for each Transaction. Merchant warrants the cardholder's identity whether or not Authorization is received and whether or not Card is present. Merchant shall deliver to the cardholder a true and completed copy of the sales draft. Failure to comply with the above requirements will, in addition to other penalties, subject Merchant to immediate termination, indemnification of Bank and EMS by Merchant under Section 17 and the establishment of a Reserve Account under Section 15 hereof.
				</p>
				<p>
				4. Mail, Telephone, E-Commerce (Internet), Recurring and Pre-Authorized Transactions. Bank and EMS discourage accepting mail or telephone orders and other Transactions in which the Card is not presented by the cardholder in person. Merchant understands that mail, telephone and e-commerce (internet) Transactions have substantially higher risk of Chargeback and cardholder dispute than Card "present' Transactions, as Merchant will not have an imprinted or magnetically "swiped" Transaction with the cardholder's signature on the sales draft. Merchant understands that it may engage in mail, telephone and e-commerce Transactions only if requested in the Application and only for the approved percentage of Merchant's total monthly sales volume limit reflected on the Application, or as may otherwise have been approved in writing by Bank and EMS. If Merchant exceeds the approved percentages disclosed on the Application, payment for said Transactions may be withheld by Bank and EMS pending further review. Bank and EMS may make payment of these Transactions at its sole discretion. Bank and EMS reserve the right to establish a Reserve Account pursuant to Section 15 below to fund Chargebacks that may arise from said Transactions. Merchant acknowledges that failure to disclose true and accurate percentages as part of the Application may result in the establishment of a Reserve Account, increased discount rate or fees and transaction fees, or the termination of this Agreement.
					Mail, Telephone, Recurring Transactions, Pre-authorized Orders and E-Commerce (Internet) Transactions. Merchant acknowledges that all mail order Transactions, telephone order Transactions, periodic charges for recurring goods or services to be provided by Merchant ("Recurring Transactions"), pre-authorized order Transactions, and e-commerce (internet) Transactions are difficult to defend against Chargeback and agrees that it shall take reasonable precautions to protect against Chargebacks, including, but not limited to the following: (i) delivering merchandise only to the cardholder's billing address where the issuing bank sends the cardholder's billing statement, (ii) using a delivery service that maintains shipping logs and requires signatures by a person receiving merchandise, (iii) using AVS and not processing sales unless all information matches the AVS, (iv) obtaining CVV2/CVC2 verification from the issuing bank, (v) obtaining the expiration date of the Card, and (vi) on the sales draft, clearly print the cardholder's account number, effective and expiration dates, date of Transaction, description of the goods and services, amount of the Transaction (including shipping, handling, insurance, etc.), cardholder's name, billing address and shipping address, Authorization code, and Merchant's name and address.
					Recurring Transaction and Pre-authorized Order Regulations. If Merchant processes Recurring Transactions and charges a cardholder's account periodically for recurring products or services (e.g., monthly insurance premiums, yearly subscriptions, annual membership fees, etc.), Merchant must, in addition to other applicable procedures and the Rules, comply with the following: (i) have the cardholder complete and deliver to Merchant a written request and consent for such products or services to be charged to cardholder's account. At a minimum, the written request must specify the transaction amounts, the frequency of recurring charges and the duration of time for which the cardholder's consent or permission is granted and be provided promptly in response to a cardholder's request for a copy; (ii) if the Recurring Transaction is renewed, the cardholder must complete and deliver to Merchant a subsequent written request for the continuation of such products or services to be charged to the cardholder's account; (iii) Merchant may not complete a Recurring Transaction after receiving a cancellation notice from the cardholder or issuing bank or after a request for Authorization has been denied; (iv) Merchant must obtain an Authorization for each Transaction and type or print legibly on the signature line of the sales draft for Recurring Transactions the words "Recurring Transaction" (and "PO" for MasterCard Transaction) in lieu of the cardholder's signature and must provide both an invoice number and the appropriate "Recurring Transaction" indicator must be included in each Authorization request; (v) Merchant must perform an AVS inquiry for at least the first Transaction and then annually thereafter, if applicable. Merchant understands that penalties can be assessed by the Associations for failure to use the Recurring Payment Indicator; (vi) a Recurring Transaction or Pre-authorized order may not include partial payments for products or services purchased in a single Transaction; and (vii) no finance charge may be imposed in connection with a Recurring Transaction or Pre-authorized order. Pre-authorized Transactions may be submitted if Merchant advises the cardholder that it will be immediately billing his or her Card at the time of the transaction for prepayment of services or for full prepayment of custom-ordered merchandise to be manufactured to the cardholder's specifications. In any case, all Transactions with an Authorization date more than thirty (30) days prior to shipping date or date services are rendered are subject to a greater risk of Chargeback.
					E-Commerce (Internet). If Merchant is an e-commerce merchant and accepts orders via the internet, Merchant agrees that it must, in addition to other applicable procedures and the Rules, comply with the following: (i) post its privacy and security policies on its websites, where such policies shall be clearly marked for consumers to see and review; (ii) include on its website all the following information in a prominent manner: (1) complete description of the products or services offered, (2) returned merchandise and refund policy, (3) method for the cardholder to acknowledge his acceptance of the terms and conditions for returned merchandise or for the refund policy; this acknowledgment should be in a format that complies with Association guidelines for proper disclosure, (4) customer service contact, including e-mail address and/or telephone number; (5) Transaction currency (U.S. dollars, unless permission is otherwise received from Bank and EMS), (6) any applicable export or legal restrictions, (7) delivery policy, (8) consumer data privacy policy, and (9) a description of the Transaction security used on Merchant's website. Merchant acknowledges that the Electronic Commerce Indicator must be used to identify e-commerce Transactions in the Authorization request and clearing record. Penalties may be assessed for failure to use the correct Electronic Commerce Indicator.
				</p>
				<p>
					5. Retention of Records. Bank and/or EMS may examine and verify at reasonable times all records of Merchant pertaining to all Transactions processed hereunder. Merchant will be responsible for the retrieval of all sales drafts and receipts and credit receipts requested by Bank or EMS within the time limits established by the Rules. Merchant will retain originals or copies of sales drafts and receipts and credit receipts for at least three (3) years from the processing date of the Transaction. Merchant agrees to deliver the paper copy or facsimile of any such sales drafts and credit receipts in its files to Bank or EMS, or to such person as Bank or EMS may designate, within such period after request therefor as is required by law or by the Rules. Such requested copies must be legible. Merchant will be responsible for all liabilities arising from any failure to provide an acceptable copy of any sales drafts as required by law or the Rules. Prior to discarding any sales drafts or other records of Transactions, Merchant will destroy in a manner rendering data unreadable, all material containing cardholder account numbers, Card imprints, and carbons. Merchant shall not under any circumstances retain cardholder information including cardholder name, account number, expiration dates, billing addresses, etc. in a database that can be accessed via a web-based application. Merchant shall indemnify and hold Bank and EMS harmless from all judgments, losses, costs and expenses, including reasonable attorneys' fees, incurred by Bank or EMS and arising out of any claim by cardholders whose security has been breached due to violation of Merchant of this Section. Merchant acknowledges that EMS may pass on research fees of up to $75 per hour resulting from research of archived records that are the responsibility of the Merchant. Merchant further acknowledges that it is responsible for examining its monthly Merchant Statement for billing accuracy. EMS reserves the right to limit billing error corrections and refunds to those occurring within the last ninety (90) days. Merchant further agrees that Bank or EMS or their representatives may, during normal business hours, inspect, audit, and make copies of Merchant's books, accounts, records and files pertaining to any transactions, refunds or adjustments thereon.
				</p>
				<p>
					6. Settlement. Merchant understands and agrees to balance and settle its POS terminal(s) daily and to electronically submit sales no later than the day following the date of Authorization. Transactions submitted for settlement more than one day after the date of Authorization may be refused, become subject to Chargeback or assessed additional fees by Bank and EMS. Transactions charged to a Card issued by a foreign (non U.S.A.) issuer or a commercial card issued for business purposes may be assessed additional fees. Merchant acknowledges that all transactions between Merchant, Bank and EMS under this Agreement shall be treated as a single transaction and that all settlements are provisional subject to the cardholder's rights under the Rules for disputing charges against the cardholder's account. In submitting transactions to Bank and EMS, Merchant endorses and assigns to Bank and EMS all right, title and interest to such items with rights of endorsement. Bank and EMS have the right to receive payment on all Transactions acquired and Merchant will not attempt to collect any such Transactions. If any payment is received, Merchant will hold it in trust and promptly deliver it to Bank or EMS.
				</p>
				<p>
					7. Payment. Merchant shall at all times maintain a commercial checking account with Bank or with another financial institution of Merchant's choice that belongs to the Automated Clearing House ("ACH") network and can accept ACH transactions and that Bank and EMS will use to debit and/or credit funds on a daily or monthly basis. EMS and Bank will debit Merchant's Designated Deposit Account ("DDA") daily for the Discount Fees. Merchant agrees to cooperate with Bank and EMS to help resolve any problems in crediting/debiting Merchant's DDA. Merchant agrees to be bound by the terms of the operating rules of the National Automated Clearing House Association as in effect from time to time. Merchant hereby authorizes EMS and Bank to access information from the DDA and to initiate credit and/or debit entries and adjustments to Merchant's DDA by bank wire or ACH transfer process and/or through direct instructions to the financial institution where Merchant's DDA is maintained for amounts due under this Agreement and under any agreements with Bank or its affiliates for any related services, as well as for any credit entries in error. Merchant hereby authorizes the financial institution where Merchant's DDA is maintained at to effect all such debits and credits to the Merchant DDA. This authorization is without respect to the source of any funds in the DDA, is irrevocable and is coupled with an interest, and shall remain in full force and effect until Bank and/or EMS have given written notice to the financial institution where Merchant's DDA is maintained that all monies due under this Agreement and under any other agreements with Bank or its affiliates for any related services have been paid in full. All settlements for Visa and MasterCard Card Transactions will be net of credits/ refunds, adjustments, applicable Discount Fees when due, Transaction Fees, Chargebacks, reserves, lease payments, rental fees, Minimum Discount Fees, or other adjustments, charges and any other amounts then due from Merchant. All credits to Merchant's DDA or other payments to Merchant are provisional and are subject to, among other things, final audit by Bank and/or EMS, Chargebacks (including Bank and/or EMS related losses) fees, assessments, and fines imposed by the Associations. Merchant agrees that Bank and/or EMS may debit or credit Merchant's DDA for any deficiencies, overages, fees, fines, charges, and pending Chargebacks, or may deduct such amounts from settlement funds due to Merchant. Merchant hereby also agrees and authorizes Bank and/or EMS at its/their sole discretion, to debit any other banking account maintained by Merchant for any and all such amounts. Alternatively, Bank and/or EMS may elect to invoice Merchant for any such amounts, net due 30 days after the invoice date or on such earlier date as may be specified. Bank and/or EMS cannot guarantee the timeliness with which any ACH payment may be credited by Merchant's bank. Merchant understands that, due to the nature of the ACH and the electronic networks utilized for the movement of funds and the fact that not all banks belong to the ACH Network, payment to Merchant can be delayed. Bank and EMS will not be liable for any delays in receipt of funds or errors in debit and credit entries caused by third parties, by Bank and/or EMS, including but not limited to any Association or Merchant's financial institution. Merchant acknowledges that the funds due for Visa and MasterCard Transactions will generally be processed and transferred to the Merchant's DDA within two (2) business days from the time a batch is closed. Bank and/or EMS reserve the right to divert and hold all funds when Bank and/or EMS is investigating the breach of any warranty, covenant, representation, or agreement by Merchant or has reasonable cause to believe that Merchant may have violated a provision of this Agreement, the Rules and/or is engaged in illegal, fraudulent or suspicious activity. In the event that a payment is rejected by Merchant's bank or fails to arrive within five (5) business days after Bank's attempted ACH payment, Bank may periodically wire transfer any funds due Merchant until the ACH problem is resolved, and all such wire transfers and resolution of all issues shall be solely at the Merchant's expense. If Merchant receives settlement funds by wire transfer, Bank and/or EMS may charge a wire transfer fee per wire, which fee is not subject to refund. Not all fees will be debited on a daily basis, but may be subject to a month end debit to the DDA or other available funds.
				</p>
				<p>
					8. Visa Cardholder Information Security Program/MasterCard Site Data Protection Program, Non-Disclosure, Retention, and Storage of Cardholder and Transaction Information Requirements. Merchant understands that it must comply with the Rules, including without limitation, those relating to cardholder information security issues, non-disclosure of cardholder information and Transaction documents, retention and storage of cardholder and Transaction information and other security procedures adopted by the Associations. Merchant hereby confirms its agreement to abide by and fully comply with such Rules, including without limitation the Rules and procedures described below:
					Visa Cardholder Information Security Program and MasterCard Site Data Protection Program. Visa and MasterCard have implemented programs to protect cardholder data. The Visa Cardholder Information Security Program ("CISP") and MasterCard Site Data Protection Program ("SDP") apply to Merchant if Merchant processes or stores cardholder data as a result of internet or mail/telephone acceptance of Visa or MasterCard Card account information. A copy of the complete Visa Cardholder Information Security Standards manual and a Self-Assessment Worksheet can be obtained online at www.visa.com/cisp or from EMS's customer service department, and a copy of the SDP provisions can be obtained from EMS's customer service department. Visa and MasterCard may impose restrictions, fines, or prohibit Merchant from participating in Visa or MasterCard programs if it is determined that Merchant is non-compliant. Merchant may be required to comply with an audit to verify compliance with security procedures. The following is a highlight of the current CISP and SDP program requirements, all of which Merchant may be required to comply with, if applicable to Merchant: (i) install and maintain a working network firewall to protect data accessible via the internet; (ii) keep security patches up-to-date; (iii) encrypt stored data; (iv) encrypt data sent across networks; (v) use and regularly update anti-virus software; (vi) restrict access to data by business "need to know"; (vii) assign a unique ID to each person with computer access to data; (viii) don't use vendor-supplied defaults for system passwords and other security parameters; (ix) track access to data by unique ID; (x) maintain a policy that addresses information security for employees and contractors; and (xi) restrict physical access to cardholder information.
					Transaction Information. Merchant acknowledges that the sale or disclosure of databases containing cardholder account numbers, personal information, or other Transaction information to third parties is strictly prohibited by the Rules. Unless Merchant obtains consents from Bank and EMS, and each applicable Association, issuing bank and cardholder, Merchant must not use, disclose, sell or disseminate any cardholder information obtained in connection with a Transaction (including without limitation, the names, addresses and Card account numbers of cardholders, copies of imprinted sales drafts and/or credit records, mailing lists, tapes or other media obtained in connection with a sales draft and/or credit record) except for purposes of authorizing, completing and settling Transactions and resolving any Chargebacks, retrieval requests or similar issues involving Transactions, other than pursuant to a court or governmental agency request, subpoena or order. Merchant shall use proper controls for, limit access to, and render unreadable prior to discarding all records containing cardholder account numbers and Card imprints. Merchant may not retain or store magnetic stripe data after a Transaction has been authorized. If Merchant stores any electronically captured signature of a cardholder, Merchant may not reproduce such signature except upon the specific request of Bank or EMS. Merchant shall store all media containing cardholder names, cardholder account information, and other personal information, as well as Card imprints (such as sales drafts and credit records, auto rental agreements, and carbons) in an area limited to selected personnel and, prior to discarding any such information, destroy it in a manner that renders the data unreadable. Merchant further warrants and agrees that in the event of its failure, including bankruptcy, insolvency, or other suspension of business operations, it will not sell, transfer or disclose any materials that contain cardholder account numbers, personal information, or Transaction information to third parties, and shall return the information to Bank or EMS and provide acceptable proof of destruction to Bank and EMS.
				</p>
				<p>
					9. Term. The term of this Agreement shall be eighteen (18) months commencing on the acceptance of the Application and this Agreement by Bank and EMS and the issuance of a merchant account identification number to Merchant identifying Merchant for accounting, billing, customer service and related purposes in connection with the Services. Thereafter, the Term shall automatically renew for additional consecutive eighteen (18) month terms, unless written notice of termination (to be effective upon the expiration of the then current term) is provided by Merchant to Bank and EMS or by Bank and EMS to Merchant at least ninety (90) days prior to the then existing term, unless earlier terminated in accordance with the provisions of this Agreement.
				</p>
				<p>
					10. Termination and Events of Default. Bank and/or EMS, in addition to any rights of immediate termination without notice as may be contained elsewhere in this Agreement, may terminate this Agreement, and at Bank's and/or EMS's discretion, any merchant processing agreement(s) of any other business that is commonly owned or controlled by Merchant for any reason or cause (or for no reason) whatsoever upon ten (10) business days prior written notice to Merchant. Such termination shall become effective on the later of ten (10) business days from the date such notice is given in the manner prescribed for notices herein or the date specified in such notice; provided, however, that in the event of termination due to breach by Merchant of any of the terms and conditions of this Agreement, such termination shall become effective immediately upon the giving of such notice by Bank and/or EMS, and Merchant shall pay to EMS a termination fee in the amount of $595. This Agreement may also be terminated effective upon the giving of notice at the discretion of Bank and/or EMS for reasons including but not limited to: (a) Bank and/or EMS determines that Merchant's type of business as indicated on the Application differs from the actual type of business Merchant operates; (b) Merchant moves or relocates to a new location without giving Bank and EMS at least thirty (30) days prior written notice; (c) the business as conducted by Merchant could endanger the safety and/or soundness of Bank; (d) the owner, officer or corporate entity has a separate relationship with Bank and/or EMS and such relationship has been terminated by Bank and/or EMS; (e) Merchant and/or any of its guarantors files for bankruptcy or is otherwise shown to be insolvent; (f) Merchant has Chargebacks which exceed one-half of one percent (0.50%) of the total number of Transactions completed by Merchant in any thirty (30) calendar day period; (g) Merchant owes money to Bank and/or EMS and fails to make a timely payment thereof; or (h) Merchant has breached or is in default under an End-User Agreement or similar agreement regarding the provision of web hosting, e-mail, electronic commerce, domain name and/or other internet application or system services. Upon the occurrence of an event of default or the termination of this Agreement by Bank or EMS in accordance with the terms hereof, Bank and EMS shall be entitled to pursue all rights and remedies available to it or them under this Agreement, at law or in equity. All obligations of confidentiality and of any party to this Agreement to pay funds to another shall survive any termination hereof. Nothing herein shall be construed as relieving Merchant of the obligation for the Minimum Discount Fee as provided in Schedule Of Fees for the term of this Agreement.
				</p>
				<p>
					11. Point of Sales Devices. (a) Merchant agrees to utilize and maintain, at Merchant's expense, POS terminal(s), proprietary software and related equipment approved by Bank and EMS for all Transactions, in a format and medium of transmission acceptable to Bank and EMS. Bank and EMS shall have no liability or responsibility for any negligent design or manufacture of any POS terminal or printer, or for any proprietary software or related equipment; EMS' entire liability, if any, and Merchant's exclusive remedy in all situations, shall be to perform repair services on any inoperative POS terminal or printer sold or leased by EMS. (b) Merchant shall record each transaction by "swiping" the card through the POS terminal whenever possible. Merchant acknowledges that each outlet, retail location, or business entity will have its own POS terminal and Merchant identification number. Merchant understands and agrees that sales completed at one location cannot be processed through a terminal at another location. (c) In the event of breakdown of the POS terminal or other system failure, Merchant shall immediately contact the designated Merchant Help Desk. In such case, Merchant shall imprint each sales draft with the embossed data from each card and Merchant's imprinter plate and obtain the cardholder's or authorized user's signature which must match the signature on the card. If Merchant uses an electronic printer connected to a POS terminal, Merchant must still obtain the cardholder's or authorized user's signature on the printed sales draft. As soon as a POS Terminal is operable, Merchant will enter all transactions engaged in during such period. Failure to comply with these requirements may result in a Chargeback. (d) Merchant is responsible for all telephone and communication fees and charges with respect to POS terminals.
				</p>
				<p>
					12. Returns and Credits. Merchant shall maintain a fair policy permitting refunds, exchanges, returns and adjustments in accordance with applicable law. If, with respect to any Transaction, any goods are accepted for return or any services are refunded, terminated or canceled, or any price adjustment is allowed by Merchant and except where otherwise required by law or governmental regulations, Merchant shall not under any circumstances, except as permitted by certain debit card networks, during the term of this Agreement, issue cash for return of goods or cancellations of service where goods or services were originally purchased in a Transaction. Instead, Merchant shall utilize a credit record evidencing such refund or adjustment. Merchant must process the credit record Transaction within three (3) business days of the original Transaction. Merchant shall date each credit record with the credit date and include thereon a brief description of the goods returned, services canceled or adjustment made and the amount of the credit, in sufficient detail to identify the Transaction. A completed copy of the credit record shall be delivered to the cardholder at the time of each return or cancellation of a transaction. The credit shall not exceed the amount of the original Transaction. The per item Transaction Fee will be applicable and Merchant may not receive a refund of Discount Fees paid for the original Transaction. With proper disclosure at the time of the Transaction, Merchant may: (a) refuse to accept goods in return or exchange and refuse to issue a refund to a cardholder; (b) accept returned goods in exchange for the Merchant's promise to deliver goods or services of equal value available from Merchant at no additional cost to cardholder; or (c) stipulate special circumstances agreed to by the cardholder. Proper disclosure shall be deemed to have been given only if, at the time of the Transaction, the following notice appears on all copies of the sales draft in legible letters at least one-quarter (1/4) inch high and in close proximity to the space provided for the cardholder's signature stating "NO REFUND" or "EXCHANGE ONLY" or "IN STORE CREDIT ONLY" or any special terms as applicable, or equivalent language, provided and to the extent such sales practices are permitted under applicable law.
				</p>
				<p>
					13. Warranties by Merchant. Merchant represents and warrants to Bank and EMS that Merchant has taken all necessary action and has the authority to enter into this Agreement with Bank and EMS and that the person(s) signing for or on behalf of Merchant is (are) specifically authorized and directed to do so by Merchant. This Agreement constitutes the legal, valid and binding obligation of Merchant, enforceable against Merchant in accordance with its terms. Without limiting any other representations, warranties, covenants and agreements hereunder, Merchant agrees, represents and warrants to Bank and EMS that at all times during the term of this Agreement: (a) it is engaged in the lawful business shown on the front of the Application and is duly licensed under the laws of the State, County and City in which Merchant is located to conduct such business; (b) Merchant currently accepts or desires to accept Cards for the purchase of goods and services through Transactions with cardholders; (c) it has not been terminated from the settlement of card transactions by any financial institution or determined to be in violation of the rules and regulations of Bank, EMS, MasterCard, Visa or any other Association or network; (d) it will fully comply with all federal, state, and local laws, rules and regulations, as amended from time to time, including all laws with respect to consumer protection and credit, and the Rules; (e) it will provide Bank and EMS sixty (60) days prior written notice of its intent to (i) transfer or sell 10% or more of its total stock, assets and/or liquidate, (ii) change the nature of its business, or (iii) convert all or part of its retail sales to mail or telephone orders or any other sales method in which the Card is not present and swiped through the POS terminal; (f) as to each Transaction presented to Bank and EMS for payment: (i) the sales draft is valid in form and has been completed in accordance with the Rules, all applicable laws and requirements, (ii) Merchant has delivered goods to the Cardholder or completed the service described on the sales draft in accordance with Merchant's agreement with the Cardholder, (iii) each sales draft represents a bona fide Transaction directly between the Merchant and the cardholder in the Merchant's ordinary course of business and the sales draft shows the cardholder's indebtedness for the total amount shown, (iv) the cardholder has no claim, defense, right of offset, or dispute against Merchant in connection with the purchase of the goods or service and Merchant will provide adequate services to cardholders and will honor all warranties applicable thereto, (v) Merchant has not charged cardholder any separate or additional fee(s) in connection with the Transaction other than as may be required by law. The foregoing shall not prohibit Merchant from extending discounts to customers paying by cash, check, or any other means, other than by Card, (vi) each Transaction was placed by a person who is the cardholder or authorized user of the Card; (g) all of Merchant's business locations engage in the same or substantially similar business activity as that listed on the face of this Agreement; (h) the percentage of mail and/or telephone order sales listed by Merchant is consistent at all of Merchant's locations; (i) MERCHANT offers no enticements or incentives to cardholders in connection with Transactions for the sale of Merchant products; (j) Merchant and its employees will not use their personal credit cards on the Merchant's POS Terminal; (j) Merchant uses both the name and address shown on the front of the Agreement on all sales drafts and does not use any other name; (k) shall include all items of goods and services purchased in a single Transaction in the total amount on a single sales draft or transaction record (i.e., Merchant shall not "split tickets"). Merchant shall not submit duplicates of any transaction; (l) no Transaction is between a cardholder and an entity other than Merchant; and (m) Merchant shall be responsible for its employees' and agents' actions. Merchant further warrants and agrees that it shall not, without the cardholder's consent and as permitted by law and the Rules, sell, purchase, provide, or exchange card account information in the form of sales drafts, mailing lists,
					tapes, or any other media obtained by reason of a Transaction or otherwise, to any third party other than to Merchant's agents approved by Bank and EMS for the purpose of assisting Merchant in its business, to Bank, EMS, or the respective card issuer or Association or pursuant to lawful government demand. All media containing card account numbers must be stored in an area limited to selected personnel until discarding and must be destroyed in a manner that will render the data unreadable. Merchant will not disclose and will keep confidential the terms and conditions of this Agreement. If Merchant processes and stores Card data and/or has access to that information via the internet, Merchant agrees to comply with all Rules in respect of protecting Card data and maintaining security measures. Failure to comply with the Rules or foregoing requirements, the occurrence of any significant circumstance that may create harm or loss of goodwill to any Association, and/or any security breach compromising Card data shall make the Merchant liable for any network fines, fees and/or unauthorized charges to compromised Card accounts. Merchant understands and agrees that violation of any of the foregoing warranties, representations, covenants and agreements or otherwise provided in this Agreement shall constitute an event of default and breach by Merchant of this Agreement, and may cause this Agreement to be immediately terminated, or be subject to termination, and may result in all funds being placed in a Reserve Account pursuant to Section 15 hereof.
				</p>
				<p>
					14. Chargebacks. Merchant understands and acknowledges that an authorized sale does not constitute a guarantee of payment, only available credit, and may be subject to dispute or chargeback. For purposes of this Agreement, 'Chargeback' shall mean the procedure by which a sales draft or other indicia of a Transaction (or disputed portion thereof) is denied or returned to Bank or the issuing bank after it was entered into the appropriate settlement network for payment, in accordance with the Rules, for failing to comply with the Rules or due to a cardholder dispute, the liability of which is the Merchant's responsibility. Notwithstanding any nonrecourse provisions contained herein, Merchant is responsible for any and all Chargebacks, as well as Association fines, assessments and fees related to or arising out of such Chargeback's, and will pay BANK and EMS, upon demand and without notice, the face amount of any Chargeback, and Bank and EMS shall have the right to debit the Merchant's DDA, incoming transactions, or any other funds of the Merchant in Bank's and EMS's direct or indirect control by reason of Bank's and EMS's security interest granted by Merchant under Section 20 below, for the face amount of any Chargeback including without limitation and by way of example, in any of the following circumstances: (a) a mail order or telephone order Transaction is disputed by the cardholder; (b) merchandise has been returned or service canceled by cardholder and cardholder requested a credit from Merchant and such credit was not processed by Merchant; (c) the purchase had not been authorized as required or the denial of an Authorization was disregarded; (d) a Transaction is for a type of merchandise or services other than as described in the Application and the draft was charged back by the cardholder; (e) the cardholder contends or disputes to Bank, EMS or the appropriate issuing bank that: (1) the goods or services were not received by the cardholder or their authorized user or (2) the goods or services received by cardholder or their authorized user do not conform to what was on the sales draft or (3) goods or services of value were defective or (4) the dispute reflects a claim or defense authorized against card issuers or creditors by a relevant statute or regulation; (f) Merchant fails to honor a retrieval request for an original sales draft from Bank or EMS in accordance with the requirements hereof; (g) a sales draft is illegible, incomplete or does not contain a Transaction date on the face or such dollar amount has been altered or incorrectly entered and sales draft has been charged back by the Card issuer; (h) the sales draft contains the imprinted or otherwise transcribed description of a Card other than the Card specified; (i) the transaction was generated through the use of an invalid, altered, counterfeit or expired Card; (j) no signature appears on the sales draft or sales draft does not contain the embossed legend from a Card or Merchant has failed to obtain the specific authorization from a designated Authorization Center to complete the Transaction and/or the cardholder has certified in writing, to Bank, EMS or the issuing bank that he did not make or authorize the Transaction; (k) security procedures have not been followed or where the signature on the sales draft is different from the signature appearing on the signature panel of the Card and the sales slip is charged back; (l) a Card issuer, Bank or EMS has information that Merchant fraud occurred at the time of the Transaction, whether or not such Transaction was authorized by the issuer and the cardholder neither participated in nor authorized the Transaction; (m) if with respect to any one Merchant outlet, the ratio of questionable Merchant activity to Card sales exceeds industry standards, in the sole determination of Bank or EMS. If, with respect to any one of Merchant's outlets, the amount of any Card counterfeit or fraud incidences becomes excessive, in the sole and absolute discretion of Bank or EMS, Merchant may be charged back for all Transactions, terminated immediately for cause, and Merchant's funds, including but not limited to those incoming Transactions and in Merchant's DDA and Reserve Account shall be held pursuant to the provisions of this Agreement. Merchant agrees to accept and understands that it is responsible for all Chargebacks and understands that some Chargebacks cannot be rebutted or remedied. Merchant agrees to satisfy directly with the cardholder any claim or dispute arising from a Transaction. Bank and EMS will provide Merchant with any information possessed by them that will enable Merchant to recover from others the amount of any Chargeback. Bank and EMS shall retain any discount and/or other fee related to a Chargeback. Merchant understands that Bank and EMS will assess up to $25 per Chargeback, or other charges that may be established by Bank and EMS from time to time. Furthermore, Bank and EMS may assess Merchant for any fines imposed by MasterCard and Visa plus a processing fee for such fine as may be required by Bank and EMS at their sole discretion. Disputes relating to Chargebacks shall be governed by the Rules of each Card issuing Association and as amended from time to time, including Merchant's obligation to provide required documentation. If the actual Card is 'not present', Merchant understands and acknowledges that Merchant bears one hundred percent (100%) of the risk of Chargeback under the Rules, for all Transactions and any fees resulting from any losses, claims, and costs arising from or associated with such all Transactions, including any Authorizations.
				</p>
				<p>
					15. Reserve Account. In addition to the security interest and Chargeback rights granted to Bank and EMS by Merchant, Merchant hereby authorizes Bank or EMS to establish a non-interest bearing 'Reserve Account', with or without notice to the Merchant, at any time prior to, at, or after the termination of this Agreement, when the Bank or EMS have determined that any of the following has occurred: (a) reasonable doubt exists concerning Merchant's ability to comply with this Agreement; (b) Merchant's breach of this Agreement or other applicable Rules and regulations; (c) excessive Chargebacks, customer disputes, ACH rejects, retrieval requests or the reasonable possibility of any of the foregoing occurring; (d) inability of the Merchant to fund any potential Chargebacks, post termination fees, charges or other expenses and fees payable to the Bank or EMS. The Reserve Account may be funded, supplemented or replenished by the Bank or EMS in any or all of the following methods: (i) one or more debits to Merchant's DDA; (ii) one or more deductions from payments due Merchant; or (iii) if Bank, EMS and Merchant agree, delivery of letter of credit or certificate of deposit issued by a financial institution acceptable to Bank and EMS. Merchant hereby agrees that Bank or EMS may deduct from this Reserve Account any amount owed to such party in accordance with this Agreement. Any funds in the Reserve Account may be held until the expiration of any applicable Chargeback rights in respect to purchased indebtedness under applicable Rules of the Card issuer, whose holding period may extend beyond the termination of this Agreement. Bank or EMS may fund, supplement or replenish the Reserve Account in such an amount as Bank or EMS may reasonably estimate is necessary to secure Merchant's payment obligations under this Agreement. Funds in the Reserve Account will be non-interest bearing. Without limiting the generality of the foregoing, Merchant shall, upon termination of this Agreement, maintain sufficient funds in the Reserve Account in such amount as may be reasonably required by Bank or EMS until all of the Chargeback rights of the Transactions processed preceding termination have expired. Merchant hereby agrees that any financial institution at which Merchant maintains a deposit account may rely upon an executed copy of this Agreement provided by Bank and/or EMS as Merchant's express, written instruction and authorization to permit such offset by Bank and/or EMS, and Merchant's agreement that said financial institution shall be released from any liability for any good faith compliance with the express written instruction and authorization as set forth herein to permit such offset by Bank and/or EMS. Notwithstanding any provision contained herein to the contrary, EMS may not have access, directly or indirectly, to any account for funds or funds due to Merchant and/or funds withheld from Merchant for Chargebacks, and Bank may not assign or otherwise transfer an obligation to pay or reimburse Merchant arising from or related to the performance of this Agreement to EMS.
				</p>
				<p>
					16. Fraudulent Sales, Factoring Or Laundering. Merchant shall never accept or deposit or enter into its POS terminal a fraudulent Transaction or Transaction made by any entity other than the Merchant. Should Merchant do so, Bank or EMS may immediately terminate this Agreement, have all funds placed into a Reserve Account pursuant to Section 15 above and be placed on the 'Combined Terminated Merchant File' as required by the Rules. Said action may result in Merchant's being restricted from settling Transactions with any bank in the future. Merchant hereby releases Bank and EMS and agrees to hold Bank and EMS harmless from any claims, liabilities, losses or damages arising out of or resulting from Merchant's being placed on any such restrictive list.
				</p>
				<p>
					17. Indemnification; Bank and EMS Liability. Merchant agrees to indemnify and hold Bank and EMS harmless from and against any Association fines or fees and all losses, liabilities, damages and expenses (including attorneys' fees and collection costs) resulting from any breach of any warranty, covenant or agreement or any misrepresentation by Merchant under this Agreement (including, without limitation, a violation of the Rules), or arising out of Merchant's or Merchant's employees' negligence or willful misconduct, in connection with Transactions or otherwise arising from Merchant's provision of products and services to cardholders. Bank agrees to indemnify and hold Merchant harmless from and against all losses, liabilities, damages and expenses resulting from any breach of any warranty, covenant or agreement or any misrepresentation by Bank under this Agreement or arising out of Bank's or its employees' gross negligence or willful misconduct in connection with this Agreement. EMS agrees to indemnify and hold Merchant harmless from and against all losses, liabilities, damages and expenses resulting from any breach of any warranty, covenant or agreement or any misrepresentation by EMS under this Agreement or arising out of EMS's or its employees' gross negligence or willful misconduct in connection with this Agreement. Except as expressly provided in this Agreement, Bank and EMS make no other warranties whether express, implied or statutory, in connection with this Agreement and without limiting the foregoing, Bank and EMS disclaim all warranties of merchantability and fitness for a particular purpose. Bank or EMS may utilize systems of others, including those of any Associations, in connection with its performances of the services described hereunder. Bank and EMS shall not be responsible or liable for any information provided by others or for the use of any system or equipment of Bank and EMS or others or for any circumstances beyond its control. Bank and EMS shall not be liable for lost profits, consequential, special, punitive, exemplary or incidental damages, even if Bank and EMS have been advised of the possibility of such damages. The sole and exclusive liability of Bank and EMS and remedy of Merchant hereunder (including negligence) shall be general money damages not to exceed the amount of the item subject to claim or dispute, regardless of the characterization of such action.
						NOTWITHSTANDING ANYTHING IN THIS AGREEMENT TO THE CONTRARY, IN NO EVENT SHALL BANK AND EMS, OR THEIR AFFILIATES OR ANY OF THEIR RESPECTIVE DIRECTORS, OFFICERS, EMPLOYEES, AGENTS OR SUBCONTRACTORS, BE LIABLE UNDER ANY THEORY OF TORT, CONTRACT, STRICT LIABILITY OR OTHER LEGAL THEORY, FOR LOST PROFITS, LOST REVENUES, LOST BUSINESS OPPORTUNITIES, EXEMPLARY, PUNITIVE, SPECIAL, INCIDENTAL, INDIRECT OR CONSEQUENTIAL DAMAGES, EACH OF WHICH IS HEREBY EXCLUDED BY AGREEMENT OF THE PARTIES, REGARDLESS OF WHETHER SUCH DAMAGES WERE FORESEEABLE OR WHETHER ANY PARTY OR ANY ENTITY HAS BEEN ADVISED OF THE POSSIBILITY OF SUCH DAMAGES.
				</p>
				<p>
					18. Force Majeure. The parties to this Agreement shall be released from liability hereunder for failure to perform any of the obligations herein where such failure to perform occurs by reason of any act of God, fire, flood, storm, earthquake, tidal wave, communications failure, sabotage, war, military operation, national emergency, mechanical or electronic breakdown, civil commotion or the order, requisition, request or recommendation of any governmental agency or acting governmental authority, or either party's compliance therewith, or governmental proclamation, regulation, or priority, or any other cause beyond either party's reasonable control, whether similar or dissimilar to such causes.
				</p>
				<p>
					19. Notices. Any notice, request, instruction or other document required or permitted under this Agreement shall be deemed to have been given (i) upon receipt if by personal delivery or by overnight courier service by way of a national courier or (ii) on the third day after the same shall be deposited in the United States mail, by first class registered mail, return receipt requested and postage prepaid, and addressed in either event to Merrick Bank at 10705 South Jordan Gateway, Suite 200, South Jordan, Utah 84095, EMS at 5005 Rockside Road, Suite PH100, Cleveland, Ohio 44131 or to MERCHANT at the address written on the Application or at such other address as any party may give to the others from time to time by written notice to the other parties.
				</p>
				<p>
					20. Security Interest. IN ORDER TO SECURE ALL OBLIGATIONS OF MERCHANT TO BANK AND EMS ARISING FROM THIS AGREEMENT, MERCHANT HEREBY GRANTS BANK AND EMS A CONTINUING SECURITY INTEREST IN AND TO ALL DEPOSITS, REGARDLESS OF SOURCE, TO MERCHANT'S DDA AND OTHER ACCOUNTS IN THE DIRECT OR INDIRECT CONTROL OF THE BANK OR EMS, ESTABLISHED IN MERCHANT'S NAME OR BY ANY PARTY SIGNING THE PERSONAL GUARANTY AS PART OF THIS AGREEMENT, AND TO ALL PROCEEDS OF SAID DEPOSITS. Said security interest may be set-off or otherwise exercised by BANK and EMS without notice or demand of any kind by making an immediate withdrawal from or holding said account, upon Bank's or EMS's reasonable determination that a breach of any obligation of Merchant under this Agreement has occurred. The exercise of this security interest shall be in addition to any other rights of Bank and EMS under this Agreement or applicable laws. The parties specifically acknowledge and affirm that pursuant to the Uniform Commercial Code of Ohio, Bank and EMS have a general lien and right of offset upon all funds on deposit with Bank and EMS, which shall stand as one continuing collateral security for the timely performance by Merchant of all of its obligations to Bank and EMS. Bank and EMS shall also have the right to require Merchant to furnish such other and different security as Bank or EMS shall deem appropriate in its sole discretion in order to secure Merchant's obligations under this Agreement. Merchant agrees to execute any documents or take any actions required in order to comply with and perfect any security interest under this Section, at Merchant's cost. To the extent permitted by law, Merchant irrevocably authorizes Bank and EMS to record any financing statement or other documents relating to this security interest.
				</p>
				<p>
					21. Discount Fee and Transaction Fees. Merchant agrees to pay to Bank and EMS the non-returnable fees stated in the Schedule of Fees incorporated herein and as amended from time to time with thirty (30) days' written notice. The Bank must approve, in advance, any fee to or obligation of the Merchant arising from or related to performance of this Agreement. Bank and EMS shall have the right to increase the Discount Fees, Transaction Fees, or add fees from time to time in accordance with Sections 19 or 30. EMS also may increase Discount Fees or Transaction Fees immediately without notice to Merchant, if approved by Bank in advance, if Merchant changes the nature of its business from that indicated on the Application or otherwise changes its business or goods sold or services rendered in a way that may increase EMS's costs or lead to excessive Chargebacks, or if Merchant's percentage of on and off premises, mail, telephone, and internet transaction sales varies from that disclosed in the Application. Fees become due at the time a Transaction is submitted to Bank and EMS. A 'Discount Fee' means a fee charged on the total value of a Transaction at the Discount Rate disclosed on the face of this Agreement. A 'Transaction Fee' shall mean a fee charged on each sales draft and each credit record regardless of the total stated and shall also mean a fee charged for any other Transaction which utilizes a POS device for transmission or reception of Card data or information, including but not limited to, debit card transactions, batch closing, Authorizations and any other communications using the POS terminal. Merchant acknowledges that Bank and EMS have relied upon the information contained in the Application including but not limited to the type of business in which Merchant is engaged, the product or service sold, the average transaction or ticket size and monthly volume, the amount of telephone and mail order sales, and the ratio of keyed/swiped transactions in determining whether to accept the Application and in setting the Discount Fees and Transaction Fees charged Merchant. Merchant acknowledges the Discount Fees quoted in the accompanying Application is contingent upon Merchant's closing batches at least once every business day, and further understands that in the event that batches are not closed at least daily, Bank and EMS may initiate batch closing on Merchant's behalf. In the event of a change in the parameters stated above or should special circumstances arise which shall change either temporarily or permanently the existing conditions, Merchant must notify Bank or EMS prior to those changes, so that necessary adjustments can be made. Additional fees may be assessed for processing sales or credit drafts emanating from foreign (non U.S.A.) credit cards or commercial cards issued for business purposes. Merchant will be charged additional Discount Fees (See Schedule Of Fees) and subject to increase for all Transactions which do not qualify for the lowest interchange fees. To qualify, batches must be closed daily and an Authorization obtained for every Transaction-matching the sales amount exactly (or within 15% for hotels and car rentals, 20% for restaurants, bars and night clubs). PLEASE REFER TO THE SCHEDULE OF FEES INCORPORATED INTO THIS AGREEMENT FOR THE AMOUNT OF THESE FEES. Merchant agrees to pay these fees and any increase in interchange fees. If not covered by the Schedule Of Fees, any additional fees due to a transaction not qualifying for the lowest interchange fee shall be paid by Merchant.
				</p>
				<p>
					22. Minimum Discount Fee/Access Fee. Merchant agrees that the Minimum Discount Fee to be imposed for any month, or portion thereof, shall be in accordance with the Schedule Of Fees. Merchant acknowledges that Bank and EMS shall assess a Monthly Access Fee (see Schedule Of Fees) or such other fee as may be established from time to time if approved in advance by Bank. Merchant also acknowledges that the monthly minimum Discount Fee and Access Fee apply to each Merchant identification number assigned to Merchant, and hereby agrees to pay these fees.
				</p>
				<p>
					23. Severability. If any part of this Agreement is held unenforceable or invalid or prohibited by law, said part shall be deemed stricken therefrom and this Agreement shall be read and interpreted as though said part did not exist, and shall not affect the validity or enforcement of any other provision.
				</p>
				<p>
					24. Waiver. Neither the failure nor any delay on the part of Bank or EMS to exercise any right, remedy, power or privilege hereunder shall operate as a waiver nor be construed as an agreement to modify the terms of this Agreement, nor shall any single or partial exercise of any right, remedy, power or privilege with respect to any occurrence be construed as a waiver of such right, remedy, power or privilege with respect to any other occurrence. No waiver by a party hereunder shall be effective unless it is in writing and signed by the party making such waiver, and then such waiver shall apply only to the extent specifically stated in such writing.
				</p>
				<p>
					25. Entire Agreement. This Agreement, including the Application and any other documents executed in conjunction herewith, constitutes and expresses the entire agreement and understanding between the Merchant, Bank and EMS with respect to the subject matter hereof and supersedes all prior and contemporaneous agreements and understandings, inducements, or conditions, by Bank, EMS or its sales representative, whether expressed or implied, oral or written. Neither this Agreement nor any portion or provision hereof may be changed, waived or amended orally or in any manner other than be a writing specifically identified as such and sighed by the duly authorized representatives of Bank and EMS. This Agreement is not effective and may not be modified in any respect without the express written consent of Bank.
				</p>
				<p>
					26. Assignment and Delegation. This Agreement may be assigned by Bank. EMS may not subcontract, sublicense, assign, license, franchise, or in any manner extend or transfer to any third party any right or obligation of EMS set forth herein except as may be approved by Bank and permitted under the Rules. This Agreement may not be assigned by Merchant without Bank's and EMS's prior written consents and any purported assignment without such consents shall be void. This Agreement shall be binding on the parties and their permitted heirs, successors, and assigns. Bank (and EMS, if and to the extent permitted under the Rules) reserves the right, in its sole discretion, to delegate or assign to third parties the performance of certain of Bank's (or EMS's, if applicable) servicing or settlement obligations to Merchant. The relationship of Bank, EMS and Merchant is solely that of independent parties contracting for services.
				</p>
				<p>
					27. Disputes, Governing Law, Jurisdiction, and Venue. Bank and EMS shall have the absolute right to initiate or defend any and all disputes arising from this Agreement with Merchant. This Agreement shall be governed by and constructed in accordance with the laws of the State of Ohio. In the event of a claim by Bank and/or EMS for the failure of a Merchant to pay any Chargebacks, fees, settlement costs or other amounts due hereunder, Merchant agrees that personal jurisdiction and venue of any such claim shall lie in the federal or state courts of Cuyahoga County, Ohio, and Merchant and any guarantors of Merchant's obligations and duties hereunder do each hereby waive all objections to said jurisdiction and agree to submit thereto. Each party is responsible to its own costs and expenses, except that Merchant shall be liable for all costs and expenses of Bank and EMS (including attorneys fees in connection with the enforcement of this Agreement), as a result of any breach or the collection of any sums due to Bank or EMS hereunder (including bankruptcy).
				</p>
				<p>
					28. Arbitration. Except as expressly provided in Section 27, any claim or dispute arising out of or related to this Agreement shall be finally resolved by final and binding arbitration. Whenever a party shall decide to institute arbitration proceedings, it shall give written notice to that effect to the other parties. The party giving such notice shall refrain from instituting the arbitration proceedings for a period of thirty (30) days following such notice to allow the parties to attempt to resolve the dispute between or among themselves. If the parties are still unable to resolve the dispute, the party giving notice may institute the arbitration proceeding under the rules of the American Arbitration Association ('AAA Rules'). Arbitration shall exclusively and solely be held in Cleveland, Ohio. The arbitration shall be conducted before a single arbitrator mutually chosen by the parties, but if the parties have not agreed upon a single arbitrator within fifteen (15) days after notice of the institution of the arbitration proceeding, then the arbitration shall be conducted by a panel of three (3) arbitrators. In such case, Merchant, on the one hand, and Bank and/or EMS on the other, shall within thirty (30) days after notice of the institution of the arbitration proceedings appoint one arbitrator. The presiding arbitrator shall then be appointed in accordance with AAA Rules. Decisions of the arbitrator(s) shall be final and binding on the parties. The arbitrator shall have the authority to award any remedy or relief a court of the State of Ohio could order or grant, including, without limitation, specific performance of any obligation created under this Agreement, the awarding of the issuance of an injunction or the imposition of sanctions for abuse or frustration of the arbitration process. Judgment upon the award of the arbitrator may be entered in any court of competent jurisdiction and enforced with full judicial effect thereafter. All fees and expenses of the arbitration shall be borne by the parties equally. However, each party shall bear the expense of its own counsel, experts, witnesses, and preparation and presentations. The arbitrator(s) is/are authorized to award any party such sums as shall be deemed proper for the time, expense and inconvenience of arbitration, including arbitration fees and attorney fees. Except to the extent that entry of judgment and any subsequent enforcement may require disclosure, all matters relating to the arbitration, including the award, shall be held in confidence by the parties.
				</p>
				<p>
					29. Compliance and Disclosure of Information; Patriot Act. Merchant shall provide such information and certifications as Bank and EMS may reasonably require from time to time to determine Merchant's compliance with the terms and conditions of this Agreement and the Rules. Merchant further agrees to produce and make available for inspection by Bank, EMS or its officers, agents or representatives, such books and records of Merchant as Bank or EMS may deem reasonably necessary to be adequately informed of the business practices and financial condition of Merchant, or the ability of Merchant to observe or perform its obligations to Bank and EMS pursuant to this Agreement. Merchant further agrees to provide to Bank or EMS within seven (7) days of notice such information as Bank or EMS may request including but not limited to, credit reports, personal and/or business financial statements, income tax returns, or other such information as Bank or EMS may request. Merchant grants to Bank and EMS continuing authority to conduct credit checks and background investigation and inquiries concerning Merchant and its owner(s) including, but not limited to, character and business references and the financial condition of Merchant and Merchant's owner(s). Merchant expressly authorizes Bank, EMS or its agents and representatives to provide and receive such information from any and all third parties directly, without further consent or authorization on the part of Merchant. Bank and EMS may share with others its credit, sales and other information. Merchant will not transfer, sell, or merge or liquidate its business or assets or otherwise transfer control of its business, change its ownership in any amount or respect, engage in any joint venture partnership or similar business arrangement, change its basic nature or method of business, types of products sold or engage in sales by phone or mail order without providing notice to Bank or EMS and providing Bank or EMS with the opportunity to terminate this Agreement. Merchant acknowledges that Bank has implemented a customer identification program as required under the USA Patriot Act and other similar state laws and regulations. Merchant agrees to make available to Bank and/or EMS such information as may be required by Bank in connection with its customer identification program and/or as required under the USA Patriot Act and related state laws and regulations.
				</p>
				<p>
					30. Amendments. This Agreement may be amended by Bank and EMS from time to time upon written notice of the change(s) in terms and conditions or fees. Any amendment to this Agreement shall be effective on the effective date specified in the notice or in the manner prescribed for notices herein. In the event of any amendment of the terms and conditions of this Agreement or of the fees payable to Bank or EMS hereunder, Merchant shall have the right to terminate this Agreement without the payment of the termination fee provided in Section 10 above by providing Bank and EMS written notice of such termination prior to the effective date of such amendment. No such termination shall effect any obligation of Merchant to pay any fees, charges, or other obligations incurred by Merchant under this Agreement prior to the date of termination. Submission of transactions to Bank and EMS on or after any effective date constitutes acceptance of any amendment. Any unrelated alteration or modification to the preprinted form of this Agreement has no effect and, at the Bank and EMS's discretion, may render this Agreement void.
				</p>
				<p>
					31. Survival. All representations, warranties and covenants shall survive the termination of this Agreement.
				</p>
				<p>
					32. Construction. The captions contained in this Agreement are for the convenience of the parties and shall not be construed or interpreted to limit or otherwise define the scope of this Agreement.
				</p>
				<p>
					33. Counterparts. This Agreement may be executed in one or more counterparts, each of which shall be deemed to be an original, such counterparts to constitute but one and the same instrument.
				</p>
				<p>
					34. Schedule of Fees. Incorporated herein by reference is a Schedule of Fees that contains the Discount Fee, Transaction Fees, and other terms and conditions in effect on the commencement date of this Agreement. Bank and EMS reserve the right at all times to unilaterally change all or part thereof or any other terms of this Agreement in accordance with Sections 19, 21 or 30.
				</p>
				<p>
					35. Exclusive Agent. For purposes of this Agreement and performance of the Services by EMS, (i) EMS is the exclusive agent of Bank, (ii) Bank is at all times and entirely responsible for and in control of EMS's performance hereunder, and (iii) Bank must approve, in advance, any fee to or obligation of Merchant arising from or related to performance of this Agreement.
				</p>
				<p>
					36. Default Interest Rate. Merchant agrees that all amounts due and payable by Merchant to Bank or EMS under this Agreement shall accrue interest at the rate of one and one-half percent (1.50%) per month, or the maximum interest rate permissible under law, whichever is lesser, beginning as of date due and continuing following any judgment obtained by Bank or EMS against Merchant until paid in full.
				</p>
				<p>
					37. Financial Accommodation. The acquisition, processing and settlement of Transactions is a financial accommodation and, as such, in the event Merchant becomes a debtor in bankruptcy, this Agreement cannot be assigned or enforced and Bank and EMS shall be excused from performance hereunder.
						This Agreement shall be effective only upon acceptance and signature by Bank and EMS. Any application fee paid to Bank or EMS is nonrefundable whether or not Merchant and this Agreement are accepted by Bank and EMS.

				</p>
			</td>
		</tr>
	</table>
    <table border="0" id="tbl6" cellpadding="5">
        <tr>
            <td width="450" height="90">
                <input type="text" name="input1_2" class="indispensable" value="<?php echo (empty($custom_inputs['input1_2']) && isset($_COOKIE[$data_id.'_input1'])) ?$_COOKIE[$data_id.'_input1']: $custom_inputs['input1_2']; ?>" style="width: 80%;"/>
                <hr style="color: wheat;"/>
                Print Merchant Name
            </td>
            <td width="150"></td>
            <td width="400">ELECTRONIC MERCHANT SYSTEMS<hr style="color: wheat;"/></td>
        </tr>
        <tr>
            <td width="450">
                <input type="text" name="input4_3" class="indispensable" value="<?php echo (empty($custom_inputs['input4_3']) && isset($_COOKIE[$data_id.'_input4_1'])) ?$_COOKIE[$data_id.'_input4_1']: $custom_inputs['input4_3']; ?>" style="width: 80%;"/>
                <hr style="color: wheat;"/>
                Print Officer Name
            </td>
            <td width="150"></td>
            <td width="400">
                <input type="text" class="signature" name="input[]" value="<?php echo isset($inp_txt[$i]) ? $inp_txt[$i++] : ""; ?>" style="width: 40%; float: left;"/>
                <input type="text" name="input[]" value="<?php echo isset($inp_txt[$i]) ? $inp_txt[$i++] : ""; ?>" style="width: 40%; float: right;"/>
                <div style="clear: both;"></div>
                <hr style="color: wheat;"/>
                <span style="float: left;">Signed by</span> <span style="float: right;">Date</span>
            </td>
        </tr>
        <tr>
            <td width="450">
                <input type="text" name="input[]" class="indispensable signature" value="<?php echo isset($inp_txt[$i]) ? $inp_txt[$i++] : ""; ?>" style="width: 40%; float: left;"/>
                <input type="text" name="input6_4" class="indispensable" value="<?php echo (empty($custom_inputs['input6_4']) && isset($_COOKIE[$data_id.'_input6'])) ?$_COOKIE[$data_id.'_input6']: $custom_inputs['input6_4']; ?>" style="width: 40%; float: right;"/>
                <div style="clear: both;"></div>
                <hr style="color: wheat;"/>
                <span style="float: left;">Signed by</span> <span style="float: right;">Date</span>
            </td>
            <td width="150"></td>
            <td width="400"><img src="<?php echo BASE_URL; ?>images/el_merch_sys.png" width="150" style="float: right; margin: 20px 10px 0 0;"/></td>
        </tr>
    </table>
</form>
