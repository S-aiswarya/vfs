$(function(){
	display_select2();
    
    if($('#fileupload').length)
        file_upload('#fileupload');

    if($('#change-media').length)
        update_media('#change-media');

    if($('#change-cover-media').length)
        update_media('#change-cover-media');

    if($('.editor').length)
        initiate_editor();

	$(document).on('click', '.webadmin-btn-warning-popup', function(event){
        event.preventDefault();
        var url = $(this).attr('href');
        var redirect_url = $(this).data('redirect-url');
        var message = $(this).data('message');
        if (typeof redirect_url !== typeof undefined && redirect_url !== false)
            var action = 'redirect';

        $.confirm({
                title: 'Warning',
                content: message,
                closeAnimation: 'scale',
                opacity: 0.5,
                buttons: {
                    'ok_button': {
                        text: 'Proceed',
                        btnClass: 'btn-blue',
                        action: function(){
                            var obj = this;
                            obj.buttons.ok_button.setText('Processing..'); // setText for 'hello' button
                            obj.buttons.ok_button.disable();
                            $.get(url).done(function (data) {
                                obj.$$close_button.trigger('click');
                                if (typeof data.error != "undefined") {
                                    var title = "Alert!";
                                    var response_msg = data.error;
                                }
                                else
                                {
                                    var title = "Success!";
                                    var response_msg = data.success;
                                }
                                $.confirm({
                                    title: title,
                                    content: response_msg,
                                    type: 'red',
                                    buttons: {
                                      'ok': function(){
                                        if(action == 'redirect')
                                            window.location.href= redirect_url;
                                        else
                                            dt();
                                      }
                                    },
                                   
                                });
                            });
                            return false;
                        }
                    },
                    close_button: {
                          text: 'Cancel',
                          action: function () {
                        }
                    },
                }
            });
    });

    $(document).on('click', '.view-image', function(){
        var src = $(this).data('src');
        var title = $(this).data('title');
        $.confirm({
            title: title,
            content: '<img src="'+src+'"/>',
            type: 'red',
            typeAnimated: true,
            buttons: {
                close: function () {
                }
            }
        });
    });

	var jc = false;

    $(document).on('click', '#webadmin-ajax-form-submit-btn', function(){
    	var obj = $(this);
    	var form = obj.parents('.confirm-wrap').find('form');
        var form_id = form.attr('id');
        var need_validation = form.attr('data-validate');
        var no_close_parent = obj.attr('data-force-open');
        var frmValid = true;
        if(typeof need_validation !== "undefined")
        {
            validate();
            frmValid = form.valid();
        }
        if(frmValid){
            obj.html('Processing..');
            obj.prop('disabled', true);
            var data = new FormData( $('#'+form_id)[0] );
            $.ajax({
                url : form.attr('action'),
                type: "POST",
                data : data,
                processData: false,
                contentType: false,
                success:function(data, textStatus, jqXHR){
                    if (typeof data.error != "undefined") {
                    	obj.html('Save');
            			obj.prop('disabled', false);
                        miniweb_alert('Alert!', data.error);
                    }
                    else if (typeof data.errors != "undefined") {
                    	obj.html('Save');
            			obj.prop('disabled', false);
            			var errorString = '<ul>';
				        $.each( data.errors, function( key, value) {
				            errorString += '<li>' + value + '</li>';
				        });
				        errorString += '</ul>';
                        miniweb_alert('Alert!', errorString);
                    }
                    else if(typeof data.success != "undefined"){
                        if(typeof no_close_parent == "undefined")
                        {
                    	   jc.close();
                        }
                        else
                        {
                            obj.html('Save');
                            obj.prop('disabled', false);
                        }
                        miniweb_alert('Success!', data.success, 'redraw');
                    }
                }
            });
        }
    });

    $(document).on('click', '#add-faq-btn', function(){
        var obj = $(this);
        faq_validate();
        if($('#faqForm').valid())
        {
            obj.prop('disabled', true).text('Processing');
            var url = $('#faqForm').attr('action');
            var data = $('#faqForm').serialize();
            $.post(url, data, function(result){
                $('#faq-form').html(result.form_html);
                $('#faq-listing').html(result.list_html);
                $.alert(result.message);
                obj.prop('disabled', false).text('Save');
                initiate_editor();
            })
        }
    });

    $(document).on('click', '#faq-create-new', function(e){
        e.preventDefault();
        var url = $(this).attr('href');
        $.get(url, function(result){
            $('#faq-form').html(result);
            initiate_editor();
        })
    });

    $(document).on('click', '.edit-faq-btn', function(e){
        e.preventDefault();
        var url = $(this).attr('href');
        $.get(url, function(result){
            $('#faq-form').html(result);
            initiate_editor();
        })
    })

    $(document).on('click', '.delete-faq-btn', function(e){
        e.preventDefault();
        var obj = $(this);
        var message = obj.data('message');
        var url = obj.attr('href');
        $.confirm({
                title: 'Warning',
                content: message,
                closeAnimation: 'scale',
                opacity: 0.5,
                buttons: {
                    'ok_button': {
                        text: 'Proceed',
                        btnClass: 'btn-blue',
                        action: function(){
                            var obj2 = this;
                            obj2.buttons.ok_button.setText('Processing..'); // setText for 'hello' button
                            obj2.buttons.ok_button.disable();
                            $.get(url).done(function (data) {
                                obj2.$$close_button.trigger('click');
                                if(data.success)
                                    obj.parents('.faq-item-card').remove();
                                $.alert(data.message);
                            });
                            return false;
                        }
                    },
                    close_button: {
                          text: 'Cancel',
                          action: function () {
                        }
                    },
                }
            });
    })

        $(document).on('click', '.media-popup-nav .pagination .page-link', function(e){
              e.preventDefault();
              var loadurl = $(this).attr('href');
              var targ = $('#mediaList');
              if(loadurl != 'undefined'){
                  targ.load(loadurl, function(){
                    $("img.checkable").imgCheckbox({
                        onclick: function(el){
                            select_checked_image(el);  
                        }
                    });
                  });
              }
          });

          $(document).on('keyup', '#mediaPopupSearchInput', function(e){
            var req = $(this).val();
            var type = $(this).data('type');
            var loadurl = $(this).data('url');
            $.ajax({
               url: loadurl,
               data: {req: req, type: type}, // serializes the form's elements.
               success: function(data)
               {
                  $('#mediaList').html(data);
                  $("img.checkable").imgCheckbox({
                        onclick: function(el){
                            select_checked_image(el);  
                        }
                    });
               }
             });
          });

        $(document).on('click', '#extra-data-popup-submit-btn', function(){

          $(this).prop('disabled', true).text('Processing...');
          var url = $('#mediaExtraFrm').attr('action');
          var data = $('#mediaExtraFrm').serialize();
          $.post(url, data, function(result){
              $('#show-media-item').html(result);
              $.alert('Details successfully saved!');
          })
        })

        $(document).on('click', '#set-media-btn', function(){

            if($('#media-popupType').val() == 'set_url')
            {
                var path = $('.imgChked').find('img').data('file-path');
                $('#'+$('#media-holder_attr').val()).val(path);
                jc.close();
            }
            else{
                var id = $('.imgChked').find('img').attr('id');

                var data = {
                    "_token": _token,
                    id: id,
                    'type': $('#media-related_type').val(),
                    'title': $('#media-title').val(),
                    'holder_attr': $('#media-holder_attr').val(),
                    'related_id': $('#media-related_id').val(),
                    'popup_type': $('#media-popupType').val(),
                    'media_id': $('#media-id').val(),
                    'display': $('#media-display').val(),
                };

                $.post(base_url+'/media/set-media', data, function(data){
                    if(data.success == 1)
                    {
                        $('#'+$('#media-id').val()).replaceWith(data.html);
                        jc.close();
                    }
                    else
                        $.alert('Opps! something wrong happend, please try again.')
                })
            }
            
        });

        $(document).on('click', '#set-gallery-btn', function(){
            $(this).prop('disabled', true);
            var url = $(this).attr('data-url');
            var ids = [];
            $('.imgChked').each(function(i, e) {
                var id = $(this).find('img').attr('id');
                ids.push(id);
            });
            $.ajax({
                    url: url,
                    method: 'POST',
                    data: {
                            "_token": _token,
                            id: $('#media-related_id').val(),
                            ids: ids,
                    },
                    success: function(result){
                       $('#'+$('#media-holder_attr').val()).html(result);
                       jc.close();
                    },
                });
        });


        $(document).on('click', '.image-remove', function(){
            var that = $(this);
            $.confirm({
                title: 'Confirm!',
                content: 'Are you sure to delete this?',
                buttons: {
                    confirm:{
                        btnClass: 'btn-blue',
                        action: function(){
                            var holder = that.data('holder-id');
                            that.parents('.thumbnail').remove();
                            $('#'+holder).find('input').val('');
                            $('#add-new-'+holder).show();
                        }
                    },
                    cancel: function () {
                    },
                }
            });

            
        });

        $('.numeric').ForceNumericOnly();
        $('.amount').ForcePriceOnly();
        $('input[name="slug"]').ForceSlugOnly();

        $(".copy-name").keyup(function () {
            var name = $(this).val();
            $("input[name='slug']").val(slugify(name));
            $("input[name='title']").val(name);
            $("input[name='browser_title']").val(name);
        });

        if($('.datepicker').length)
        {
            datePicker();
        }

        if($('.date-range-picker').length)
        {
            $('.date-range-picker').daterangepicker({
                autoUpdateInput: false,
                locale: {
                  format: 'DD/MM/YYYY',
                  cancelLabel: 'Clear'
                },
                parentEl: '.filter-form'
            });

            $('.date-range-picker').on('cancel.daterangepicker', function(ev, picker) {
              $('.date-range-picker').val('');
            });

            $('.date-range-picker').on('apply.daterangepicker', function(ev, picker) {
                  $(this).val(picker.startDate.format('DD/MM/YYYY') + ' - ' + picker.endDate.format('DD/MM/YYYY'));
             });
        }
});



