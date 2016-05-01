$(function () {
    var regex = /^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)([a-zA-Z0-9]{8,})$/;
    var textPwd = "Le mot de passe doit comporter au moins 8 caractères et doit comporter au moins un chiffre, une lettre en majuscule et en miniscule";
    
    $('.password').tooltip({
        'trigger': 'focus',
        placement: "bottom",
        'title': textPwd
    });
    $('[data-toggle="tooltip"]').tooltip(); 

    $(".password").keyup(function () {

        var pwd = $.trim($(this).val());
        if ((/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)([a-zA-Z0-9]{6,})$/).test(pwd)) {
            console.log("yes " + pwd);
        } else {
            console.log("No " + pwd);
        }

    });

    $('#save-edit-pwd').click(function (e) {

        var response = grecaptcha.getResponse();
        if (!$.trim($('.old-password').val())) {
            if (!$('#champs-old-pwd').hasClass('has-error')) {
                $('#champs-old-pwd').addClass('has-error');
            }
            if ($("#field-message-error").hasClass("hide")) {
                $("#field-message-error").removeClass("hide")
            }
            $('.message-error').html('Veuillez rentrer le mot de passe actuel.');
        } else if (!regex.test($.trim($('.password').val()))) {
            $('.message-error').html(textPwd);

            if (!$('#champs-new-pwd').hasClass('has-error')) {
                $('#champs-new-pwd').addClass('has-error');
            }
            $("#champs-new-pwd .form-control-feedback").addClass('glyphicon-warning-sign');

            if ($("#field-message-error").hasClass("hide")) {
                $("#field-message-error").removeClass("hide")
            }
            e.preventDefault();
        } else if ($.trim($('.confirm-pwd').val()) != $.trim($('.password').val())) {
            e.preventDefault();

            if (!$('#champs-check-pwd').hasClass('has-error')) {
                $('#champs-check-pwd').addClass('has-error');
            }
            if ($("#field-message-error").hasClass("hide")) {
                $("#field-message-error").removeClass("hide")
            }
            $('.message-error').html('Veuillez confirmer votre nouveau mot de passe.');
        } else if (response.length == 0) {
            e.preventDefault();
            $('#recaptcha-error').show();
            if ($("#field-message-error").hasClass("hide")) {
                $("#field-message-error").removeClass("hide")
            }
            $('.message-error').html('Veuillez cocher la case "Je ne suis pas un robot" .');
        }
    });


    $('.password').keyup(function () {
        if ($('#champs-check-pwd').hasClass('has-error')) {
            $('#champs-check-pwd').removeClass('has-error');
        }

        if (regex.test($.trim($('.password').val()))) {
            $("#champs-new-pwd .form-control-feedback").removeClass('glyphicon-warning-sign');
            $("#champs-new-pwd .form-control-feedback").addClass('glyphicon-ok');
            if ($('#champs-new-pwd').hasClass('has-error')) {
                $('#champs-new-pwd').removeClass('has-error');
            }
            if (!$('#champs-new-pwd').hasClass('has-success')) {
                $('#champs-new-pwd').addClass('has-success');
            }
        } else {
            $("#champs-new-pwd .form-control-feedback").removeClass('glyphicon-ok');
            $("#champs-new-pwd .form-control-feedback").addClass('glyphicon-warning-sign');
            if (!$('#champs-new-pwd').hasClass('has-error')) {
                $('#champs-new-pwd').addClass('has-error');
            }

            if ($('#champs-new-pwd').hasClass('has-success')) {
                $('#champs-new-pwd').removeClass('has-success');
            }
        }
    });

    $('.confirm-pwd').keyup(function () {
        if ($.trim($('.confirm-pwd').val()) != $.trim($('.password').val()) || !$.trim($('.confirm-pwd').val()) || !regex.test($.trim($('.password').val()))) {
            if ($('#champs-check-pwd').hasClass('has-success')) {
                $('#champs-check-pwd').removeClass('has-success');
            }
            $('#champs-check-pwd').addClass('has-error');
            $("#champs-check-pwd .form-control-feedback").addClass('glyphicon-warning-sign');
        } else {
            $("#champs-check-pwd .form-control-feedback").removeClass('glyphicon-warning-sign');
            $("#champs-check-pwd .form-control-feedback").addClass('glyphicon-ok');
            if ($('#champs-check-pwd').hasClass('has-error')) {
                $('#champs-check-pwd').removeClass('has-error');
            }
            if (!$('#champs-check-pwd').hasClass('has-success')) {
                $('#champs-check-pwd').addClass('has-success');
            }
        }
    });


    $('.old-password').keyup(function () {
        if ($(this).val()) {
            console.log($(this).val());
            if ($('#champs-old-pwd').hasClass('has-error')) {
                $('#champs-old-pwd').removeClass('has-error');
            }
        }
    });
    
});
