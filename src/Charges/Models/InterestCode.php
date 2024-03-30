<?php

declare(strict_types=1);

namespace Samfelgar\Inter\Charges\Models;

enum InterestCode: string
{
    case NoInterest = 'NAOTEMMULTA';
    case FixedAmount = 'VALORFIXO';
    case PercentualAmount = 'PERCENTUAL';
}
