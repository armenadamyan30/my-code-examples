<?php
$data_id = $data["data_id"];
$dr_img = $data["drivers"];
$i = 0;
?>

<!--<form action="forms/image_uplaod.php" method="post" enctype="multipart/form-data" id="image_loader">-->
<form method="post" action = "<?php echo BASE_URL; ?>ajax" enctype="multipart/form-data" id="main_form">
    <input type="hidden" name="data_id" value="<?php echo $data_id; ?>" />
    <input type="hidden" name="action" value="drivers" />
    <div class="choose_input_wrapper">
        <h1>Please choose file:</h1>
        <input type="file" name="uploadFile" id="upload_driver">
        <p class="error_image"></p>
        <p class="success_upload"></p>
        <div class="driver_img_wrapper">
            <?php if ($dr_img) { ?>
                <img src="<?php echo BASE_URL."images/drivers/".$dr_img; ?>" />
            <?php } ?>
        </div>
</form>
</div>

