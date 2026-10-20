<?php

namespace App\Enums;

enum BankAccountType: string
{
    case Checking = 'checking';
    case Savings = 'savings';
}
