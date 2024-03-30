<?php

declare(strict_types=1);

namespace Samfelgar\Inter\Charges\Models;

enum MoratoriumCode: string
{
    case NoMoratorium = 'ISENTO';
    case DailyAmount = 'VALORDIA';
    case MonthlyTax = 'TAXAMENSAL';
}
