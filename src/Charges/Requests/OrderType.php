<?php

namespace Samfelgar\Inter\Charges\Requests;

enum OrderType: string
{
    case Asc = 'ASC';
    case Desc = 'DESC';
}
