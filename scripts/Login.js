var urlControllerLogin = urlBase + "php/controller/ControllerLogin.php";

function verificarLogin() {
	$.ajax({
		data: { peticion: "checkLogin" }, //necesario para enviar archivos
		dataType: "json", //Si no se especifica jQuery automaticamente encontrará el tipo basado en el header del archivo llamado (pero toma mas tiempo en cargar, asi que especificalo)
		url: urlControllerLogin, //url a donde hacemos la peticion
		type: "POST",
		beforeSend: function () {
			$("#overlayText").text("Cargando Modulos Usuario...");
			$(".overlayCargue").fadeIn("slow");
		},
		complete: function () {
			$(".overlayCargue").fadeOut("slow");

			var last = window.location.pathname.lastIndexOf("/") + 1;
			var pagina = window.location.pathname.substring(last).replace(".php", "");
			$("a[href='" + pagina + "']").addClass("active");

			$("a[href='" + pagina + "']")
				.parent(".submenu-content")
				.parent(".nav-item")
				.children("a")
				.addClass("active");

			$("a[href='" + pagina + "']")
				.parent(".submenu-content")
				.parent(".nav-item")
				.addClass("open");
		},
		success: function (result) {
			var estado = result.status;
			switch (estado) {
				case "0":
					window.location.replace(urlBase + "Login");
					break;
				case "1":
					$("#user-top").text(result.usuario.toUpperCase());
					$("#containerMenu").html(result.menu);
					break;

				default:
					window.location.replace(urlBase + "Login");
					break;
			}
		},
		error: function (xhr) {
			console.log(xhr);
		},
	});
}

function Login(form) {
	var respuestavalidacion = validarcampos("#" + form);
	if (respuestavalidacion) {
		$("#btnLoginIngresar").prop("disabled", true);
		var data = new Object();
		data["datos"] = $("#" + form).serialize();
		data["peticion"] = "Login";
		$.ajax({
			// cache:       false,//necesario para enviar archivos
			// contentType: false,//necesario para enviar archivos
			// processData: false,//necesario para enviar archivos
			data: data, //necesario para enviar archivos
			dataType: "json", //Si no se especifica jQuery automaticamente encontrará el tipo basado en el header del archivo llamado (pero toma mas tiempo en cargar, asi que especificalo)
			url: urlControllerLogin, //url a donde hacemos la peticion
			type: "POST",
			beforeSend: function () {
				$("#overlayText").text("Iniciando Sesion...");
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
						showToast("Usuario no existe!", "El usuario no se encuentra registrado en la plataforma.", "error", "derecha", 6000);
						break;

					case "1":
						if (result.url != null) {
							window.location.replace(urlBase + result.url);
						} else {
							window.location.replace(urlBase + "Inicio");
						}
						break;

					case "2":
                        Swal.fire({
                            icon: 'warning',
                            position: "top",
                            title: "<strong>Contraseña</strong>",
                            html: "<h5>La contraseña del usuario esta errada.</h5>",
                            showCloseButton: false,
                            showCancelButton: false,
                            confirmButtonText: "Entendido",
                            confirmButtonColor: "#13844e",
                            backdrop: true,
                            allowOutsideClick: false,
                            allowEscapeKey: false,
                            allowEnterKey: false,
                        })
						break;

					case "3":
                        Swal.fire({
                            icon: 'warning',
                            position: "top",
                            title: "<strong>Datos Ingresados</strong>",
                            html: "<h5>Uno de los campos ingresados no es correcto.</h5>",
                            showCloseButton: false,
                            showCancelButton: false,
                            confirmButtonText: "Entendido",
                            confirmButtonColor: "#13844e",
                            backdrop: true,
                            allowOutsideClick: false,
                            allowEscapeKey: false,
                            allowEnterKey: false,
                        })
						break;

					case "4":
                        Swal.fire({
                            icon: 'warning',
                            position: "top",
                            title: "<strong>Usuario Logueado</strong>",
                            html: "<h5>El usuario ya se encuentra logueado en otra sesion.</h5>",
                            showCloseButton: false,
                            showCancelButton: false,
                            confirmButtonText: "Entendido",
                            confirmButtonColor: "#13844e",
                            backdrop: true,
                            allowOutsideClick: false,
                            allowEscapeKey: false,
                            allowEnterKey: false,
                        })
						break;

					case "5":
                        Swal.fire({
                            icon: 'error',
                            position: "top",
                            title: "<strong>Servidor</strong>",
                            html: "<h5>Estamos presentando alguna falla del lado del servidor, por favor comunicar al SysAdmin.</h5>",
                            showCloseButton: false,
                            showCancelButton: false,
                            confirmButtonText: "Entendido",
                            confirmButtonColor: "#13844e",
                            backdrop: true,
                            allowOutsideClick: false,
                            allowEscapeKey: false,
                            allowEnterKey: false,
                        })
						break;

                    case "6":
                        Swal.fire({
                            icon: 'error',
                            position: "top",
                            title: "<strong>Acceso no autorizado</strong>",
                            html: "<h5>Estas intentado acceder desde una ubicacion no autorizada, por favor contacte a su supervisor inmediato.</h5>",
                            showCloseButton: false,
                            showCancelButton: false,
                            confirmButtonText: "Entendido",
                            confirmButtonColor: "#13844e",
                            backdrop: true,
                            allowOutsideClick: false,
                            allowEscapeKey: false,
                            allowEnterKey: false,
                        })
						break;
				}
			},
			error: function (xhr) {
				console.log(xhr);
				$("#btnLoginIngresar").prop("disabled", false);
			},
		});
	}
}

