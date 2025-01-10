<?php

namespace App\Models;

use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Traits\HasPermissions;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class TransactionalData extends Model
{
    use HasFactory, HasRoles,HasPermissions;

    // Specify the table name (if different from plural of model name)
    protected $table = 'transactionaldata';

    // Set the primary key
    protected $primaryKey = 'ID';

    // Disable timestamps if the table does not have `created_at` and `updated_at`
    public $timestamps = false;

    // Specify the fields that are mass assignable
    protected $fillable = [
        'SO_No',
        'SO_Date',
        'SO_DebtorID',
        'SO_Target_CompletionDatePerPO',
        'SO_DebtorName',
        'SO_Agent',
        'SO_CustPONo',
        'SO_Item_Description',
        'SO_LiftNo',
        'SO_Qty',
        'SO_UOM',
        'SO_OIR_SentTo_Finance',
        'SO_RQ_No',
        'SO_Remark',
        'PCH_PO_to_TELC_MS',
        'PCH_ETA',
        'PCH_PO_ReceiveDate',
        'PCH_Transfered_Qty',
        'PCH_Doc',
        'PCH_Date',
        'PCH_Inform Finance on',
        'PCH_Remark',
        'MTC_RQ_No',
        'MTC_RQ_Date',
        'MTC_Job_Done',
        'MTC_Target_Completion',
        'MTC_SBK',
        'MTC_JO',
        'MTC_DN_DO',
        'MTC_BA',
        'MTC_Other',
        'MTC_Remarks',
        'ACTG_Unit_Price',
        'ACTG_Currency',
        'ACTG_Currency_Rate',
        'ACTG_Local_Net_Total',
        'ACTG_Invoicing',
        'ACTG_Inv_Date',
        'ACTG_Remarks',
        'ACTG_Payment_Receipt',
        'ACTG_Payment_Rcpt_Date',
        'SO_Status',
        'updated_by',
        'updated_at',
    ];
    public function scopeCurrentMonth($query)
    {
        return $query->whereMonth('SO_Date', now()->month)
                     ->whereYear('SO_Date', now()->year);
    }
    protected $casts = [
        'SO_Date' => 'date',
        'SO_Target_CompletionDatePerPO' => 'date',
        'PCH_ETA' => 'date',
        'PCH_PO_ReceiveDate' => 'date',
        'PCH_Date' => 'date',
        'MTC_RQ_Date' => 'date',
        'MTC_Target_Completion' => 'date',
        'ACTG_Inv_Date' => 'date',
        'ACTG_Payment_Rcpt_Date' => 'date',
    ];

}
