<?php 
    // Php mailer file Include
    require('phpmailer/PHPMailerAutoload.php');

    // Form Field Data
    $name = trim($_POST['name']);
    $user_email = trim($_POST['email']);
    $message = trim($_POST['message']);

    // Recipient Data
    $to_email = 'youremail@domain.com';
    $to_name = 'Your Name';

    // Success Message
    $success_msg = 'Thanks for Contacting Us! We will contact you soon';

    
    if(!filter_var($user_email, FILTER_VALIDATE_EMAIL)) 
    {
        $signal = 'bad';
        $msg = 'Please enter a valid email!';
    }elseif (empty($name)) {
        $signal = 'bad';
        $msg = 'Name is too short or empty!';
    }elseif (strlen($message)<5) {
        $signal = 'bad';
        $msg = 'Too short message! Please enter something.';
    }
    else{
        $mail = new PHPMailer;

        //  ******************************************
        //  Use these Settings if email is not working.
        //  ******************************************

        /*$mail->isSMTP();                                      // Set mailer to use SMTP
        $mail->Host = 'smtp.gmail.com';  // Specify main and backup SMTP servers
        $mail->SMTPAuth = true;                               // Enable SMTP authentication
        $mail->Username = 'yourusername@gmail.com';                 // SMTP username
        $mail->Password = 'Your Gmail password';                           // SMTP password
        $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
        $mail->Port = 587; */                                   // TCP port to connect to

        //  ******************************************
        //  Use these Settings if email is not working.
        //  ******************************************
        
        $mail->From = $user_email;
        $mail->FromName = $name;
        $mail->addAddress($to_email, $to_name);
        $mail->addReplyTo($user_email, $name);

        $mail->isHTML(true);                                  // Set email format to HTML
        
        $mail->Subject = 'Contact form of Your Site';
        $mail->Body    = 'Name: '.$name.' <br />Message: '.$message;
        
        if(!$mail->send()) {
            echo 'Message could not be sent.';
            echo 'Mailer Error: ' . $mail->ErrorInfo;
        } else {
            $signal = 'ok';
            $msg = $success_msg;
        }
    }

    $data = array(
        'signal' => $signal,
        'msg' => $msg
    );
    echo json_encode($data);