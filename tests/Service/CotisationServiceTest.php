<?php

namespace App\Tests\Service;

use App\Entity\Adherent;
use App\Service\CotisationService;
use PHPUnit\Framework\TestCase;

class CotisationServiceTest extends TestCase
{
    public function testCompute_withNoDiscount_whenAmountIsLessThan5000(): void
    {
        $service = new CotisationService();
        $adherent = new Adherent();
        $adherent->setCotisation(1000);

        $cotisation = $service->compute($adherent, 4200);

        $this->assertEquals(1000, $cotisation);
    }

    public function testCompute_with10PercentDiscount_whenAmountIsBeetween5000and10000(): void
    {
        $service = new CotisationService();
        $adherent = new Adherent();
        $adherent->setCotisation(1000);

        $cotisation = $service->compute($adherent, 5432);

        $this->assertEquals(900, $cotisation);
    }

    public function testCompute_with20PercentDiscount_whenAmountIsGreatThen10000(): void
    {
        $service = new CotisationService();
        $adherent = new Adherent();
        $adherent->setCotisation(1000);

        $cotisation = $service->compute($adherent, 11258);

        $this->assertEquals(800, $cotisation);
    }
}