var datePicker = function(){
    $('.datepicker').datepicker({
        dateFormat: 'dd-mm-yy',
    });
}

var save_order = function()
{
    var ids = [];
    $('#accordionExample .card').each(function(){
        ids.push($(this).attr('id'));
    });
    $.post(base_url+'/faq/re-order', {ids: ids, '_token':_token}, function(){

    });
}

var faq_validate = function(){
    $('#faqForm').validate({
        ignore: [],
        rules: {
            "question": "required",
            "answer": "required",
        },
        messages: {
            "question": "Question cannot be blank",
            "answer": "Answer cannot be blank",
        },
    });
};

function initiate_editor()
{
    document.querySelectorAll('.editor').forEach(function (val){
        $('.ck-editor').remove();
        initEditor(val);
    });
}

var initEditor = function(editor_obj){
        ClassicEditor.create( editor_obj, { toolbar: {
            items: [
                'heading',
                '|',
                'bold',
                'underline',
                'italic',
                'link',
                'strikethrough',
                'blockQuote',
                'horizontalLine',
                'subscript',
                'superscript',
                'bulletedList',
                'numberedList',
                '|',
                'outdent',
                'indent',
                'alignment',
                '|',
                'fontBackgroundColor',
                'fontColor',
                'fontFamily',
                'fontSize',
                'highlight',
                'insertTable',
                'imageUpload',
                'mediaEmbed',
                'findAndReplace',
                'htmlEmbed',
                'CKFinder',
                'sourceEditing',
                'undo',
                'redo'
            ]
        },
        ckfinder: {
            uploadUrl: base_url+'/media/editor-upload?_token='+_token,
        },
        language: 'en',
        image: {
            toolbar: [
                'imageTextAlternative',
                'imageStyle:inline',
                'imageStyle:block',
                'imageStyle:side'
            ]
        },
        table: {
            contentToolbar: [
                'tableColumn',
                'tableRow',
                'mergeTableCells'
            ]
        },
        licenseKey: '',
    })
    .then( editor => {
        editor.model.document.on('change:data', () => {
            editor_obj.value = editor.getData();
        });
    } )
    .catch( error => {
        console.error( 'Oops, something went wrong!' );
        console.error( 'Please, report the following error on https://github.com/ckeditor/ckeditor5/issues with the build id and the error stack trace:' );
        console.warn( 'Build id: x5jie6v3f21p-6vz1y3ys641p' );
        console.error( error );
    } );
}

