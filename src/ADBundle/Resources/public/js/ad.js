$(function () {
    var regex = /^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)([a-zA-Z0-9]{8,})$/;
    var textPwd = "Le mot de passe doit comporter au moins 8 caractères et doit comporter au moins un chiffre, une lettre en majuscule et en miniscule";
    var address = {
        "Saint-Mandé": {}
    }
    /* Deleting User */
    $(".btn-remove").click(function (e) {

        var username = $.trim($(this).attr("id"));

        var action = $(this).attr("data-action");
        $(".btn-ok-delete").attr("data-action", action);

        //  console.log(action);
        if (action == "remove-user") {
            var tree = $(this).attr("data-tree");
            var line = $(this).attr("data-line");
            $(".modal-body").html("<h5>Vous voulez vraiment supprimer l'utilisateur (<b>" + username + "</b>) ?</h5>");
            $(".modal-body h5").css("font-size", "16px");

            //    console.log(tree);
            $(".btn-ok-delete").attr("data-tree", tree);
            $(".btn-ok-delete").attr("data-line", line);
            $(".btn-ok-delete").attr("data-username", username);
        } else if (action == "remove-group") {

            var nameGroupe = $(this).attr("data-groupName");
            var dnGroup = $(this).attr("data-dnGroup");
            var dnUser = $(this).attr("data-dnUser");
            var line = $(this).attr("data-line");

            $(".modal-body")
                .html("<h5>Vous voulez vraiment supprimer l'utilisateur (<b>" + username + "</b>) du groupe (<b>" + nameGroupe + "</b>) ?</h5>");
            $(".modal-body h5").css("font-size", "16px");

            $(".btn-ok-delete").attr("data-dnGroup", dnGroup);
            $(".btn-ok-delete").attr("data-dnUser", dnUser);
            $(".btn-ok-delete").attr("data-line", line);
            $(".btn-ok-delete").attr("data-username", username);
            $(".btn-ok-delete").attr("data-groupName", nameGroupe);
        }
        //$.notify("Access granted", "success");

         $('#remove-modal').modal();
        e.preventDefault();
    });

    $(".btn-ok-delete").click(function (e) {
        var action = $(this).attr("data-action");
        var href = null, data = null;
        if (action == "remove-user") {
            href = Routing.generate('ad_remove_user');
            var tree = $.trim($(this).attr("data-tree"));
            var line = $.trim($(this).attr("data-line"));
            var username = $.trim($(this).attr("data-username"));

            data = {
                tree: tree,
                username: username
            };

        } else if (action == "remove-group") {

            href = Routing.generate('ad_remove_user_group');
            var dnUser = $.trim($(this).attr("data-dnUser"));
            var username = $.trim($(this).attr("data-username"));
            var groupName = $.trim($(this).attr("data-groupName"));

            var dnGroup = $.trim($(this).attr("data-dnGroup"));
            var line = $.trim($(this).attr("data-line"));

            data = {
                dnUser: dnUser,
                dnGroup: dnGroup,
                groupName: groupName,
                username: username
            };
            console.log(data);
        }

        if (href != null && data != null) {
            $.getJSON(href, data)
                .done(function (data) {
                    console.log(data.result);
                    $(".text-response-ajax").html(data.message);
                    if (data.result) {
                        $('#' + line).fadeOut(500, function () {
                            $(this).remove();
                        });

                        if ($(".response-ajax").hasClass("hide")) {
                            if (!$(".response-ajax").hasClass("alert-success")) {
                                $(".response-ajax").hasClass("alert-success");
                            }
                            if ($(".response-ajax").hasClass("alert-danger")) {
                                $(".response-ajax").removeClass("alert-danger");
                            }
                            $(".response-ajax").fadeIn(300, function () {
                                $(this).removeClass("hide");
                            });
                        }


                    } else {
                        if ($(".response-ajax").hasClass("hide")) {
                            if ($(".response-ajax").hasClass("alert-success")) {
                                $(".response-ajax").removeClass("alert-success");
                            }
                            if (!$(".response-ajax").hasClass("alert-danger")) {
                                $(".response-ajax").hasClass("alert-danger");
                            }
                            $(".response-ajax").fadeIn(300, function () {
                                $(this).removeClass("hide");
                            });
                        }
                    }
                    $(".response-ajax").delay(35000).fadeOut(1000, function () {
                        $(this).addClass("hide");
                    });
                });
        }

        e.preventDefault();
    });


    /* ADD User */
    $('.name, .firstName').keyup(function () {
        var login;
        if ($(".firstName").val()) {
            login = $.trim($(".firstName").val()).charAt(0);
            if ($('#field-firstName').hasClass('has-error')) {
                $('#field-firstName').removeClass('has-error');
                $("#field-firstName .form-control-feedback").removeClass('glyphicon-warning-sign');
            }

        }
        if ($(".name").val()) {
            login += $.trim($(".name").val());
            if ($('#field-name').hasClass('has-error')) {
                $('#field-name').removeClass('has-error');
                $("#field-name .form-control-feedback").removeClass('glyphicon-warning-sign');
            }
        }


        if ($(".name").val() && $(".firstName").val()) {
            $('.login').val(login.toUpperCase());
            $('.email').val($.trim($(".firstName").val().toLowerCase()) + "." + $.trim($(".name").val().toLowerCase()));
            $("#field-email .form-control-feedback").removeClass('glyphicon-warning-sign');
            $("#field-email .form-control-feedback").addClass('glyphicon-ok');
            if ($('#field-email').hasClass('has-error')) {
                $('#field-email').removeClass('has-error');
            }
            if (!$('#field-email').hasClass('has-success')) {
                $('#field-email').addClass('has-success');
            }
        } else {
            if (!$('#field-email').hasClass('has-error')) {
                $('#field-email').addClass('has-error');
            }
            $("#field-email .form-control-feedback").addClass('glyphicon-warning-sign');

        }

    });


    $('.password').tooltip({
        'trigger': 'focus',
        placement: "bottom",
        'title': textPwd
    });

    /* Password */
    $(".password").keyup(function () {

        if ($('#field-confirm-password').hasClass('has-error')) {
            $('#field-confirm-password').removeClass('has-error');
        }
        $("#field-confirm-password .form-control-feedback").removeClass('glyphicon-warning-sign');

        if (regex.test($.trim($(this).val()))) {
            $("#field-password .form-control-feedback").removeClass('glyphicon-warning-sign');
            $("#field-password .form-control-feedback").addClass('glyphicon-ok');
            if ($('#field-password').hasClass('has-error')) {
                $('#field-password').removeClass('has-error');
            }
            if (!$('#field-password').hasClass('has-success')) {
                $('#field-password').addClass('has-success');
            }
        } else {
            if (!$('#field-password').hasClass('has-error')) {
                $('#field-password').addClass('has-error');
            }
            $("#field-password .form-control-feedback").addClass('glyphicon-warning-sign');
        }
        //console.log($(this).val());
    });


    /* Checking password*/
    $('.confirm-password').keyup(function () {
        var checkPwd = $.trim($('.confirm-password').val());
        console.log(checkPwd);


        if (checkPwd != $.trim($('.password').val()) || !checkPwd || !regex.test(checkPwd)) {
            if ($('#field-confirm-password').hasClass('has-success')) {
                $('#field-confirm-password').removeClass('has-success');
            }
            $('#field-confirm-password').addClass('has-error');
            $("#field-confirm-password .form-control-feedback").addClass('glyphicon-warning-sign');
        } else {
            $("#field-confirm-password .form-control-feedback").removeClass('glyphicon-warning-sign');
            $("#field-confirm-password .form-control-feedback").addClass('glyphicon-ok');
            if ($('#field-confirm-password').hasClass('has-error')) {
                $('#field-confirm-password').removeClass('has-error');
            }
            if (!$('#field-confirm-password').hasClass('has-success')) {
                $('#field-confirm-password').addClass('has-success');
            }
        }
        //console.log($('.confirm-password').val() + "   ---> " + $('.password').val());
    });

    /* Btn Add User  */
    $('.add-user').click(function (e) {

        if (!$('.name').val() || !$('.firstName').val() || !$('.password').val() || !$('.confirm-password').val()) {
            var checkField = ["name", "firstName", 'password', 'confirm-password'];

            $.each(checkField, function (key, value) {
                if (!$.trim($("." + value).val())) {
                    if (!$('#field-' + value).hasClass('has-error')) {
                        $('#field-' + value).addClass('has-error');
                        $("#field-" + value + " .form-control-feedback").addClass('glyphicon-warning-sign');
                    }
                }
            });
            if ($("#field-message-error").hasClass("hide")) {
                $("#field-message-error").removeClass("hide")
            }
            $('.message-error').html('Veuillez remplir tous les champs obligatoires.');
            e.preventDefault();
        } else if (!regex.test($.trim($('.password').val()))) {
            $('.message-error').html(textPwd);

            if (!$('#field-password').hasClass('has-error')) {
                $('#field-password').addClass('has-error');
            }
            $("#field-password .form-control-feedback").addClass('glyphicon-warning-sign');

            if ($("#field-message-error").hasClass("hide")) {
                $("#field-message-error").removeClass("hide")
            }
            e.preventDefault();
        } else if ($.trim($('.confirm-password').val()) != $.trim($('.password').val())) {
            e.preventDefault();

            if (!$('#field-confirm-password').hasClass('has-error')) {
                $('#field-confirm-password').addClass('has-error');
            }
            if ($("#field-message-error").hasClass("hide")) {
                $("#field-message-error").removeClass("hide")
            }
            $('.message-error').html('Veuillez confirmer votre nouveau mot de passe.');
        }

        //console.log(!(/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)([a-zA-Z0-9]{6,})$/).test($.trim($('.password').val())));
    });


    $('.password').keyup(function () {

        if (regex.test($.trim($('.password').val()))) {
            $("#field-password .form-control-feedback").removeClass('glyphicon-warning-sign');
            $("#field-password .form-control-feedback").addClass('glyphicon-ok');
            if ($('#field-password').hasClass('has-error')) {
                $('#field-password').removeClass('has-error');
            }
            if (!$('#field-password').hasClass('has-success')) {
                $('#field-password').addClass('has-success');
            }
        } else {
            $("#field-password .form-control-feedback").removeClass('glyphicon-ok');
            $("#field-password .form-control-feedback").addClass('glyphicon-warning-sign');
            if (!$('#field-password').hasClass('has-error')) {
                $('#field-password').addClass('has-error');
            }

            if ($('#field-password').hasClass('has-success')) {
                $('#field-password').removeClass('has-success');
            }
        }
    });


    //
    //$('.old-password').keyup(function () {
    //    if ($(this).val()) {
    //        console.log($(this).val());
    //        if ($('#champs-old-pwd').hasClass('has-error')) {
    //            $('#champs-old-pwd').removeClass('has-error');
    //        }
    //    }
    //});

    $(".btn-user-info").click(function (e) {
        e.preventDefault();
        var user = $(this).attr("data-user");
        var data = {"user": user};
        var href = Routing.generate('ad_get_user');
        console.log(href);
        if (user) {
            $.getJSON(href, data)
                .done(function (data) {
                    console.log(data.user);
                    if (data.result) {
                        var user = data.user
                        $('#fullName').val(user.fullname);
                        $('#login-user').val(user.login);
                        // $('#service').val(user.service);
                        $('#mail').val(user.username);
                        $('#address').val(user.address);
                        $('#ville-postalCode').val(user.villePostalCode);
                        $('#phone').val(user.tel);
                        $('#office').val(user.office);
                    }

                });
        }
        console.log(user);
    });
    $("#save-edit").click(function (e) {
        var dnGroupNotSelect = "";
        $('#select-group option:not(:selected)').each(function (i, selected) {
            dnGroupNotSelect += "#DnGroup:" + $(selected).val();
        });
        $("#user_edit_groupNotSelect").val(dnGroupNotSelect);

        if (!$('.name').val() || !$('.firstName').val()) {
            var checkField = ["name", "firstName"];

            $.each(checkField, function (key, value) {
                if (!$.trim($("." + value).val())) {
                    if (!$('#field-' + value).hasClass('has-error')) {
                        $('#field-' + value).addClass('has-error');
                        $("#field-" + value + " .form-control-feedback").addClass('glyphicon-warning-sign');
                    }
                }
            });
            if ($("#field-message-error").hasClass("hide")) {
                $("#field-message-error").removeClass("hide")
            }
            $('.message-error').html('Veuillez remplir tous les champs obligatoires.');
            e.preventDefault();
        } else if ($('.password').val()) {
            if (!regex.test($.trim($('.password').val()))) {
                $('.message-error').html(textPwd);

                if (!$('#field-password').hasClass('has-error')) {
                    $('#field-password').addClass('has-error');
                }
                $("#field-password .form-control-feedback").addClass('glyphicon-warning-sign');

                if ($("#field-message-error").hasClass("hide")) {
                    $("#field-message-error").removeClass("hide")
                }
            } else if ($.trim($('.confirm-password').val()) != $.trim($('.password').val())) {
                e.preventDefault();

                if (!$('#field-confirm-password').hasClass('has-error')) {
                    $('#field-confirm-password').addClass('has-error');
                }
                if ($("#field-message-error").hasClass("hide")) {
                    $("#field-message-error").removeClass("hide")
                }
                $('.message-error').html('Veuillez confirmer votre nouveau mot de passe.');
            }
        }
    });


    $('#btn-search').click(function () {
        var search = $("#input-search").val();
        var href = Routing.generate('ad_search', {"person": search});

        alert(href);

    });


    function displayError(idBlock, message) {
        if (!$('#' + idBlock).hasClass('has-error')) {
            $('#' + idBlock).addClass('has-error');
        }
        if (message == null) {
            if ($("#field-message-error").hasClass("hide")) {
                $("#field-message-error").removeClass("hide")
            }
            $('.message-error').html('Veuillez confirmer votre nouveau mot de passe.');
        }
        
    }

});
