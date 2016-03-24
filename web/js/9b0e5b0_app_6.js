$(function () {
    console.log("document ready*");
    $(".btn-remove-user").click(function (e) {
        var username = $.trim($(this).attr("id"));
        $("#username").html(username);
        var tree = $(this).attr("data-tree");
        var line = $(this).attr("data-line");
        console.log(tree);
        $(".ok-delete-user").attr("data-tree", tree);
        $(".ok-delete-user").attr("data-line", line);
        $(".ok-delete-user").attr("data-username", username);
        $('#myModal').modal();
        e.preventDefault();
    });

    $(".ok-delete-user").click(function (e) {
        var href = Routing.generate('ad_remove_user');
        var tree = $.trim($(this).attr("data-tree"));
        var line = $.trim($(this).attr("data-line"));
        var username = $.trim($(this).attr("data-username"));

        var data = {
            tree: tree,
            username: username
        };

        console.log(href + " " + tree);
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
            })
            /*.fail(function (jqxhr, textStatus, error) {
             var err = textStatus + ", " + error;
             console.log("Request Failed: " + err);
             })*/;

        e.preventDefault();
    });


    $('.name, .firstName').keyup(function () {
        var login;
        if ($(".name").val())
            login = $(".name").val().charAt(0);
        if ($(".firstName").val())
            login += $(".firstName").val();

        console.log(login);
        $('.login').val(login);
    });
    
    $(".password").keyup(function(){
    
        
    });
    
    
    $('#save-edit').click(function (e) {
        e.preventDefault();
        
        if ($.trim($('.confirm-pwd').val()) != $.trim($('.password').val))
        {
            console.log("Veuillez confirmer le nouveau mot de passe");
        }
    });
    
    
    
    

});
