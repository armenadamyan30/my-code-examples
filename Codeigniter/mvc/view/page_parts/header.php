<!DOCTYPE html>
<head>
    <title>BailSwipe</title>
    <link href='http://fonts.googleapis.com/css?family=Euphoria+Script' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>libs/css/magnific-popup.css" />
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>libs/css/style_<?php echo $data["form"]; ?>.css" />
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>libs/css/style.css" />
	<?php if($this->readonly){ ?>
		<link rel="stylesheet" href="<?php echo BASE_URL; ?>libs/css/filled.css" />
	<?php } ?>
</head>
<body>
    <div class="body_wrapper">
        <div class="urls">
            <a href="<?php echo FORM_LIST_URL; ?>?id=<?php echo $data["data_id"]; ?>" class="print_submit">Forms list</a>
            <input type="button" value="Clear Form" class="clear_button print_submit"/>
            <input type="button" value="SAVE" class="print_submit form_submit_button">
        </div>
        <div class="clear center height20">
            <span class="notice_p"></span>
        </div>