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
    public function sendEmail($user, $mailer)
    {
        $email = (new Email())
            ->from('contact@snowtricks.com')       
            ->to($user->getEmail())

            ->subject('Confirmation de création d\'un compte Snowtricks')
            ->text('Sending emails is fun again!')
            ->html('<p>Salut '.$user->getUsername().',</p>
            <p>clique sur ce lien pour activer ton compte Snowtricks : 
            <a href="http://localhost:8000/user/'.$user->getId().'/validate?confirm='.$user->getToken().'">localhost:8000/user/'.$user->getId().'/validate?confirm='.$user->getToken().'</a><br>
             si le lien ne fonctionne pas, copie-colle l\'URL dans ton navigateur</p>
             <p>Bienvenue dans la communauté Snowtricks,<br>
             L\'équipe Snowtricks</p>');

        $mailer->send($email);
    }

    /**
     * @Route("/emailPass", name="email_pass")
     */
    public function sendPass($user, $mailer)
    {
        $email = (new Email())
            ->from('contact@snowtricks.com')       
            ->to($user->getEmail())
            ->subject('Changement de mot de passe Snowtricks')
            ->text('Sending emails is fun again!')
            ->html('<p>Salut '.$user->getUsername().',</p>
            <p>clique sur ce lien pour définir un nouveau mot de passe Snowtricks : 
            <a href="http://localhost:8000/user/'.$user->getId().'/change?confirm='.$user->getToken().'">localhost:8000/user/'.$user->getId().'/change?confirm='.$user->getToken().'</a><br>
             si le lien ne fonctionne pas, copie-colle l\'URL dans ton navigateur</p>
             <p>Bon retour dans la communauté Snowtricks,<br>
             L\'équipe Snowtricks</p>');

        $mailer->send($email);
    }
}