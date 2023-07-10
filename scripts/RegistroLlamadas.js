function modelRegistroLlamada(idTabla, nombre, tabla) {
	if ($.isNumeric(idTabla)) {
        var titulo = `Registrar llamada de <strong>${nombre}</strong>`;
		var htmlForm = `<form id="frmRegistro" class="container">
            <div class="row recuadro mb-10 pt-10">
                <div class="col-xs-12 col-md-12 col-lg-12">
                    <a class="tooltips">
                        <label class="negrita" for="selectEstados">Estado de la llamada</label>
                        <div class="form-group">
                            <select class="form-control requerido select" id="selectEstados" name="selectEstados" title="Seleccione estado de la llamada"></select>
                            <i class="fa-solid fa-dice-d6"></i>
                        </div>
                        <span class="spanValidacion hidden"></span>
                    </a>
                </div>
                <div class="col-xs-12 col-md-12 col-lg-12">
                    <a class="tooltips">
                        <label class="negrita" for="observacion">Observación</label>
                        <div class="form-group">
                            <textarea name="observacion" id="observacion" class="form-control maxlength-textarea requerido" title="Observación llamada" minlength="1" maxlength="500"></textarea>
                            <i class="fa-solid fa-comments"></i>
                        </div>
                        <span class="spanValidacion hidden"></span>
                    </a>
                </div>
            </div>
            <div class="row recuadro pb-10 pt-10">
                <div class="col-xs-12 col-md-12 col-lg-12">
                    <a class="tooltips">
                        <label class="negrita" for="fechaLlamada">Fecha llamada</label>
                        <div class="input-group" style="margin-bottom: 0;">
                            <input type="text" class="form-control datepicker-readonly datepicker-filtro validarDP text-capitalize" id="fechaLlamada" readonly>
                            <span class="input-group-append">
                                <label class="input-group-text"><i class="ik ik-calendar"></i></label>
                            </span>
                        </div>
                        <span class="spanValidacion hidden"></span>
                    </a>
                </div>
            </div>
        </form>`;
		Swal.fire({
			position: "top",
            iconHtml: '<i class="fa-solid fa-phone-volume"></i>',
			customClass: {
				icon: "swal2-icon-custom",
			},
			title: titulo,
			html: htmlForm,
			showCloseButton: true,
			showCancelButton: true,
			showConfirmButton: true,
			showDenyButton: false,
			confirmButtonColor: "#13844e",
			confirmButtonText: "Registrar llamada",
			cancelButtonColor: "#F22613",
			cancelButtonText: "Cancelar ",
			backdrop: true,
			allowEscapeKey: false,
			allowOutsideClick: false,
			width: 700,
			showLoaderOnConfirm: true,
			showLoaderOnDeny: true,
			didOpen: () => {
				$("textarea.maxlength-textarea").maxlength({
					alwaysShow: true,
					warningClass: "hoverMaxlength hoverMaxlength-success",
					limitReachedClass: "hoverMaxlength hoverMaxlength-danger",
					placement: "top",
					validate: true,
				});
                $(".select").select2({
                    language: "es",
                    tags: false,
                    allowClear: false,
                    width: "100%",
                    placeholder: "Seleccione",
                    multiple: false,
                });
                initDateDropper(["fechaLlamada"], 1, 0);
				setDateDropper("fechaLlamada", moment().format("YYYY-MM-DD"), 1);
			},
            willOpen: () => {
                cargarSelect("Estados", false, "Seleccione estado de llamada");
                $(".requerido").on("change click mouseleave keypress mousehover", function (e) {
                    if ($(this).is("select") == true) {
                        $(this).removeClass("required");
                        $(this).parents(".tooltips").find(".spanValidacion").toggleClass("hidden", true);
                    } else {
                        $(this).removeClass("required");
                        $(this).parents(".tooltips").find(".spanValidacion").toggleClass("hidden", true);
                    }
                });
            },
			preConfirm: (response) => {
				if ( validarcampos("#frmRegistro") ) {
                    return registrarLlamada("frmRegistro", idTabla, tabla)
                        .then((response) => {
                            if (response.status == 1) {
                                Swal.fire({
                                    icon: "success",
                                    title: titulo,
                                    html: `<h5>Se ha registrado los datos con exito</h5>`,
                                    showCloseButton: true,
                                    confirmButtonColor: "#13844e",
                                    confirmButtonText: "Entendido",
                                    backdrop: true,
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        showRegistros();
                                    }
                                });
                            } else {
                                Swal.showValidationMessage(`Request failed: ${response.status}`);
                            }
                        })
                        .catch((error) => {
                            Swal.showValidationMessage(`Request failed: ${error}`);
                        });
				} else {
					Swal.showValidationMessage("Complete el formulario");
				}
			}
		});
	}
}

function registrarLlamada(form, idTabla, tabla) {
	return new Promise((resolve, reject) => {
		var formData = new FormData( document.getElementById(form) );
		formData.append("peticion", "registrarLlamada");
        formData.append("idTabla", idTabla);
        formData.append("tabla", tabla);
		$.ajax({
			cache: false, //necesario para enviar archivos
			contentType: false, //necesario para enviar archivos
			processData: false, //necesario para enviar archivos
			data: formData, //datos a enviar a la url
			dataType: "json", //Si no se especifica jQuery automaticamente encontrará el tipo basado en el header del archivo llamado (pero toma mas tiempo en cargar, asi que especificalo)
			url: urlController, //url a donde hacemos la peticion
			type: "POST",
			beforeSend: function () {
				$("#overlayText").text("Registrando datos...");
				$(".overlayCargue").fadeIn("slow");
			},
			success: function (result) {
				var estado = result.status;
				switch (estado) {
					case 0:
						reject("Ya se existe registro con ese numero de documento.");
						break;

					case 1:
						resolve(result);
						break;
				}
			},
			complete: function () {
				$(".overlayCargue").fadeOut("slow");
			},
			error: function (xhr) {
				console.error(xhr);
			},
		});
	});
}