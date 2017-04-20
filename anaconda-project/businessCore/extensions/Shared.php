<?php
/**
 * User: Salah Eddine MIMOUNI
 * Date: 18/09/14
 * Time: 17:08
 */

class Shared {
    /**
     * Retourne la date d'anniversaire après une nombre de jour
     * @return date
     */
    public static function getDateBirthAfter($birthday, $days = 20){
        return date('Y-m-d', strtotime($birthday . ' + '.$days.' day'));
    }
} 