<?php

declare(strict_types=1);

namespace Samfelgar\Inter\Charges\Models;

use Webmozart\Assert\Assert;

readonly class Phone
{
    public string $areaCode;
    public string $number;

    public function __construct(string $areaCode, string $number)
    {
        $areaCode = preg_replace('/\D/', '', $areaCode);
        $number = preg_replace('/\D/', '', $number);
        Assert::maxLength($areaCode, 2);
        Assert::maxLength($number, 9);
        $this->areaCode = $areaCode;
        $this->number = $number;
    }
}
