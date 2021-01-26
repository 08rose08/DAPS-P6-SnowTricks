<?php

namespace App\Tests\Entity;

use App\Entity\Trick;
use App\Repository\FigTypeRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class TrickTest extends KernelTestCase
{
    public function getEntity(): Trick
    {
        self::bootKernel();

        $figTypeRepository = self::$container->get(FigTypeRepository::class);
        $figType = $figTypeRepository->findOneBy(['id' => 1]);

        return (new Trick())
            ->setName("Trick")
            ->setFigType($figType)
            ->setDescription("la description")
            ->setImage('default.jpg')
            ->setCreatedAt(new \Datetime());
    }

    public function assertHasErrors(Trick $trick, int $nb = 0)
    {
        self::bootKernel();

        $error = self::$container->get('validator')->validate($trick);
        $this->assertCount($nb, $error);
    }

    public function testValidEntity() 
    {
        $this->assertHasErrors($this->getEntity(), 0);
    }

    public function testInvalidEntity() 
    {  
        $trick = $this->getEntity()->setName("Trick Grab n°1"); //Nom d'un trick existant dans la fixture
        $this->assertHasErrors($trick, 1);
    }
}