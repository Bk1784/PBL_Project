<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MBankingEWallet extends Model
{
    protected $table = 'mbanking_and_ewallet';

    protected $fillable = [
        'refund_id',
        'nama_penerima',
        'bank_ewallet',
        'nomor_rekening',
    ];

    public function refund()
    {
        return $this->belongsTo(Refund::class);
    }
}
