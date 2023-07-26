$(document).ready(function () {
});

// Definimos la url de peticiones
var urlController = urlBase + "php/controller/ControllerUsuarios.php";

$("#dttableListado")
	.on("init.dt", function () {
		showRegistros();
	})
	.DataTable({
		data: "",
		columns: [
			{ title: "ID", className: "text-center", responsivePriority: 1 },
            { title: "ROL", className: "text-center", responsivePriority: 1 },
			{ title: "DOCUMENTO", className: "text-center", responsivePriority: 1 },
			{ title: "NOMBRE", className: "text-center", responsivePriority: 1 },
			{ title: "CORREO", className: "text-center text-nowrap", responsivePriority: 2 },
			{ title: "ACCIONES", className: "text-center text-nowrap", responsivePriority: 2 },
		],
		responsive: true,
		searching: false,
		paging: false,
		info: false,
		ordering: false,
		language: {
			url: urlBase + "scripts/plugins/datatable/language/Spanish.json",
		}
	});
$("#dttableListado").on("draw.dt", function () {
	$(".overlayCargue").fadeOut("slow");
});

//carga por defecto de registros
function showRegistros() {
	filtrarRegistro(1);
}

function getFiltrar() {
	return {
		filtroRegistro: $("#filtroRegistro").val(),
		filtroPorRegistro: $(".rbtnFiltroRegistroPor:checked").val()
	}
}

//filtra los registros
function filtrarRegistro(pagina) {
	$("#dttableListado").DataTable().clear();
	var parametros = getFiltrar();

	var filtroRegistro = parametros.filtroRegistro;
	var filtroPorRegistro = parametros.filtroPorRegistro;

	$.ajax({
		data: { peticion: "buscarRegistros", pagina: pagina, numeroitemsporpagina: 40, filtro: filtroRegistro, filtroPor: filtroPorRegistro, orden: 0 }, //datos a enviar a la url
		dataType: "json", //Si no se especifica jQuery automaticamente encontrará el tipo basado en el header del archivo llamado (pero toma mas tiempo en cargar, asi que especificalo)
		url: urlController, //url a donde hacemos la peticion
		type: "POST",
		beforeSend: function () {
			$("#overlayText").text("Cargando registros...");
			$(".overlayCargue").fadeIn("slow");
		},
		success: function (result) {
			var estado = result.status;
			switch (estado) {
				case 0:
					$("#dttableListado").DataTable().draw();
					$(".btn-paginador-registro").html("");
					//--------------------------------
					$(".info-pagination-registro").html("");
					break;

				case 1:
					//--------------------------------
					$("#dttableListado").DataTable().rows.add(result.datos).draw();

					$("#paginaActual").val("" + result.paginador.pagina + "");

					var primeros = "";
					var atras = "";
					var adelante = "";
					var ultimos = "";
					var numpaginador = "";

					//boton atras
					if (result.paginador.pagina > 1) {
						atras = "<li class='paginate_button'><a class='cursor-pointer'><a onclick='filtrarRegistro(" + result.paginador.pagina + "-1)'>Atrás</a></li>";
						primeros = "<li class='paginate_button'><a class='cursor-pointer'><a onclick='filtrarRegistro(1)'>Inicio</a></li>";
					} else {
						atras = "<li class='paginate_button disabled'><a>Atrás</a></li>";
						primeros = "<li class='paginate_button disabled'><a>Inicio</a></li>";
					}

					var numpaginador = "";
					//botones del paginador
					for (var i = result.paginador.inicio_loop; i <= result.paginador.finaliza_loop; i++) {
						if (result.paginador.pagina == i) {
							numpaginador += "<li class='paginate_button active'><a>" + i + "</a></li>";
						} else {
							numpaginador += "<li class='paginate_button cursor-pointer'><a onclick='filtrarRegistro(" + i + ")'>" + i + "</a></li>";
						}
					}
					//mostrar botones adelante
					if (result.paginador.pagina < result.paginador.total_paginas) {
						adelante = "<li class='paginate_button cursor-pointer'><a onclick='filtrarRegistro(" + result.paginador.pagina + "+1)'>Adelante</a></li>";
						ultimos = "<li class='paginate_button cursor-pointer'><a onclick='filtrarRegistro(" + result.paginador.total_paginas + ")'>Fin</a></li>";
					} else {
						adelante = "<li class='paginate_button disabled'><a>Adelante</a></li>";
						ultimos = "<li class='paginate_button disabled'><a>Fin</a></li>";
					}

					//se muestran los botones de las paginas
					$(".btn-paginador-registro").html(primeros + atras + numpaginador + adelante + ultimos);
					//--------------------------------
					$(".info-pagination-registro").html("Página " + result.paginador.pagina + " de " + result.paginador.total_paginas + " - Total de registros: " + result.paginador.totalRegistros);
					break;

				default:
					//window.location.replace(urlBase+"login.php");
					break;
			}
		},
		complete: function () {
			$("#dttableListado").DataTable().columns.adjust().draw();
		},
		error: function (xhr) {
			console.log(xhr);
		},
	});
}

