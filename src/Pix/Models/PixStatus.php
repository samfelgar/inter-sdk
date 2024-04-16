<?php

namespace Samfelgar\Inter\Pix\Models;

enum PixStatus: string
{
    case Active = 'ATIVA';
    case Finished = 'CONCLUIDA';
    case RemovedByReceiver = 'REMOVIDA_PELO_USUARIO_RECEBEDOR';
    case RemovedByBank = 'REMOVIDA_PELO_PSP';
}
