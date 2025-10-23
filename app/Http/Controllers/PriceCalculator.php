<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PriceCalculator extends Controller
{
    public function finalTotal(float $subtotal, float $taxRate, float $discount, bool $isPercent = true): float
    {
        if ($subtotal < 0 || $discount < 0 || $taxRate < 0) {
            throw new \InvalidArgumentException('Negative values are not allowed.');
        }
        $disc = $isPercent ? ($subtotal * ($discount / 100)) : $discount;
        if ($disc > $subtotal) {
            $disc = $subtotal; // Prevent negative subtotal
        }
        $afterDisc = $subtotal - $disc;
        $tax = $afterDisc * ($taxRate / 100);
        return round($afterDisc + $tax, 2);
    }
}
