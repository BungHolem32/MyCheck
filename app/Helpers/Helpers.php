<?php
/**
 * Created by PhpStorm.
 * User: ilanvac
 * Date: 5/10/2019
 * Time: 5:43 PM
 */

namespace App\Helpers;


use RecursiveArrayIterator;
use RecursiveIteratorIterator;

class Helpers
{
    static function getArrayDepth($array)
    {
        $max_indentation = 1;

        $array_str = print_r($array, true);
        $lines     = explode("\n", $array_str);

        foreach ($lines as $line) {
            $indentation = (strlen($line) - strlen(ltrim($line))) / 4;

            if ($indentation > $max_indentation) {
                $max_indentation = $indentation;
            }
        }

        return ceil(($max_indentation - 1) / 2) + 1;
    }

    static function getArrayDepth2($array) {
        $depth = 0;
        $iteIte = new RecursiveIteratorIterator(new RecursiveArrayIterator($array));

        foreach ($iteIte as $ite) {
            $d = $iteIte->getDepth();
            $depth = $d > $depth ? $d : $depth;
        }

        return $depth;
    }
}
