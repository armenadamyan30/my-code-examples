<?php
$data_id = $data["data_id"];
$inp_txt = $data["input_text"];
$inp_com = $data["common"];
$i = 0;

?>
<form method="post" action = "<?php echo BASE_URL; ?>ajax" id="main_form">
    <input type="hidden" name="data_id" value="<?php echo $data_id; ?>" />
    <input type="hidden" name="action" value="form" />
    <input type="hidden" name="form_name" value="bondsman" />
    <!--<input type="submit" value="SAVE & VIEW" class="print_submit"/>--> 



    <h1 style="font-size: 32px;">Bondsman Verification</h1>

<h3 style="text-decoration: underline;">MERCHANT INFORMATION</h3>
    <br/>
    <div class="form_div merch_inform">
        
        <div class="clear"></div>
        <p><span style="width: 153px;">Merchant Name: </span><input type="text" name="merchantName" value="<?php echo isset($inp_com["merchantName"]) ? $inp_com["merchantName"] : ""; ?>" size="44" class="indispensable"/></p>
        <p><span>Merchant Phone:</span> <input type="text" name="input[]" value="<?php echo isset($inp_txt[$i]) ? $inp_txt[$i++] : ""; ?>" size="44" class="indispensable"/></p>
    </div>
    
</form>