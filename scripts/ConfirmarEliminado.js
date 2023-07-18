
var urlControllerEliminaciones = urlBase + "php/controller/ControllerEliminaciones.php";

function eliminar(id, tabla, campoId, titulo, proyecto) {
    // Estado eliminado 13
	if ($.isNumeric(id)) {
        Swal.fire({
			position: "top",
			icon: "question",
			title: `<strong>Eliminar ${titulo}</strong>`,
			html: `<h5>Esta seguro de eliminar el registro?</h5><br><p class="negrita">${proyecto}</p>`,
			showCloseButton: true,
			showCancelButton: true,
			confirmButtonText: "Si, eliminar",
			confirmButtonColor: "#df382c",
			cancelButtonText: "No, cancelar",
			cancelButtonColor: "#95a5a6",
			backdrop: true,
            allowEscapeKey: false,
            allowOutsideClick: false,
			input: "text",
			inputLabel: "Motivo por el cual realiza eliminación",
			inputPlaceholder: "Ingrese motivo",
			inputValidator: (value) => {
				if (!value) {
					return "Por favor indica el motivo!";
				}
			},
			// width: 600
		}).then((res) => {
			if ( res.isConfirmed && !isEmpty(res.value) ) {
				$.ajax({
					data: {
                        peticion: "eliminar",
                        id: id,
                        motivo: res.value,
                        tabla: tabla,
                        campoId: campoId
                    },
					dataType: "json",
					url: urlControllerEliminaciones,
					type: "POST",
					beforeSend: function () {
						//code
						$("#overlayText").text("Eliminando Registro...");
						$(".overlayCargue").fadeIn("slow");
					},
					success: function (result) {
						var estado = result.status;
						switch (estado) {
							case "1":
                                showRegistros();
                                Swal.fire({
                                    position: 'top',
                                    icon: 'success',
                                    title: '<strong>Eliminado</strong>',
                                    html: '<h5>Se elimino el registro satisfactoriamente.</h5>',
                                    showCloseButton: true,
                                    showCancelButton: true,
                                    showConfirmButton: false,
                                    // confirmButtonText: 'Si',
                                    // confirmButtonColor: "#11386f",
                                    cancelButtonText: 'Cerrar',
                                    cancelButtonColor: "#95a5a6",
                                    backdrop: true
                                    // width: 600
                                })
								break;
						}
					},
					complete: function () {
						$(".overlayCargue").fadeOut("slow");
					},
					error: function (xhr) {
						console.log(xhr);
					},
				});
			}
		});
	}
}