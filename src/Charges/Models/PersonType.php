<?php

namespace Samfelgar\Inter\Charges\Models;

enum PersonType: string
{
    case Natural = 'FISICA';
    case Legal = 'JURIDICA';
}
