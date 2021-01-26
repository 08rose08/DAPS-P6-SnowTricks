<?php

namespace App\Tests\Repository;

use App\Repository\FigTypeRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class FigTypeRepositoryTest extends kernelTestCase // Récupère le repository
{
    public function testCount()
    {
        self::bootKernel();

        $figTypes = self::$container->get(FigTypeRepository::class)->count([]);

        $this->assertEquals(4, $figTypes);
    }
}