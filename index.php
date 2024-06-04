<?php
require_once("lib/template.inc");
require_once("lib/funciones.php");
require_once("lib/config.php");
$path="templates/";
$vector=obtener_templates($path);
$t = new Template("templates/");
$t->set_file($vector);
if(!isset($s) || $s == "")
{
	$s="home";
}
set_menu($s);
$tot = count($array_sub);
for($i = 0; $i < $tot; $i++)
{
	$t->set_var($array_sub[$i],"");	
}
$t->set_var(strtoupper($s),"_activo");
switch($s)
{
	default:
	case "home":
	{
		$pag = "templates/" . strtolower($s) . ".html";
		if(is_file($pag))
		{
			$pagina="_" . strtoupper($s);
			$t->parse("CONTENIDO",$pagina);			
		}		
		else
		{
			header("location:index.php?s=titulo&p=menu_noticias&seccion=home");	
		}
	}
	break;
	
		//SECCION//
	case "titulo":
	{
		$pag = "templates/" . strtolower($p) . ".html";
		if(is_file($pag))
		{
			$pag = "templates/" . strtolower($seccion) . ".html";
			if(is_file($pag))
			{
				$sub_menu = "_" . strtoupper($p);
				$t->parse("SUBMENU",$sub_menu);
				$pagina = "_" . strtoupper($seccion);

				$t->parse("CONTENIDO",$pagina);	
			}
			else
			{
				header("location:index.php");	
			}
		}
		else
		{
			header("location:index.php");	
		}	
	}
	break;
	//FIN SECCION//
		
	case "enviar":
	{
		foreach($_POST as $nombre_campo => $valor)
		{
			ValidarDatos($valor);	
		}		
		$des = $contacto_email;
		$de = $nombre . " " . $apellido;
		$de_email = $email;
		$fec=date("d/m/Y - H:i:s");
		$cuerpo="Mensaje de contacto de $nombre $apellido \n";
		$cuerpo.="\n Nombre: " . $nombre ;
		$cuerpo.="\n Apellido: " . $apellido;
		$cuerpo.="\n Teléfono: " . $telefono;
		$cuerpo.="\n Email: " . $email;
		$cuerpo.="\n Dirección: " . $direccion;
		$cuerpo.="\n Mensaje: " . $mensaje;
		$cuerpo.="\n Fecha de envio: " . $fec;
		$headersAviso = "From: $de <$de_email>\n";
		$headersAviso .= "Reply-To: $de_email\n";
		$headersAviso .= "Return-Path: $de_email\n";
		$headersAviso .= "X-Originally_To: $des\n";
		$headersAviso .= "X-Sender: $de_email\n";
		$headersAviso .= "X-Mailer: PHP/". phpversion()."\n";
		$headersAviso .= "MIME-Version: 1.0\n";
		$headersAviso .= "Content-Type: text/plain; charset=ISO-8859-1\n"." Content-Transfer-Encoding: 7bit\n\n";
		mail($des,$asunto,$cuerpo,$headersAviso);
		header("location:index.php?s=contacto_ok");		
	}
	break;
	
	
}
$t->pparse("OUTPUT","_MAIN");
?>