function cerrarSesion() {
	$.ajax({
		data: { peticion: "logOut" }, //necesario para enviar archivos
		dataType: "json", //Si no se especifica jQuery automaticamente encontrará el tipo basado en el header del archivo llamado (pero toma mas tiempo en cargar, asi que especificalo)
		url: urlControllerLogin, //url a donde hacemos la peticion
		type: "POST",
		beforeSend: function () {
			$("#overlayText").text("Cerrando Sesión...");
			$(".overlayCargue").fadeIn("slow");
		},
		complete: function () {
			$(".overlayCargue").fadeOut("slow");
		},
		success: function (result) {
			var estado = result.status;
			switch (estado) {
				case "1":
					window.location.replace(urlBase + "Login");
					break;
			}
		},
		error: function (xhr) {
			console.log(xhr);
		},
	});
}

//funcion para preguntar si desea seguir conectado
var seguirConectado = function () {
	var tiempoRestante = 30;
	var contadorRegresivoSeguirConectado = setInterval(function () {
		tiempoRestante -= 1;
		if (tiempoRestante == 0) {
			clearInterval(contadorRegresivoSeguirConectado);
		}
		$("#cuentaAtrasDesconexion").html(tiempoRestante);
	}, 1000);
	$("#cuentaAtrasDesconexion").html(30);
	Swal.fire({
		icon: "info",
		position: "top",
		title: "<strong>Aviso de Sesion</strong>",
		html: `<div class="container row">
            <div class="col-xs-12 col-md-12 col-lg-12">
                <h5>Su sesion esta a punto de finalizar</h5>
                <p>Han transcurrido cerca de 15 minutos desde tu última interacción con la plataforma.</p>
                <p>Para continuar logueado da clic en "Seguir conectado", de lo contrario tu sesión expirará.</p>
                <h1 class="text-center"><span class="cuenta-inactividad" id="cuentaAtrasDesconexion">30</span></h1>
            </div>
        </div>`,
		showCloseButton: false,
		confirmButtonText: "Seguir conectado",
		confirmButtonColor: "#11386f",
		backdrop: true,
		allowOutsideClick: false,
		allowEscapeKey: false,
		allowEnterKey: false,
		timer: tiempoEspera,
		timerProgressBar: true,
	}).then((result) => {
		if (result.isConfirmed) {
			// Actualizamos la sesion
			actualizarUltimaConexionOnline();
		}
	});
};

// 15000
// 300000 milisegundos - 5 minutos
// 600000 milisegundos - 10 minutos
// 900000 milisegundos - 15 minutos
// 30000 tiempo de Espera
//variables globales para los timer de desconexion
var contadorCerrarSesion;
var contadorSeguirConectado;
var tiempoMaximo = 900000;
var tiempoEspera = 30000;
var tiempoMaximoCierreSesion = tiempoMaximo + tiempoEspera;

function actualizarUltimaConexion() {
	//reinicio los timer
	clearTimeout(contadorCerrarSesion);
	clearTimeout(contadorSeguirConectado);
	contadorCerrarSesion = setTimeout(function () {
		cerrarSesion();
	}, tiempoMaximoCierreSesion);
	contadorSeguirConectado = setTimeout(seguirConectado, tiempoMaximo);
}

