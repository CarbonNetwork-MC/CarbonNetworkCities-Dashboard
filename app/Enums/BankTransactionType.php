<?php

namespace App\Enums;

enum BankTransactionType: string
{
    case OpeningBalance = 'opening_balance';
    case Deposit = 'deposit';
    case Withdrawal = 'withdrawal';
    case TransferIn = 'transfer_in';
    case TransferOut = 'transfer_out';
    case Payment = 'payment';
    case Refund = 'refund';
}
