<?php

namespace Samfelgar\Inter\Charges\Requests;

enum OrderBy: string
{
    case Payer = 'PAGADOR';
    case OurNumber = 'NOSSONUMERO';
    case YourNumber = 'SEUNUMERO';
    case SituationDate = 'DATASITUACAO';
    case DueDate = 'DATAVENCIMENTO';
    case Amount = 'VALOR';
    case Status = 'STATUS';
}