function actualizarUltimaConexionOnline() {
	// $.ajax({
	// 	data: { peticion: "continuarSesion" }, //datos a enviar a la url
	// 	dataType: "json", //Si no se especifica jQuery automaticamente encontrará el tipo basado en el header del archivo llamado (pero toma mas tiempo en cargar, asi que especificalo)
	// 	url: urlControllerLogin, //url a donde hacemos la peticion
	// 	type: "POST",
	// 	beforeSend: function () {},
	// 	success: function (result) {
	// 		var estado = result.status;
	// 		switch (estado) {
	// 			case "0":
	// 				cerrarSesion();
	// 				break;
	// 			case "1":
	clearTimeout(contadorCerrarSesion);
	actualizarUltimaConexion();
	// 			break;
	// 		default:
	// 	}
	// },
	// complete: function () {},
	// error: function () {},
	// });
}

function cambiarContrasenia() {
	let htmlInputs = `
    <div class="container row">
        <div class="col-xs-12 col-md-12 col-lg-12">
            <a class="tooltips"><label class="negrita" for="passwordOne">Digite la contraseña nueva</label>
                <input type="password" class="form-control requerido maxlength-input" minlength="3" maxlength="100" title="Digite la contraseña nueva" id="passwordOne" name="passwordOne"><span class="spanValidacion hidden"></span>
            </a>
        </div>
        <div class="col-xs-12 col-md-12 col-lg-12">
            <a class="tooltips" style="margin-top: 14px;"><label class="negrita" for="passwordTwo">Repita la contraseña</label>
                <input type="password" class="form-control maxlength-input" minlength="0" maxlength="100" title="Repita la contraseña" id="passwordTwo" name="passwordTwo"><span class="spanValidacion hidden"></span>
            </a>
        </div>
    </div>`;
	Swal.fire({
		// icon: 'info',
		position: "top",
		title: "<strong>Cambiar contraseña</strong>",
		html: htmlInputs,
		showCloseButton: false,
		showCancelButton: true,
		confirmButtonText: "Cambiar",
		confirmButtonColor: "#11386f",
		cancelButtonText: "Cancelar",
		cancelButtonColor: "#f5365c",
		backdrop: true,
		allowOutsideClick: false,
		allowEscapeKey: false,
		allowEnterKey: false,
		focusConfirm: true,
		preConfirm: function () {
			if (document.getElementById("passwordOne").value == "") {
				Swal.showValidationMessage("Ingrese contraseña uno");
			} else if (document.getElementById("passwordTwo").value == "") {
				Swal.showValidationMessage("Ingrese contraseña dos");
			} else if (document.getElementById("passwordOne").value != document.getElementById("passwordTwo").value) {
				Swal.showValidationMessage("Las contraseñas no son las mismas");
			} else {
				return [document.getElementById("passwordOne").value, document.getElementById("passwordTwo").value];
			}
		},
	}).then((result) => {
		if (result.isConfirmed) {
			let password = result.value[0];
			$.ajax({
				data: { peticion: "updatePassword", password: password }, //necesario para enviar archivos
				dataType: "json", //Si no se especifica jQuery automaticamente encontrará el tipo basado en el header del archivo llamado (pero toma mas tiempo en cargar, asi que especificalo)
				url: urlControllerLogin, //url a donde hacemos la peticion
				type: "POST",
				beforeSend: function () {
					$("#overlayText").text("Actualizando Contraseña...");
					$(".overlayCargue").fadeIn("slow");
				},
				complete: function () {
					$(".overlayCargue").fadeOut("slow");
				},
				success: function (result) {
					var estado = result.status;
					switch (estado) {
						case "0":
							// showToast('Usuario Existente!','El usuario ya se encuentra registrado.','info','derecha',5000);
							break;

						case "1":
							Swal.fire({
								icon: "success",
								position: "top",
								title: "<strong>Actualizacion Contraseña</strong>",
								html: "<h5>Se ha actualizado con exito la contraseña</h5>",
								showCloseButton: false,
								showConfirmButton: false,
								showCancelButton: true,
								cancelButtonText: "Cerrar",
								cancelButtonColor: "#f5365c",
								backdrop: true,
							});
							break;

						default:
							// Code
							break;
					}
				},
				error: function (xhr) {
					console.log(xhr);
				},
			});
		}
	});
}
