<?php


namespace App\Notification;


use App\Entity\User;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mime\Email;

class Sender
{
    protected $mailer;

    public function  __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    public function sendNewUserNotificationToAdmin(UserInterface $user): void
    {
        //pour tester la function
        //file_put_contents('debug.txt', $user->getEmail());
        $message = new Email();
        $message->from('hello@example.com')
            ->to('admin@series.com')
            //->cc('cc@example.com')
            //->bcc('bcc@example.com')
            //->replyTo('fabien@example.com')
            //->priority(Email::PRIORITY_HIGH)
            ->subject('Test new account on series.com !')
            ->text('Sending emails is fun again!')
            ->html('<h1>New account !</h1>email: ' . $user->getEmail());


        $this->mailer->send($message);
    }
}