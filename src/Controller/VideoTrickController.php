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
use App\Repository\ImageTrickRepository;

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

class VideoTrickController extends AbstractController
{
    /**
     * @Route("videoTrick/{id}/delete", name="videoTrick_delete")
     * @IsGranted("ROLE_USER")
     */
    public function videoDelete(Request $request, VideoTrick $videoTrick, EntityManagerInterface $entityManager)
    {
        if(!$videoTrick){
            $this->addFlash('info', 'Video introuvable !');
            return $this->redirectToRoute('trick_index');
        }else{
            
            $id = $videoTrick->getTrick()->getId();
            $entityManager->remove($videoTrick);
            $entityManager->flush();
            return $this->redirectToRoute('trick_edit', ['id' => $id]);
        }
    }
}