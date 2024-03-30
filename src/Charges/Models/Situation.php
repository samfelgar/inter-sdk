<?php

namespace Samfelgar\Inter\Charges\Models;

enum Situation: string
{
    case Expired = 'EXPIRADO';
    case PastDue = 'VENCIDO';
    case Pending = 'EMABERTO';
    case Paid = 'PAGO';
    case Cancelled = 'CANCELADO';
}
