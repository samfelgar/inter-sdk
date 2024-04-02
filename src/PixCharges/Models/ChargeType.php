<?php

namespace Samfelgar\Inter\PixCharges\Models;

enum ChargeType: string
{
    case Simple = 'SIMPLES';
    case Installments = 'PARCELADO';
    case Recurring = 'RECORRENTE';
}
