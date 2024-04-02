<?php

namespace Samfelgar\Inter\PixCharges\Requests;

enum OrderBy: string
{
    case Payer = 'PESSOA_PAGADORA';
    case ChargeType = 'TIPO_COBRANCA';
    case ChargeCode = 'CODIGO_COBRANCA';
    case Identifier = 'IDENTIFICADOR';
    case EmissionDate = 'DATA_EMISSAO';
    case DueDate = 'DATA_VENCIMENTO';
    case Amount = 'VALOR';
    case Status = 'STATUS';
}
