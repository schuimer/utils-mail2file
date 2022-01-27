<?php

    error_reporting(E_ERROR | E_PARSE);
    require __DIR__ . '/vendor/autoload.php';
    require_once("config.php");

    use Ddeboer\Imap\Server;
    $server = new Server(MAIL_SERVER);
    $connection = $server->authenticate(MAIL_USER, MAIL_PASSWORD);
    $mailbox = $connection->getMailbox(MAIL_MAILBOX);
    $messages = $mailbox->getMessages();
   
    $attachmentDir = dirname(__FILE__)."/attachments/";

    // iterate through messages
    foreach ($messages as $message) {

        // check for attachments and save them to attachment folder
        $attachments = $message->getAttachments();
        foreach ($attachments as $attachment) {
            file_put_contents($attachmentDir.$attachment->getFilename(),$attachment->getDecodedContent());
            echo "attachment ".$attachmentDir.$attachment->getFilename()." saved to folder ".$attachmentDir."<br>";
        }

        // mark message as deleted
        $message->delete();

    }

    // delete messages
    $connection->expunge();

?>