
$(function(){

    $(document).on('click', '.webadmin-open-ajax-popup', function(e){
        e.preventDefault();
        
        var title = $(this).attr('title');
        let html = modalHeader(title);
        $('#webAdminModal .modal-content').html(html);
        $('#webAdminModal').modal('show');
    
        if($(this).attr('data-url'))
            var targetUrl = $(this).data('url'); 
        else
            var targetUrl = $(this).attr('href');
        var popup_size = 'medium';
        if($(this).attr('data-popup-size'))
            popup_size = $(this).attr('data-popup-size');
    
        $.get(targetUrl, function(response){
            setTimeout( function() {
                $('#webAdminModal .modal-body').html(response);
                loadModal();
            }, 500 );
        })
    });
    
    $(document).on('click', '#webAdminModal button[type="submit"]', function(event){
        event.preventDefault();
        let obj = $(this);
        var form = obj.parents('#webAdminModal').find('form');
        var form_id = form.attr('id');
        let frmValid = true;
        let validate_fn = obj.attr('data-validation-fn');
        if(typeof validate_fn !== "undefined"){
            eval(validate_fn+"()");
            frmValid = form.valid();
        }
        if(frmValid){
            let btn_text = obj.html();
            obj.html('Processing..');
            obj.prop('disabled', true);
            var data = new FormData( $('#'+form_id)[0] );
            $.ajax({
                url : form.attr('action'),
                type: "POST",
                data : data,
                processData: false,
                contentType: false,
                success:function(response){
                    let reload_type = obj.data('reload-type');
                    if(typeof reload_type != "undefined"){
                        if(reload_type == 'hard'){
                            let html = modalHeader(response.title);
                            $('#webAdminModal .modal-content').html(html);
                            $('#webAdminModal .modal-body').html(response.html);
                            toast(response.message);
                            dt();
                            loadModal();
                        }
                        else if(reload_type == 'soft'){
                            toast(response.message);
                            dt();
                            $('#webAdminModal').modal('hide')
                        }
                    }
                    else{
                        $('#webAdminModal').modal('hide')
                    }
                },
                error: function (xhr, status, error) {
                    var data = JSON.parse(xhr.responseText);
                    if (typeof data.error != "undefined") {
                        obj.html(btn_text);
                        obj.prop('disabled', false);
                        miniweb_alert('Alert!', data.error);
                    }
                    else if (typeof data.errors != "undefined") {
                        obj.html(btn_text);
                        obj.prop('disabled', false);
                        var errorString = '<ul>';
                        $.each( data.errors, function( key, value) {
                            errorString += '<li>' + value + '</li>';
                        });
                        errorString += '</ul>';
                        miniweb_alert('Alert!', errorString);
                    }
                }
            });
        }
    })

    $(document).on('change', '.image-upload', function(event){
        var fileName = $(this).val().split("\\").pop();
        $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
        let image_holder = $(this).parent().next(".file-upload-holder");
        let output = image_holder.find('img');

        file = event.target.files[0];
        if (file) {
            let reader = new FileReader();
            reader.onload = function (event2) {
                $(output).attr("src", event2.target.result);
            };
            reader.readAsDataURL(file);
            image_holder.find('.remove-uploaded-image').val(1);
            image_holder.show();
        }
    })

    $(document).on('click', '.remove-uploaded-image', function(){
        $(this).parent().hide();
        $(this).parent().find('img').attr('src', '');
        let upload_label_holder = $(this).parents('.form-group-image-upload').find(".custom-file .custom-file-label");
        if(upload_label_holder.html() != "Choose file")
            upload_label_holder.removeClass("selected").html('');
        $(this).parent().find('.remove-uploaded-image').val(1);
    })

})

var toast = function(message){
    const Toast = Swal.mixin({
        toast: true,
        position: "top-end",
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
        didOpen: (toast) => {
          toast.onmouseenter = Swal.stopTimer;
          toast.onmouseleave = Swal.resumeTimer;
        }
      });
      Toast.fire({
        icon: "success",
        title: message
      });
}

var modalHeader = function(title){
    var html = '<div class="modal-header" style=""><h5 class="modal-title" id="webAdminModalLabel">'+title+'</h5><button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button></div><div class="modal-body"></div>';
    return html;
}

var loadModal = function(){
    display_select2();
    if($('img.checkable').length)
    {
        $("img.checkable").imgCheckbox({
            onclick: function(el){
                select_checked_image(el);  
            }
        });

        if($('#media-related_id').length)
        {
            select_checked_image($('#single-file-item-'+$('#media-related_id').val()+' span'));
        }
    }
    if($('#fileupload').length)
    {
        file_upload('#fileupload');
    }

    if($('#change-media').length)
        update_media('#change-media');

    if($('#change-cover-media').length)
        update_media('#change-cover-media');

    if($('.accordion').length)
    {
        $('.accordion').collapse();
        $('#accordionExample').sortable({
            axis: 'y',
            update: function (event, ui) {
                save_order();
            }
        });
    }

    if($('.datepicker').length)
    {
        datePicker();
    }

    if($('.fileinput').length)
        $('.fileinput').fileinput();

    if($('.editor').length)
        initiate_editor();

    if($('.phone').length)
        initTelInput();

    if($('#sortable').length){
        $( "#sortable" ).sortable();
        $( "#sortable" ).disableSelection();
    }
}

var initTelInput = function(){

    document.querySelectorAll('.phone').forEach(function (input){
        window.intlTelInput(input, {
            utilsScript: "https://cdn.jsdelivr.net/npm/intl-tel-input@19.2.20/build/js/utils.js",
            autoPlaceholder: "off",
            formatAsYouType: false,
            formatOnDisplay: false
        });

        const iti = window.intlTelInputGlobals.getInstance(input);
        let init_num = $(input).data('full-phone-number');
        if(typeof init_num != "undefined"){
            let original_val = $(input).val();
            iti.setNumber(init_num);
            $(input).val(original_val);
        }
        
        input.addEventListener("countrychange", function() {
            let data = iti.getSelectedCountryData();
            $(input).parents('.form-group').find('.country-code').val(data.dialCode);
        });
    });    
}