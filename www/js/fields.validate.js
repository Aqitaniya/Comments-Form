$(function(){
    var send_form = false;
    $.validator.addMethod("existfile", function (value, event) {
        var status;
        if (value != '') {
            $.ajax({
                type: "post",
                url: 'requireClasses.php',
                cache: false,
                async: false,
                dataType: "text",
                data: {fileExist: value},
                success: function (data) {
                    if (data == 1)
                        status = false;
                    else status = true;
                }
            });
        } else status = true;
        return status;
    }, '');

    $("#comment-form").validate({
        rules: {
            email: {
                required: true,
                email: true
            },
            fullname: {
                required: true,
                minlength: 6
            },
            phone: {
                required: true,
                minlength: 3,
                pattern: '[+]?[0-9]+$'
            },
            comment: {
                required: true,
            },
            filename: {
                existfile: true,
            },
        },
        messages: {
            fullName: {
                required: "Please enter your fullname.",
                minlength: "Your Full Name must consist of at least 6 character.",
            },
            email: "Please enter a valid email address",
            phone: {
                required: "Please enter the phone in the format +XXXXXXXXXXXX.",
                minlength: "Your phone must consist of at least 3 character.",
                pattern: "Please enter your phone in the correct format."
            },
            comment: "Please enter your comment.",
            filename: {
                existfile: "File with this name has already been loaded."
            },
        },

        submitHandler: function (form) {
            if(!send_form) {
                send_form=true;
                $(form).ajaxSubmit({
                    url: 'requireClasses.php',
                    dataType: "json",
                    success: function (data){
                        send_form = false;
                        if (data.status) {
                            $(".comments").prepend(data.html);
                            $(form).resetForm();
                        }
                        else {
                            $.each(data.errors, function (k, v) {
                                var id = "input" + k.charAt(0).toUpperCase() + k.substr(1).toLowerCase();
                                var id_err = id + "-error";
                                $("#" + id).after('<label id="' + id_err + '" class="error" for="' + id + '">' + v + '</label>');
                            });
                        }
                    }
                });
            }
        }

    });
});

