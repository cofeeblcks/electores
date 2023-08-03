$(document).ready(function () {
	cargarSelect("FiltroSemaforos", false, "Seleccione filtro semaforo", null, null, "Semaforo", true);
});

// Definimos la url de peticiones
var urlController = urlBase + "php/controller/ControllerElectores.php";

$("#dttableListado")
	.on("init.dt", function () {
		cargarDateInput();
	})
	.DataTable({
		data: "",
		columns: [
			{ title: "ES LIDER", visible: false },
			{ title: "FECHA REGISTRO", className: "text-center text-nowrap", responsivePriority: 3 },
			{ title: "DOCUMENTO", className: "text-center text-nowrap", responsivePriority: 1 },
			{ title: "NOMBRE VOTANTE", className: "text-center text-nowrap", responsivePriority: 1 },
			{ title: "TELEFONO", className: "text-center text-nowrap", responsivePriority: 1 },
			{ title: "EDAD", className: "text-center text-nowrap", responsivePriority: 1 },
			{ title: "SEXO", className: "text-center text-nowrap", responsivePriority: 1 },
			{ title: "DIRECCION", className: "text-center text-nowrap", responsivePriority: 2 },
			{ title: "SEMAFORO", className: "text-center", responsivePriority: 1 },
			{ title: "SECTOR LIDER", className: "text-center text-nowrap", responsivePriority: 2 },
			{ title: "PUESTO DE VOTACIÓN", className: "text-center", responsivePriority: 2 },
			{ title: "REFERIDOS", className: "text-center", responsivePriority: 1 },
			{ title: "REGISTRO DE LLAMADAS", className: "text-center", responsivePriority: 1 },
			{ title: "ACCIONES", className: "text-center", responsivePriority: 1 },
		],
		responsive: true,
		searching: false,
		paging: false,
		info: false,
		ordering: true,
		order: [[0, "desc"]],
		language: {
			url: urlBase + "scripts/plugins/datatable/language/Spanish.json",
		},
		createdRow: function (row, data, index) {
			if (index % 2) {
				$(row).addClass("bg-table-gray");
			}
			if (data[0] == 1) {
				$(row).removeClass("bg-table-gray");
				$(row).addClass("bg-table-green");
			}
		},
		dom: "Blfrtip",
		buttons: ["excel"],
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
	var elm = document.getElementById("selectFiltroSemaforos");
	var color = "";
	setTimeout(() => {
		color = elm.options[elm.selectedIndex].dataset.adicional != undefined && elm.options[elm.selectedIndex] != undefined ? elm.options[elm.selectedIndex].dataset.adicional : "";
		$(".selectSemaforo").data("select2").$selection.css({
			backgroundColor: color,
		});
		$(".selectSemaforo").data("select2").$selection.children("span.select2-selection__rendered").css("color", "#fff");
	}, 200);
	filtrarRegistro(1);
}

function cargarDateInput() {
	// initDateDropper(["fechaNacimiento"], 1, 0, [], 1, true);
	// setDateDropper("fechaNacimiento", moment().format("YYYY-MM-DD"));
	// setDateDropper("fechaFinalPeriodo", moment().format("YYYY-MM-DD"), 1);
	showRegistros();
}

function getFiltrar() {
	return {
		filtroRegistro: $("#filtroRegistro").val(),
		filtroPorRegistro: $(".rbtnFiltroRegistroPor:checked").val(),
		ordenShowRegistro: 0,
		filtroSemaforo: $("#selectFiltroSemaforos").val(),
		filtroSexo: $("#selectFiltroSexo").val(),
	};
}

let canvaGrafica = document.getElementById("canvasGraficaTemperatura");
let contextGrafica = canvaGrafica.getContext("2d");
let chart = new Chart(contextGrafica);

//filtra los registros
function filtrarRegistro(pagina) {
	$("#dttableListado").DataTable().clear();
	var parametros = getFiltrar();

	var filtroRegistro = parametros.filtroRegistro;
	var ordenShowRegistro = parametros.ordenShowRegistro;
	var filtroPorRegistro = parametros.filtroPorRegistro;
	var filtroSemaforo = parametros.filtroSemaforo;
	var filtroSexo = parametros.filtroSexo;

	chart.destroy();

	$.ajax({
		data: {
			peticion: "buscarRegistros",
			pagina: pagina,
			numeroitemsporpagina: 40,
			filtro: filtroRegistro,
			filtroPor: filtroPorRegistro,
			orden: ordenShowRegistro,
			filtroSemaforo: filtroSemaforo,
			filtroSexo: filtroSexo,
		}, //datos a enviar a la url
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
					$('[data-bs-toggle="popover"]').popover();
					//--------------------------------
					$("#dttableListado").DataTable().rows.add(result.datos).draw();

					if (result.isAdmin) {
						$("#dttableListado").DataTable().column(13).visible(true);
                        $("#dttableListado").DataTable().buttons(".buttons-excel").nodes().css("display", "block");
					} else {
						$("#dttableListado").DataTable().column(13).visible(false);
                        $("#dttableListado").DataTable().buttons(".buttons-excel").nodes().css("display", "none");
					}

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

					// Cargamos la estdistica de la grafica
					// Graficamos la seleccion
					if (result.dataGrafica.status == 1) {
						$("#containerGrafica").toggleClass("hidden", false);
						chart = new Chart(canvaGrafica, {
							options: {
								responsive: true,
								maintainAspectRatio: false,
								plugins: {
									title: {
										display: true,
										text: "Grafica votos",
										font: {
											size: 20,
										},
									},
								},
                                legend: false,
                                tooltips: {
                                    enabled: true
                                }
							},
							type: "doughnut",
							data: {
								labels: result.dataGrafica.labels,
								datasets: result.dataGrafica.dataSet,
							},
						});
						chart.resize(300, 300);
					} else {
						$("#containerGrafica").toggleClass("hidden", true);
					}
					break;
			}
		},
		complete: function () {
			$("#dttableListado").DataTable().columns.adjust().draw();
			const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
			const tooltipList = [...tooltipTriggerList].map((tooltipTriggerEl) => new bootstrap.Tooltip(tooltipTriggerEl));
		},
		error: function (xhr) {
			console.log(xhr);
		},
	});
}

