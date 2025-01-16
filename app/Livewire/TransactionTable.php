<?php

namespace App\Livewire;

use Livewire\Component;

class TransactionTable extends Component
{
    public function render()
    {
        return view('livewire.transaction-table');



    }

    public function setUp(): void
    {
        $this->addColumn('ID')
            ->label('ID')
            ->sortable()
            ->searchable();

        $this->addColumn('SO_No')
            ->label('SO No')
            ->sortable()
            ->searchable();

        $this->addColumn('SO_Date')
            ->label('SO Date')
            ->sortable()
            ->formatStateUsing(fn($state) => Carbon::parse($state)->format('Y-M-d'));

        $this->addColumn('SO_DebtorID')
            ->label('SO Debtor ID')
            ->sortable();

        $this->addColumn('SO_Target_CompletionDatePerPO')
            ->label('Target Comp. Date/PO')
            ->sortable()
            ->formatStateUsing(fn($state) => Carbon::parse($state)->format('Y-M-d'));

        $this->addColumn('SO_DebtorName')
            ->label('Debtor Name')
            ->sortable()
            ->searchable()
            ->formatStateUsing(fn($state) => strtoupper($state));

        $this->addColumn('SO_Agent')
            ->label('SO Agent')
            ->searchable()
            ->formatStateUsing(fn($state) => strtoupper($state));

        $this->addColumn('SO_CustPONo')
            ->label('PO No.')
            ->formatStateUsing(fn($state) => strtoupper($state));

        $this->addColumn('SO_Item_Description')
            ->label('Description');

        $this->addColumn('SO_LiftNo')
            ->label('Lift No.');

        $this->addColumn('SO_Qty')
            ->label('Quantity');

        $this->addColumn('SO_UOM')
            ->label('UOM');

        $this->addColumn('SO_OIR_SentTo_Finance')
            ->label('OIR Sent Finance');

        $this->addColumn('SO_RQ_No')
            ->label('Request No.')
            ->formatStateUsing(fn($state) => strtoupper($state));

        $this->addColumn('SO_Remark')
            ->label('Remark');

        // Additional columns for Purchase Information (PCH)
        $this->addColumn('PCH_PO_to_TELC_MS')
            ->label('PO to TELC MS')
            ->columnSpan(1);

        $this->addColumn('PCH_ETA')
            ->label('ETA')
            ->columnSpan(1);

        $this->addColumn('PCH_PO_ReceiveDate')
            ->label('PO Receive Date')
            ->columnSpan(1);

        $this->addColumn('PCH_Transfered_Qty')
            ->label('Transf. Qty')
            ->columnSpan(1);

        $this->addColumn('PCH_Doc')
            ->label('Purchase Document')
            ->columnSpan(1);

        $this->addColumn('PCH_Date')
            ->label('Purchase Date')
            ->columnSpan(1);

        $this->addColumn('PCH_Inform_Finance_on')
            ->label('Inform Finance on')
            ->columnSpan(1);

        $this->addColumn('PCH_Remark')
            ->label('Purchase Remark')
            ->columnSpan(1);

        // Additional columns for MTC
        $this->addColumn('MTC_RQ_No')
            ->label('MTC Req. No.');

        $this->addColumn('MTC_RQ_Date')
            ->label('MTC Req. Date');

        $this->addColumn('MTC_Job_Done')
            ->label('Job Done');

        $this->addColumn('MTC_Target_Completion')
            ->label('Target Compl. Date');

        $this->addColumn('MTC_SBK')
            ->label('SBK');

        $this->addColumn('MTC_JO')
            ->label('Job Order');

        $this->addColumn('MTC_DN_DO')
            ->label('DN / DO');

        $this->addColumn('MTC_BA')
            ->label('BA');

        $this->addColumn('MTC_Other')
            ->label('Other MTC Info');

        $this->addColumn('MTC_Remarks')
            ->label('MTC Remarks');

        // Additional columns for ACTG (Accounting)
        $this->addColumn('ACTG_Unit_Price')
            ->label('Unit Price');

        $this->addColumn('ACTG_Currency')
            ->label('Currency');

        $this->addColumn('ACTG_Currency_Rate')
            ->label('Currency Rate');

        $this->addColumn('ACTG_Local_Net_Total')
            ->label('Local Net Total');

        $this->addColumn('ACTG_Invoicing')
            ->label('Invoicing');

        $this->addColumn('ACTG_Inv_Date')
            ->label('Invoice Date');

        $this->addColumn('ACTG_Payment_Receipt')
            ->label('Payment Receipt Date');

        $this->addColumn('ACTG_Payment_Rcpt_Date')
            ->label('Payment Receipt Date');

        $this->addColumn('ACTG_Remarks')
            ->label('Accounting Remarks');

        // SO Status with formatting
        $this->addColumn('SO_Status')
            ->label('Status')
            ->formatStateUsing(fn($state) => match ($state) {
                'SENT' => 'ALL SENT',
                'CANCELED' => 'CANCELED',
                'COMPLETED' => 'COMPLETED',
                'DELIVERED PARTIAL' => 'DELIVERED PARTIAL',
                'INVOICED' => 'INVOICED',
                'ITEM INCOMPLETE' => 'ITEM INCOMPLETE',
                'OUTSTANDING' => 'OUTSTANDING',
                'PAYMENT' => 'PAYMENT',
                'TAKE ID' => 'TAKE ID',
                'W/OFF' => 'W/OFF',
                default => $state,
            })
            ->color(fn($state) => match ($state) {
                'SENT' => 'success',
                'CANCELED' => 'danger',
                'COMPLETED' => 'success',
                'DELIVERED PARTIAL' => 'warning',
                'INVOICED' => 'primary',
                'ITEM INCOMPLETE' => 'danger',
                'OUTSTANDING' => 'warning',
                'PAYMENT' => 'primary',
                'TAKE ID' => 'secondary',
                'W/OFF' => 'gray',
                default => 'gray',
            });

        // Audit columns
        $this->addColumn('updated_by')
            ->label('Updated by')
            ->default(fn() => auth()->user()->name);

        $this->addColumn('updated_at')
            ->label('Updated at')
            ->formatStateUsing(fn($state) => Carbon::parse($state)->format('Y-m-d H:i:s'))
            ->disabled()
            ->sortable()
            ->extraAttributes([
                'style' => 'max-width: 200px;',
            ])
            ->columnSpan(1);
    }
    public function builder()
    {
        return Transaction::query();  // Sesuaikan dengan query yang Anda perlukan
    }
}
