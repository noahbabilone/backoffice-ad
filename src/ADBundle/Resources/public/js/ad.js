$(function () {
    console.log("Ad-js");
    //var regex = /^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)([a-zA-Z0-9]{8,})$/;
    var regex = /^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)([-+!*$@%_\w]{8,})$/;

    var regexPhone = /^0[1-9]([0-9]){8}$/;
    var textPwd = "Le mot de passe doit comporter au moins 8 caractères et doit comporter au moins un chiffre ou des " +
        "caractères spéciaux (-+!*$@%_), une lettre en majuscule et en miniscule";
    $(".service").change(function () {
        console.log($(this).val());
        var address = "";
        var service = $(this).val().substr(0, 4);
        if (service == "Sain") {
            address = {
                "address": "Bis, 2 Avenue Foch",
                "postalCode": "94160",
                "city": "Saint-Mandé",
                "country": "France",
            };
        } else if (service == "Issy") {
            address =
            {
                "address": "16 Rue Jean Jacques Rousseau",
                "postalCode": "92130",
                "city": "Issy-les-Moulineaux",
                "country": "France",
            };
        } else if (service == "Luxe") {
            address = {
                "address": "4 Avenue de la Gare",
                "postalCode": "1610",
                "city": "Luxembourg",
                "country": "Luxembourg",
            }
        }
        console.log(address);
        $(".address").val(address.address);
        $(".postalCode").val(address.postalCode);
        $(".city").val(address.city);
        $(".country").val(address.country);

    });


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

                        $.notify({
                            // options
                            icon: 'glyphicon glyphicon-ok',
                            message: data.message,
                        }, {
                            type: "success",
                        });

                    } else {
                        $.notify({
                            // options
                            icon: 'glyphicon glyphicon-warning-sign',
                            title: 'Error: ',
                            message: 'Une erreur s\'est produit lors de la suppression de l\'utilisateur',
                        }, {
                            type: "danger",

                        });

                    }
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

        $('#field-confirm-password').removeClass('has-error');
        console.log($(this).val());
        $("#field-password .form-control-feedback").removeClass('fa-unlock-alt');
        $("#field-confirm-password .form-control-feedback").removeClass('fa-warning');
        $("#field-confirm-password .form-control-feedback").removeClass('fa-check');
        $("#field-confirm-password .form-control-feedback").addClass('fa-unlock-alt');
        $("#field-confirm-password").removeClass('has-error');
        $("#field-confirm-password").removeClass('has-success');
        $(".confirm-password").val("");


        if (regex.test($.trim($(this).val()))) {
            hasSuccess("field-password");
        } else {
            hasError("field-password");
        }
        //console.log($(this).val());
    });


    /* Checking password*/
    $('.confirm-password').keyup(function () {
        var checkPwd = $.trim($('.confirm-password').val());
        console.log(checkPwd);
        $("#field-confirm-password .form-control-feedback").removeClass('fa-unlock-alt');
        if (checkPwd != $.trim($('.password').val()) || !checkPwd || !regex.test(checkPwd)) {
            hasError("field-confirm-password");
        } else {
            hasSuccess("field-confirm-password");
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
                        $("#field-" + value + " .form-control-feedback");
                    }
                }
            });
            $("#field-message-error").removeClass("hide")
            $('.message-error').html('Veuillez remplir tous les champs obligatoires.');
            e.preventDefault();
        } else if (!regex.test($.trim($('.password').val()))) {
            $('.message-error').html(textPwd);
            $('#field-password').addClass('has-error');
            $("#field-message-error").removeClass("hide")
            e.preventDefault();
        } else if ($.trim($('.confirm-password').val()) != $.trim($('.password').val())) {

            $('#field-confirm-password').addClass('has-error');
            $("#field-message-error").removeClass("hide")
            $('.message-error').html('Veuillez confirmer votre nouveau mot de passe.');
            e.preventDefault();

        } else if (!regexPhone.test($('.phone').val()) && $('.phone').val()) {
            e.preventDefault();
            $("#field-message-error").removeClass("hide")
            $('.message-error').html("Numero de téléphone fix invalide");

        }else if (!regexPhone.test($('.mobile').val()) && $('.mobile').val()) {
            e.preventDefault();
            $("#field-message-error").removeClass("hide")
            $('.message-error').html("Numero de téléphone mobile invalide");

        }
        //console.log(!(/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)([a-zA-Z0-9]{6,})$/).test($.trim($('.password').val())));
    });

    $('.old-password').keyup(function () {
        if ($(this).val()) {
            console.log($(this).val());
            if ($('#champs-old-pwd').hasClass('has-error')) {
                $('#champs-old-pwd').removeClass('has-error');
            }
        }
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
            $("#field-message-error").removeClass("hide")
            $('.message-error').html('Veuillez remplir tous les champs obligatoires.');
            e.preventDefault();
        } else if ($('.password').val()) {

            if (!regex.test($.trim($('.password').val()))) {
                $('.message-error').html(textPwd);
                $('#field-password').addClass('has-error');
                $("#field-message-error").removeClass("hide");
                e.preventDefault();
            } else if ($.trim($('.confirm-password').val()) != $.trim($('.password').val())) {
                e.preventDefault();
                $('#field-confirm-password').addClass('has-error');
                $("#field-message-error").removeClass("hide")
                $('.message-error').html('Veuillez confirmer votre mot de passe.');
                e.preventDefault();
            }
        } else if (!regexPhone.test($('.phone').val()) && $('.phone').val()) {
            e.preventDefault();
            $("#field-message-error").removeClass("hide")
            $('.message-error').html("Numero de téléphone invalide");
        } else if (!regexPhone.test($('.mobile').val()) && $('.mobile').val()) {
            e.preventDefault();
            $("#field-message-error").removeClass("hide")
            $('.message-error').html("Numero de téléphone mobile invalide");
        }
        // e.preventDefault();

    });


    $('#btn-search').click(function () {
        var search = $("#input-search").val();
        var href = Routing.generate('ad_search', {"person": search});

        alert(href);

    });


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
                        $('#service').val(user.service);
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


    function hasSuccess(idField) {
        $("#" + idField + " .form-control-feedback").removeClass('fa-warning');
        $("#" + idField + " .form-control-feedback").addClass('fa-check');
        $("#" + idField).removeClass('has-error');
        $("#" + idField).addClass('has-success');
    }

    function hasError(idField) {
        $("#" + idField).removeClass('has-success');
        $("#" + idField).addClass('has-error');
        $("#" + idField + " .form-control-feedback").removeClass('fa-check');
        $("#" + idField + " .form-control-feedback").addClass('fa-warning')

    }

});
