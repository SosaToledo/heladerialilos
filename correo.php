<?php
if(isset($_POST['correo'])) {
     
    //Para y asunto del mensaje a enviar
    $email_subject = "pruebas";
     
     
    function died($error) {
       
        echo "Lo sentimos mucho, pero hubo un error(es) encontrado en el formulario que se quizo cotizar. ";
        echo "Estos son los errores:<br /><br />";
        echo $error."<br /><br />";
        echo "Retroceda y arregle el error.<br /><br />";
        die();
    }
     
    // validation expected data exists
    
    if(!isset($_FILES['archivo']['name'])){
        died('Lo sentimos, pero hubo un error(es) encontrado en el formulario que se quizo cotizar.');       
    }
     
    
    //variables para los campos
    $nombre = $_POST['nombre']; // required
    $email_from = $_POST['correo']; // required
        
   // print_r("var1: ".$nombreArchivo." var2:".$tamanioArchivo."  var3:".$tipoArchivo."  var4:".$tempArchivo);exit;
     //print_r($_FILES);exit;
    $error_message = "";
    $email_exp = '/^[A-Za-z0-9._%-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,4}$/';
    $string_exp = "/^[A-Za-z .'-]+$/";
    
  if(!preg_match($email_exp,$email_from)) {
    $error_message .= 'El email que ingresaste no parece ser válido. <br />';
  }
  if(!preg_match($string_exp,$nombre)) {
    $error_message .= 'El nombre que ingresaste no parece ser válido. <br />';
  }
  if(strlen($error_message) > 0) {
    died($error_message);
  }
     
    
    
    //variables para los datos del archivo 
    $nombrearchivo = $_FILES['archivo']['name'];
    $archivo = $_FILES['archivo']['tmp_name'];

    // Leemos el archivo a adjuntar
    
    $archivo = file_get_contents($archivo);
    $archivo = chunk_split(base64_encode($archivo));
     
    
     
// create email headers

      /*$headers = "MIME-Version: 1.0\r\n";
      $headers .= "Content-type: multipart/mixed;";
      $headers .= "boundary=\"=A=G=R=O=\"\r\n";
      $headers .= "From : ".$email_from."\r\n"; */
     

        function clean_string($string) {
      $bad = array("content-type","bcc:","to:","cc:","href");
      return str_replace($bad,"",$string);
    }
    
    
     // Cuerpo del Email
    $CuerpoMensaje .= "Recordá no contestar este correo desde el boton responder, para ello precione en el correo de color azul debajo de este mensaje\r\n\r\n";
    $CuerpoMensaje .= "Nombre: ".clean_string($nombre)."\r\n";
    $CuerpoMensaje .= "Correo: ".clean_string($email_from)."\r\n";
      
    //$header .= "Reply-To: " . $replyto . "\r\n";
    $headers .= "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: multipart/mixed; boundary=\"=A=G=R=O=\"\r\n\r\n";
    
    
    //armando mensaje del email
    $email_message = "--=A=G=R=O=\r\n";
    $email_message .= "Content-type:text/plain; charset=utf-8\r\n";
    $email_message .= "Content-Transfer-Encoding: 7bit\r\n\r\n";
    $email_message .= $CuerpoMensaje . "\r\n\r\n";
    
    //archivo adjunto  para email    
    $email_message .= "--=A=G=R=O=\r\n";
    $email_message .= "Content-Type: application/octet-stream; name=\"" . $nombrearchivo . "\"\r\n";
    $email_message .= "Content-Transfer-Encoding: base64\r\n";
    $email_message .= "Content-Disposition: attachment; filename=\"" . $nombrearchivo . "\"\r\n\r\n";
    $email_message .= $archivo . "\r\n\r\n";
    $email_message .= "--=A=G=R=O=--";
    
    
    
    //enviamos el email

    mail("luchososa1990@gmail.com", $email_subject, $email_message, $headers);
    header('Location: index.html');
          
}
    
    
?>