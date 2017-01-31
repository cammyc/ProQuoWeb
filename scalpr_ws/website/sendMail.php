<?php
use google\appengine\api\mail\Message;


try {
    $message = new Message();
    $message->setSender('ProQuo@scalpr-143904.appspotmail.com');
    $message->addTo('cammy.connor@gmail.com');
    $message->setSubject('Forgot Password - Do Not Reply');
    $message->setTextBody('Please follow the link below to reset your password');
    $message->send();
    echo 'Mail Sent';
} catch (InvalidArgumentException $e) {
    echo 'There was an error'.var_dump($e);
}

?>