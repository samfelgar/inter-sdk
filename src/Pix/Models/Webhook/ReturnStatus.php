<?php

namespace Samfelgar\Inter\Pix\Models\Webhook;

enum ReturnStatus: string
{
    case Processing = 'EM_PROCESSAMENTO';
    case Returned = 'DEVOLVIDO';
    case Cancelled = 'NAO_REALIZADO';
}
