
$.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });
$(".select-toggle").on("click", function(){ $("input[type=checkbox]").prop('checked', $(this).prop('checked')); });
$(document).ajaxStart($.blockUI).ajaxStop($.unblockUI);

function appAlert(message, status, location) {
    var container = location;
    var place = "prepend";
    status = (status !== 'undefined') ? status : 'danger';
    App.alert({
        type: status,
        icon: status,
        message: message,
        container: container,
        place: place
    });
}
function validation_check(data) {
    $(".form-group.has-error").children(".help-block").remove();
    $(".form-group").removeClass("has-error");
    if (data.validation) {
        appAlert("Validation error found", "danger");
        var errors = data.errors;
        for (var field in errors) {
            var group = $(".has-error-" + field);
            if (group.length) {
                if (group.children(".help-block").length) {
                    group.children(".help-block").remove();
                }
                if (!group.hasClass("has-error")) {
                    group.addClass("has-error");
                }
                group.append("<span class='help-block'><strong>" + errors[field][0] + "</strong></span>");
            }
        }
    }
}

$("#ajax-form, .ajax-form").on("submit", function (e) {
    e.preventDefault();
    var form_data = new FormData(this);
    var action = $(this).children('input[name="action"]').val();
    var method = $(this).children('input[name="method"]').val();
    var response_type = "form_response";
    NProgress.start();
    $.ajax({
        xhr: function () {
            var xhr = new window.XMLHttpRequest();
            xhr.upload.addEventListener("progress", function (evt) {
                if (evt.lengthComputable) {
                    var percentComplete = parseInt(evt.loaded / evt.total);
                    NProgress.set(percentComplete);
                }
            }, false);
            return xhr;
        },
        url: action,
        type: method,
        data: form_data,
        cache: false,
        contentType: false,
        processData: false
    }).done(function (response) {
        if (response.success) {
            $(".has-error").children(".help-block").remove();
            $(".has-error").removeClass("has-error");
            appAlert(response.message, 'success');
            if (response.data !== undefined) {
                if (response.data.redirect_to !== undefined) {
                    window.location = response.data.redirect_to;
                }
            }
        } else if(response.validation) {
            validation_check(response);
        }else {
            appAlert(response.message, 'danger');
        }
    }).fail(function (response) {
        appAlert(response.message, "danger");
    }).always(function () {
        NProgress.done();
    });
});

$(".select_dropdown").on("change", function (e) {
    e.preventDefault();
    var current = $(this);
    var target = $(this).data('target');
    $.ajax({
        url : current.data('action'),
        method : 'post',
        data : {
            'id' : current.val()
        }
    }).done(function (response) {
        var option_text = $(target + " option:first-child").html();
        html = '<option value="">' + option_text + '</option>';
        $.each(response.data, function( index, value ) {
            html += '<option value="' +value.id+ '">' +value.name+ '</option>'
        });
        $(target).html(html);
    }).fail(function (response) {
        appAlert(response.message, "danger", ".app-alert");
    });
});

$(".submit-note").on('click', function(){
    var ref_id = $("#change_status").children("input[name=ref_id]").val();
    var note_message = $(".note-message").val();
    var update_url = $("#change_status").children("input[name=status_update_url]").val();
    var message_requirement = window.change_status_message_requirement;
    $.ajax({
        url: update_url,
        method: "POST",
        data: {
            id     : ref_id,
            note   : note_message,
            status : window.change_status,
            message_requirement : window.change_status_message_requirement
        },
    }).done(function(response){
        if(response.success) {
            if (response.data.current) {
                var html = "<i class='" + response.data.current.icon_class + "'></i><span class='hidden-xs'> " + response.data.current.title + " </span>";
                html += (Object.keys(response.data.next).length) ? " <i class='fa fa-angle-down'></i>" : "";
                $("#change_status > a").removeClass().addClass("btn " + response.data.current.color).html(html);
            }
            if (response.data.next) {
                var options_html = "";
                $.each(response.data.next, function (index, option) {
                    options_html += "<li><a href='javascript:;' class='tool-action' data-value='"+index+"' data-message-required='"+option.message_required+"'><i class='"+option.icon_class+"'></i> "+option.title+"</a></li>";
                });
                $("#change_status > ul").html(options_html);
            }
            if (response.data.redirect_to) {
                location.href = response.data.redirect_to;
            }
            appAlert(response.message, "success", ".app-alert");
        }else {
            appAlert(response.message, "danger", ".app-alert");
        }

    }).fail(function(response){
        $("div.alert.alert-danger").removeClass('display-hide');
        appAlert(response.message, "danger", ".app-alert");
    });
});

$('#change_status').on('click', '.tool-action', function(){
    window.change_status = $(this).data("value");
    window.change_status_message_requirement = $(this).data("message-required");
    $('#confirm-status-modal').modal('show');

});

$("#confirm-status-modal-confirm-btn").on('click', function(){
    $(".btn-note").click();
});

$("#changeLanguage").on("click", function () {
    var id = $(this).data('id');
    $.ajax({
        xhr: function () {
            var xhr = new window.XMLHttpRequest();
            xhr.upload.addEventListener("progress", function (evt) {
                if (evt.lengthComputable) {
                    var percentComplete = parseInt(evt.loaded / evt.total);
                    NProgress.set(percentComplete);
                }
            }, false);
            return xhr;
        },
        url: '/lang' ,
        type: 'post',
        data: {
            'lang': id
        }
    }).done(function(response){
        var current_link = window.location.href;
        window.location = current_link;
    }).fail(function(){
        console.log("Redirecting error please fix the issue");
    }).always(function(){
        var current_link = window.location.href.split('#')[0];
      // window.location = current_link;
    });
});

$('.chart_button').on('click', function(){
    var toggle_div = '#'+$(this).data("manipulate-div");
    console.log(toggle_div);
    $(toggle_div).toggleClass('hide');
});


/* This function helps to generate temporary certificates.
 *
 * First the temporary certificates are generated in "certificate_tmp" folder in "storage".
 * After the certificates are generated successfully, the user sees the newly created certificate in a new window.
 */
$(document).on('click', '.preview-certificate-btn', function(e){
    var selector        = $(this);
    var file_exists     = selector.data('file-exists');
    var file_path       = selector.data('file-path');
    var certificate_id  = selector.data('certificate-id');
    var file_lang       = selector.data('certificate-lang');
    var tmp_route       = selector.data('generate-tmp-certificate-url');

    if(file_exists){
        window.open(file_path, '_blank');
    }
    else{
        $.ajax({
            xhr: function () {
                var xhr = new window.XMLHttpRequest();
                xhr.upload.addEventListener("progress", function (evt) {
                    if (evt.lengthComputable) {
                        var percentComplete = parseInt(evt.loaded / evt.total);
                        NProgress.set(percentComplete);
                    }
                }, false);
                return xhr;
            },
            url: tmp_route,
            type: 'POST',
            data: {
                'certificate_id': certificate_id,
            }
        }).done(function(response){
            if(response.certificate_data != null){
                var new_certificate_temp_exists = response.certificate_data[file_lang+'_exists'];
                $('.preview-certificate-btn').data('file-exists', new_certificate_temp_exists);
                if(new_certificate_temp_exists){
                    window.open(response.certificate_data[file_lang+'_path'], '_blank');
                }
            }
        }).fail(function(){
            //console.log("Redirecting error please fix the issue");
        }).always(function(){
            //var current_link = window.location.href.split('#')[0];
        });
    }
});
