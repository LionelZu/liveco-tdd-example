<?php

namespace App\Dto;

class EtudeDto {
    private $currentTotal;
    private $negotiateTotal;
    private $economy;
    private $cotisation;
    private $realEconomy;

    public function __construct($currentTotal, $negotiateTotal, $cotisation) {
        $this->currentTotal = $currentTotal;
        $this->negotiateTotal = $negotiateTotal;
        $this->economy = $currentTotal - $negotiateTotal;
        $this->cotisation = 0 + $cotisation;
        $this->realEconomy = $currentTotal - $negotiateTotal - $cotisation;
    }


    /**
     * Get the value of currentTotal
     */
    public function getCurrentTotal()
    {
        return $this->currentTotal;
    }

    /**
     * Get the value of negotiateTotal
     */
    public function getNegotiateTotal()
    {
        return $this->negotiateTotal;
    }

    /**
     * Get the value of economy
     */
    public function getEconomy()
    {
        return $this->economy;
    }


    /**
     * Get the value of cotisation
     */
    public function getCotisation()
    {
        return $this->cotisation;
    }

    /**
     * Get the value of realEconomy
     */
    public function getRealEconomy()
    {
        return $this->realEconomy;
    }
}