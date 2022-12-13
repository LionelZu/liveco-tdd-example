<?php

class CotisationService {
    public function computeCotisation($cotisation, $totalAchats) {
        if ($totalAchats > 5000) {
            return $cotisation * 0.8;
        } 
        return $cotisation;
    }
}