function slugify(string) {
    const a = 'Ã Ã¡Ã¢Ã¤Ã¦Ã£Ã¥ÄÄƒÄ…Ã§Ä‡ÄÄ‘ÄÃ¨Ã©ÃªÃ«Ä“Ä—Ä™Ä›ÄŸÇµá¸§Ã®Ã¯Ã­Ä«Ä¯Ã¬Å‚á¸¿Ã±Å„Ç¹ÅˆÃ´Ã¶Ã²Ã³Å“Ã¸ÅÃµá¹•Å•Å™ÃŸÅ›Å¡ÅŸÈ™Å¥È›Ã»Ã¼Ã¹ÃºÅ«Ç˜Å¯Å±Å³áºƒáºÃ¿Ã½Å¾ÅºÅ¼Â·/_,:;'
    const b = 'aaaaaaaaaacccddeeeeeeeegghiiiiiilmnnnnooooooooprrsssssttuuuuuuuuuwxyyzzz------'
    const p = new RegExp(a.split('').join('|'), 'g')

    return string.toString().toLowerCase()
            .replace(/\s+/g, '-') // Replace spaces with -
            .replace(p, c => b.charAt(a.indexOf(c))) // Replace special characters
            .replace(/&/g, '-and-') // Replace & with 'and'
            .replace(/[^\w\-]+/g, '') // Remove all non-word characters
            .replace(/\-\-+/g, '-') // Replace multiple - with single -
            .replace(/^-+/, '') // Trim - from start of text
            .replace(/-+$/, '') // Trim - from end of text
}

