<?php

declare(strict_types=1);

namespace Samfelgar\Inter\Charges\Models;

enum DiscountCode: string
{
    case NoDiscount = 'NAOTEMDESCONTO';
    case FixedValue = 'VALORFIXODATAINFORMADA';
    case PercentageValue = 'PERCENTUALDATAINFORMADA';
}
