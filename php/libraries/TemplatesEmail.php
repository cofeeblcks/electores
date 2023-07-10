<?php
require_once(dirname(__DIR__) . '/libraries/Rutas.php');

class TemplateEmail
{
    public static function FirmaCorreo($tipoFirma, $mysqli){
        $contenido = '';
        switch ($tipoFirma) {
            case 1:
                $idEmleado = Sesion::GetParametro('idUsuario');

                $sql = "SELECT 
                CONCAT(UCASE(LEFT(E.primer_nombre, 1)),SUBSTRING(LOWER(E.primer_nombre), 2),' ',UCASE(LEFT(E.primer_apellido, 1)),SUBSTRING(LOWER(E.primer_apellido), 2)) AS nombre,
                E.telefono,
                E.correo,
                C.descripcion AS cargo
                FROM empleados E
                JOIN cargos C ON C.id_cargo = E.id_cargo
                WHERE E.id_empleado = $idEmleado;";

                $rtdo = mysqli_query($mysqli, $sql) or die("Error en la Consulta SQL".$sql);

                if( mysqli_num_rows($rtdo) == 1 ){
                    $dataUsuario = mysqli_fetch_array($rtdo);
                    $contenido = '<br><br>';
                    $contenido .= '<span style="font-family: terminal, monaco, monospace;">Cordialmente,</span>';
                    $contenido .= '<br><br>';
                    $contenido .= '<table style="font-size: medium; font-family: Courier New;" class="table__StyledTable-sc-1avdl6r-0 jWJRxL" cellspacing="0" cellpadding="0">';
                        $contenido .= '<tbody>';
                            $contenido .= '<tr>';
                                $contenido .= '<td>';
                                    $contenido .= '<table style="font-size: medium; font-family: Courier New;" class="table__StyledTable-sc-1avdl6r-0 jWJRxL" cellspacing="0" cellpadding="0">';
                                        $contenido .= '<tbody>';
                                            $contenido .= '<tr>';
                                                $contenido .= '<td style="vertical-align: top;margin-rigth: 10px !important">';
                                                    $contenido .= '<img src="https://samebox.somos19d.com/img/logo/LogoFormatos.png" role="presentation" style="display: inline-block; max-width: 110px;" class="image__StyledImage-sc-hupvqm-0 fQKUvi" width="110">';
                                                $contenido .= '</td>';
                                                $contenido .= '<td style="vertical-align: middle;">';
                                                    $contenido .= '<h2 color="#383838" style="margin: 0px; font-size: 18px; color: rgb(56, 56, 56); font-weight: 600;" class="name__NameContainer-sc-1m457h3-0 hkyYrA">';
                                                        $contenido .= '<span>'.$dataUsuario['nombre'].'</span>';
                                                    $contenido .= '</h2>';
                                                    $contenido .= '<p color="#383838" font-size="medium" style="margin: 0px; color: rgb(56, 56, 56); font-size: 14px; line-height: 22px;" class="job-title__Container-sc-1hmtp73-0 iJcqpv">';
                                                        $contenido .= '<span>'.ucfirst(mb_strtolower($dataUsuario['cargo'])).'</span>';
                                                    $contenido .= '</p>';
                                                    $contenido .= '<p color="#383838" font-size="medium" style="margin: 0px; font-weight: 500; color: rgb(56, 56, 56); font-size: 14px; line-height: 22px;" class="company-details__CompanyContainer-sc-j5pyy8-0 bEBXsp">';
                                                        $contenido .= '<span>Somos 19D</span>';
                                                    $contenido .= '</p>';
                                                $contenido .= '</td>';
                                                $contenido .= '<td width="30">';
                                                    $contenido .= '<div style="width: 30px;"></div>';
                                                $contenido .= '</td>';
                                                $contenido .= '<td color="#1959B1" direction="vertical" style="width: 1px; border-bottom: medium none; border-left: 1px solid rgb(25, 89, 177);" class="color-divider__Divider-sc-1h38qjv-0 dcKmvZ" width="1" height="auto"></td>';
                                                $contenido .= '<td width="30">';
                                                    $contenido .= '<div style="width: 30px;"></div>';
                                                $contenido .= '</td>';
                                                $contenido .= '<td style="vertical-align: middle;">';
                                                    $contenido .= '<table style="font-size: medium; font-family: Courier New;" class="table__StyledTable-sc-1avdl6r-0 jWJRxL" cellspacing="0" cellpadding="0">';
                                                        $contenido .= '<tbody>';
                                                            $contenido .= '<tr style="vertical-align: middle;" height="25">';
                                                                $contenido .= '<td style="vertical-align: middle;" width="30">';
                                                                    $contenido .= '<table style="font-size: medium; font-family: Courier New;" class="table__StyledTable-sc-1avdl6r-0 jWJRxL" cellspacing="0" cellpadding="0">';
                                                                        $contenido .= '<tbody>';
                                                                            $contenido .= '<tr>';
                                                                                $contenido .= '<td style="vertical-align: bottom;">';
                                                                                    $contenido .= '<span color="#1959B1" width="11" style="display: inline-block; background-color: rgb(25, 89, 177);" class="contact-info__IconWrapper-sc-mmkjr6-1 hBHfIp">';
                                                                                        $contenido .= '<img src="https://cdn2.hubspot.net/hubfs/53/tools/email-signature-generator/icons/phone-icon-2x.png" color="#1959B1" alt="mobilePhone" style="display: block; background-color: rgb(25, 89, 177);" class="contact-info__ContactLabelIcon-sc-mmkjr6-0 dGVIJx" width="13">';
                                                                                    $contenido .= '</span>';
                                                                                $contenido .= '</td>';
                                                                            $contenido .= '</tr>';
                                                                        $contenido .= '</tbody>';
                                                                    $contenido .= '</table>';
                                                                $contenido .= '</td>';
                                                                $contenido .= '<td style="padding: 0px; color: rgb(56, 56, 56);">';
                                                                    $contenido .= '<a href="tel:+57'.$dataUsuario['telefono'].'" color="#383838" style="text-decoration: none; color: rgb(56, 56, 56); font-size: 12px;" class="contact-info__ExternalLink-sc-mmkjr6-2 bibcmr">';
                                                                        $contenido .= '<span>+57 '.$dataUsuario['telefono'].'</span>';
                                                                    $contenido .= '</a>';
                                                                $contenido .= '</td>';
                                                            $contenido .= '</tr>';
                                                            $contenido .= '<tr style="vertical-align: middle;" height="25">';
                                                                $contenido .= '<td style="vertical-align: middle;" width="30">';
                                                                    $contenido .= '<table style="font-size: medium; font-family: Courier New;" class="table__StyledTable-sc-1avdl6r-0 jWJRxL" cellspacing="0" cellpadding="0">';
                                                                        $contenido .= '<tbody>';
                                                                            $contenido .= '<tr>';
                                                                                $contenido .= '<td style="vertical-align: bottom;">';
                                                                                    $contenido .= '<span color="#1959B1" width="11" style="display: inline-block; background-color: rgb(25, 89, 177);" class="contact-info__IconWrapper-sc-mmkjr6-1 hBHfIp">';
                                                                                        $contenido .= '<img src="https://cdn2.hubspot.net/hubfs/53/tools/email-signature-generator/icons/email-icon-2x.png" color="#1959B1" alt="emailAddress" style="display: block; background-color: rgb(25, 89, 177);" class="contact-info__ContactLabelIcon-sc-mmkjr6-0 dGVIJx" width="13">';
                                                                                    $contenido .= '</span>';
                                                                                $contenido .= '</td>';
                                                                            $contenido .= '</tr>';
                                                                        $contenido .= '</tbody>';
                                                                    $contenido .= '</table>';
                                                                $contenido .= '</td>';
                                                                $contenido .= '<td style="padding: 0px;">';
                                                                    $contenido .= '<a href="mailto:'.mb_strtolower($dataUsuario['correo']).'" color="#383838" style="text-decoration: none; color: rgb(56, 56, 56); font-size: 12px;" class="contact-info__ExternalLink-sc-mmkjr6-2 bibcmr">';
                                                                        $contenido .= '<span>'.mb_strtolower($dataUsuario['correo']).'</span>';
                                                                    $contenido .= '</a>';
                                                                $contenido .= '</td>';
                                                            $contenido .= '</tr>';
                                                            $contenido .= '<tr style="vertical-align: middle;" height="25">';
                                                                $contenido .= '<td style="vertical-align: middle;" width="30">';
                                                                    $contenido .= '<table style="font-size: medium; font-family: Courier New;" class="table__StyledTable-sc-1avdl6r-0 jWJRxL" cellspacing="0" cellpadding="0">';
                                                                        $contenido .= '<tbody>';
                                                                            $contenido .= '<tr>';
                                                                                $contenido .= '<td style="vertical-align: bottom;">';
                                                                                    $contenido .= '<span color="#1959B1" width="11" style="display: inline-block; background-color: rgb(25, 89, 177);" class="contact-info__IconWrapper-sc-mmkjr6-1 hBHfIp">';
                                                                                        $contenido .= '<img src="https://cdn2.hubspot.net/hubfs/53/tools/email-signature-generator/icons/link-icon-2x.png" color="#1959B1" alt="website" style="display: block; background-color: rgb(25, 89, 177);" class="contact-info__ContactLabelIcon-sc-mmkjr6-0 dGVIJx" width="13">';
                                                                                    $contenido .= '</span>';
                                                                                $contenido .= '</td>';
                                                                            $contenido .= '</tr>';
                                                                        $contenido .= '</tbody>';
                                                                    $contenido .= '</table>';
                                                                $contenido .= '</td>';
                                                                $contenido .= '<td style="padding: 0px;">';
                                                                    $contenido .= '<a href="https://somos19d.com/" color="#383838" style="text-decoration: none; color: rgb(56, 56, 56); font-size: 12px;" class="contact-info__ExternalLink-sc-mmkjr6-2 bibcmr">';
                                                                        $contenido .= '<span>https://somos19d.com/</span>';
                                                                    $contenido .= '</a>';
                                                                $contenido .= '</td>';
                                                            $contenido .= '</tr>';
                                                        $contenido .= '</tbody>';
                                                    $contenido .= '</table>';
                                                $contenido .= '</td>';
                                            $contenido .= '</tr>';
                                        $contenido .= '</tbody>';
                                    $contenido .= '</table>';
                                $contenido .= '</td>';
                            $contenido .= '</tr>';
                            $contenido .= '<tr>';
                                $contenido .= '<td>';
                                    $contenido .= '<table style="width: 100%; font-size: medium; font-family: Courier New;" class="table__StyledTable-sc-1avdl6r-0 jWJRxL" cellspacing="0" cellpadding="0">';
                                        $contenido .= '<tbody>';
                                            $contenido .= '<tr>';
                                                $contenido .= '<td height="20"></td>';
                                            $contenido .= '</tr>';
                                            $contenido .= '<tr>';
                                                $contenido .= '<td color="#1959B1" direction="horizontal" style="width: 100%; border-bottom: 1px solid rgb(25, 89, 177); border-left: medium none; display: block;" class="color-divider__Divider-sc-1h38qjv-0 dcKmvZ" width="auto" height="1"></td>';
                                            $contenido .= '</tr>';
                                        $contenido .= '</tbody>';
                                    $contenido .= '</table>';
                                $contenido .= '</td>';
                            $contenido .= '</tr>';
                        $contenido .= '</tbody>';
                    $contenido .= '</table>';
                    $contenido .= '<table style="border-collapse: collapse;" width="100%" cellpadding="0">';
                        $contenido .= '<tr>';
                            $contenido .= '<td style="margin: 0.1px; padding: 15px 0px 0px; font: 14.4px / 18.3px terminal, monaco, monospace; color: rgb(186, 198, 217);">';
                                $contenido .= 'IMPORTANTE: El contenido de este correo electrónico y los archivos adjuntos son confidenciales. Está estrictamente prohibido compartir cualquier parte de este mensaje con terceros, sin el consentimiento por escrito del remitente. Si recibió este mensaje por error, responda a este mensaje y continúe con su eliminación, para que podamos asegurarnos de que tal error no ocurra en el futuro.';
                            $contenido .= '</td>';
                        $contenido .= '</tr>';
                    $contenido .= '</table>';
                }
                break;

            case 2:
                $sql = "SELECT
                P.documento AS nit,
                P.razon_social AS nombre,
                'recepcion.facturas@somos19d.com' AS correo,
                P.telefono
                FROM parametros P
                JOIN bancos B ON B.id_banco = P.id_banco
                JOIN tipos_cuentas TC ON TC.idtipo_cuenta = P.idtipo_cuenta;";

                $rtdo = mysqli_query($mysqli, $sql) or die("Error en la Consulta SQL".$sql);

                if( mysqli_num_rows($rtdo) == 1 ){
                    $dataUsuario = mysqli_fetch_array($rtdo);
                    $contenido = '<br><br>';
                    $contenido .= '<span style="font-family: terminal, monaco, monospace;">Cordialmente,</span>';
                    $contenido .= '<br><br>';
                    $contenido .= '<table style="font-size: medium; font-family: Courier New;" class="table__StyledTable-sc-1avdl6r-0 jWJRxL" cellspacing="0" cellpadding="0">';
                        $contenido .= '<tbody>';
                            $contenido .= '<tr>';
                                $contenido .= '<td>';
                                    $contenido .= '<table style="font-size: medium; font-family: Courier New;" class="table__StyledTable-sc-1avdl6r-0 jWJRxL" cellspacing="0" cellpadding="0">';
                                        $contenido .= '<tbody>';
                                            $contenido .= '<tr>';
                                                $contenido .= '<td style="vertical-align: top;margin-rigth: 10px !important">';
                                                    $contenido .= '<img src="https://samebox.somos19d.com/img/logo/LogoFormatos.png" role="presentation" style="display: inline-block; max-width: 110px;" class="image__StyledImage-sc-hupvqm-0 fQKUvi" width="110">';
                                                $contenido .= '</td>';
                                                $contenido .= '<td style="vertical-align: middle;">';
                                                    $contenido .= '<h2 color="#383838" style="margin: 0px; font-size: 18px; color: rgb(56, 56, 56); font-weight: 600;" class="name__NameContainer-sc-1m457h3-0 hkyYrA">';
                                                        $contenido .= '<span>'.$dataUsuario['nombre'].'</span>';
                                                    $contenido .= '</h2>';
                                                    $contenido .= '<p color="#383838" font-size="medium" style="margin: 0px; color: rgb(56, 56, 56); font-size: 14px; line-height: 22px;" class="job-title__Container-sc-1hmtp73-0 iJcqpv">';
                                                        $contenido .= '<span>'.$dataUsuario['nit'].'-'.Utilidades::CalcularDv($dataUsuario['nit']).'</span>';
                                                    $contenido .= '</p>';
                                                    $contenido .= '<p color="#383838" font-size="medium" style="margin: 0px; font-weight: 500; color: rgb(56, 56, 56); font-size: 14px; line-height: 22px;" class="company-details__CompanyContainer-sc-j5pyy8-0 bEBXsp">';
                                                        $contenido .= '<span>Somos 19D</span>';
                                                    $contenido .= '</p>';
                                                $contenido .= '</td>';
                                                $contenido .= '<td width="30">';
                                                    $contenido .= '<div style="width: 30px;"></div>';
                                                $contenido .= '</td>';
                                                $contenido .= '<td color="#1959B1" direction="vertical" style="width: 1px; border-bottom: medium none; border-left: 1px solid rgb(25, 89, 177);" class="color-divider__Divider-sc-1h38qjv-0 dcKmvZ" width="1" height="auto"></td>';
                                                $contenido .= '<td width="30">';
                                                    $contenido .= '<div style="width: 30px;"></div>';
                                                $contenido .= '</td>';
                                                $contenido .= '<td style="vertical-align: middle;">';
                                                    $contenido .= '<table style="font-size: medium; font-family: Courier New;" class="table__StyledTable-sc-1avdl6r-0 jWJRxL" cellspacing="0" cellpadding="0">';
                                                        $contenido .= '<tbody>';
                                                            $contenido .= '<tr style="vertical-align: middle;" height="25">';
                                                                $contenido .= '<td style="vertical-align: middle;" width="30">';
                                                                    $contenido .= '<table style="font-size: medium; font-family: Courier New;" class="table__StyledTable-sc-1avdl6r-0 jWJRxL" cellspacing="0" cellpadding="0">';
                                                                        $contenido .= '<tbody>';
                                                                            $contenido .= '<tr>';
                                                                                $contenido .= '<td style="vertical-align: bottom;">';
                                                                                    $contenido .= '<span color="#1959B1" width="11" style="display: inline-block; background-color: rgb(25, 89, 177);" class="contact-info__IconWrapper-sc-mmkjr6-1 hBHfIp">';
                                                                                        $contenido .= '<img src="https://cdn2.hubspot.net/hubfs/53/tools/email-signature-generator/icons/phone-icon-2x.png" color="#1959B1" alt="mobilePhone" style="display: block; background-color: rgb(25, 89, 177);" class="contact-info__ContactLabelIcon-sc-mmkjr6-0 dGVIJx" width="13">';
                                                                                    $contenido .= '</span>';
                                                                                $contenido .= '</td>';
                                                                            $contenido .= '</tr>';
                                                                        $contenido .= '</tbody>';
                                                                    $contenido .= '</table>';
                                                                $contenido .= '</td>';
                                                                $contenido .= '<td style="padding: 0px; color: rgb(56, 56, 56);">';
                                                                    $contenido .= '<a href="tel:+57'.$dataUsuario['telefono'].'" color="#383838" style="text-decoration: none; color: rgb(56, 56, 56); font-size: 12px;" class="contact-info__ExternalLink-sc-mmkjr6-2 bibcmr">';
                                                                        $contenido .= '<span>+57 '.$dataUsuario['telefono'].'</span>';
                                                                    $contenido .= '</a>';
                                                                $contenido .= '</td>';
                                                            $contenido .= '</tr>';
                                                            $contenido .= '<tr style="vertical-align: middle;" height="25">';
                                                                $contenido .= '<td style="vertical-align: middle;" width="30">';
                                                                    $contenido .= '<table style="font-size: medium; font-family: Courier New;" class="table__StyledTable-sc-1avdl6r-0 jWJRxL" cellspacing="0" cellpadding="0">';
                                                                        $contenido .= '<tbody>';
                                                                            $contenido .= '<tr>';
                                                                                $contenido .= '<td style="vertical-align: bottom;">';
                                                                                    $contenido .= '<span color="#1959B1" width="11" style="display: inline-block; background-color: rgb(25, 89, 177);" class="contact-info__IconWrapper-sc-mmkjr6-1 hBHfIp">';
                                                                                        $contenido .= '<img src="https://cdn2.hubspot.net/hubfs/53/tools/email-signature-generator/icons/email-icon-2x.png" color="#1959B1" alt="emailAddress" style="display: block; background-color: rgb(25, 89, 177);" class="contact-info__ContactLabelIcon-sc-mmkjr6-0 dGVIJx" width="13">';
                                                                                    $contenido .= '</span>';
                                                                                $contenido .= '</td>';
                                                                            $contenido .= '</tr>';
                                                                        $contenido .= '</tbody>';
                                                                    $contenido .= '</table>';
                                                                $contenido .= '</td>';
                                                                $contenido .= '<td style="padding: 0px;">';
                                                                    $contenido .= '<a href="mailto:'.mb_strtolower($dataUsuario['correo']).'" color="#383838" style="text-decoration: none; color: rgb(56, 56, 56); font-size: 12px;" class="contact-info__ExternalLink-sc-mmkjr6-2 bibcmr">';
                                                                        $contenido .= '<span>'.mb_strtolower($dataUsuario['correo']).'</span>';
                                                                    $contenido .= '</a>';
                                                                $contenido .= '</td>';
                                                            $contenido .= '</tr>';
                                                            $contenido .= '<tr style="vertical-align: middle;" height="25">';
                                                                $contenido .= '<td style="vertical-align: middle;" width="30">';
                                                                    $contenido .= '<table style="font-size: medium; font-family: Courier New;" class="table__StyledTable-sc-1avdl6r-0 jWJRxL" cellspacing="0" cellpadding="0">';
                                                                        $contenido .= '<tbody>';
                                                                            $contenido .= '<tr>';
                                                                                $contenido .= '<td style="vertical-align: bottom;">';
                                                                                    $contenido .= '<span color="#1959B1" width="11" style="display: inline-block; background-color: rgb(25, 89, 177);" class="contact-info__IconWrapper-sc-mmkjr6-1 hBHfIp">';
                                                                                        $contenido .= '<img src="https://cdn2.hubspot.net/hubfs/53/tools/email-signature-generator/icons/link-icon-2x.png" color="#1959B1" alt="website" style="display: block; background-color: rgb(25, 89, 177);" class="contact-info__ContactLabelIcon-sc-mmkjr6-0 dGVIJx" width="13">';
                                                                                    $contenido .= '</span>';
                                                                                $contenido .= '</td>';
                                                                            $contenido .= '</tr>';
                                                                        $contenido .= '</tbody>';
                                                                    $contenido .= '</table>';
                                                                $contenido .= '</td>';
                                                                $contenido .= '<td style="padding: 0px;">';
                                                                    $contenido .= '<a href="https://somos19d.com/" color="#383838" style="text-decoration: none; color: rgb(56, 56, 56); font-size: 12px;" class="contact-info__ExternalLink-sc-mmkjr6-2 bibcmr">';
                                                                        $contenido .= '<span>https://somos19d.com/</span>';
                                                                    $contenido .= '</a>';
                                                                $contenido .= '</td>';
                                                            $contenido .= '</tr>';
                                                        $contenido .= '</tbody>';
                                                    $contenido .= '</table>';
                                                $contenido .= '</td>';
                                            $contenido .= '</tr>';
                                        $contenido .= '</tbody>';
                                    $contenido .= '</table>';
                                $contenido .= '</td>';
                            $contenido .= '</tr>';
                            $contenido .= '<tr>';
                                $contenido .= '<td>';
                                    $contenido .= '<table style="width: 100%; font-size: medium; font-family: Courier New;" class="table__StyledTable-sc-1avdl6r-0 jWJRxL" cellspacing="0" cellpadding="0">';
                                        $contenido .= '<tbody>';
                                            $contenido .= '<tr>';
                                                $contenido .= '<td height="20"></td>';
                                            $contenido .= '</tr>';
                                            $contenido .= '<tr>';
                                                $contenido .= '<td color="#1959B1" direction="horizontal" style="width: 100%; border-bottom: 1px solid rgb(25, 89, 177); border-left: medium none; display: block;" class="color-divider__Divider-sc-1h38qjv-0 dcKmvZ" width="auto" height="1"></td>';
                                            $contenido .= '</tr>';
                                        $contenido .= '</tbody>';
                                    $contenido .= '</table>';
                                $contenido .= '</td>';
                            $contenido .= '</tr>';
                        $contenido .= '</tbody>';
                    $contenido .= '</table>';
                    $contenido .= '<table style="border-collapse: collapse;" width="100%" cellpadding="0">';
                        $contenido .= '<tr>';
                            $contenido .= '<td style="margin: 0.1px; padding: 15px 0px 0px; font: 14.4px / 18.3px terminal, monaco, monospace; color: rgb(186, 198, 217);">';
                                $contenido .= 'IMPORTANTE: El contenido de este correo electrónico y los archivos adjuntos son confidenciales. Está estrictamente prohibido compartir cualquier parte de este mensaje con terceros, sin el consentimiento por escrito del remitente. Si recibió este mensaje por error, responda a este mensaje y continúe con su eliminación, para que podamos asegurarnos de que tal error no ocurra en el futuro.';
                            $contenido .= '</td>';
                        $contenido .= '</tr>';
                    $contenido .= '</table>';
                }
                break;
        }
        
        return $contenido;
    }
}