function sendFile(files, that, image_upload_url){
    data = new FormData();
    data.append("file", files);
    data.append("_token", _token);
    $.ajax({
        data: data,
        type: "POST",
        url: image_upload_url,
        cache: false,
        contentType: false,
        processData: false,
        success: function(url){
            that.summernote('insertImage', url)
        }
    });
}

function miniweb_alert(title, message, action, redirect_url)
{
    $.confirm({
        title: title,
        content: message,
        autoClose: 'ok_button1|8000',
        buttons: {
            ok_button1: {
                text: 'OK',
                btnClass: 'btn-success',
                action: function(){
                    if (typeof action !== "undefined" || action != null) { 
                        if(action == 'redraw')
                            dt();
                        else if(action == 'redirect'){
                            if (typeof redirect_url !== "undefined" || redirect_url != null) {
                                window.location.href= redirect_url;
                            }
                        } 
                    }
                }
            }
        }
    });
}

function select_checked_image(el)
{
    if(typeof el.children()[0] != 'undefined')
    {
        var id = el.children()[0].id;
        $.get(base_url+'/media/edit/'+id+'/listing', function(data){
            $('#show-media-item').html(data);
        });

        var popType = $('#media-popupType').val();
        if(popType == 'single_image' || popType == 'set_file_simple')
        {
            $('.imgChked').each(function(i, e) {
                $(this).removeClass('imgChked');
            });
            el.addClass("imgChked");
        }
    }
}

function display_select2()
{
    if($('.webadmin-select2-input').length)
    {
        $( ".webadmin-select2-input" ).each(function( index ) {

            var url = $(this).data('select2-url');
            var placeholder = $(this).data('placeholder');
            var parent = $(this).data('parent');
            if (typeof parent !== typeof undefined && parent !== false)
                parent = $(parent);
            else
                parent = $('body');
            var allowClear = false;
            if (typeof placeholder !== typeof undefined && placeholder !== false)
                allowClear = true;
            if (typeof url !== typeof undefined && url !== false){
	            $(this).select2({
	                placeholder: placeholder,
	                allowClear: allowClear,
	                dropdownParent: parent,
	                ajax: {
	                    url: url,
	                    dataType: 'json',
	                    method: 'get',
	                    processResults: function (data) {
	                        return {
	                            results: data
	                        };
	                    },
	                    cache: true
	                }
	            });
	        }
	        else
	        {
	        	$(this).select2({
	                placeholder: placeholder,
	                allowClear: allowClear,
	                dropdownParent: parent,
	            });
	        }
        });
    }
}


