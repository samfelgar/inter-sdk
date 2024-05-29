<?php

declare(strict_types=1);

namespace Samfelgar\Inter\Banking\Models;

enum PaymentRequestStatus: string
{
    case Approving = 'APROVACAO';
    case Processed = 'PROCESSADO';
    case Scheduled = 'AGENDADO';
}
