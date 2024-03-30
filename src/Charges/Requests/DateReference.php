<?php

namespace Samfelgar\Inter\Charges\Requests;

enum DateReference: string
{
    case DueDate = 'VENCIMENTO';
    case Emission = 'EMISSAO';
    case Situation = 'SITUACAO';
}
