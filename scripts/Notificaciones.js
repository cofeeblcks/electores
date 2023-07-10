/**
 * @author Hadik Chavez (ChivoDev) -  CofeeBlcks
 * @email cofeeblcks@gmail.com, chavezhadik@gmail.com
 * @create date 2023-04-17 13:20:42
 * @modify date 2023-04-20 08:31:59
 * @desc Sistema de notifiicar por correo electronico la informacion
 * @desc los tipos de notificaciones son:
 * 1 - Facturacion
 */

var urlControllerNotificaciones = urlBase + "php/controller/ControllerNotificaciones.php";

function informacionNotificacion(idTabla, tipoNotificacion) {
    if( $.isNumeric(idTabla) && $.isNumeric(tipoNotificacion) ){
        $.ajax({
			data: { 
                peticion: "informacionNotificacion",
                id: idTabla,
                tipo: tipoNotificacion
            },
			dataType: "json", //Si no se especifica jQuery automaticamente encontrará el tipo basado en el header del archivo llamado (pero toma mas tiempo en cargar, asi que especificalo)
			url: urlControllerNotificaciones, //url a donde hacemos la peticion
			type: "POST",
			beforeSend: function () {
				$("#overlayText").text("Consultando Información de envio...");
				$(".overlayCargue").fadeIn("slow");
			},
			success: function (result) {
				var estado = result.status;
				switch (estado) {
					case 0:
						//--------------------------------
						showToast("Error!", "Error de sistema, consulte al administrador del sistema.", "error", "derecha", 5000);
						break;

					case 1:
						//--------------------------------
                        Swal.fire({
                            position: "top",
                            iconHtml: '<i class="fa-solid fa-envelopes-bulk"></i>',
                            customClass: {
                                icon: 'swal2-icon-custom'
                            },
                            title: `Información de envio ${result.titulo}`,
                            html: `${result.informacion}`,
                            showCloseButton: false,
                            showConfirmButton: true,
                            showCancelButton: true,
                            confirmButtonText: "Si, notificar",
                            confirmButtonColor: "#16a085",
                            cancelButtonText: "Cancelar",
                            cancelButtonColor: "#df382c",
                            backdrop: true,
                            allowOutsideClick: false,
                            allowEscapeKey: false,
                            width: 700
                        }).then((res) => {
                            if (res.isConfirmed) {
                                $.ajax({
                                    data: { 
                                        peticion: "enviarNotificacion",
                                        id: idTabla,
                                        tipo: tipoNotificacion
                                    },
                                    dataType: "json", //Si no se especifica jQuery automaticamente encontrará el tipo basado en el header del archivo llamado (pero toma mas tiempo en cargar, asi que especificalo)
                                    url: urlControllerNotificaciones, //url a donde hacemos la peticion
                                    type: "POST",
                                    beforeSend: function () {
                                        $("#overlayText").text("Enviando notificación...");
                                        $(".overlayCargue").fadeIn("slow");
                                    },
                                    success: function (response) {
                                        var estado = response.status;
                                        switch (estado) {
                                            case 0:
                                                //--------------------------------
                                                showToast("Error!", "Error de sistema, consulte al administrador del sistema.", "error", "derecha", 5000);
                                                break;
                        
                                            case 1:
                                                //--------------------------------
                                                Swal.fire({
                                                    position: "top",
                                                    icon: "success",
                                                    title: `Información de envio ${result.titulo}`,
                                                    html: `<div><p>Se ha enviado satisfatoriamente la factura al correo del cliente.</p><br>${result.informacion}</div>`,
                                                    showCloseButton: false,
                                                    showConfirmButton: false,
                                                    showCancelButton: true,
                                                    // confirmButtonText: "Si, notificar",
                                                    // confirmButtonColor: "#16a085",
                                                    cancelButtonText: "Cerrar",
                                                    cancelButtonColor: "#95a5a6",
                                                    backdrop: true,
                                                    allowOutsideClick: false,
                                                    allowEscapeKey: false
                                                });
                                                break;
                                        }
                                    },
                                    complete: function () {
                                        //--------------------------------
                                        $(".overlayCargue").fadeOut("slow");
                                    },
                                    error: function (xhr) {
                                        console.log(xhr);
                                    },
                                });
                            }
                        });
						break;
				}
			},
			complete: function () {
				//--------------------------------
				$(".overlayCargue").fadeOut("slow");
			},
			error: function (xhr) {
				console.log(xhr);
			},
		});
    }
}