<?php

declare(strict_types=1);

namespace Samfelgar\Inter\Banking\Models;

enum PixKeyType
{
    case Document;
    case Phone;
    case Email;
    case Evp;
}
