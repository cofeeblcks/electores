$(document).ready(function () {
	cargarSelect("TipoDocumento", false, "Seleccione Tipo Documento", null, null, "TipoDocumentoIdentidad");
	cargarSelect("Rol", false, "Seleccione Rol");
	cargarSelect("Cargos", false, "Seleccione Cargo");
	cargarSelect("Eps", false, "Seleccione Eps", 2, null, "Terceros");
	cargarSelect("Arl", false, "Seleccione Arl", 3, null, "Terceros");
	cargarSelect("Afp", false, "Seleccione Afp", 4, null, "Terceros");
	cargarSelect("AreaLabor", false, "Seleccione Area de Labor");
	cargarSelect("Bancos", false, "Seleccione Banco");
	cargarSelect("TipoCuentas", false, "Seleccione Tipo de Cuenta");
    cargarSelect("TiposUsuarios", false, "Seleccione Tipo de Usuario");
    cargarSelect("TiposContratos", false, "Seleccione Tipo de Contrato");
	// $("#panelListado").toggleClass("hidden",true);
	// regresarPanelListado("panelRegistro");
	$("#selectTiempoLaboral").select2({
		language: "es",
		tags: false,
		allowClear: false,
		width: "100%",
		placeholder: "Seleccione Tiempo Laboral",
		multiple: false,
	});
});

// Definimos la url de peticiones
var urlController = urlBase + "php/controller/ControllerEmpleados.php";

$("#dttableListado")
	.on("init.dt", function () {
		cargarDateInput();
	})
	.DataTable({
		data: "",
		columns: [
			{ title: "FECHA REGISTRO", className: "text-center", responsivePriority: 1 },
			{ title: "DOCUMENTO", className: "text-center", responsivePriority: 1 },
			{ title: "NOMBRE", className: "text-center", responsivePriority: 1 },
			{ title: "TELEFONO", className: "text-center", responsivePriority: 2 },
			{ title: "CORREO", className: "text-center text-nowrap", responsivePriority: 2 },
			{ title: "EPS", className: "text-center text-nowrap", responsivePriority: 2 },
			{ title: "ARL", className: "text-center text-nowrap", responsivePriority: 2 },
			{ title: "AFP", className: "text-center text-nowrap", responsivePriority: 2 },
			{ title: "CARGO", className: "text-center text-nowrap", responsivePriority: 2 },
			{ title: "ESTADO", className: "text-center text-nowrap", responsivePriority: 2 },
			{ title: "ACCIONES", className: "text-center text-nowrap", responsivePriority: 2 },
		],
		responsive: true,
		searching: false,
		paging: false,
		info: false,
		ordering: false,
		language: {
			url: urlBase + "scripts/plugins/datatable/language/Spanish.json",
		},
		/*"createdRow": function ( row, data, index ) {
        if( data[13].replace(",",".") >= 350 ){
            $(row).addClass('label-rojo-tabla-jaime');
        }
    }*/
	});
$("#dttableListado").on("draw.dt", function () {
	$(".overlayCargue").fadeOut("slow");
});

$(".orden-show-registro").click(function (event) {
	$(".orden-show-registro").removeClass("item-orden-seleccionado");
	$(this).addClass("item-orden-seleccionado");
	filtrarRegistro(1);
});

//carga por defecto de registros
function showRegistros() {
	filtrarRegistro(1);
}

function cargarDateInput() {
	initDateDropper(["fechaNacimiento"], 1, 0, [], 1, true);
    setDateDropper("fechaNacimiento", moment().format("YYYY-MM-DD"));
	showRegistros();
}

$("#fechaNacimiento").on("change", function () {
    $("#edadEmpleado").val(calcularEdad(obtenerFechaDateDropper(1,"fechaNacimiento",false))+" Años");
});

function getFiltrar() {
	var filtroRegistro = $("#filtroRegistro").val();
	var ordenShowRegistro = $(".orden-show-registro.item-orden-seleccionado a").attr("data-value");
	// var filtroRegistroFecha = obtenerFechaDateDropper(2,"fechaInicialPeriodo","fechaFinalPeriodo",false);
	var filtroPorRegistro = $(".rbtnFiltroRegistroPor:checked").val();

	var parametros = {
		filtroRegistro: filtroRegistro,
		filtroPorRegistro: filtroPorRegistro,
		ordenShowRegistro: ordenShowRegistro,
		// filtroRegistroFecha: filtroRegistroFecha
	};

	return parametros;
}

