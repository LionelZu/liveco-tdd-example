<?php

namespace App\Dto;

class EtudeDTO {
    private ?float $total;
    private ?float $totalNegociate;
    private ?float $economy;
    private ?float $cotisation;
    private ?float $economyReal;

    public function __construct($total, $totalNegociate, $economy, $cotisation, $economyReal) {
        $this->total = $total;
        $this->totalNegociate = $totalNegociate;
        $this->economy = $economy;
        $this->cotisation = $cotisation;
        $this->economyReal = $economyReal;
    }

    /**
     * Get the value of total
     */
    public function getTotal(): ?float
    {
        return $this->total;
    }

    /**
     * Set the value of total
     */
    public function setTotal(?float $total): self
    {
        $this->total = $total;

        return $this;
    }

    /**
     * Get the value of totalNegociate
     */
    public function getTotalNegociate(): ?float
    {
        return $this->totalNegociate;
    }

    /**
     * Set the value of totalNegociate
     */
    public function setTotalNegociate(?float $totalNegociate): self
    {
        $this->totalNegociate = $totalNegociate;

        return $this;
    }

    /**
     * Get the value of economy
     */
    public function getEconomy(): ?float
    {
        return $this->economy;
    }

    /**
     * Set the value of economy
     */
    public function setEconomy(?float $economy): self
    {
        $this->economy = $economy;

        return $this;
    }

    /**
     * Get the value of cotisation
     */
    public function getCotisation(): ?float
    {
        return $this->cotisation;
    }

    /**
     * Set the value of cotisation
     */
    public function setCotisation(?float $cotisation): self
    {
        $this->cotisation = $cotisation;

        return $this;
    }

    /**
     * Get the value of economyReal
     */
    public function getEconomyReal(): ?float
    {
        return $this->economyReal;
    }

    /**
     * Set the value of economyReal
     */
    public function setEconomyReal(?float $economyReal): self
    {
        $this->economyReal = $economyReal;

        return $this;
    }
}