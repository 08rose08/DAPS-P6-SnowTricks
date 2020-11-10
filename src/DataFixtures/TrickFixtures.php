<?php

namespace App\DataFixtures;

use App\Entity\Trick;
use App\Entity\FigType;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class TrickFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {

        $figTypes = ['Grab', 'Rotation', 'Flip', 'Slide'];

        foreach($figTypes as $figTypeName){
            $figType = new FigType();
            $figType->setName($figTypeName);
            $manager->persist($figType);

            for($i = 1; $i <= 3; $i++){
                $trick = new Trick();
                $trick->setName("Trick n°$i")
                      ->setDescription("La description du trick n°$i")
                      ->setImage("http://placehold.it/200x100")
                      ->setFigType($figType);
                $manager->persist($trick);
            }

        }

        $manager->flush();
    }
}
