<?php
date_default_timezone_set('Europe/Rome');
/**
 * Created by PhpStorm.
 * User: Claudio Cardinale <cardi@thecsea.it>
 * Date: 07/10/15
 * Time: 15.33
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 */

require_once(__DIR__ . "/vendor/autoload.php");

if(isset($argv[1]) && $argv[1])
    $stream = fopen($argv[1], "r");
else
    $stream = STDIN;

print "SMTP host: ";
fscanf($stream, "%s\n", $smtp);
print ($stream != STDIN?$smtp."\n":"");
print "SMTP user: ";
fscanf($stream, "%s\n", $user);
print ($stream != STDIN?$user."\n":"");
print "SMTP password: ";
fscanf($stream, "%s\n", $password);
print ($stream != STDIN?$password."\n":"");
print "SMTPSecure (encryption type): ";
fscanf($stream, "%s\n", $encryptionType);
print ($stream != STDIN?$encryptionType."\n":"");
print "SMTP port: ";
fscanf($stream, "%d\n", $smtpPort);
print ($stream != STDIN?$smtpPort."\n":"");
print "From email: ";
fscanf($stream, "%s\n", $fromEmail);
print ($stream != STDIN?$fromEmail."\n":"");
print "from name: ";
fscanf($stream, "%s\n", $nameF);
print ($stream != STDIN?$nameF."\n":"");
print "to: ";
fscanf($stream, "%s\n", $to);
print ($stream != STDIN?$to."\n":"");
print "Subject: ";
fscanf($stream, "%s\n", $subject);
print ($stream != STDIN?$subject."\n":"");
print "Body file path: ";
fscanf($stream, "%s\n", $body);
print ($stream != STDIN?$body."\n":"");
print "Attachment file path (blank for not attachment): ";
fscanf($stream, "%s\n", $attachment);
print ($stream != STDIN?$attachment."\n":"");
print "Attachment cc, separated by semicolon (blank for not attachment): ";
fscanf($stream, "%s\n", $cc);
print ($stream != STDIN?$cc."\n":"");
print "Send time (h:m:s): ";
fscanf($stream, "%s\n", $time);
print (($stream != STDIN)?$time."\n":"");

$time = explode(":", $time);
//TODO check time exploded length
$time = mktime($time[0], $time[1], $time[2]);
if($time-time()>1)
    sleep($time-time());

$mail = new PHPMailer();

//$mail->SMTPDebug = 4;                              // Enable verbose debug output

$mail->isSMTP(); // Set mailer to use SMTP
$mail->Host = $smtp;  // Specify main and backup SMTP servers
$mail->SMTPAuth = true;                               // Enable SMTP authentication
$mail->Username = $user;                 // SMTP username
$mail->Password = $password;                           // SMTP password
$mail->SMTPSecure = $encryptionType;                            // Enable TLS encryption, `ssl` also accepted
$mail->Port = $smtpPort;                                    // TCP port to connect to

$mail->setFrom($fromEmail, $nameF);
$mail->addAddress($to);     // Add a recipient
//$mail->addAddress('ellen@example.com');               // Name is optional
$mail->addReplyTo($fromEmail, $nameF);
$cc = explode(";", $cc);
if(count($cc))
    foreach($cc as $value)
        $mail->addCC($value);
//$mail->addBCC('bcc@examplie.com');

//$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
//$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name
if($attachment)
    $mail->addAttachment($attachment);
$mail->isHTML(true);                                  // Set email format to HTML

$mail->Subject = $subject;
$mail->Body    = file_get_contents($body);
//$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

if(!$mail->send()) {
    echo "Message could not be sent.\n";
    echo 'Mailer Error: ' . $mail->ErrorInfo. "\n";
} else {
    echo "Message has been sent\n";
}