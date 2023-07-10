<?php
/**
* clase para validar datos recibidos en php
*/
class Validar
{
	public static function Longitud($valor, $minimo, $maximo){
		$caracteres = strlen( trim($valor) );
		if( $caracteres >= $minimo && $caracteres <= $maximo ){
			return true;
		}else{
			return false;
		}
	}

	public static function Requerido($valor){
		$caracteres = strlen( trim($valor) );
		if($caracteres > 0){
			return true;
		}else{
			return false;
		}
	}

	public static function Correo($valor){
		if( filter_var($valor, FILTER_VALIDATE_EMAIL) ){
			return true;
		}else{
			return false;
		}
	}

	public static function Letras($valor){
		$patronAlfaNumerico = "/^[a-zA-ZáéíóúÁÉÍÓÚäëïöüÄËÏÖÜàèìòùÀÈÌÒÙÑñ\s]+$/";
		if( preg_match($patronAlfaNumerico, $valor ) ){
			return true;
		}else{
			return false;
		}
	}

	public static function Alfanumerico($valor){
		$patronAlfaNumerico = "/^[a-zA-Z0-9áéíóúÁÉÍÓÚäëïöüÄËÏÖÜàèìòùÀÈÌÒÙ\s]+$/";
		if( preg_match($patronAlfaNumerico, $valor ) ){
			return true;
		}else{
			return false;
		}
	}

	public static function Direccion($valor){
		$patronDireccion = "/^[a-zA-Z0-9áéíóúÁÉÍÓÚäëïöüÄËÏÖÜàèìòùÀÈÌÒÙº#.,\-\s]+$/";
		if( preg_match($patronDireccion, $valor ) ){
			return true;
		}else{
			return false;
		}
	}

	public static function Numeros($valor){
		if( is_numeric($valor) ){
			return true;
		}else{
			return false;
		}
	}

	public static function Password($valor){
		$patronAlfaNumerico = "/^[a-zA-Z0-9]+$/";
		if( preg_match($patronAlfaNumerico, $valor ) ){
			return true;
		}else{
			return false;
		}
	}

	public static function PatronNumeros($valor){
		$patronNumerico = "/^[0-9]+$/";
		if( preg_match($patronNumerico, $valor ) ){
			return true;
		}else{
			return false;
		}
	}

	public static function PatronAlfanumerico1($valor){
		$patronAN = "/^[a-zA-Z0-9áéíóúÁÉÍÓÚüÜñÑŃń,\/.\-\s]+$/";
		if( preg_match($patronAN, $valor ) ){
			return true;
		}else{
			return false;
		}
	}

	public static function PatronAlfanumerico2($valor){
		$patronAN = "/^[a-zA-Z0-9_]+$/";
		if( preg_match($patronAN, $valor ) ){
			return true;
		}else{
			return false;
		}
	}

	public static function PatronAlfanumericoTelefono($valor){
		$patronAN = "/^[0-9,()%\/+\-\s]+$/";
		if( preg_match($patronAN, $valor ) ){
			return true;
		}else{
			return false;
		}
	}

	public static function PatronAlfanumericoContrasenia($valor){
		$patronAN = "/^[a-zA-Z0-9]+$/";
		if( preg_match($patronAN, $valor ) ){
			return true;
		}else{
			return false;
		}
	}

	public static function Entero($valor){
		if( filter_var($valor, FILTER_VALIDATE_INT) === 0 || !filter_var($valor, FILTER_VALIDATE_INT) === false ){
			return true;
		}else{
			return false;
		}
	}

	public static function ValoresNumericos($valor,$arrayValores){
		$numerosvalidos = explode(",", $arrayValores);
		if(in_array($valor, $numerosvalidos)){
			return true;
		}else{
			return false;
		}
	}

	public static function ArrayNumerico($array){
		$patronNumerico = "/^[0-9]+$/";
		if( preg_match($patronNumerico, implode('', $array) ) ){
			return true;
		}else{
			return false;
		}
	}

	public static function ArrayRequerido($array){
		if( count($array) > 0 ){
			return true;
		}else{
			return false;
		}
	}

	public static function ValoresNumericoMultiple($valoresIngresados,$valoresValidos){
		$banderaValidacion = true;
		$arryValoresIngresados = explode(",", $valoresIngresados);
		$arryValoresValidos = explode(",", $valoresValidos);
		foreach ( $arryValoresIngresados as $valor ) {
			if( !in_array($valor, $arryValoresValidos) ){
				$banderaValidacion = false;
				break;
			}
		}
		return $banderaValidacion;
	}


