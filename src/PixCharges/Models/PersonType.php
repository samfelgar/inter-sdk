<?php

namespace Samfelgar\Inter\PixCharges\Models;

enum PersonType: string
{
    case Natural = 'FISICA';
    case Legal = 'JURIDICA';
}
