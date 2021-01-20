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

class ImageTrickController extends AbstractController
{
    /**
     * @Route("imageTrick/{id}/delete", name="imageTrick_delete")
     * @IsGranted("ROLE_USER")
     */
    public function imgDelete(Request $request, ImageTrick $imageTrick, EntityManagerInterface $entityManager, Filesystem $filesystem)
    {
        if(!$imageTrick){
            $this->addFlash('info', 'Image introuvable !');
            return $this->redirectToRoute('trick_index');
        }else{
            $path = $this->getParameter('trick_directory').'/'.$imageTrick->getSrc();
            $filesystem->remove($path);
            $id = $imageTrick->getTrick()->getId();
            $entityManager->remove($imageTrick);
            $entityManager->flush();
            return $this->redirectToRoute('trick_edit', ['id' => $id]);
        }
    }
}