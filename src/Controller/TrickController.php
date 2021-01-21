<?php

namespace App\Controller;

use App\Entity\Trick;
use App\Entity\Comment;
use App\Form\Trick1Type;
use App\Form\TrickMainType;
use App\Form\TrickOptionType;
use App\Entity\ImageTrick;
use App\Entity\VideoTrick;
use App\Repository\TrickRepository;
use App\Controller\CommentController;
use App\Repository\CommentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Filesystem\Filesystem;

class TrickController extends AbstractController
{
    /**
     * @Route("/", name="trick_index", methods={"GET"})
     */
    public function index(TrickRepository $trickRepository, Request $request): Response
    {
        $rang = max(0, $request->query->getInt('rang', 0));
        $tricks = $trickRepository->findByPaginator($rang);
        $rang++;
        //dd($tricks);

        $nbPagesMax = ceil(count($trickRepository->findAll()) / TrickRepository::PAGINATOR_PER_PAGE);

        return $this->render('trick/index.html.twig', [
            'tricks' => $tricks,
            'rang' => $rang,
            'nbPagesMax' => $nbPagesMax,
        ]);
    }
    /**
     * @Route("/more", name="trick_more", methods={"GET"})
     */
    public function loadMore(TrickRepository $trickRepository, Request $request)
    {
        $rang = max(0, $request->query->getInt('rang', 0));
        $tricks = $trickRepository->findByPaginator($rang);
        $rang++;
        //dd($tricks);
        $nbPagesMax = ceil(count($trickRepository->findAll()) / TrickRepository::PAGINATOR_PER_PAGE);

        $twig = $this->render('trick/_load_more.html.twig', [
            'tricks' => $tricks,
            'rang' => $rang,
            'nbPagesMax' => $nbPagesMax,
        ]);
        return $this->json(['code' => 200, 'twig' => $twig], 200);
    }

    private function addImg($trick, $request, $slugger, $imageFile)
    {   
        //dd($imageTrick);
        
            $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
            
            $safeFilename = $slugger->slug($trick->getId());
            $newFilename = $safeFilename.'-'.uniqid().'.'.$imageFile->guessExtension();
    
            try {
                $imageFile->move(
                    $this->getParameter('trick_directory'),
                    $newFilename
                );
            } catch (FileException $e) {
                // ... handle exception if something happens during file upload
            }
            return $newFilename;
              
    }

    private function addVideo($trick, $entityManager, $videoTrick)
    {
        if($videoTrick->getSrc())
        {
            $videoTrick->setTrick($trick);
    
            $entityManager->persist($videoTrick);
            $entityManager->flush();
        }
    }

    /**
     * @Route("trick/new", name="trick_new", methods={"GET","POST"})
     * @Route("trick/{id}/edit", name="trick_edit", methods={"GET","POST"})
     * @IsGranted("ROLE_USER")
     */
    public function new(Trick $trick = null, Request $request, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
    {
        if(!$trick) {
            $trick = new Trick();
        }

        $form = $this->createForm(Trick1Type::class, $trick);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            if(!$trick->getId()){
                $trick->setCreatedAt(new \Datetime());                
            }else{
                $trick->setEditAt(new \Datetime());
            }
            $entityManager->persist($trick);
            $entityManager->flush();

            //Add mainImage
            $mainImageFile = $form->get('image')->getData();
            //dd($mainImageFile);
            if($mainImageFile)
            {
                $finalMainImageFilename = $this->addImg($trick, $request, $slugger, $mainImageFile);
                $trick->setImage($finalMainImageFilename);
            }
            elseif(!$mainImageFile && !$trick->getImage()) 
            {
                $trick->setImage('default.jpg');
            }
            $entityManager->persist($trick);
            $entityManager->flush();

            // -------- Gallery -------
            
            $images = $form->get('imageTricks')->getData();
            //$images = $trick->getImageTricks();
            //dd($images);
            foreach($images as $image){
                $imageFile = $image->getFile();

                if($imageFile)
                {
                    $newFilename = $this->addImg($trick, $request, $slugger, $imageFile);
                    
                    $imageTrick = new ImageTrick();
                    $imageTrick->setSrc($newFilename)
                            ->setTrick($trick);
                    //$trick->addImageTrick($imageTrick);
                    $entityManager->persist($imageTrick);
                    $entityManager->flush();
                }
            }

            $videos = $form->get('videos')->getData();
            foreach($videos as $video){
                $this->addVideo($trick, $entityManager, $video);
            }

            $this->addFlash('info', 'Trick ajouté !');

            return $this->redirectToRoute('trick_index');
        }

        return $this->render('trick/new.html.twig', [
            'formTrick' => $form->createView()  
        ]);
    }

    /**
     * @Route("trick/{id}", name="trick_show", methods={"GET", "POST"})
     */
    