function update_media(obj){

    var progressBar = $('<div/>').addClass('progress').append($('<div/>').addClass('progress-bar')); //progress bar
        $(obj).fileupload({
            url: base_url+"/media/update",
            dataType: 'json',
            formData: {
                "_token": _token,
                'id': $(obj).data('id'),
                "type": $(obj).data('type'),
            },
            add: function (e, data) {
                let holder_div = '#media-change-file-message'+$(obj).data('type');
                data.context = $('<div/>').addClass('col-md-12 media-previe-wrap').prependTo(holder_div);
                $.each(data.files, function (index, file) {
                    var node = $('<p/>').addClass('media-upload-preview');
                    progressBar.clone().appendTo(node);
                    node.appendTo(data.context);
                });
                data.submit();
            },
            progress: function (e, data) {
                var progress = parseInt(data.loaded / data.total * 100, 10);
                if (data.context) {
                    data.context.each(function () {
                        $(this).find('.progress').attr('aria-valuenow', progress).children().first().css('width',progress + '%').text(progress + '%');
                    });
                }
            },
            done: function (e, data) {
                if(data.result.success)
                {
                    var id = data.result.id;
                    var view_html = data.result.view_html;
                    var item_html = data.result.list_html;
                    $('#media-item-edit').html(view_html);

                    if($('#change-media').length)
                        update_media('#change-media');

                    if($('#change-cover-media').length)
                        update_media('#change-cover-media');

                    if($('#media-item-list-'+id).length)
                    {
                        $('#media-item-list-'+id).replaceWith(item_html);
                    }
                }
                else{
                    $.alert('Oops, something wrong happend. Please check your file.');
                    data.context.remove();
                }
            }
        });
}

function file_upload(obj)
{
    var progressBar = $('<div/>').addClass('progress').append($('<div/>').addClass('progress-bar')); //progress bar

    $(obj).fileupload({
        dataType: 'json',
        formData: {
            "_token": _token,
            'id': $(obj).data('id'),
        },
        add: function (e, data) {
            data.context = $('<div/>').addClass('col-md-3 media-previe-wrap').prependTo('#mediaList');
            $.each(data.files, function (index, file) {
                var node = $('<p/>').addClass('media-upload-preview');
                progressBar.clone().appendTo(node);
                node.appendTo(data.context);
            });
            data.submit();
        },
        progress: function (e, data) {
            var progress = parseInt(data.loaded / data.total * 100, 10);
            if (data.context) {
                data.context.each(function () {
                    $(this).find('.progress').attr('aria-valuenow', progress).children().first().css('width',progress + '%').text(progress + '%');
                });
            }
        },
        done: function (e, data) {
            if(data.result.files.length>0)
            {
                $.each(data.result.files, function (index, file) {
                    $(data.context[index]).replaceWith(file);
                });
            }
            else{
                $.alert('Oops, something wrong happend. Please check your file.');
                data.context.remove();
            }
        }
    });
}

jQuery.fn.ForceNumericOnly = function()
        {
            return this.each(function()
            {
                $(this).keypress(function(e)
                {
                    console.log("entered");
                    if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
                        //display error message
                        var errorMessage = '<span class="text-danger">Invalid number!</span>';
                        $(this).next('.text-danger').remove();
                        $(this).after(errorMessage).next('.text-danger').fadeOut("slow");
                               return false;
                    }
                });
            });
        };
        
        jQuery.fn.ForcePriceOnly = function()
        {
            return this.each(function(){
                $(this).keyup(function(){
                    var valid = /^\d{0,6}(\.\d{0,2})?$/.test(this.value),
                    val = this.value;
                    if(!valid){
                        this.value = val.substring(0, val.length - 1);
                        var errorMessage = '<span class="text-danger">Invalid amount!</span>';
                        $(this).next('.text-danger').remove();
                        $(this).after(errorMessage).next('.text-danger').fadeOut("slow");
                               return false;
                    }
                })
            })
        }
        
        jQuery.fn.ForceSlugOnly = function()
        {
            return this.each(function(){
                $(this).keyup(function(){
                    var valid = /^[a-zA-Z0-9-]+$/.test(this.value),
                    val = this.value;
                    if(!valid){
                        this.value = val.substring(0, val.length - 1);
                        var errorMessage = '<span class="text-danger">Invalid slug!</span>';
                        $(this).next('.text-danger').remove();
                        $(this).after(errorMessage).next('.text-danger').fadeOut("slow");
                               return false;
                    }
                })
            })
        }