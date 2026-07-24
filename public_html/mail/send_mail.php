<?php 

if (isset($_POST["btn"])) {

if ( !isset($_POST["nombre"]) || !isset($_POST["email"]) || !isset($_POST["tel"])  || !isset($_POST["mensaje"]) || !isset($_POST["asunto"])  && empty($_POST["invisible"])) {

    die ("Es necesario completar todos los datos del formulario");


}
else { 

$nombre = htmlspecialchars($_POST['nombre']);

$tel = htmlspecialchars($_POST['tel']);

$email = htmlspecialchars($_POST['email']);

$asunto = htmlspecialchars($_POST['asunto']);

$mensaje = htmlspecialchars($_POST['mensaje']);
 




$to = "info@conativetime.com";
$subject = "Formulario de Contactos";
$message =  "
   
<table border='1' cellpadding='0' cellspacing='0'>

  <tr>

    <td height='40' colspan='2' align='center'><h2>Mensaje de {$nombre}</h2></td>

  </tr>

  <tr>

    <td style='padding: 5px;'><div><b>Nombre y Apellido:</b></div></td>

    <td style='padding: 5px;'>{$nombre}</td>

  </tr>

  <tr>

    <td style='padding: 5px;'><div><b>teléfono / Whatsapp:</b></div></td>

    <td style='padding: 5px;'>{$tel}</td>

  </tr>

  <tr>

    <td style='padding: 5px;'><div><b>Correo electrónico:</b></div></td>

    <td style='padding: 5px;'>{$email}</td>

  </tr>  
    <tr>

    <td style='padding: 5px;'><div><b>Asunto:</b></div></td>

    <td style='padding: 5px;'>{$asunto}</td>

  </tr>
   <tr>

    <td style='padding: 5px;'><div><b>Mensaje:</b></div></td>

    <td style='padding: 5px;'>{$mensaje}</td>

  </tr>

  <tr>

    <td style='padding: 5px;' height='50' colspan='2' align='center'><div>

      <p style='font-size:10px;'>Este mensaje es generado por el formulario de contacto de la web de Conative Time, Powered by <a href='http://www.rdgwebmaster.com'>RDG Webmaster</a></p>

    </div></td>

  </tr>

</table>



<br />";
$headers = 'From: contactos';
$headers = "MIME-Version: 1.0" . "\r\n";
$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

 mail($to, $subject, $message, $headers);

 if (mail) {
       header('location: /mensaje-enviado');
 }
 else{
     header('location: /mensaje-no-enviado');  
 }

  }



}
else{

  header('location: /mensaje-no-enviado'); 

}

    
?>