//filtra los registros
function filtrarRegistro(pagina) {
	$("#dttableListado").DataTable().clear();
	var parametros = getFiltrar();

	var filtroRegistro = parametros.filtroRegistro;
	var ordenShowRegistro = parametros.ordenShowRegistro;
	// var filtroRegistroFecha = parametros.filtroRegistroFecha;
	var filtroPorRegistro = parametros.filtroPorRegistro;

	$.ajax({
		data: { peticion: "buscarRegistros", pagina: pagina, numeroitemsporpagina: 40, filtro: filtroRegistro, filtroPor: filtroPorRegistro, orden: ordenShowRegistro }, //datos a enviar a la url
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
				case "0":
					$("#dttableListado").DataTable().draw();
					$(".btn-paginador-registro").html("");
					//--------------------------------
					$(".info-pagination-registro").html("");
					break;

				case "1":
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

var editarEmpleado = false;
function datosEmpleado(id, tipoAccion) {
	if ($.isNumeric(id)) {
		$.ajax({
			data: { peticion: "datosEmpleado", id: id }, //datos a enviar a la url
			dataType: "json", //Si no se especifica jQuery automaticamente encontrará el tipo basado en el header del archivo llamado (pero toma mas tiempo en cargar, asi que especificalo)
			url: urlController, //url a donde hacemos la peticion
			type: "POST",
			beforeSend: function () {
				$("#overlayText").text("Consultando Datos...");
				$(".overlayCargue").fadeIn("slow");
			},
			success: function (result) {
				var estado = result.status;
				switch (estado) {
					case "0":
						//--------------------------------
						showToast("Error!", "Error de sistema, consulte al administrador del sistema.", "error", "derecha", 5000);
						break;

					case "1":
						if (result.aporta_pension == 1) {
							$("#chkAporta").iCheck("check");
						} else {
							$("#chkAporta").iCheck("uncheck");
						}
						if (tipoAccion) {
							$("#btnGuardar").fadeIn();
							$("#chkAporta").iCheck("enable");
							// -------------------------------
							habilitarcampos("#frmRegistro");
							$("#tituloPanel").text("Actualizar Datos del Empleado");
						} else {
							$("#btnGuardar").fadeOut();
							$("#chkAporta").iCheck("disable");
							// -------------------------------
							deshabilitarcampos("#frmRegistro");
							$("#tituloPanel").text("Datos del Empleado");
						}
						editarEmpleado = tipoAccion;
						$("#idCampo").val(id);
						$("#primerNombre").val(result.primer_nombre);
						$("#segundoNombre").val(result.segundo_nombre);
						$("#primerApellido").val(result.primer_apellido);
						$("#segundoApellido").val(result.segundo_apellido);
						$("#selectTipoDocumento").attr("disabled", true).val(result.idtipo_documento).trigger("change");
						$("#documento").attr("disabled", true).val(result.documento);
						$("#telefono").val(result.telefono);
						$("#correo").val(result.correo);
                        $("#direccion").val(result.direccion);
						$("#salario").val(ponerPuntosMil(result.salario != undefined ? result.salario : 0));
						$("#selectRol").val(result.id_rol).trigger("change");
						$("#selectEps").val(result.id_tercero_eps).trigger("change");
						$("#selectAfp").val(result.id_tercero_afp).trigger("change");
						$("#selectArl").val(result.id_tercero_arl).trigger("change");
						$("#selectCargos").val(result.id_cargo).trigger("change");
						$("#selectTiempoLaboral").val(result.tiempo_laboral).trigger("change");
						$("#selectAreaLabor").val(result.idarea_labor).trigger("change");
						$("#selectBancos").val(result.id_banco).trigger("change");
						$("#selectTipoCuentas").val(result.idtipo_cuenta).trigger("change");
						$("#numeroCuentaBancaria").val(result.numero_cuenta);
                        $("#selectTiposUsuarios").val(result.idtipo_usuario).trigger("change");
                        $("#selectTiposContratos").val(result.idtipo_contrato).trigger("change");
                        setDateDropper("fechaNacimiento", moment(result.fecha_nacimiento).format("YYYY-MM-DD"));
                        $("#fechaNacimiento").trigger("change");
                        document.getElementById("edadEmpleado").disabled = true;
						// Contraseñas Inactivas
						$("#contraseniaUno").val("").attr("disabled", true).removeClass("requerido");
						$("#contraseniaDos").val("").attr("disabled", true).removeClass("requerido");
						mostrarPanelAdicional("panelRegistro");
						break;

					default:
						//--------------------------------
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

function registroEmpleado(form) {
	var respuestavalidacion = validarcampos("#" + form);
	if (respuestavalidacion) {
		var pass1 = $("#contraseniaUno");
		var pass2 = $("#contraseniaDos");
		var respuestacontrasenia = validarcontrasenias(pass1, pass2);
		if (respuestacontrasenia) {
			var data = new Object();
			data["datos"] = $("#" + form).serialize();
			data["peticion"] = "registrarEmpleado";
			data["isUpdate"] = editarEmpleado;
            data["fecha_nacimiento"] = obtenerFechaDateDropper(1,"fechaNacimiento",false);
			$.ajax({
				// cache:       false,//necesario para enviar archivos
				// contentType: false,//necesario para enviar archivos
				// processData: false,//necesario para enviar archivos
				data: data, //necesario para enviar archivos
				dataType: "json", //Si no se especifica jQuery automaticamente encontrará el tipo basado en el header del archivo llamado (pero toma mas tiempo en cargar, asi que especificalo)
				url: urlBase + "php/controller/ControllerEmpleados.php", //url a donde hacemos la peticion
				type: "POST",
				beforeSend: function () {
					$("#overlayText").text("Registrando Datos...");
					$(".overlayCargue").fadeIn("slow");
				},
				complete: function () {
					$(".overlayCargue").fadeOut("slow");
				},
				success: function (result) {
					$("#btnLoginIngresar").prop("disabled", false);
					var estado = result.status;
					switch (estado) {
						case "0":
							showToast("Empleado Existente!", "El Empleado ya se encuentra registrado.", "info", "derecha", 5000);
							break;

						case "1":
							limpiarcampos("#" + form);
							showToast("Datos Guardados!", "Se registro el Empleado con exito.", "success", "derecha", 5000);
							regresarPanelListado("panelRegistro");
							break;

						case "2":
							showToast("Datos Ingresados!", "Alguno de los datos ingresados no es valido.", "warning", "derecha", 5000);
							break;

						case "3":
							showToast("Contraseña!", "Las contraseñas son diferentes.", "error", "derecha", 5000);
							break;

						default:
							break;
					}
				},
				error: function (xhr) {
					console.log(xhr);
				},
			});
		}
	}
}

function eliminar(id, motivo) {
	if ($.isNumeric(id)) {
		$.ajax({
			data: { peticion: "eliminarEmpleado", id: id, motivo: motivo }, //datos a enviar a la url
			dataType: "json", //Si no se especifica jQuery automaticamente encontrará el tipo basado en el header del archivo llamado (pero toma mas tiempo en cargar, asi que especificalo)
			url: urlBase + "php/controller/ControllerEmpleados.php", //url a donde hacemos la peticion
			type: "POST",
			beforeSend: function () {
				$("#overlayText").text("Eliminando registro...");
				$(".overlayCargue").fadeIn("slow");
			},
			success: function (result) {
				var estado = result.status;
				switch (estado) {
					case "0":
						//--------------------------------
						showToast("Error!", "Error de sistema, consulte al administrador del sistema.", "error", "derecha");
						break;

					case "1":
						//--------------------------------
						$("#modalConfirmar").modal("hide");
						showRegistros();
						showToast("Eliminado!", "El registro se ha eliminado con exito.", "success", "derecha");
						break;

					case "2":
						//--------------------------------
						showToast("Error!", "No tiene permiso para eliminar este registro.", "error", "derecha");
						break;

					default:
						//--------------------------------
						break;
				}
			},
			complete: function () {
				//--------------------------------
			},
			error: function (xhr) {
				console.log(xhr);
			},
		});
	}
}
