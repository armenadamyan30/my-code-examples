<?php
$data_id = $data["data_id"];
$inp_txt = $data["input_text"];
$inp_ch = $data["input_check"];
$inp_com = $data["common"];
$custom_inputs = isset($data['inputs'])?$data['inputs']:array();
$i = 0;
?>

<form method="post" action = "<?php echo BASE_URL; ?>ajax" id="main_form">
    <input type="hidden" name="data_id" value="<?php echo $data_id; ?>" />
    <input type="hidden" name="action" value="form" />
    <input type="hidden" name="form_name" value="w9" />
	<input type="hidden" name="filled" value="0" />
    <!--<input type="submit" value="SAVE & VIEW" class="print_submit" id="form_submit_button"/>--> 

    <table class="table table-body">
        <tr>
            <td width="15%" class="very_top very_top_left">
                Form <span>W-9</span><br/>
        <l style="font-size: 13px;">(Rev. October 2007)</l><br/>
        Department of the Treasury<br/>
        Internal Revenue Service
        </td>
        <td width="70%" class="very_top very_top_mid">
            <h1>Request for Taxpayer<br/>
                Identification Number and Certification</h1>
        </td>
        <td width="15%" class="very_top very_top_right">
            Give form to the
            requester. Do not
            send to the IRS.
        </td >
        </tr>
    </table>
    <table class="table table-body second_table">
        <tr>
            <td rowspan="8" class="border_right" width="10%" >
                <div class="rotate">
                    <b>Print or type</b> <br/>
                    See <b>Specific Instructions</b> on page 2.
                </div>
            </td>
            <td colspan="3" >Name (as shown on your income tax return)<br/>
                <input type="text" name="merchantName" value="<?php echo isset($inp_com["merchantName"]) ? $inp_com["merchantName"] : ""; ?>" size="44" class="indispensable" />
            </td>
        </tr>
        <tr>
            <td colspan="3" >Business name, if different from above<br/>
                <input type="text" name="input[]" value="<?php echo isset($inp_txt[$i]) ? $inp_txt[$i++] : ""; ?>" size="44" class="fixed" />
            </td>
        </tr>
        <tr>
            <td colspan="2" class="border_right" width="70%">
                Check appropriate box:
                <span class="required_radio"><input class="" id="radio11" type="radio" name="radio1" value="1" <?php echo (isset($inp_ch["radio1"]) && $inp_ch["radio1"] == 1)? "checked": ""; ?> /></span>
                <label for="radio11">Individual/Sole proprietor</label>
                <span class="required_radio"><input class="" id="radio12" type="radio" name="radio1" value="2" <?php echo (isset($inp_ch["radio1"]) && $inp_ch["radio1"] == 2)? "checked": ""; ?> /></span>
                <label for="radio12">Corporation</label>
                <span class="required_radio"><input class="" id="radio13" type="radio" name="radio1" value="3" <?php echo (isset($inp_ch["radio1"]) && $inp_ch["radio1"] == 3)? "checked": ""; ?> /></span>
                <label for="radio13">Partnership</label>
                <br/>
                <span class="required_radio"><input class="" id="radio14" type="radio" name="radio1" value="4" <?php echo (isset($inp_ch["radio1"]) && $inp_ch["radio1"] == 4)? "checked": ""; ?> /></span>
                <label for="radio14">Limited liability company.
                Enter the tax classification (D=disregarded entity, C=corporation, P=partnership)</label>
                <img src="<?php echo BASE_URL ?>images/arrow.png" height="10"/> ... <br/>
                <span class="required_radio"><input class="" type="radio" id="radio15" name="radio1" value="5" <?php echo (isset($inp_ch["radio1"]) && $inp_ch["radio1"] == 5)? "checked": ""; ?> /></span>
                <label for="radio15">Other (see instructions)</label> <img src="<?php echo BASE_URL ?>images/arrow.png" height="10"/>
            </td>
            <td>
              <input class="" id="checkbox6" type="checkbox" name="checkbox6" value="" <?php echo isset($inp_ch["checkbox6"]) ? "checked": ""; ?> />
                <label for="checkbox6">Exempt payee</label>
            </td>
        </tr>
        <tr>
            <td class="border_right">
                Address (number, street, and apt. or suite no.)<br/>
                <input type="text" name="input2_1" value="<?php echo (empty($custom_inputs['input2_1']) && isset($_COOKIE[$data_id.'_input2_1'])) ?$_COOKIE[$data_id.'_input2_1']: $custom_inputs['input2_1']; ?>" size="44" class="indispensable" />
            </td>
            <td colspan="2" rowspan="4">
                Requester’s name and address (optional)<br/>
                <input type="text" name="input[]" value="<?php echo isset($inp_txt[$i]) ? $inp_txt[$i++] : ""; ?>" />
            </td>
        </tr>
        <tr>
            <td class="border_right">
                City<br/>
                <input type="text" name="input11_1" value="<?php echo (empty($custom_inputs['input11_1']) && isset($_COOKIE[$data_id.'_input11_1'])) ?$_COOKIE[$data_id.'_input11_1']: $custom_inputs['input11_1']; ?>" size="44" class="indispensable" />
            </td>
		</tr>
		<tr>
            <td class="border_right">
                State<br/>
                <input type="text" name="input12_1" value="<?php echo (empty($custom_inputs['input12_1']) && isset($_COOKIE[$data_id.'_input12_1'])) ?$_COOKIE[$data_id.'_input12_1']: $custom_inputs['input12_1']; ?>" size="44" class="indispensable" />
            </td>
		</tr>
		<tr>
            <td class="border_right">
                ZIP code<br/>
                <input type="text" name="input13_1" value="<?php echo (empty($custom_inputs['input13_1']) && isset($_COOKIE[$data_id.'_input13_1'])) ?$_COOKIE[$data_id.'_input13_1']: $custom_inputs['input13_1']; ?>" size="44" class="indispensable" />
            </td>
		</tr>
        <tr>
            <td colspan="3" >
                List account number(s) here (optional)<br/>
                <input type="text" name="input[]" value="<?php echo isset($inp_txt[$i]) ? $inp_txt[$i++] : ""; ?>" size="44"  />
            </td>
        </tr>
    </table>
    <table class="table table-body">
        <tr class="border_bottom border_top" style="font: 22px bold;">
            <td width="9%" style="background: #000; color: #fff;">Part I</td>
            <td colspan="3" width="91%">Taxpayer Identification Number (TIN) </td>
        </tr>
        <tr style="height: 10px;">
            <td colspan="3"></td>
            <td rowspan="4" width="2%"></td>
        </tr>
        <tr>
            <td rowspan="2" colspan="2" width="70%" style="font-size: 16px;">
                Enter your TIN in the appropriate box. The TIN provided must match the name given on Line 1 to avoid
                backup withholding. For individuals, this is your social security number (SSN). However, for a resident
                alien, sole proprietor, or disregarded entity, see the Part I instructions on page 3. For other entities, it is
                your employer identification number (EIN). If you do not have a number, see How to get a TIN on page 3.
            </td>
            <td width="28%">
                <div style="border: 1px solid #000; padding: 5px; font: 18px bold;">
                    Social security number<br/>
                    <input type="text" name="input[]" value="<?php echo isset($inp_txt[$i]) ? $inp_txt[$i++] : ""; ?>" class="indispensable soc_sec_number" />
                </div>
            </td>
        </tr>
        <tr>
            <td width="16%" style="font: 22px bold; line-height: 12px; text-align: center;">or</td>
        </tr>
        <tr class="border_bottom third_table_last_row">
            <td colspan="2" width="70%" style="font-size: 16px;">
                <b>Note.</b> If the account is in more than one name, see the chart on page 4 for guidelines on whose number to enter.
            </td>
            <td width="28%">
                <div style="border: 1px solid #000; border-bottom: 0; padding: 5px; font: 18px bold;">
                    Employer identification number
                    <input type="text"  name="input[]" value="<?php echo isset($inp_txt[$i]) ? $inp_txt[$i++] : ""; ?>" class="indispensable emp_iden_number" />
                </div>
            </td>
        </tr>
    </table>
    <table class="table table-body">
        <tr class="border_bottom border_top" style="font: 22px bold;">
            <td width="1%" style="background: #000; color: #fff;">Part II</td>
            <td colspan="3" width="91%">Certification</td>
        </tr>
        <tr class="border_bottom" style="font-size: 16px;">
            <td colspan="2" width="20%">
                Under penalties of perjury, I certify that:
                <ol style="padding-left: 17px;">
                    <li>
                        The number shown on this form is my correct taxpayer identification number 
                        (or I am waiting for a number to be issued to me), and
                    </li>
                    <li>
                        I am not subject to backup withholding because: (a) I am exempt from backup withholding, 
                        or (b) I have not been notified by the Internal Revenue Service (IRS) that I am subject 
                        to backup withholding as a result of a failure to report all interest or dividends, 
                        or (c) the IRS has notified me that I am no longer subject to backup withholding, and 
                    </li>
                    <li>
                        I am a U.S. citizen or other U.S. person (defined below).
                    </li>
                </ol>
                <b>Certification instructions.</b> You must cross out item 2 above if you have been notified by the IRS that you are currently subject to backup withholding because you have failed to report all interest and dividends on your tax return. For real estate transactions, item 2 does not apply.  For mortgage interest paid, acquisition or abandonment of secured property, cancellation of debt, contributions to an individual retirement arrangement (IRA), and generally, payments other than interest and dividends, you are not required to sign the Certification, but you must provide your correct TIN. See the instructions on page 4.  
            </td>
        </tr>
    </table>
    <table class="table table-body">
        <tr class="border_bottom" style="font: 14px bold;">
            <td width="1%" class="border_left border_right">Sign Here</td>
            <td width="54%">
                Signature of <br/>
                U.S. person 
                <img src="<?php echo BASE_URL ?>images/arrow.png" height="10"/> 
                <input type="text" name="input[]" value="<?php echo isset($inp_txt[$i]) ? $inp_txt[$i++] : ""; ?>" size="44" class="indispensable signature" />
            </td>
            <td width="45%">
                <br/>
                Date 
                <img src="<?php echo BASE_URL ?>images/arrow.png" height="10"/> 
                <input type="text" name="input6" value="<?php echo (empty($custom_inputs['input6']) && isset($_COOKIE[$data_id.'_input6'])) ?$_COOKIE[$data_id.'_input6']: $custom_inputs['input6']; ?>" size="44" class="indispensable" />
            </td>
        </tr>
    </table>
    <table class="table table-body last_table">
        <tr class="border_bottom" style="font: 16px bold; text-align: justify;">
            <td width="50%" style="padding-right: 20px;">
                <h3>General Instructions</h3>
                Section references are to the Internal Revenue Code unless otherwise noted.
                <h3>Purpose of Form</h3>
                A person who is required to file an information return with the
                IRS must obtain your correct taxpayer identification number (TIN)
                to report, for example, income paid to you, real estate
                transactions, mortgage interest you paid, acquisition or
                abandonment of secured property, cancellation of debt, or
                contributions you made to an IRA.
                <br/>
                &nbsp;&nbsp;&nbsp;&nbsp;Use Form W-9 only if you are a U.S. person (including a
                resident alien), to provide your correct TIN to the person
                requesting it (the requester) and, when applicable, to:
                <ol style="padding-left: 35px;">
                    <li>
                        Certify that the TIN you are giving is correct (or you are
                        waiting for a number to be issued)
                    </li>
                    <li>
                        Certify that you are not subject to backup withholding, or
                    </li>
                    <li>
                        Claim exemption from backup withholding if you are a U.S.
                        exempt payee. If applicable, you are also certifying that as a
                        U.S. person, your allocable share of any partnership income from
                        a U.S. trade or business is not subject to the withholding tax on
                        foreign partners’ share of effectively connected income.
                    </li>
                </ol>
                <b>Note.</b> If a requester gives you a form other than Form W-9 to
                request your TIN, you must use the requester’s form if it is
                substantially similar to this Form W-9.
            </td>
            <td width="50%" style="padding-left: 40px;">
                <b>Definition of a U.S. person.</b> For federal tax purposes, you are
                considered a U.S. person if you are:
                <ul>
                    <li>An individual who is a U.S. citizen or U.S. resident alien, </li>
                    <li>
                        A partnership, corporation, company, or association created or
                        organized in the United States or under the laws of the United
                        States,
                    </li>
                    <li>An estate (other than a foreign estate), or </li>
                    <li>
                        A domestic trust (as defined in Regulations section
                        301.7701-7).
                    </li>
                </ul>
                <b>Special rules for partnerships.</b> Partnerships that conduct a
                trade or business in the United States are generally required to
                pay a withholding tax on any foreign partners’ share of income
                from such business. Further, in certain cases where a Form W-9
                has not been received, a partnership is required to presume that
                a partner is a foreign person, and pay the withholding tax.
                Therefore, if you are a U.S. person that is a partner in a
                partnership conducting a trade or business in the United States,
                provide Form W-9 to the partnership to establish your U.S.
                status and avoid withholding on your share of partnership
                income.
                <br/>
                The person who gives Form W-9 to the partnership for
                purposes of establishing its U.S. status and avoiding withholding
                on its allocable share of net income from the partnership
                conducting a trade or business in the United States is in the
                following cases:
                <ul>
                    <li>The U.S. owner of a disregarded entity and not the entity,</li>
                </ul>

            </td>
        </tr>
    </table>


</form>
