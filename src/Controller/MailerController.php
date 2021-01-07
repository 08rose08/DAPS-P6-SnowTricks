<?php

namespace App\Controller;

use App\Entity\User;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class MailerController extends AbstractController
{
    /**
     * @Route("/email", name="email")
     */
    /*public function sendEmail(MailerInterface $mailer)
    {
        $email = (new Email())
            ->from('hello@example.com')       
            ->to('naudinrose@gmail.com')
            //->cc('cc@example.com')
            //->bcc('bcc@example.com')
            //->replyTo('fabien@example.com')
            //->priority(Email::PRIORITY_HIGH)
            ->subject('Confirmation de création d\'un compte Snowtricks')
            ->text('Sending emails is fun again!')
            ->html('<p>Salut,</p>
            <p>clique sur cette URL pour activer ton compte Snowtricks : blablabla<br>
             si le lien ne fonctionne pas, copie-colle l\'URL dans ton navigateur</p>
             <p>Bienvenue dans la communauté Snowtricks,<br>
             L\'équipe Snowtricks</p>');

        $mailer->send($email);


        return $this->redirectToRoute('trick_index');
        // ...
    }*/
    
    public function sendEmail($user, $mailer)
    {
        $email = (new Email())
            ->from('hello@example.com')       
            ->to($user->getEmail())
            //->cc('cc@example.com')
            //->bcc('bcc@example.com')
            //->replyTo('fabien@example.com')
            //->priority(Email::PRIORITY_HIGH)
            ->subject('Confirmation de création d\'un compte Snowtricks')
            ->text('Sending emails is fun again!')
            ->html('<p>Salut '.$user->getUsername().',</p>
            <p>clique sur cette URL pour activer ton compte Snowtricks : blablabla<br>
             si le lien ne fonctionne pas, copie-colle l\'URL dans ton navigateur</p>
             <p>Bienvenue dans la communauté Snowtricks,<br>
             L\'équipe Snowtricks</p>');

        $mailer->send($email);

        //return $this->render('security/sendmail.html.twig');
        //return $this->redirectToRoute('trick_index'); // fonctionne via l'url /email mais pas par le bouton create account
        // ...
    }
    
}