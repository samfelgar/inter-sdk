<?php

namespace Samfelgar\Inter\PixCharges\Models;

enum Situation: string
{
    case Paid = 'RECEBIDO';
    case Pending = 'A_RECEBER';
    case MarkedAsReceived = 'MARCADO_RECEBIDO';
    case PastDue = 'ATRASADO';
    case Cancelled = 'CANCELADO';
    case Expired = 'EXPIRADO';
}
