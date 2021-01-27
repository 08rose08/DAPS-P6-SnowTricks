<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Form\SignupType;
use App\Form\UsernameType;
use App\Form\PassType;

use App\Repository\UserRepository;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Controller\MailerController;
use Symfony\Component\Mailer\MailerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;



class SecurityController extends AbstractController
{
    /**
     * @Route("/signup", name="app_signup")
     */
    public function signup(Request $request, EntityManagerInterface $manager, UserPasswordEncoderInterface $encoder, MailerInterface $mailer)
    {
        $user = new User();

        $form = $this->createForm(SignupType::class, $user);
        
        $form->handleRequest($request);
        
        if($form->isSubmitted() && $form->isValid()){
            $hash = $encoder->encodePassword($user, $user->getPassword());
            $user->setPassword($hash);
            $user->setImage('default.jpg');
            
            $token = $user->getUsername() . '-' . uniqid();
            $user->setToken($token);
            $user->setIsValid(0);

            $user->setRoles(['ROLE_USER']);

            $manager->persist($user);
            $manager->flush();

            $mailerController = new MailerController();
            $mailerController->sendEmail($user, $mailer);

            $this->addFlash('ok', 'Un email d\'activation de compte a été envoyé à votre adresse mail.');

            return $this->redirectToRoute('app_login');

        }else{
            return $this->render('security/signup.html.twig', [
                'form' => $form->createView()
                ]);
        } 
        
    }

    /**
     * @Route("/user/{id}/validate", name="validate_account");
     */
    public function validateAccount(User $user, Request $request, EntityManagerInterface $entityManager)
    {
        $mailToken = $request->query->get('confirm');
        $userToken = $user->getToken();
        if($mailToken == $userToken){
            $user->setIsValid(true);
            $user->setToken('');
            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('ok', 'Compte activé !');
        }
        return $this->redirectToRoute('app_login');
    }

    /**
     * @Route("/user/reset", name="want_reset")
     */
    public function wantResetPass(Request $request, MailerInterface $mailer, UserRepository $userRepository, EntityManagerInterface $manager)
    {
        $form = $this->createForm(UsernameType::class);
        
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $username = $form->get('username')->getData();
            $user = $userRepository->findOneBy(['username' => $username]);

            if(!$user){
                $this->addFlash('error', "Ce nom d\'utilisateur n\'existe pas");

            }else{
              $token = $user->getUsername() . '-' . uniqid();
                $user->setToken($token);
    
                $manager->persist($user);
                $manager->flush();

                $mailerController = new MailerController();
                $mailerController->sendPass($user, $mailer);

                $this->addFlash('ok', "Un email de réinitialisation du mot de passe a été envoyé à votre adresse mail.");
            
            }
        }
        
        return $this->render('security/username.html.twig', ['form' => $form->createView()]);
        
    }
    
    /**
     * @Route("/user/{id}/change", name="change_pass");
     */
    public function changePass(User $user, Request $request, EntityManagerInterface $entityManager, UserPasswordEncoderInterface $encoder)
    {
        $form = $this->createForm(PassType::class);
        
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $mailToken = $request->query->get('confirm');
            $userToken = $user->getToken();

            if($mailToken == $userToken){
                $password = $form->get('password')->getData();
                $hash = $encoder->encodePassword($user, $password);
                $user->setPassword($hash);
                $user->setToken('');
    
                $entityManager->persist($user);
                $entityManager->flush();
    
                $this->addFlash('ok', "Mot de passe réinitialisé !");
                return $this->redirectToRoute('app_login');
            }
        }
        return $this->render('security/pass.html.twig', ['form' => $form->createView()]);

    }

    /**
     * @Route("/login", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // if ($this->getUser()) {
        //     return $this->redirectToRoute('target_path');
        // }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername, 
            'error' => $error]);
    }

    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout()
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    /**
     * @Route ("/profil", name="show_user", methods={"GET", "POST"})
     * @IsGranted("ROLE_USER")
     */
    public function show(Request $request, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
    {   
                //ici vérifier que c'est le bon user 
        $user = $this->getUser();

        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            $imageFile = $form->get('image')->getData();

            if($imageFile)
            {
                $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
            
                $safeFilename = $slugger->slug($user->getId());
                $newFilename = $safeFilename.'-'.uniqid().'.'.$imageFile->guessExtension();
            
                try {
                    $imageFile->move(
                        $this->getParameter('avatar_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }
                $user->setImage($newFilename);
            }
            
            $entityManager->persist($user);
            $entityManager->flush();

        }

        return $this->render('user/show.html.twig', [
            'user' => $user,
            'form' => $form->createView()
        ]);
    }
}