const arrayTipos = {
	0: "elector",
	1: "lider",
};

var arrayTagifySector = [];
function modelRegistro(tipo, idElector = null, lider = null, nombreLider = null) {
	if ($.isNumeric(tipo)) {
		var titulo = `Registrar ${arrayTipos[tipo]}`;
		let htmlAdicion = "";
		switch (tipo) {
			case 0:
				titulo = lider != null ? `Registrar referido del lider <strong class="color-verde">${nombreLider}</strong>` : titulo;
				htmlAdicion += `
                <div class="row recuadro mb-10">
                    <div class="col-xs-12 col-md-12 col-lg-12">
                        <a class="tooltips">
                            <label class="negrita" for="selectLideres">Lider que refiere</label>
                            <div class="form-group">
                                <select class="form-control select" id="selectLideres" name="selectLideres" title="Seleccione lider"></select>
                                <i class="fa-solid fa-people-group"></i>
                            </div>
                            <span class="spanValidacion hidden"></span>
                        </a>
                    </div>
                </div>
                <div class="row recuadro mb-10">
                    <div class="col-xs-12 col-md-12 col-lg-12">
                        <a class="tooltips">
                            <label class="negrita" for="selectSemaforo">Semaforo de votacion</label>
                            <div class="form-group">
                                <select class="form-control requerido select" id="selectSemaforo" name="selectSemaforo" title="Seleccione semaforo"></select>
                                <i class="fa-solid fa-traffic-light"></i>
                            </div>
                            <span class="spanValidacion hidden"></span>
                        </a>
                    </div>
                </div>`;
				break;

			case 1:
				htmlAdicion = `
                <div class="row recuadro mb-10">
                    <div class="col-xs-12 col-md-12 col-lg-12">
                        <a class="tooltips">
                            <label class="negrita" for="selectSectorLider">Sector liderazgo</label>
                            <div class="form-group">
                                <input type="text" class="form-control requerido" id="selectSectorLider" name="selectSectorLider" title="Seleccione el sector lider">
                                <i class="fa-solid fa-diagram-project"></i>
                            </div>
                            <span class="spanValidacion hidden"></span>
                        </a>
                    </div>
                </div>`;
				break;
		}
		var htmlForm = `<form id="frmRegistro" class="container">
            <div class="row recuadro mb-10 pt-10">
                <div class="col-xs-12 col-md-12 col-lg-12">
                    <a class="tooltips">
                        <label class="negrita" for="documento">Documento</label>
                        <div class="form-group">
                            <input type="text" class="form-control numerico requerido" name="documento" id="documento" title="Documento del ${arrayTipos[tipo]}" onkeypress="validarElector(this.value, ${tipo})" onkeyup="validarElector(this.value, ${tipo})">
                            <i class="fa-regular fa-address-card"></i>
                        </div>
                        <span class="spanValidacion hidden"></span>
                    </a>
                </div>
                <div class="col-xs-12 col-md-12 col-lg-12">
                    <a class="tooltips">
                        <label class="negrita" for="nombres">Nombres</label>
                        <div class="form-group">
                            <input type="text" class="form-control requerido maxlength-input" name="nombres" id="nombres" title="Nombres del ${arrayTipos[tipo]}" minlength="1" maxlength="200">
                            <i class="fa-regular fa-user"></i>
                        </div>
                        <span class="spanValidacion hidden"></span>
                    </a>
                </div>
                <div class="col-xs-12 col-md-12 col-lg-12">
                    <a class="tooltips">
                        <label class="negrita" for="apellidos">Apellidos</label>
                        <div class="form-group">
                            <input type="text" class="form-control requerido maxlength-input" name="apellidos" id="apellidos" title="Apellidos del ${arrayTipos[tipo]}" minlength="1" maxlength="200">
                            <i class="fa-regular fa-user"></i>
                        </div>
                        <span class="spanValidacion hidden"></span>
                    </a>
                </div>
                <div class="col-xs-12 col-md-12 col-lg-12 mb-10">
                    <a class="tooltips">
                        <label class="negrita" for="fechaNacimiento">Fecha nacimiento</label>
                        <div class="input-group" style="margin-bottom: 0;">
                            <span class="input-group-append">
                                <label class="input-group-text"><i class="ik ik-calendar"></i></label>
                            </span>
                            <input type="text" class="form-control datepicker-readonly datepicker-filtro validarDP text-capitalize" id="fechaNacimiento" readonly>
                        </div>
                        <span class="spanValidacion hidden"></span>
                    </a>
                </div>
            </div>
            <div class="row recuadro mb-10 pt-10">
                <div class="col-xs-12 col-md-12 col-lg-12">
                    <a class="tooltips">
                        <label class="negrita" for="direccion">Direccion</label>
                        <div class="form-group">
                            <input type="text" class="form-control requerido maxlength-input" name="direccion" id="direccion" title="Direccion del ${arrayTipos[tipo]}" minlength="1" maxlength="100">
                            <i class="fa-solid fa-map-location-dot"></i>
                        </div>
                        <span class="spanValidacion hidden"></span>
                    </a>
                </div>
                <div class="col-xs-12 col-md-12 col-lg-12">
                    <a class="tooltips">
                        <label class="negrita" for="telefono">Numero de Telefono</label>
                        <div class="form-group">
                            <input type="text" class="form-control requerido numerico maxlength-input" name="telefono" id="telefono" title="Telefono del ${arrayTipos[tipo]}" minlength="1" maxlength="10">
                            <i class="fa-solid fa-phone"></i>
                        </div>
                        <span class="spanValidacion hidden"></span>
                    </a>
                </div>
                <div class="col-xs-12 col-md-12 col-lg-12">
                    <a class="tooltips">
                        <label class="negrita" for="selectSexo">Sexo del ${arrayTipos[tipo]}</label>
                        <div class="form-group">
                            <select class="form-control requerido select" id="selectSexo" name="selectSexo" title="Seleccione Sexo">
                                <option value="">Seleccione</option>
                                <option value="F">Femenino</option>
                                <option value="M">Masculino</option>
                            </select>
                            <i class="fa-solid fa-venus-mars"></i>
                        </div>
                        <span class="spanValidacion hidden"></span>
                    </a>
                </div>
            </div>
            <div class="row recuadro pt-10">
                <div class="col-xs-12 col-md-12 col-lg-12">
                    <a class="tooltips">
                        <label class="negrita" for="observacion">Observación</label>
                        <div class="form-group">
                            <textarea name="observacion" id="observacion" class="form-control maxlength-textarea requerido" title="Observación para ${arrayTipos[tipo]}" minlength="1" maxlength="500"></textarea>
                            <i class="fa-solid fa-comments"></i>
                        </div>
                        <span class="spanValidacion hidden"></span>
                    </a>
                </div>
            </div>
            ${htmlAdicion}
            <div class="row recuadro mb-10 pt-10">
                <h5 class="negrita">Informacion de votacion</h5>
                <div class="col-xs-12 col-md-12 col-lg-12">
                    <a class="tooltips">
                        <label class="negrita" for="selectInformacionVotacion">Puesto de votacion del ${arrayTipos[tipo]}</label>
                        <div class="form-group">
                            <select class="form-control requerido select" id="selectInformacionVotacion" name="selectInformacionVotacion" title="Seleccione puesto de votación"></select>
                            <i class="fa-solid fa-info"></i>
                        </div>
                        <span class="spanValidacion hidden"></span>
                    </a>
                </div>
            </div>
        </form>`;
		Swal.fire({
			position: "top",
			iconHtml: '<i class="fa-solid fa-users-viewfinder"></i>',
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
			confirmButtonText: "Registrar",
			cancelButtonColor: "#F22613",
			cancelButtonText: "Cancelar ",
			backdrop: true,
			allowEscapeKey: false,
			allowOutsideClick: false,
			width: 700,
			showLoaderOnConfirm: true,
			showLoaderOnDeny: true,
			didOpen: () => {
				$(".numerico").inputmask("numerico");
				$("textarea.maxlength-textarea").maxlength({
					alwaysShow: true,
					warningClass: "hoverMaxlength hoverMaxlength-success",
					limitReachedClass: "hoverMaxlength hoverMaxlength-danger",
					placement: "top",
					validate: true,
				});
				$("input.maxlength-input").maxlength({
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
				setTimeout(() => {
					new Tagify(document.getElementById("selectSectorLider"), {
						whitelist: arrayTagifySector,
						enforceWhitelist: false,
						maxTags: 1,
						dropdown: {
							maxItems: 20,
							classname: "tags-look",
							enabled: 0,
							closeOnSelect: false,
						},
						originalInputValueFormat: (valuesArr) => valuesArr.map((item) => item.value),
					});
				}, 100);
				initDateDropper(["fechaNacimiento"], 1, 0);
				// setDateDropper("fechaConsejo", isEmpty(fechaConsejoU) ? moment().format("YYYY-MM-DD") : fechaConsejoU, 1);
			},
			willOpen: () => {
				cargarSelect("SectorLider", false, "Seleccione sector liderazgo", null, null, "SectorLider", false, true, "arrayTagifySector");
				cargarSelect("Semaforo", false, "Seleccione semaforo");
				cargarSelect("InformacionVotacion", false, "Seleccione puesto de votación");
				if (tipo == 0) {
					cargarSelect("Lideres", false, "Seleccione lider");
					if (lider != null) {
						setTimeout(() => {
							$("#selectLideres").val(idElector).trigger("change").prop("disabled", true);
						}, 300);
					}
				}
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
				let fecha = obtenerFechaDateDropper(1, "fechaNacimiento", false);
				if (validarcampos("#frmRegistro") && !isEmpty(fecha)) {
					return registrarDatos("frmRegistro", tipo, lider)
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
			},
		});
	}
}

function registrarDatos(form, tipo, lider) {
	return new Promise((resolve, reject) => {
		var formData = new FormData(document.getElementById(form));
		formData.append("peticion", "registrarDatos");
		formData.append("tipo", tipo);
		formData.append("lider", lider);
		formData.append("fechaNacimiento", obtenerFechaDateDropper(1, "fechaNacimiento", false));
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

					case 2:
						reject("Error guardando datos en informacion de votacion.");
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

function datosElector(idElector, isLider, isUpdate, isAdmin) {
	if ($.isNumeric(idElector)) {
		$.ajax({
			data: {
				peticion: "datosElector",
				id: idElector,
			}, //datos a enviar a la url
			dataType: "json", //Si no se especifica jQuery automaticamente encontrará el tipo basado en el header del archivo llamado (pero toma mas tiempo en cargar, asi que especificalo)
			url: urlController, //url a donde hacemos la peticion
			type: "POST",
			beforeSend: function () {
				$("#overlayText").text("Consultando datos...");
				$(".overlayCargue").fadeIn("slow");
			},
			success: function (result) {
				var estado = result.status;
				switch (estado) {
					case 0:
						Swal.fire({
							icon: "error",
							position: "top",
							title: "<strong>Ha ocurrido un error</strong>",
							html: "<h5>Se ha presentando inconvenientes en la consulta de los datos, intentalo mas tarde, gracias.</h5>",
							showCloseButton: false,
							showCancelButton: false,
							confirmButtonText: "Entendido",
							confirmButtonColor: "#13844e",
							backdrop: true,
							allowOutsideClick: false,
							allowEscapeKey: false,
							allowEnterKey: false,
						});
						break;

					case 1:
						let htmlAdicion = "";
						var tipo = null;
						if (isLider == 1) {
							tipo = 1;
							htmlAdicion = `
                            <div class="row recuadro mb-10">
                                <div class="col-xs-12 col-md-12 col-lg-12">
                                    <a class="tooltips">
                                        <label class="negrita" for="selectSectorLider">Sector liderazgo</label>
                                        <div class="form-group">
                                            <input type="text" class="form-control requerido" id="selectSectorLider" name="selectSectorLider" title="Seleccione el sector lider">
                                            <i class="fa-solid fa-diagram-project"></i>
                                        </div>
                                        <span class="spanValidacion hidden"></span>
                                    </a>
                                </div>
                            </div>`;
						} else {
							tipo = 0;
							htmlAdicion += `
                            <div class="row recuadro mb-10">
                                <div class="col-xs-12 col-md-12 col-lg-12">
                                    <a class="tooltips">
                                        <label class="negrita" for="selectLideres">Lider que refiere</label>
                                        <div class="form-group">
                                            <select class="form-control select" id="selectLideres" name="selectLideres" title="Seleccione lider"></select>
                                            <i class="fa-solid fa-people-group"></i>
                                        </div>
                                        <span class="spanValidacion hidden"></span>
                                    </a>
                                </div>
                            </div>
                            <div class="row recuadro mb-10">
                                <div class="col-xs-12 col-md-12 col-lg-12">
                                    <a class="tooltips">
                                        <label class="negrita" for="selectSemaforo">Semaforo de votacion</label>
                                        <div class="form-group">
                                            <select class="form-control requerido select" id="selectSemaforo" name="selectSemaforo" title="Seleccione semaforo"></select>
                                            <i class="fa-solid fa-traffic-light"></i>
                                        </div>
                                        <span class="spanValidacion hidden"></span>
                                    </a>
                                </div>
                            </div>`;
						}
						var titulo = `${isUpdate ? "Editar datos del " : "Datos del "} ${arrayTipos[tipo]}`;
						var htmlForm = `<form id="frmRegistro" class="container">
                            <div class="row recuadro mb-10 pt-10">
                                <div class="col-xs-12 col-md-12 col-lg-12">
                                    <a class="tooltips">
                                        <label class="negrita" for="documento">Documento</label>
                                        <div class="form-group">
                                            <input value="${result.documento}" type="text" class="form-control numerico" name="documento" id="documento" title="Documento del ${arrayTipos[tipo]}" ${isAdmin == 1 ? "" : "disabled"}>
                                            <i class="fa-regular fa-address-card"></i>
                                        </div>
                                        <span class="spanValidacion hidden"></span>
                                    </a>
                                </div>
                                <div class="col-xs-12 col-md-12 col-lg-12">
                                    <a class="tooltips">
                                        <label class="negrita" for="nombres">Nombres</label>
                                        <div class="form-group">
                                            <input value="${result.nombres}" type="text" class="form-control requerido maxlength-input" name="nombres" id="nombres" title="Nombres del ${arrayTipos[tipo]}" minlength="1" maxlength="200">
                                            <i class="fa-regular fa-user"></i>
                                        </div>
                                        <span class="spanValidacion hidden"></span>
                                    </a>
                                </div>
                                <div class="col-xs-12 col-md-12 col-lg-12">
                                    <a class="tooltips">
                                        <label class="negrita" for="apellidos">Apellidos</label>
                                        <div class="form-group">
                                            <input value="${result.apellidos}" type="text" class="form-control requerido maxlength-input" name="apellidos" id="apellidos" title="Apellidos del ${arrayTipos[tipo]}" minlength="1" maxlength="200">
                                            <i class="fa-regular fa-user"></i>
                                        </div>
                                        <span class="spanValidacion hidden"></span>
                                    </a>
                                </div>
                                <div class="col-xs-12 col-md-12 col-lg-12 mb-10">
                                    <a class="tooltips">
                                        <label class="negrita" for="fechaNacimiento">Fecha nacimiento</label>
                                        <div class="input-group" style="margin-bottom: 0;">
                                            <span class="input-group-append">
                                                <label class="input-group-text"><i class="ik ik-calendar"></i></label>
                                            </span>
                                            <input type="text" class="form-control datepicker-readonly datepicker-filtro validarDP text-capitalize" id="fechaNacimiento" readonly>
                                        </div>
                                        <span class="spanValidacion hidden"></span>
                                    </a>
                                </div>
                            </div>
                            <div class="row recuadro mb-10 pt-10">
                                <div class="col-xs-12 col-md-12 col-lg-12">
                                    <a class="tooltips">
                                        <label class="negrita" for="direccion">Direccion</label>
                                        <div class="form-group">
                                            <input value="${result.direccion}" type="text" class="form-control requerido maxlength-input" name="direccion" id="direccion" title="Direccion del ${arrayTipos[tipo]}" minlength="1" maxlength="100">
                                            <i class="fa-solid fa-map-location-dot"></i>
                                        </div>
                                        <span class="spanValidacion hidden"></span>
                                    </a>
                                </div>
                                <div class="col-xs-12 col-md-12 col-lg-12">
                                    <a class="tooltips">
                                        <label class="negrita" for="telefono">Numero de Telefono</label>
                                        <div class="form-group">
                                            <input value="${result.telefono}" type="text" class="form-control requerido numerico maxlength-input" name="telefono" id="telefono" title="Telefono del ${arrayTipos[tipo]}" minlength="1" maxlength="10">
                                            <i class="fa-solid fa-phone"></i>
                                        </div>
                                        <span class="spanValidacion hidden"></span>
                                    </a>
                                </div>
                                <div class="col-xs-12 col-md-12 col-lg-12">
                                    <a class="tooltips">
                                        <label class="negrita" for="selectSexo">Sexo del ${arrayTipos[tipo]}</label>
                                        <div class="form-group">
                                            <select class="form-control requerido select" id="selectSexo" name="selectSexo" title="Seleccione Sexo">
                                                <option value="">Seleccione</option>
                                                <option value="F">Femenino</option>
                                                <option value="M">Masculino</option>
                                            </select>
                                            <i class="fa-solid fa-venus-mars"></i>
                                        </div>
                                        <span class="spanValidacion hidden"></span>
                                    </a>
                                </div>
                            </div>
                            <div class="row recuadro pt-10">
                                <div class="col-xs-12 col-md-12 col-lg-12">
                                    <a class="tooltips">
                                        <label class="negrita" for="observacion">Observación</label>
                                        <div class="form-group">
                                            <textarea name="observacion" id="observacion" class="form-control maxlength-textarea requerido" title="Observación para ${arrayTipos[tipo]}" minlength="1" maxlength="500">${result.observacion}</textarea>
                                            <i class="fa-solid fa-comments"></i>
                                        </div>
                                        <span class="spanValidacion hidden"></span>
                                    </a>
                                </div>
                            </div>
                            ${htmlAdicion}
                            <div class="row recuadro mb-10 pt-10">
                                <h5 class="negrita">Informacion de votacion</h5>
                                <div class="col-xs-12 col-md-12 col-lg-12">
                                    <a class="tooltips">
                                        <label class="negrita" for="selectInformacionVotacion">Puesto de votacion del ${arrayTipos[tipo]}</label>
                                        <div class="form-group">
                                            <select class="form-control requerido select" id="selectInformacionVotacion" name="selectInformacionVotacion" title="Seleccione puesto de votación"></select>
                                            <i class="fa-solid fa-info"></i>
                                        </div>
                                        <span class="spanValidacion hidden"></span>
                                    </a>
                                </div>
                            </div>
                        </form>`;
						Swal.fire({
							position: "top",
							iconHtml: '<i class="fa-solid fa-users-viewfinder"></i>',
							customClass: {
								icon: "swal2-icon-custom",
							},
							title: titulo,
							html: htmlForm,
							showCloseButton: true,
							showCancelButton: true,
							showConfirmButton: isUpdate,
							showDenyButton: false,
							confirmButtonColor: "#13844e",
							confirmButtonText: `${isUpdate ? "Guardar datos" : ""}`,
							cancelButtonColor: "#F22613",
							cancelButtonText: "Cancelar ",
							backdrop: true,
							allowEscapeKey: false,
							allowOutsideClick: false,
							width: 700,
							showLoaderOnConfirm: true,
							showLoaderOnDeny: true,
							didOpen: () => {
								$(".numerico").inputmask("numerico");
								$("textarea.maxlength-textarea").maxlength({
									alwaysShow: true,
									warningClass: "hoverMaxlength hoverMaxlength-success",
									limitReachedClass: "hoverMaxlength hoverMaxlength-danger",
									placement: "top",
									validate: true,
								});
								$("input.maxlength-input").maxlength({
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
								setTimeout(() => {
									$("#selectSectorLider").val(result.sector_tag).trigger("change");
									new Tagify(document.getElementById("selectSectorLider"), {
										whitelist: arrayTagifySector,
										enforceWhitelist: false,
										maxTags: 1,
										dropdown: {
											maxItems: 20,
											classname: "tags-look",
											enabled: 0,
											closeOnSelect: false,
										},
										originalInputValueFormat: (valuesArr) => valuesArr.map((item) => item.value),
									});
								}, 200);
								initDateDropper(["fechaNacimiento"], 1, 0);
								setDateDropper("fechaNacimiento", result.fecha_nacimiento, 1);
							},
							willOpen: () => {
								cargarSelect("SectorLider", false, "Seleccione sector liderazgo", null, null, "SectorLider", false, true, "arrayTagifySector");
								cargarSelect("Semaforo", false, "Seleccione semaforo");
								cargarSelect("InformacionVotacion", false, "Seleccione puesto de votación");
								if (tipo == 0) {
									cargarSelect("Lideres", false, "Seleccione lider");
									setTimeout(() => {
										$("#selectLideres").val(result.id_lider).trigger("change");
										if (isAdmin == 0) {
											$("#selectLideres").prop("disabled", true);
										}
									}, 400);
								}
								setTimeout(() => {
									$("#selectSemaforo").val(result.id_semaforo).trigger("change");
									$("#selectSexo").val(result.sexo).trigger("change");
									$("#selectInformacionVotacion").val(result.id_informacion_votacion).trigger("change");
									if (isAdmin == 0) {
										$("#selectInformacionVotacion").prop("disabled", true);
									}
								}, 400);
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
								let fecha = obtenerFechaDateDropper(1, "fechaNacimiento", false);
								if (validarcampos("#frmRegistro") && !isEmpty(fecha)) {
									return editarDatos("frmRegistro", idElector, isLider)
										.then((response) => {
											if (response.status == 1) {
												Swal.fire({
													icon: "success",
													title: titulo,
													html: `<h5>Se ha actualizado los datos con exito</h5>`,
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
							},
						});
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
	}
}

function editarDatos(form, idElector, isLider) {
	return new Promise((resolve, reject) => {
		var formData = new FormData(document.getElementById(form));
		formData.append("peticion", "editarDatos");
		formData.append("id", idElector);
		formData.append("fechaNacimiento", obtenerFechaDateDropper(1, "fechaNacimiento", false));
		formData.append("isLider", isLider);
		$.ajax({
			cache: false, //necesario para enviar archivos
			contentType: false, //necesario para enviar archivos
			processData: false, //necesario para enviar archivos
			data: formData, //datos a enviar a la url
			dataType: "json", //Si no se especifica jQuery automaticamente encontrará el tipo basado en el header del archivo llamado (pero toma mas tiempo en cargar, asi que especificalo)
			url: urlController, //url a donde hacemos la peticion
			type: "POST",
			beforeSend: function () {
				$("#overlayText").text("Actualizando datos...");
				$(".overlayCargue").fadeIn("slow");
			},
			success: function (result) {
				var estado = result.status;
				switch (estado) {
					case 0:
						reject("Ha ocurrido un problema actualizando los datos.");
						break;

					case 1:
						resolve(result);
						break;

					case 2:
						reject("Error guardando datos en informacion de votacion.");
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

function listaReferidos(idLider) {
	if ($.isNumeric(idLider)) {
		var htmlForm = `<div class="container row">
            <div class="col-xs-12 col-md-12 col-lg-12 recuadro mt-4">
                <table id="dttableListadoModal" class="table w-100 ml-0 table-hover" width="100%"></table>
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="mt-4 d-flex aligns-items-center justify-content-between">
                        <div class="negrita info-pagination-registro"></div>
                    </div>
                </div>
            </div>
        </div>`;
		$.ajax({
			data: {
				peticion: "listaReferidos",
				id: idLider,
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
					case 1:
						Swal.fire({
							position: "top",
							title: `<strong>Listado de electores referidos del lider ${result.lider}</strong>`,
							html: htmlForm,
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
										{ title: "DOCUMENTO", className: "text-center text-nowrap", responsivePriority: 1 },
										{ title: "NOMBRE VOTANTE", className: "text-center text-nowrap", responsivePriority: 1 },
										{ title: "TELEFONO", className: "text-center text-nowrap", responsivePriority: 1 },
										{ title: "EDAD", className: "text-center text-nowrap", responsivePriority: 1 },
										{ title: "DIRECCIÓN", className: "text-center text-nowrap", responsivePriority: 1 },
										{ title: "SEMAFORO", className: "text-center text-nowrap", responsivePriority: 1 },
										{ title: "PUESTO DE VOTACIÓN", className: "text-center text-nowrap", responsivePriority: 1 },
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
                                $("#dttableListadoModal").siblings("div").find(".info-pagination-registro").html("Página 1 de 1 - Total de registros: " + result.totalRegistros);
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

function validarElector(documento, tipo) {
	if (!isEmpty(documento)) {
		$.ajax({
			data: {
				peticion: "validarElector",
				documento: documento,
			},
			dataType: "json", //Si no se especifica jQuery automaticamente encontrará el tipo basado en el header del archivo llamado (pero toma mas tiempo en cargar, asi que especificalo)
			url: urlController, //url a donde hacemos la peticion
			type: "POST",
			beforeSend: function () {},
			success: function (result) {
				var estado = result.status;
				switch (estado) {
					case 0:
						// No existe
						$(Swal.getConfirmButton()).prop("disabled", false);
						Swal.resetValidationMessage();
						break;

					case 1:
						// Si existe
						$("#documento").parents(".tooltips").find(".spanValidacion").toggleClass("hidden", false).text("El documento ya se encuentra registrado.");
						$(Swal.getConfirmButton()).prop("disabled", true);
						Swal.showValidationMessage(`Ya existe un elector con el documento numero ${documento}`);
						break;
				}
			},
			complete: function () {},
			error: function (xhr) {
				console.error(xhr);
			},
		});
	}
}
