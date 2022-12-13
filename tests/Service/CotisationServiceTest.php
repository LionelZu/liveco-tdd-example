<?php

namespace App\Tests\Service;

use CotisationService;
use PHPUnit\Framework\TestCase;

class CotisationServiceTest extends TestCase
{
    public function testSomething(): void
    {
        $service = new CotisationService();
        $realCotisation = $service->computeCotisation(100, 900);
        $this->assertEquals($realCotisation, 100);
    }

    public function should_Discount10Percent_WhenCotisationIsGreaterThan5000(): void
    {
        $service = new CotisationService();
        $realCotisation = $service->computeCotisation(100, 5300);
        $this->assertEquals($realCotisation, 100);
    }

    public function should_Discount20Percent_WhenCotisationIsGreaterThan10000(): void
    {
        $service = new CotisationService();
        $realCotisation = $service->computeCotisation(100, 10200);
        $this->assertEquals($realCotisation, 80);
    }


    public function should_Discount20Percent_WhenCotisationIsEqual10000(): void
    {
        $service = new CotisationService();
        $realCotisation = $service->computeCotisation(100, 10000);
        $this->assertEquals($realCotisation, 90);
    }
}