	public static function ValorEnValores($valor,$valores){
		$arrayValores = explode(',', $valores);
		if( in_array($valor, $arrayValores) ){
			return true;
		}else{
			return false;
		}
	}

	public static function Float($valor,$separadorDecimales){
		if( $valor == "0" || filter_var($valor, FILTER_VALIDATE_FLOAT,array('options' => array('decimal' => $separadorDecimales))) ){
			return true;
		}else{
			return false;
		}
	}

	public static function Fecha($fecha,$separador,$formato){
		//fecha->string de la fecha con dia, mes y año en el
		//orden que quiera en formato numerico ej: 17-12-2001, 2001/12/17
		//separador->un caracter unico que separa el dia mes y año
		//para los casos anteriores fue - y /
		//formato el orden en que se da el dia mes año se establece
		//mediante las letras d(dia) m(mes) a(año) para los casos
		//seria dma y amd
		$arryFecha = explode($separador, $fecha);
		if(count($arryFecha) == 3){

			switch ($formato) {
				case 'dma':
					$dia = $arryFecha[0];
					$mes = $arryFecha[1];
					$anio = $arryFecha[2];

					break;
				case 'dam':
					$dia = $arryFecha[0];
					$mes = $arryFecha[2];
					$anio = $arryFecha[1];
					break;
				case 'mad':
					$dia = $arryFecha[2];
					$mes = $arryFecha[0];
					$anio = $arryFecha[1];
					break;
				case 'mda':
					$dia = $arryFecha[1];
					$mes = $arryFecha[0];
					$anio = $arryFecha[2];
					break;
				case 'adm':
					$dia = $arryFecha[1];
					$mes = $arryFecha[2];
					$anio = $arryFecha[0];
					break;
				case 'amd':
					$dia = $arryFecha[2];
					$mes = $arryFecha[1];
					$anio = $arryFecha[0];
					break;
				default:
					# code...
					break;
			}
			if(checkdate($mes, $dia, $anio)){
				return true;
			}else{
				return false;
			}
		}else{
			//var_dump($arryFecha);
			return false;
		}
	}
	//validar ip ipv4
	public static function ip($ip,$tipo){
		switch ($tipo) {
			case 'IPV4':
				$tipoIp = "FILTER_FLAG_IPV4";
				break;
			case 'IPV6':
				$tipoIp = "FILTER_FLAG_IPV6";
				break;
			default:
				$tipoIp = "FILTER_FLAG_IPV4";
				break;
		}
		if(filter_var($ip, FILTER_VALIDATE_IP, $tipoIp)) {
			return true;
		} else {
			return false;
		}
	}
	//validar un numero entre un rango incluyendo los limites del rango
	public static function NumeroEnRango($numero,$numeroMinimo,$numeroMaximo){
		$boolNumero = self::patronnumeros($numero);
		if( $boolNumero ){
			if( $numero < $numeroMinimo || $numero > $numeroMaximo ){
				return false;
			}else{
				return true;
			}
		}else{
			return false;
		}
	}

	public static function TipoArchivo($typefile){
		$arraytiposarchivos = array(
			// Adobe
			'application/pdf',
			'application/postscript',
			'image/vnd.adobe.photoshop',
			// Binarios
			'application/octet-stream',
			// Compresion
			'application/x-bzip',
			'application/x-bzip2',
			'application/epub+zip',
			// Microoft
			'application/msword',
			'application/vnd.ms-powerpoint',
			'application/vnd.ms-excel',
			'application/rtf',
			// Open Office
			'application/vnd.oasis.opendocument.text',
			'application/vnd.oasis.opendocument.spreadsheet',
			// Texto y Web
			'text/plain',
            'text/html',
            'text/html',
            'text/html',
            'text/css',
			'text/csv',
            'application/javascript',
            'application/json',
            'application/xml',
            'application/x-shockwave-flash',
            'video/x-flv',
            // images
            'image/png',
            'image/jpeg',
            'image/jpg',
            'image/gif',
            'image/bmp',
            'image/vnd.microsoft.icon',
            'image/tiff',
            'image/tiff',
            'image/svg+xml',
			'image/x-icon'
		);
		if( in_array($typefile, $arraytiposarchivos) ){
			return true;
		}else{
			return false;
		}
	}

	public static function tipoArchivoImg($typefile){
		$arraytiposarchivos = array(
            'image/png',
            'image/jpeg',
            'image/jpg',
            'image/bmp'
		);
		if( in_array($typefile, $arraytiposarchivos) ){
			return true;
		}else{
			return false;
		}
	}
}
?>