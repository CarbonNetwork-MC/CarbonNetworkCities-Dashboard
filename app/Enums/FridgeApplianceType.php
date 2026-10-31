<?php

namespace App\Enums;

enum FridgeApplianceType: string
{
    case Refrigerator = 'refrigerator';
    case Freezer = 'freezer';
    case Combined = 'combined';
}
