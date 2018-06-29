<?php

namespace App\Unison;

/**
 * Funzioni di utilità generale.
 * @package App\Unison
 */
class GeneralUtils
{

    /**
     * @var string Moltiplicatori utilizzati nella funzione formatNumberWithMultipliers.
     */
    private static $multipliers = " KMB";

    /**
     * Formatta il valore numerico in ingresso in modo che venga visualizzato arrotondato alla 3°, 6° o 9° cifra e
     * con il moltiplicatore appropriato (B per i miliardi, M per i milioni, K per le migliaia, nessuno per i valori
     * inferiori a 1000).
     * @param $value integer Valore numerico da formattare.
     * @return string Valore numerico formattato come descritto.
     */
    public static function formatNumberWithMultipliers($value) {
        $currentMultipliers = GeneralUtils::$multipliers;
        while ($value >= 1000 && strlen($currentMultipliers) > 1) {
            $value = floor($value / 1000);
            $currentMultipliers = substr($currentMultipliers, 1);
        }
        return strval($value) . $currentMultipliers[0];
    }

    /**
     * Formatta il valore numerico in ingresso in modo che venga visualizzato sempre su due cifre ed eseguendo un
     * padding con zeri qualora non si arrivi a tale dimensione.
     * @param $value integer Valore numerico da formattare.
     * @return string Valore numerico formattato come descritto.
     */
    public static function formatNumberAsTwoDigits($value) {
        return sprintf("%02d", $value);
    }

}
