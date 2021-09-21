<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'amount', 'customer_email', 'customer_name', 'customer_phone', 'payment_method', 'reff_number_to_duitku', 'duitku_reff_number', 'virtual_account_number', 'duitku_payment_url', 'product', 'product_description', 'status', 'payment_method_name', 'user_id'
    ];
}
