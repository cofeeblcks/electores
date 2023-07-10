$(document).ready(function () {
	fechaHora();
});

// Estableciendo fecha hora actual para mostrar
var dia_semana = new Array();
dia_semana[0] = "Domingo";
dia_semana[1] = "Lunes";
dia_semana[2] = "Martes";
dia_semana[3] = "Miercoles";
dia_semana[4] = "Jueves";
dia_semana[5] = "Viernes";
dia_semana[6] = "Sabado";

var mes_anio = new Array();
mes_anio[1] = "Enero";
mes_anio[2] = "Febrero";
mes_anio[3] = "Marzo";
mes_anio[4] = "Abril";
mes_anio[5] = "Mayo";
mes_anio[6] = "Junio";
mes_anio[7] = "Julio";
mes_anio[8] = "Agosto";
mes_anio[9] = "Septiembre";
mes_anio[10] = "Octubre";
mes_anio[11] = "Noviembre";
mes_anio[12] = "Diciembre";

function fechaHora() {
	var hoy = new Date();
	var hora = hoy.getHours();
	var minutos = hoy.getMinutes();
	var segundos = hoy.getSeconds();

	if (hora < 10) {
		hora = "0" + hora;
	}
	if (minutos < 10) {
		minutos = "0" + minutos;
	}

	if (segundos < 10) {
		segundos = "0" + segundos;
	}

	fecha_actual = dia_semana[hoy.getDay()] + ", " + hoy.getDate() + " de " + mes_anio[hoy.getMonth() + 1] + " de " + hoy.getFullYear();
	hora_actual = hora + ":" + minutos + ":" + segundos;

	setTimeout(function () {
		$("#fechaHora").html('<i class="fa-solid fa-clock"> ' + fecha_actual + " " + hora_actual);
		fechaHora();
	}, 1000);
}

var formatos = ["F/d/Y", "l, d de F del Y", "F del Y", "Y", "F"];

//obtiene los datos del datedropper
function obtenerFechaDateDropper(tipo, idInicial, idFinal, requerido, clase = 1) {
	var identificador = "#";
	switch (clase) {
		case 2:
			identificador = ".";
			break;
		case 3:
			identificador = "";
			idInicial = 'input[name="' + idInicial + '"]';
			idFinal = 'input[name="' + idFinal + '"]';
			break;
		default:
			break;
	}
	var fechaRetorno = "";
	switch (tipo) {
		case 1:
			$(identificador + idInicial).dateDropper("getDate", function (date) {
				fechaRetorno = date.Y + "-" + date.m + "-" + date.d;
			});
			break;
		case 2:
			$(identificador + idInicial).dateDropper("getDate", function (date) {
				fechaInicial = date.Y + "-" + date.m + "-" + date.d;
			});
			$(identificador + idFinal).dateDropper("getDate", function (date) {
				fechaFinal = date.Y + "-" + date.m + "-" + date.d;
			});
			fechaRetorno = fechaInicial + "," + fechaFinal;
			break;
		case 3:
			$(identificador + idInicial).dateDropper("getDate", function (date) {
				fechaRetorno = date.Y;
			});
			break;
		case 4:
			$(identificador + idInicial).dateDropper("getDate", function (date) {
				fechaRetorno = date.Y + "-" + date.m;
			});
			break;
		case 5:
			$(identificador + idInicial).dateDropper("getDate", function (date) {
				fechaRetorno = date.m;
			});
			break;
	}

	return fechaRetorno;
}

// Evento de cambio sobre cualquiera de las fechas
// $(".validarDP").dateDropper({
//     onChange: function (res){
//         showRegistros();
//     }
// });

