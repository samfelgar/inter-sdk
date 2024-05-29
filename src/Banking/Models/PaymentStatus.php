<?php

declare(strict_types=1);

namespace Samfelgar\Inter\Banking\Models;

enum PaymentStatus: string
{
    case Processed = 'EFETIVADO';
    case Pending = 'NAO_EFETIVADO';
    case Error = 'ERRO';
}
