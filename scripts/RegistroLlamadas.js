var objHora = null,
objType = null;
function modelRegistroLlamada(idElector, nombre) {
	if ($.isNumeric(idElector)) {
        objHora = {
            degreesHours: 90,
            degreesMinutes: 90,
            hour: "12",
            minutes: "00",
            type: "AM"
        }
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
                <div class="col-xs-12 col-md-12 col-lg-6">
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
                <div class="col-xs-12 col-md-12 col-lg-6">
                    <a class="tooltips">
                        <label class="negrita" for="horaLlamada">Hora llamada</label>
                        <div class="input-group" style="margin-bottom: 0;">
                            <div class="timepicker-ui">
                                <input type="text" class="form-control timepicker-ui-input" value="12:00 AM" id="horaLlamada" name="horaLlamada">
                            </div>
                            <span class="input-group-append">
                                <label class="input-group-text"><i class="fa-solid fa-clock"></i></label>
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
			showLoaderOnConfirm: false,
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

				let timepicker = document.querySelector(".timepicker-ui");
				let initTimepicker = new TimepickerUI(timepicker, {
					// custom text for AM label
					amLabel: "AM",
                    // custom text for PM label
					pmLabel: "PM",
					// enable animation
					animation: true,
					// append to this element
					appendModalSelector: "",
					// enable backdrop
					backdrop: true,
					// custom text for Cancel label
					cancelLabel: "Cancelar",
					// or '24h' '12h'
					// clockType: "12h",
					// true = update picker with toLocaleTimeString() and input with value based on your location
					// object = { time: new Date(), updateInput: true, locales: "en-US", preventClockType: false }
					currentTime: undefined, // boolean | object, Set current time to the input and timepicker.
					// set delay to clickable elements like button "OK", "CANCEL" etc.
					delayHandler: 300,
					// allows to edit hour/minutes
					editable: false,
					// enable scrollbar
					enableScrollbar: true,
					// enable switch icon
					enableSwitchIcon: true,
					// OBJECT, 3 keys: hours, minutes and interval
					disabledTime: undefined,
					// set focus to the input after you close the modal
					focusInputAfterCloseModal: false,
					// turn off/on focus trap to the picker
					focusTrap: true,
					// custom text for hour label on mobile version
					hourMobileLabel: "Hora",
					// increment hour by 1, 2, 3 hour
					incrementHours: 1,
					// increment minutes by 1, 5, 10, 15 minutes
					incrementMinutes: 5,
					// icon template
					iconTemplate: '<i class="material-icons timepicker-ui-keyboard-icon">keyboard</i>',
					// icon template on mobile version
					iconTemplateMobile: '<i class="material-icons timepicker-ui-keyboard-icon">schedule</i>',
					// custom text for minute label on mobile version
					minuteMobileLabel: "Minutos",
					// enable mobile version
					mobile: false,
					// custom text for hour label on mobile version
					enterTimeLabel: "Digite la hora",
					// custom text for OK label
					okLabel: "Aceptar",
					// prevent default events
					preventDefault: true,
					// custom text for time label on desktop version
					selectTimeLabel: "Seleccione hora de la llamada",
					// switch to minutes after selecting an hour
					switchToMinutesAfterSelectHour: true,
					// crane-straight, crane-radius, basic
					theme: "crane-radius",
				});
				initTimepicker.create();
                timepicker.addEventListener('accept', (event) => (objHora = event.detail));
                timepicker.addEventListener('selectamtypemode', (event) => objType = event.detail.type);
                timepicker.addEventListener('selectpmtypemode', (event) => objType = event.detail.type);
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
				if (validarcampos("#frmRegistro")) {
                    if( objHora != null ){
                        return registrarLlamada("frmRegistro", idElector)
                            .then((response) => {
                                if (response.status == 1) {
                                    Swal.fire({
                                        icon: "success",
                                        title: titulo,
                                        html: `<h5>Se ha realizado registro de llamada con exito</h5>`,
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
                    }
				} else {
					Swal.showValidationMessage("Complete el formulario");
				}
			},
		});
	}
}

function registrarLlamada(form, idElector) {
	return new Promise((resolve, reject) => {
        objHora.type = objType;
		var formData = new FormData(document.getElementById(form));
		formData.append("peticion", "registrarLlamada");
		formData.append("idElector", idElector);
		formData.append("fecha", obtenerFechaDateDropper(1, "fechaLlamada", false));
        formData.append("hora", JSON.stringify(objHora));
		$.ajax({
			cache: false, //necesario para enviar archivos
			contentType: false, //necesario para enviar archivos
			processData: false, //necesario para enviar archivos
			data: formData, //datos a enviar a la url
			dataType: "json", //Si no se especifica jQuery automaticamente encontrará el tipo basado en el header del archivo llamado (pero toma mas tiempo en cargar, asi que especificalo)
			url: urlController, //url a donde hacemos la peticion
			type: "POST",
			beforeSend: function () {
				$("#overlayText").text("Registrando llamada...");
				$(".overlayCargue").fadeIn("slow");
			},
			success: function (result) {
				var estado = result.status;
				switch (estado) {
					case 0:
						reject("Ha ocurrido un error guardando la información.");
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

function listaRegistroLlamadas(idElector) {
	if ($.isNumeric(idElector)) {
		$.ajax({
			data: {
				peticion: "listaRegistroLlamadas",
				id: idElector,
			}, //datos a enviar a la url
			dataType: "json", //Si no se especifica jQuery automaticamente encontrará el tipo basado en el header del archivo llamado (pero toma mas tiempo en cargar, asi que especificalo)
			url: urlController, //url a donde hacemos la peticion
			type: "POST",
			beforeSend: function () {
				$("#overlayText").text("Consultando datos...");
				$(".overlayCargue").fadeIn("slow");
			},
			complete: function () {
				$(".overlayCargue").fadeOut("slow");
			},
			success: function (result) {
				var estado = result.status;
				switch (estado) {
					case 0:
						Swal.fire({
							position: "top",
							icon: "info",
							title: `<strong>No existe información de registro de llamadas</strong>`,
							html: "",
							showCloseButton: false,
							showConfirmButton: false,
							confirmButtonText: "",
							confirmButtonColor: "",
							cancelButtonText: "Cerrar",
							cancelButtonColor: "#dc3545",
							showCancelButton: true,
							backdrop: true,
							allowOutsideClick: false,
							allowEscapeKey: false,
						});
						break;
					case 1:
                        var htmlForm = `<div class="container row">
                            <div class="col-xs-12 col-md-12 col-lg-12 recuadro mt-4">
                                ${result.informacion}
                            </div>
                            <div class="col-xs-12 col-md-12 col-lg-12 recuadro mt-4">
                                <table id="dttableListadoModal" class="table w-100 ml-0 table-hover" width="100%"></table>
                            </div>
                        </div>`;
						Swal.fire({
							position: "top",
							title: `<strong>Registro de llamadas del elector ${result.elector}</strong>`,
							html: `${htmlForm}`,
							showCloseButton: false,
							showConfirmButton: false,
							confirmButtonText: "",
							confirmButtonColor: "",
							cancelButtonText: "Cerrar",
							cancelButtonColor: "#dc3545",
							showCancelButton: true,
							backdrop: true,
							width: 1300,
							allowOutsideClick: false,
							allowEscapeKey: false,
							willOpen: () => {
								// Iniciamos el datatables y el ajuste del mismo
								$("#dttableListadoModal").DataTable({
									data: "",
									columns: [
										{ title: "FECHA REGISTRO", className: "text-center text-nowrap", responsivePriority: 1 },
										{ title: "USUARIO REGISTRO", className: "text-center text-nowrap", responsivePriority: 1 },
										{ title: "FECHA DE LLAMADA", className: "text-center text-nowrap", responsivePriority: 1 },
										{ title: "OBSERVACION", className: "text-center text-nowrap", responsivePriority: 1 },
										{ title: "ESTADO LLAMADA", className: "text-center text-nowrap", responsivePriority: 1 }
									],
									responsive: true,
									searching: false,
									paging: false,
									info: false,
									ordering: false,
									language: {
										url: urlBase + "scripts/plugins/datatable/language/Spanish.json",
									},
									createdRow: function (row, data, index) {
										if (index % 2) {
											$(row).addClass("bg-azul-table");
										}
									},
								});
								if (estado == 1) {
									$("#dttableListadoModal").DataTable().rows.add(result.datos).draw();
								}
							},
							didOpen: () => {
								setTimeout(() => {
									$("#dttableListadoModal").DataTable().columns.adjust().draw();
								}, 100);
							},
						});
						break;
				}
			},
			error: function (xhr) {
				console.log(xhr);
			},
		});
	}
}
