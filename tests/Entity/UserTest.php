<?php

namespace App\Tests\Entity;

use App\Entity\User;
use App\Entity\FigType;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class UserTest extends KernelTestCase
{
    public function getEntity(): User
    {           
        return (new User())
            ->setUsername('UserTest')
            ->setEmail('userTest@test.fr')
            ->setImage('default.jpg')
            ->setPassword('$2y$13$P0tD1QEnEH4k.WasoWZ3veW7LBloElZL.OwK5v1yGfBCYrai0wja2') // = 'test'
            ->setIsValid(1)
            ->setRoles(['ROLE_USER']);
    }

    public function assertHasErrors(User $user, int $nb = 0)
    {
        self::bootKernel();

        $error = self::$container->get('validator')->validate($user);
        $this->assertCount($nb, $error);
    }

    public function testValidEntity() 
    {
        $this->assertHasErrors($this->getEntity(), 0);
    }

    public function testInvalidUniqueUsernameEntity() 
    {  
        $user = $this->getEntity()->setUserName("User"); //Nom existant dans la fixture
        $this->assertHasErrors($user, 1);
    }

    public function testInvalidUniqueEmailEntity() 
    {  
        $user = $this->getEntity()->setEmail("user@test.fr"); //Email existant dans la fixture
        $this->assertHasErrors($user, 1);
    }

    public function testInvalidUsernameEntity()
    {
        $this->assertHasErrors($this->getEntity()->setUsername('a'), 1); //Nom trop court
    }

    public function testBlankUsernameEntity()
    {
        $this->assertHasErrors($this->getEntity()->setUsername(''), 2); //Nom vide et trop court
    }

    public function testBlankEmailEntity()
    {
        $this->assertHasErrors($this->getEntity()->setEmail(''), 1); //Email vide
    }

}