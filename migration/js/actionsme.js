var Go = {
    aceptar: function(id, field) {
        $("#reescribir" + id).html('<img src="application/modules/migration/images/ajax-loader-gm.gif">');
        $.post("migration/aceptar/" + id, {
            csrf_token_name: Config.CSRF
        }, function(data) {
            UI.alert(data);
            if (data == 'La solicitud fue aceptada correctamente' || data == 'Fue atendido por otro GM' || data == 'Acceso Restringido') {
                $("#reescribir" + id).text("Gracias!")
            }
        })
    },
    rechazar: function(id, field) {
        $("#reescribir" + id).html('<img src="application/modules/migration/images/ajax-loader-gm.gif">');
        var html = '<textarea id="answer_message" style="width:90%" maxlength="25" placeholder="Razon Opcional"></textarea>';
        UI.confirm(html, "Rechazar", function() {
            var message = $("#answer_message").val();
            $.post("migration/rechazar/" + id, {
                csrf_token_name: Config.CSRF,
                reason: message
            }, function(data) {
                UI.alert(data);
                if (data == 'La solicitud fue rechazada correctamente' || data == 'Fue atendido por otro GM' || data == 'Acceso Restringido') {
                    $("#reescribir" + id).text("Gracias!")
                }
            })
        })
    },
    resend: function(id, field) {
        $("#reescribir" + id).html('<p align="center"><img src="application/modules/migration/images/ajax-loader-gm.gif"></p>');
        $.post("migration/resend/" + id, {
            csrf_token_name: Config.CSRF
        }, function(data) {
            UI.alert(data);
            if (data == 'El personaje no existe' || data == 'No se pueden re-enviar items aun!' || data == 'Los items fueron re-enviados' || data == 'Acceso Restringido' || data == 'Lo siento esta persona ya habia recibido los items') {
                $("#reescribir" + id).text("Gracias!")
            }
        })
    },
    delete: function(id, field) {
        $("#reescribir" + id).html('<img src="application/modules/migration/images/ajax-loader-gm.gif">');
        $.post("migration/delete/" + id, {
            csrf_token_name: Config.CSRF
        }, function(data) {
            UI.alert(data);
            if (data == 'Un GM ya atendio esta solicitud!' || data == 'Gracias por el click!' || data == 'Acceso Restringido') {
                $("#reescribir" + id).text("Gracias!")
            }
        })
    }
}