function datosUsuario(registro) {
    if (!isEmpty(registro)) {
        $.ajax({
            data: { peticion: "verServiciosRegistro", registro: registro, tipo: tipo },
            dataType: "json",
            url: urlBase + "php/controller/controller_registro.php", //url a donde hacemos la peticion
            type: "POST",
            beforeSend: function () {
                //code
                $("#overlayText").text("Consultando Servicios...");
                $(".overlayCargue").fadeIn("slow");
            },
            success: function (result) {
                // code
                var estado = result.status;
                switch (estado) {
                    case "0":
                        // estado 0 sin datos
                        break;

                    case "1":
                        var titulo = "<strong>Marcar Rechazo de Servicios</strong>";
                        var htmlForm = `<h5 class="mb-5">Paciente ${result.paciente} (${result.cedula})</h5>
                        <div class="container row">
                            <div class="col-xs-12 col-md-12 col-lg-12 recuadro pt-3 pb-3">
                                <div class="row">
                                    <div class="col-xs-12 col-md-6 col-lg-6 d-flex align-items-center">
                                        <a class="tooltips w-100">
                                            <label class="negrita" for="selectTiposRechazos">Tipo de Rechazo</label>
                                            <div class="container-select">
                                                <select class="form-control requerido requeridoicon" id="selectTiposRechazos" name="selectTiposRechazos" title="Seleccione Forma de pago"></select>
                                            </div>
                                            <span class="spanValidacion hidden"></span>
                                        </a>
                                    </div>
                                    <div class="col-xs-12 col-md-6 col-lg-6 d-flex align-items-center">
                                        <a class="tooltips w-100">
                                            <label class="negrita" for="selectMotivosRechazos">Motivo de Rechazo</label>
                                            <div class="container-select">
                                                <select class="form-control requerido requeridoicon" id="selectMotivosRechazos" name="selectMotivosRechazos" title="Seleccione Forma de pago"></select>
                                            </div>
                                            <span class="spanValidacion hidden"></span>
                                        </a>
                                    </div>
                                </div>
                                <div class="row mt-15">
                                    <div class="col-xs-12 col-md-6 col-lg-6">
                                        <a class="tooltips w-100">
                                            <label class="negrita" for="fechaMuestra">Fecha Proxima Muestra</label>
                                            <input type="text" class="form-control text-center datepicker-readonly validarDP" id="fechaMuestra" readonly>
                                            <span class="spanValidacion hidden"></span>
                                        </a>
                                    </div>
                                </div>
                                <div class="row mt-15">
                                    <div class="col-xs-12 col-md-12 col-lg-12">
                                        <a class="tooltips w-100">
                                            <label class="negrita" for="observacion">Observacion</label>
                                            <textarea type="text" class="form-control maxlength-textarea" minlength="0" maxlength="500" value="" title="Ingrese observacion" id="observacion" name="observacion"></textarea>
                                            <span class="spanValidacion hidden"></span>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <h5 class="mt-10">Seleccione los servicios del registro ${registro.reverse()} a los que se les rechazara la muestra.<strong></h5>`;
                        Swal.fire({
                            // icon: "error",
                            position: "top",
                            title: titulo,
                            html: htmlForm + result.html,
                            showCloseButton: true,
                            showConfirmButton: true,
                            confirmButtonText: "Rechazar Muestra",
                            confirmButtonColor: "#dc3545",
                            showDenyButton: false,
                            showCancelButton: true,
                            cancelButtonText: "Cancelar",
                            cancelButtonColor: "#e3e3e3",
                            backdrop: true,
                            width: 900,
                            allowEscapeKey: false,
                            showLoaderOnConfirm: true,
                            showLoaderOnDeny: true,
                            allowOutsideClick: () => !Swal.isLoading(),
                            didOpen: () => {
                                $(".chkRegistro").iCheck({
                                    checkboxClass: "icheckbox_square-red",
                                    increaseArea: "50%",
                                });
                                initDateDropper(["fechaMuestra"], 1, 0, [0]);
                                setDateDropper("fechaMuestra", moment().format("MM/DD/YYYY"), 1);
                                $('textarea.maxlength-textarea').maxlength({
                                    alwaysShow: true,
                                    warningClass: "hoverMaxlength hoverMaxlength-info",
                                    limitReachedClass: "hoverMaxlength hoverMaxlength-danger",
                                    placement: 'top',
                                    validate: true
                                });
                            },
                            willOpen: () => {
                                cargar_select('selectTiposRechazos', 'Seleccione Tipo de Rechazo...', false);
                                cargar_select('selectMotivosRechazos', 'Seleccione Tipo de Rechazo...', false, null, 1);
                            },
                            preConfirm: () => {
                                var arrayServicios = [];
                                $(".chkRegistro").each(function () {
                                    if (this.checked) {
                                        arrayServicios.push(this.value);
                                    }
                                });
                                if (arrayServicios.length > 0) {
                                    let tipoRechazo = document.getElementById("selectTiposRechazos") ? document.getElementById("selectTiposRechazos").value.trim() : "";
                                    let motivoRechazo = document.getElementById("selectMotivosRechazos") ? document.getElementById("selectMotivosRechazos").value.trim() : "";
                                    let fechaMuestra = obtenerFechaDateDropper(1, "fechaMuestra", false);
                                    let observacion = document.getElementById("observacion") ? document.getElementById("observacion").value.trim() : "";
                                    if ( isEmpty(tipoRechazo) ) {
                                        Swal.showValidationMessage("Seleccione el tipo de rechazo.");
                                    } else if ( isEmpty(motivoRechazo) ) {
                                        Swal.showValidationMessage("Seleccione motivo de rechazo.");
                                    } else if ( isEmpty(observacion) ) {
                                        Swal.showValidationMessage("Indique el motivo del rechazo.");
                                    } else {
                                        return rechazarMuestra( registro.reverse(), arrayServicios, tipoRechazo, motivoRechazo, fechaMuestra, observacion )
                                            .then((response) => {
                                                if (response.status == 1) {
                                                    // showRegistros();
                                                    Swal.fire({
                                                        icon: "success",
                                                        title: "<strong>Servicios rechazados.</strong>",
                                                        html: "<h5></h5>",
                                                        showCloseButton: true,
                                                        confirmButtonText: "Entendido",
                                                        backdrop: true,
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
                                    Swal.showValidationMessage("Debe seleccionar al menos 1 servicio.");
                                }
                            }
                        });
                        break;

                    default:
                        // COde
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
}

function formRegistro() {
    var htmlForm = `
    <div class="container">
        <div class="container recuadro pt-3 pb-3">
            <div class="row">
                <div class="col-xs-12 col-md-12 col-lg-12">
                    <a class="tooltips w-100">
                        <label class="negrita" for="nombres">Nombres</label>
                        <input type="text" class="form-control maxlength-input" minlength="0" maxlength="100" value="" title="Ingrese nombres" id="nombres" name="nombres">
                        <span class="spanValidacion hidden"></span>
                    </a>
                </div>
                <div class="col-xs-12 col-md-12 col-lg-12 mt-5">
                    <a class="tooltips w-100">
                        <label class="negrita" for="apellidos">Apellidos</label>
                        <input type="text" class="form-control maxlength-input" minlength="0" maxlength="100" value="" title="Ingrese apellidos" id="apellidos" name="apellidos">
                        <span class="spanValidacion hidden"></span>
                    </a>
                </div>
                <div class="col-xs-12 col-md-12 col-lg-12 mt-5">
                    <a class="tooltips w-100">
                        <label class="negrita" for="documento">Documento</label>
                        <input type="text" class="form-control numerico" minlength="0" maxlength="100" value="" title="Ingrese documento" id="documento" name="documento">
                        <span class="spanValidacion hidden"></span>
                    </a>
                </div>
                <div class="col-xs-12 col-md-12 col-lg-12 mt-5">
                    <a class="tooltips w-100">
                        <label class="negrita" for="correo">Correo</label>
                        <input type="text" class="form-control maxlength-input" minlength="0" maxlength="100" value="" title="Ingrese correo" id="correo" name="correo">
                        <span class="spanValidacion hidden"></span>
                    </a>
                </div>
            </div>
        </div>
        <div class="container recuadro pt-3 pb-3 mt-10">
            <div class="row">
                <div class="col-xs-12 col-md-12 col-lg-12">
                    <a class="tooltips w-100">
                        <label class="negrita" for="contraseniaUno">Contraseña</label>
                        <input type="password" class="form-control maxlength-input" minlength="6" maxlength="100" value="" title="Ingrese contraseña" id="contraseniaUno" name="contraseniaUno">
                        <span class="spanValidacion hidden"></span>
                    </a>
                </div>
                <div class="col-xs-12 col-md-12 col-lg-12 mt-5">
                    <a class="tooltips w-100">
                        <label class="negrita" for="contraseniaDos">Confirme la contraseña</label>
                        <input type="password" class="form-control maxlength-input" minlength="6" maxlength="100" value="" title="Confirme la contraseña" id="contraseniaDos" name="contraseniaDos">
                        <span class="spanValidacion hidden"></span>
                    </a>
                </div>
            </div>
        </div>
    </div>`;
    Swal.fire({
        position: "top",
        iconHtml: '<i class="fa-solid fa-user-plus"></i>',
        customClass: {
            icon: "swal2-icon-custom",
        },
        title: "Formulario de registro nuevo usuario",
        html: htmlForm,
        showCloseButton: true,
        showConfirmButton: true,
        confirmButtonText: "Registrar usuario",
        confirmButtonColor: "#16a085",
        showDenyButton: false,
        showCancelButton: true,
        cancelButtonText: "Cancelar",
        cancelButtonColor: "#df382c",
        backdrop: true,
        width: 600,
        allowEscapeKey: false,
        showLoaderOnConfirm: true,
        showLoaderOnDeny: true,
        allowOutsideClick: () => !Swal.isLoading(),
        didOpen: () => {
            $(".numerico").inputmask("numerico");
            $('input.maxlength-input').maxlength({
                alwaysShow: true,
                warningClass: "hoverMaxlength hoverMaxlength-info",
                limitReachedClass: "hoverMaxlength hoverMaxlength-danger",
                placement: 'top',
                validate: true
            });
        },
        willOpen: () => {
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
        preConfirm: () => {
            let nombres = document.getElementById("nombres") ? document.getElementById("nombres").value.trim() : "";
            let apellidos = document.getElementById("apellidos") ? document.getElementById("apellidos").value.trim() : "";
            let documento = document.getElementById("documento") ? document.getElementById("documento").value.trim() : "";
            let correo = document.getElementById("correo") ? document.getElementById("correo").value.trim() : "";
            let contraseniaUno = document.getElementById("contraseniaUno") ? document.getElementById("contraseniaUno").value.trim() : "";
            let contraseniaDos = document.getElementById("contraseniaDos") ? document.getElementById("contraseniaDos").value.trim() : "";

            if ( isEmpty(nombres) ) {
                $("#nombres").parents(".tooltips").find(".spanValidacion").text("Ingrese el nombre del usuario").toggleClass("hidden", false);
                Swal.showValidationMessage("Ingrese el nombre del usuario.");
            } else if ( isEmpty(apellidos) ) {
                $("#apellidos").parents(".tooltips").find(".spanValidacion").text("Ingrese el apellido dle usuario").toggleClass("hidden", false);
                Swal.showValidationMessage("Ingrese el apellido dle usuario.");
            } else if ( isEmpty(documento) ) {
                $("#documento").parents(".tooltips").find(".spanValidacion").text("Indique el documento del usuario").toggleClass("hidden", false);
                Swal.showValidationMessage("Indique el documento del usuario.");
            } else if ( isEmpty(correo) ) {
                $("#correo").parents(".tooltips").find(".spanValidacion").text("Indique el correo del usuario").toggleClass("hidden", false);
                Swal.showValidationMessage("Indique el correo del usuario.");
            } else if ( isEmpty(contraseniaUno) ) {
                $("#contraseniaUno").parents(".tooltips").find(".spanValidacion").text("Ingrese contraseña").toggleClass("hidden", false);
                Swal.showValidationMessage("Ingrese contraseña");
            } else if ( isEmpty(contraseniaDos) ) {
                $("#contraseniaDos").parents(".tooltips").find(".spanValidacion").text("Confirme la contraseña").toggleClass("hidden", false);
                Swal.showValidationMessage("Confirme la contraseña.");
            } else if ( contraseniaUno != contraseniaDos ) {
                Swal.showValidationMessage("Las contraseñas no son las mismas");
            } else {
                return registrarUsuario( nombres, apellidos, documento, correo, contraseniaUno, contraseniaDos )
                    .then((response) => {
                        if (response.status == 1) {
                            showRegistros();
                            Swal.fire({
                                icon: "success",
                                title: "<strong>Usuario registrado con exito.</strong>",
                                html: "<h5></h5>",
                                showCloseButton: true,
                                confirmButtonText: "Entendido",
                                backdrop: true,
                            });
                        } else {
                            Swal.showValidationMessage(`Request failed: ${response.status}`);
                        }
                    })
                    .catch((error) => {
                        Swal.showValidationMessage(`Request failed: ${error}`);
                    });
            }
        }
    });
}

function registrarUsuario(nombres, apellidos, documento, correo, contraseniaUno, contraseniaDos) {
    return new Promise((resolve, reject) => {
        $.ajax({
            data: {
                peticion: "registrarUsuario",
                nombres: nombres,
                apellidos: apellidos,
                documento: documento,
                correo: correo,
                contraseniaUno: contraseniaUno,
                contraseniaDos: contraseniaDos
            }, //datos a enviar a la url
            dataType: "json", //Si no se especifica jQuery automaticamente encontrará el tipo basado en el header del archivo llamado (pero toma mas tiempo en cargar, asi que especificalo)
            url: urlController, //url a donde hacemos la peticion
            type: "POST",
            beforeSend: function () {
                $("#overlayText").text("Registrando usuario...");
                $(".overlayCargue").fadeIn("slow");
            },
            success: function (result) {
                resolve(result);
            },
            complete: function () {
                $(".overlayCargue").fadeOut("slow");
            },
            error: function (xhr) {
                reject(xhr);
            },
        });
    });
}