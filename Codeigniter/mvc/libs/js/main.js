$(document).ready(function() {
    /* magnific popup START */
	$('#success-return').magnificPopup({
	  delegate: 'a',
	  showCloseBtn: true,
	  removalDelay: 500, //delay removal by X to allow out-animation
	  callbacks: {
		beforeOpen: function() {
		   this.st.mainClass = this.st.el.attr('data-effect');
		}
	  },
	  midClick: true // allow opening popup on middle mouse click. Always set it to true if you don't provide alternative source.
	});
	$('#loading-return').magnificPopup({
	  delegate: 'a',
	  showCloseBtn: false,
	  removalDelay: 500, //delay removal by X to allow out-animation
	  callbacks: {
		beforeOpen: function() {
		   this.st.mainClass = this.st.el.attr('data-effect');
		}
	  },
	  midClick: true // allow opening popup on middle mouse click. Always set it to true if you don't provide alternative source.
	});
	$('#notice-return').magnificPopup({
	  delegate: 'a',
	  showCloseBtn: true,
	  removalDelay: 500, //delay removal by X to allow out-animation
	  callbacks: {
		beforeOpen: function() {
		   this.st.mainClass = this.st.el.attr('data-effect');
		}
	  },
	  midClick: true // allow opening popup on middle mouse click. Always set it to true if you don't provide alternative source.
	});

    /* magnific popup END */
    
    /*Form field background drowing  START*/
    $(".clear_button").click(function() {
        $(':input').not(':button, :hidden, :submit').each(function() {
            $(this).val('');
            $(this).prop('checked', false);
        });
    });
    $(".form_submit_button").click(function(e) {
		$('#loading-link').click();
		$(this).attr('disabled', 'disabled');
        var i = 0;
		var tmp = 0;
        $("input[type=text]").each(function() {
			if(!$(this).hasClass('soc_sec_number') && !$(this).hasClass('emp_iden_number')){
				if ($(this).val() == '' && $(this).hasClass('indispensable')) {
					i++;
				}
			}else{
				if($(this).val() == ''){
					tmp++;
				}
			}
        });
		if(tmp>1){
			i++;
		}
		$("select").each(function() {
			if ($(this).val() == '' && $(this).hasClass('indispensable')) {
				i++;
			}
		});
		var j = 0;
		$('.required_ch input').each(function() {
			if(!$(this).is(':checked')){
				j++;
			}
		});
		
		
		var flag = false;
		if($('input:radio[name="radio1"]').length){
			if(!$('input:radio[name="radio1"]').is(':checked')){
				flag = true;
				
			}
		}
		
		if($('input:radio[name="radio2"]').length){
			if(!$('input:radio[name="radio2"]').is(':checked')){
				flag = true;
			}
		}
		
		if($('input:radio[name="radio3"]').length){
			if(!$('input:radio[name="radio3"]').is(':checked')){
				flag = true;
			}
		}	
		
		if($('input:radio[name="radio4"]').length){
			if(!$('input:radio[name="radio4"]').is(':checked')){
				flag = true;
			}
		}
		
		if($('input:radio[name="radio5"]').length){
			if(!$('input:radio[name="radio5"]').is(':checked')){
				flag = true;
			}
		}
		
		if($('input:radio[name="radio6"]').length){
			if(!$('input:radio[name="radio6"]').is(':checked')){
				flag = true;
			}
		}
		if($('input:radio[name="radio7"]').length){
			if(!$('input:radio[name="radio7"]').is(':checked')){
				flag = true;
			}
		}
		if($('input:radio[name="radio8"]').length){
			if(!$('input:radio[name="radio8"]').is(':checked')){
				flag = true;
			}
		}
		if($('input:radio[name="radio9"]').length){
			if(!$('input:radio[name="radio9"]').is(':checked')){
				flag = true;
			}
		}
		
		if($('.ch_group1').length && $('.ch_group1 input:checked').length == 0){
			flag = true;
		}	
		
		if($('.ch_group2').length && $('.ch_group2 input:checked').length == 0){
			flag = true;
		}
		if($('.ch_group3').length && $('.ch_group3 input:checked').length == 0){
			flag = true;
		}
		
        if (i == 0 && j == 0 && flag == false) {
			$('[name=filled]').val(1);
		} else {
			$('[name=filled]').val(0);
            //show_message("Please fill all <span style='color:#A5F29D'>green</span> inputs");
        }
		setTimeout(function(){
			$("#main_form").submit();
		}, 1);
    });
    /*Form field background drowing  END*/
	/*
	$('.next_form').click(function(){
		var href = $(this).attr('href');
		var app_id = $('[name=data_id]').val();
		var form_name = $('[name=form_name]').val();
		$.ajax({
		  method: "POST",
		  url: $('.ajax_url').val(),
		  dataType:'json',
		  data: {
			 action: "get_form_state", 
			 app_id:app_id,
			 form_name:form_name
		  }
		}).done(function( msg ) {
			if(msg.is_complate == 0){
				$('#notice-link').click();
			}else{
				window.location.href= href;
			}
		});	
		return false;
	});*/
	$('.delete_misc_img').click(function(){
		var img_name = $(this).attr('img_name');
		var app_id = $('.app_id').val();
		var li = $(this).parents('li');
		$.ajax({
		  method: "POST",
		  url: $('.ajax_url').val(),
		  data: {
			  img_name: img_name, 
			  action: "miscs", 
			  delete_file: 1,
			  app_id:app_id
		  }
		}).done(function( msg ) {
			li.remove();
		});		
	});
    /* Submit form using ajax form library START*/
    $("#main_form").ajaxForm({
        beforeSend: function() {
        },
        uploadProgress: function(event, position, total, percentComplete) {
        },
        success: function(response) {
            show_message(response);
            console.log(response);
            $("#success-link").click();
			$('.form_submit_button').val('SAVE').removeAttr('disabled');
            $(".notice_s").html(response);
			
        },
        complete: function(response) {
            console.log("complete");
        },
        error: function() {
            $(".error_image").html("<font color='red'> ERROR: unable to upload files</font>");
        }
    });


    /* Submit form using ajax form library END*/

    /* IMAGE UPLOAD START */
    $('#upload_driver').bind("change", function(e) {
        if (check_file(this)) {
            readURL(this);
        } else {
            $('.driver_img_wrapper').html('');
        }
    });

    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                var img = $('<img/>').attr('src', e.target.result);
                $('.driver_img_wrapper').html(img);
            };
            reader.readAsDataURL(input.files[0]);
        }
    }
    function check_file(this_) {
        var is_image = false;
        var fileSize = parseInt(this_.files[0].size / 1024);
        var fileType = this_.files[0].type;
        if (fileSize > 1024 * 2) {
            $(".error_image").text("Image size must be less than 2Mb");
            $(this_).val("");
        } else if (fileType != "image/png" && fileType != "image/jpeg" && fileType != "image/gif") {
            $(".error_image").text("Please select JPG, PNG or JIF file");
            $(this_).val("");
        } else {
            $(".error_image").text("");
            is_image = true;
        }
        return is_image;
    }
    /* IMAGE UPLOAD END*/

    function show_message(message) {
        $('.notice_p').show();
        $('.notice_p').html(message);
        setTimeout(function() {
            $('.notice_p').fadeOut();
        }, 2500);
    }
});


