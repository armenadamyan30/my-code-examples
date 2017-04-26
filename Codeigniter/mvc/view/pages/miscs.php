<?php
$data_id = $data["data_id"];
$dr_img = !empty($data["miscs"])?json_decode($data["miscs"], true):array();
$i = 0;
?>
<div style="width:60%;margin:0 auto;">
<input type="hidden" class="ajax_url" value="<?php echo BASE_URL; ?>ajax" />
<form method="post" action = "<?php echo BASE_URL; ?>ajax" enctype="multipart/form-data" id="main_form">
    <input type="hidden" class="app_id" name="data_id" value="<?php echo $data_id; ?>" />
    <input type="hidden" name="action" value="miscs" />
    <div class="choose_input_wrapper">
        <h1>Please choose file:</h1>
        <input type="file" name="uploadFile[]" id="upload_driver" multiple>
        <p class="error_image"></p>
        <p class="success_upload"></p>
        <div class="driver_img_wrapper">
            
        </div>
    </div>
</form>

<?php if ($dr_img) { ?>
	<ul>
		<?php foreach($dr_img as $k => $v){ ?>
			<li style="height:100px;list-style-type:none;">
				<img style="max-width:90px;float:left;" src="<?php echo BASE_URL . "images/miscs/" . $v; ?>" />
				<span style="float: left;cursor:pointer;margin-top:36px;margin-left:20px;" img_name = "<?php echo $v; ?>" class="delete_misc_img">Delete</span>
			</li>
		<?php } ?>
	</ul>
<?php } ?>
</div>
