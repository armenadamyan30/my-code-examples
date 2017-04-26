
        <div class="clear center height20">
            <span class="notice_p"></span>
        </div>
        <div class="urls">
            <!--<a href="<?php echo BASE_URL . 'list'; ?>" >Application List</a> |-->
            <!--<a href="<?php echo BASE_URL . 'edit.php'; ?>">Create new</a>-->
            <a href="//bailswipe.com/form-list.php?id=<?php echo $data["data_id"]; ?>" class="print_submit">Forms list</a>
            <input type="button" value="Clear Form" class="clear_button print_submit"/>
            <input type="button" value="SAVE" class="print_submit form_submit_button">
        </div>
    </div>
	<input type="hidden" class="ajax_url" value="<?php echo BASE_URL; ?>ajax" />
    <div class="hidden" id="success-return">
        <a id="success-link" href="#success-popup"></a>
    </div>
	<div class="hidden" id="notice-return">
        <a id="notice-link" href="#notice-popup"></a>
    </div>
	<div class="hidden" id="loading-return">
        <a id="loading-link" href="#loading-popup"></a>
    </div>
	<div id="loading-popup" style="text-align:center;width:252px;"  class="white-popup mfp-with-anim mfp-hide">
		 <img src="https://cms-c81e728d9d4c2f636f067f89cc14862c.s3.amazonaws.com/images/18/loading.gif" alt="Loading"><br><br>
         <p style="font-weight:bold;">Processing, one moment please...</p>
	 </div>
    <!-- Popup itself -->
	 <div id="notice-popup" class="white-popup mfp-with-anim mfp-hide">
		<p style="font-size:18px;">Not all the required fields on this form have been provided. Your current inputs have been saved, you may return at a later time.</p>
	 </div>

    <div id="success-popup" class="white-popup mfp-with-anim mfp-hide">
        <span class="notice_s"></span>
        <br/>
		<?php
		//var_dump(FORMS);
		$forms = unserialize(FORMS);
		foreach($forms as $k => $v){
			if($v == $data['form']){
				$next_form = isset($forms[($k + 1)])?$forms[($k + 1)]:FALSE;
			}
		}
		 ?>
        <a href="<?php echo FORM_LIST_URL; ?>?id=<?php echo $data["data_id"]; ?>" class="full mfp-close mfp-close-ok" />Back to list</a>
        <button class="full mfp-close mfp-close-ok">Stay on page</button>
		<?php if($next_form){ ?>
			<a href="<?php echo BASE_URL.$next_form.'/'.$data["data_id"]; ?>" class="full mfp-close mfp-close-ok next_form" />Continue to Next Form</a>
		<?php } ?>
	</div>
</body>
<script src="<?php echo BASE_URL; ?>libs/js/jquery.min.js"></script>
<script src="<?php echo BASE_URL; ?>libs/js/jquery.form.js"></script>
<script src="<?php echo BASE_URL; ?>libs/js/jquery.magnific-popup.js"></script>
<script src="<?php echo BASE_URL; ?>libs/js/main.js"></script>
<?php if($appId_read_only){ ?>
<script>
	$(document).ready(function(){
		$('input').attr('readonly', 'readonly');
		$('select, input[type=checkbox], input[type=radio]').attr('disabled', 'disabled');
	});
</script>
<?php } ?>