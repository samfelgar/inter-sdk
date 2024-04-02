<?php

declare(strict_types=1);

namespace Samfelgar\Inter\PixCharges\Models;

enum MoratoriumCode: string
{
    case NoMoratorium = 'ISENTO';
    case DailyAmount = 'VALORDIA';
    case MonthlyTax = 'TAXAMENSAL';
}
