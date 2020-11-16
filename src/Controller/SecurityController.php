<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Form\SignupType;
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

class SecurityController extends AbstractController
{
    /**
     * @Route("/signup", name="app_signup")
     */
    public function signup(Request $request, EntityManagerInterface $manager, UserPasswordEncoderInterface $encoder)
    {
        $user = new User();

        $form = $this->createForm(SignupType::class, $user);
        
        $form->handleRequest($request);
        
        if($form->isSubmitted() && $form->isValid()){
            $hash = $encoder->encodePassword($user, $user->getPassword());
            $user->setPassword($hash);
            $user->setImage('default.jpg');
            
            $manager->persist($user);
            $manager->flush();
        }
        return $this->render('security/signup.html.twig', [
            'form' => $form->createView()
            ]);
            
            /*
            


        return $this->redirectToRoute('app_login');
        */
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
     * @Route ("/user/{id}", name="show_user", methods={"GET", "POST"})
     */
    public function show(User $user, Request $request, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
    {   
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

            //return $this->redirectToRoute('user_show', ['id' => $user->getId()]);
        }

        return $this->render('user/show.html.twig', [
            'user' => $user,
            'form' => $form->createView()
        ]);
    }
}