    public function show(Trick $trick, Request $request, CommentController $commentController, EntityManagerInterface $entityManager, CommentRepository $commentRepository): Response
    {   
        $comment = null;
        $commentForm = $commentController->new($comment, $trick, $request, $entityManager);

        $offset = max(0, $request->query->getInt('offset', 0));
        $paginator = $commentRepository->findByPaginator($trick, $offset);

        $previous = $offset - CommentRepository::PAGINATOR_PER_PAGE;
        $next = min(count($paginator), $offset + CommentRepository::PAGINATOR_PER_PAGE);

        return $this->render('trick/show.html.twig', [
            'trick' => $trick,
            'commentForm' => $commentForm,
            'comments' => $paginator,
            'previous' => $offset - CommentRepository::PAGINATOR_PER_PAGE,
            'next' => min(count($paginator), $offset + CommentRepository::PAGINATOR_PER_PAGE),
        ]);
    }
    
    /**
     * @Route("trick/{id}/changePage", name="trick_change", methods={"GET"})
     */
    public function changePage(Trick $trick, Request $request, CommentRepository $commentRepository)
    {
        $offset = max(0, $request->query->getInt('offset', 0));

        $comments = $commentRepository->findByPaginator($trick, $offset);

        $previous = $offset - CommentRepository::PAGINATOR_PER_PAGE;
        $next = min(count($comments), $offset + CommentRepository::PAGINATOR_PER_PAGE);

        $twig = $this-> render('comment/_index.html.twig', [
            'trick' => $trick,
            'comments' => $comments,
            'previous' => $previous,
            'next' => $next,

        ]);

        // renvoyer aussi le form ? à tester après -> car include différent -> donc non?
        return $this->json(['code' => 200, 'twig' => $twig], 200);

    }

    /**
     * @Route("trick/{id}/edit", name="trick_edit", methods={"GET","POST"})
     * @IsGranted("ROLE_USER")
     */
    public function edit(Request $request, Trick $trick, SluggerInterface $slugger, EntityManagerInterface $entityManager, TrickRepository $trickRepository, Filesystem $filesystem): Response
    {
        $formTrick = $this->createForm(TrickMainType::class, $trick);
        $formTrick->handleRequest($request);

        if ($formTrick->isSubmitted() && $formTrick->isValid()) {
            //$this->getDoctrine()->getManager()->flush();
            
            //ici mainImg
            $imageFile = $formTrick->get('image')->getData();
            
            if($imageFile)
            {
                $oldTrick = $trickRepository->findOneBy(['id' => $trick->getId()]);
                $oldFileName = $oldTrick->getImage();
                if($oldFileName != 'default.jpg'){
                    $path = $this->getParameter('trick_directory').'/'.$oldFileName;
                    $filesystem->remove($path);
                }
                
                $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                
                $safeFilename = $slugger->slug($trick->getId());
                $newFilename = $safeFilename.'-'.uniqid().'.'.$imageFile->guessExtension();
                
                try {
                    $imageFile->move(
                        $this->getParameter('trick_directory'),
                        $newFilename
                    );
                    
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }
                $trick->setImage($newFilename);

                

                $entityManager->persist($trick);
                $entityManager->flush();
            }
            

            return $this->redirectToRoute('trick_show', ['id' => $trick->getId()]);
        }

        $formOption = $this->createForm(TrickOptionType::class);
        $formOption->handleRequest($request);
        if ($formOption->isSubmitted() && $formOption->isValid()) {
            //$this->getDoctrine()->getManager()->flush();

            $images = $formOption->get('imageTricks')->getData();
            //$images = $trick->getImageTricks();
            //dd($images);
            foreach($images as $image){
                $imageFile = $image->getFile();

                if($imageFile)
                {
                    $newFilename = $this->addImg($trick, $request, $slugger, $imageFile);
                    
                    $imageTrick = new ImageTrick();
                    $imageTrick->setSrc($newFilename)
                            ->setTrick($trick);
                    //$trick->addImageTrick($imageTrick);
                    $entityManager->persist($imageTrick);
                    $entityManager->flush();
                }
            }

            $videos = $formOption->get('videos')->getData();
            foreach($videos as $video){
                $this->addVideo($trick, $entityManager, $video);
            }

            return $this->redirectToRoute('trick_index');
        }

        return $this->render('trick/edit.html.twig', [
            'trick' => $trick,
            'formTrick' => $formTrick->createView(),
            'formOption' => $formOption->createView(),
        ]);
    }

    /**
     * @Route("trick/{id}/delete", name="trick_delete")
     * @IsGranted("ROLE_USER")
     */
    public function delete(Request $request, Trick $trick, EntityManagerInterface $entityManager, Filesystem $filesystem): Response
    {
        if(!$trick){
            //message flash ? en redirigeant vers trick_index?
            $this->addFlash('info', 'Trick introuvable !');
            return $this->redirectToRoute('trick_index');
        }else{
            // 1. supprimer les fichiers img commencant par id_
            $mainImage = $trick->getImage();
            $images = $trick->getImageTricks();
            
            foreach($images as $image){
                $path = $this->getParameter('trick_directory').'/'.$image->getSrc();
                $filesystem->remove($path);
            }
            if($mainImage != 'default.jpg'){
                $path = $this->getParameter('trick_directory').'/'.$mainImage;
                $filesystem->remove($path);
            }
            // 2. supprimer le trick
            $entityManager->remove($trick);
            $entityManager->flush();
            //message flash ? ok
            $this->addFlash('info', 'Trick supprimé !');
            return $this->redirectToRoute('trick_index');
        }
    }
}