function initDateDropper(ids, formato = 0, tema = 0, rangos = [], clase = 1, max = false, min = false) {
	var temas = ["ryanair"];
	var identificador = "#";
	switch (clase) {
		case 2:
			identificador = ".";
			break;
		case 3:
			identificador = "";
			break;
		default:
			break;
	}
	for (let index = 0; index < ids.length; index++) {
		$(identificador + ids[index]).dateDropper({
			format: formatos[formato],
			lang: "es",
			theme: temas[tema],
			large: true,
			largeDefault: true,
			modal: true,
			hideDay: formato == 2 || formato == 4 ? true : false,
			hideYear: formato == 4 ? true : false,
			maxDate: max ? moment().format("MM/DD/YYYY") : false,
			minDate: min ? moment().format("MM/DD/YYYY") : false,
			onChange: function (res) {
				// showRegistros();
			},
		});
	}
	if (rangos.length == 2) {
		switch (formato) {
			case 4:
				//FECHA INICIAL
				$(identificador + ids[rangos[0]]).dateDropper("set", {
					maxDate: moment().format("MM/DD/YYYY"),
				});
				//FECHA FINALIZA_LOOP
				$(identificador + ids[rangos[1]]).dateDropper("set", {
					onChange: function (res) {
						$(identificador + ids[rangos[0]]).dateDropper("getDate", function (date) {
							if (res.date.U < date.U) {
								$(identificador + ids[rangos[0]]).dateDropper("set", {
									maxDate: res.date.m + "/" + res.date.d + "/" + res.date.Y,
									defaultDate: res.date.m + "/" + res.date.d + "/" + res.date.Y,
								});
								$(identificador + ids[rangos[0]]).val(res.date.F);
							} else {
								$(identificador + ids[rangos[0]]).dateDropper("set", {
									maxDate: res.date.m + "/" + res.date.d + "/" + res.date.Y,
									defaultDate: date.m + "/" + date.d + "/" + date.Y,
								});
							}
						});
					},
				});
				break;

			default:
				//FECHA INICIAL
				$(identificador + ids[rangos[0]]).dateDropper("set", {
					maxDate: moment().format("MM/DD/YYYY"),
				});
				//FECHA FINALIZA_LOOP
				$(identificador + ids[rangos[1]]).dateDropper("set", {
					onChange: function (res) {
						$(identificador + ids[rangos[0]]).dateDropper("getDate", function (date) {
							if (res.date.U < date.U) {
								$(identificador + ids[rangos[0]]).dateDropper("set", {
									maxDate: res.date.m + "/" + res.date.d + "/" + res.date.Y,
									defaultDate: res.date.m + "/" + res.date.d + "/" + res.date.Y,
								});
								$(identificador + ids[rangos[0]]).val(res.date.l + ", " + res.date.d + " de " + res.date.F + " del " + res.date.Y);
							} else {
								$(identificador + ids[rangos[0]]).dateDropper("set", {
									maxDate: res.date.m + "/" + res.date.d + "/" + res.date.Y,
									defaultDate: date.m + "/" + date.d + "/" + date.Y,
								});
							}
						});
					},
				});
				break;
		}
	}
}

function setDateDropper(id, fecha, formato = 0, clase = 1) {
	var identificador = "#";
	switch (clase) {
		case 2:
			identificador = ".";
			break;
		case 3:
			identificador = "";
			id = 'input[name="' + id + '"]';
			break;
		default:
			break;
	}
	$(identificador + id).dateDropper("set", {
		defaultDate: moment(fecha).format("MM/DD/YYYY"),
	});
	$(identificador + id).dateDropper("getDate", function (date) {
		switch (formato) {
			case 0:
				$(identificador + id).val(date.F + "/" + date.d + "/" + date.Y);
				break;
			case 1:
				$(identificador + id).val(date.l + ", " + date.d + " de " + date.F + " del " + date.Y);
				break;
			case 2:
				$(identificador + id).val(date.F + " del " + date.Y);
				break;
			case 3:
				$(identificador + id).val(date.Y);
				break;
			case 4:
				$(identificador + id).val(date.F);
				break;
			default:
				$(identificador + id).val(date.F + "/" + date.d + "/" + date.Y);
				break;
		}
	});
}

function totalDias(fechaInicial, fechaFinal, tipo) {
	var inicio = moment(fechaInicial);
	var final = moment(fechaFinal);
	return final.diff(inicio, tipo) + 1;
}

function calcularEdad(fecha) {
	var hoy = new Date();
	var cumpleanos = new Date(fecha);
	var edad = hoy.getFullYear() - cumpleanos.getFullYear();
	var m = hoy.getMonth() - cumpleanos.getMonth();
	if (m < 0 || (m === 0 && hoy.getDate() < cumpleanos.getDate())) {
		edad--;
	}
	return edad;
}