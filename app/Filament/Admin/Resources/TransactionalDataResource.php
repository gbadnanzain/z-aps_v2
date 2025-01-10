<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Illuminate\Support\Carbon;
use Tables\Filters\TextFilter;
use Filament\Resources\Resource;
use Tables\Filters\SearchFilter;
use App\Models\TransactionalData;
use Illuminate\Support\Facades\DB;


use Filament\Forms\Components\Tabs;
use Filament\Tables\Actions\Action;
use Filament\Tables\Filters\Filter;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Filament\Actions\ReplicateAction;
use Filament\Forms\Components\Select;
use function Laravel\Prompts\warning;
use Filament\Forms\Components\Tabs\Tab;
use Filament\Tables\Filters\TabsFilter;
//use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

use App\Filament\Resources\TransactionalDataResource\Pages;
use App\Filament\Resources\TransactionalDataResource\RelationManagers;


class TransactionalDataResource extends Resource
{
    protected static ?string $model = TransactionalData::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Tabs::make('MainTabs')
                    ->tabs([
                        Tab::make('Sales Orders (SO)')
                            ->schema([
                                Forms\Components\TextInput::make('ID')
                                    ->columnSpan(1)
                                    ->label('ID')

                                    ->disabled()
                                    ->unique(ignoreRecord: true),
                                Forms\Components\TextInput::make('SO_No')
                                    ->label('SO No.')
                                    ->required()
                                    //->unique(ignoreRecord: true)
                                    ->columnSpan(2)
                                    ->extraAttributes(['style' => 'width: 100%;']),
                                Forms\Components\DatePicker::make('SO_Date')
                                    ->label('SO Date')
                                    ->required()
                                    ->placeholder('Select a date')
                                    ->displayFormat('Y-m-d')
                                    ->columnSpan(2)
                                    ->extraAttributes(['class' => 'w-auto']),
                                Forms\Components\TextInput::make('SO_DebtorID')
                                    ->label('Debt. ID')
                                    ->required()
                                    ->columnSpan(2)
                                    ->extraAttributes(['class' => 'w-auto']),
                                Forms\Components\DatePicker::make('SO_Target_CompletionDatePerPO')
                                    ->label('Target Compl./PO')
                                    ->required()
                                    ->placeholder('Select a date')
                                    ->displayFormat('Y-m-d')
                                    ->columnSpan(2)
                                    ->extraAttributes(['class' => 'w-auto']),
                                Forms\Components\TextInput::make('SO_DebtorName')
                                    ->label('Debtor Name')
                                    ->required()
                                    ->columnSpan(2)
                                    ->extraAttributes(['class' => 'w-auto']),
                                Forms\Components\TextInput::make('SO_Agent')
                                    ->label('Agent')
                                    ->required()
                                    ->extraAttributes(['class' => 'w-auto']),
                                Forms\Components\TextInput::make('SO_CustPONo')
                                    ->label('Cust. PO No')
                                    ->columnSpan(2)
                                    ->extraAttributes(['class' => 'w-auto']),
                                Forms\Components\Select::make('SO_Status')
                                    ->label('Status')

                                    ->required()
                                    ->columnSpan(2)
                                    ->options([
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
                                    ])
                                    ->getOptionLabelUsing(fn($value) => match ($value) {
                                        'SENT' => '<span class="text-green-600 font-bold">ALL SENT</span>',
                                        'CANCELED' => '<span class="text-red-600 font-bold">CANCELED</span>',
                                        'COMPLETED' => '<span class="text-green-600 font-bold">COMPLETED</span>',
                                        'DELIVERED PARTIAL' => '<span class="text-yellow-600 font-bold">DELIVERED PARTIAL</span>',
                                        'INVOICED' => '<span class="text-blue-600 font-bold">INVOICED</span>',
                                        'ITEM INCOMPLETE' => '<span class="text-red-600 font-bold">ITEM INCOMPLETE</span>',
                                        'OUTSTANDING' => '<span class="text-yellow-600 font-bold">OUTSTANDING</span>',
                                        'PAYMENT' => '<span class="text-blue-600 font-bold">PAYMENT</span>',
                                        'TAKE ID' => '<span class="text-gray-600 font-bold">TAKE ID</span>',
                                        'W/OFF' => '<span class="text-gray-500 font-bold">W/OFF</span>',
                                        default => '<span class="text-gray-500">UNKNOWN</span>',
                                    })

                                    ->extraAttributes(['class' => 'w-auto']),
                                    TextInput::make('updated_by')
                                    ->label('Updated by')->columnSpan(2)
                                    ->disabled(),
                                TextInput::make('updated_at')
                                    ->label('Updated at')->columnSpan(1)
                                    ->disabled(),
                            ]),

                        Tab::make('Sales Order Detail')
                            ->schema([
                                TextInput::make('SO_Item_Description')

                                    ->label('Description')
                                    ->columnSpan(2),
                                TextInput::make('SO_LiftNo')

                                    ->label('Lift No')
                                    ->columnSpan(1),
                                TextInput::make('SO_Qty')

                                    ->label('Qty')->columnSpan(1),
                                TextInput::make('SO_UOM')
                                    ->label('UOM')
                                    ->formatStateUsing(fn(string $state): string => strtoupper($state))

                                    ->columnSpan(1),
                                TextInput::make('SO_OIR_SentTo_Finance')
                                    ->label('OIR Sent to Fin.')->columnSpan(2),
                                TextInput::make('SO_RQ_No')
                                    ->label('RQ No.')->columnSpan(1),

                            ]),






                        Tab::make('Purchases')
                            ->schema([
                                TextInput::make('PCH_PO_to_TELC_MS')
                                    ->label('PO to TELC MS')
                                    ->columnSpan(1),
                                DatePicker::make('PCH_ETA')
                                    ->label('ETA')
                                    ->columnSpan(1),
                                DatePicker::make('PCH_PO_ReceiveDate')
                                    ->label('PO Receive Date')
                                    ->columnSpan(1),
                                TextInput::make('PCH_Transfered_Qty')
                                    ->label('Transf. Qty')
                                    ->columnSpan(1),
                                TextInput::make('PCH_Doc')
                                    ->label('Purchase Document')
                                    ->columnSpan(1),
                                DatePicker::make('PCH_Date')
                                    ->label('Purchase Date')
                                    ->columnSpan(1),
                                DatePicker::make('PCH_Inform_Finance_on')
                                    ->label('Inform Finance on')
                                    ->columnSpan(1),
                                TextInput::make('PCH_Remark')
                                    ->label('Purchase Remark')->columnSpan(1),
                            ]),
                        Tab::make('Maintenance')
                            ->schema([
                                TextInput::make('MTC_RQ_No')
                                    ->label('MTC Req. No.'),
                                DatePicker::make('MTC_RQ_Date')
                                    ->label('MTC Req. Date'),
                                TextInput::make('MTC_Job_Done')
                                    ->label('Job Done'),
                                DatePicker::make('MTC_Target_Completion')
                                    ->label('Target Compl. Date'),
                                TextInput::make('MTC_SBK')
                                    ->label('SBK'),
                                TextInput::make('MTC_JO')
                                    ->label('Job Order'),
                                TextInput::make('MTC_DN_DO')
                                    ->label('DN / DO'),
                                TextInput::make('MTC_BA')
                                    ->label('BA'),
                                TextInput::make('MTC_Other')
                                    ->label('Other MTC Info'),
                                TextInput::make('MTC_Remarks')
                                    ->label('MTC Remarks'),
                            ]),
                        Tab::make('Accounting')
                            ->schema([
                                TextInput::make('ACTG_Unit_Price')
                                    ->label('Unit Price'),
                                TextInput::make('ACTG_Currency')
                                    ->label('Currency'),
                                TextInput::make('ACTG_Currency_Rate')
                                    ->label('Currency Rate'),
                                TextInput::make('ACTG_Local_Net_Total')
                                    ->label('Local Net Total'),
                                TextInput::make('ACTG_Invoicing')
                                    ->label('Invoicing'),
                                DatePicker::make('ACTG_Inv_Date')
                                    ->label('Invoice Date'),
                                DatePicker::make('ACTG_Payment_Receipt')
                                    ->label('Payment Receipt Date'),
                                DatePicker::make('ACTG_Payment_Rcpt_Date')
                                    ->label('Payment Receipt Date'),
                                TextInput::make('ACTG_Remarks')
                                    ->label('Accounting Remarks'), // Tambahkan field yang relevan untuk Accounting
                            ]),
                    ])

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table

            ->columns([

                Tables\Columns\TextColumn::make('ID')
                    ->label('ID')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('SO_No')
                    ->label('SO No')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('SO_Date')
                    ->label('SO Date')
                    ->sortable(),
                Tables\Columns\TextColumn::make('SO_DebtorID')
                    ->sortable()
                    ->label('SO Debtor ID'),
                Tables\Columns\TextColumn::make('SO_Target_CompletionDatePerPO')
                    ->label('Target Comp. Date/PO')
                    ->sortable()
                    ->formatStateUsing(fn($state) => \Carbon\Carbon::parse($state)->format('Y-M-d')),
                Tables\Columns\TextColumn::make('SO_DebtorName')
                    ->sortable()
                    ->searchable()
                    ->label('Debtor Name')
                    ->formatStateUsing(fn(string $state): string => strtoupper($state)),
                Tables\Columns\TextColumn::make('SO_Agent')
                    ->label('SO Agent')
                    ->searchable()
                    ->formatStateUsing(fn(string $state): string => strtoupper($state)),
                Tables\Columns\TextColumn::make('SO_CustPONo')
                    ->label('PO No.')
                    ->formatStateUsing(fn(string $state): string => strtoupper($state)),
                /* Tables\Columns\TextColumn::make('SO_Item_Description')
                    ->label('Description'),
                Tables\Columns\TextColumn::make('SO_LiftNo')
                    ->label('Lift No.'),
                Tables\Columns\TextColumn::make('SO_Qty')
                    ->label('Quantity'),
                Tables\Columns\TextColumn::make('SO_UOM')
                    ->label('UOM'),*/
                Tables\Columns\TextColumn::make('SO_OIR_SentTo_Finance')
                    ->label('OIR Sent Finance'),
                Tables\Columns\TextColumn::make('SO_RQ_No')
                    ->label('Request No.')
                    ->formatStateUsing(fn(string $state): string => strtoupper($state)),
                Tables\Columns\TextColumn::make('SO_Remark')
                    ->label('Remark'),

                Tables\Columns\TextColumn::make('SO_Status')
                    ->label('Status')
                    //->sortable()
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
                        'SENT' => 'success', // Hijau
                        'CANCELED' => 'danger', // Merah
                        'COMPLETED' => 'success', // Hijau
                        'DELIVERED PARTIAL' => 'warning', // Kuning
                        'INVOICED' => 'primary', // Biru
                        'ITEM INCOMPLETE' => 'danger', // Merah
                        'OUTSTANDING' => 'warning', // Kuning
                        'PAYMENT' => 'primary', // Biru
                        'TAKE ID' => 'secondary', // Abu-abu
                        'W/OFF' => 'gray', // Abu-abu gelap
                        default => 'gray',
                    }),

                Tables\Columns\TextColumn::make('updated_by')
                    ->label('Updated by')
                    ->default(fn() => Auth::user()->name),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Updated at')
                    ->formatStateUsing(fn($state) => \Carbon\Carbon::parse($state)->format('Y-m-d H:i:s'))
                    ->disabled()
                    ->sortable()
                    ->extraAttributes([
                        'style' => 'max-width: 200px;',
                    ])
                    ->columnSpan(1),
            ])

            ->filters([
                // Filter berdasarkan rentang tanggal (current month & year)
                // Filter berdasarkan rentang tanggal (current month & year)
                Filter::make('date_range')
                    ->form([
                        Forms\Components\DatePicker::make('from')
                            ->label('From')
                            ->default(Carbon::now()->startOfMonth()),  // Default: awal bulan ini

                        Forms\Components\DatePicker::make('to')
                            ->label('To')
                            ->default(Carbon::now()->endOfMonth()),  // Default: akhir bulan ini
                    ])
                    ->query(function ($query, $data) {
                        if (isset($data['from']) && isset($data['to'])) {
                            $query->whereBetween('SO_Date', [
                                Carbon::parse($data['from'])->startOfDay(),
                                Carbon::parse($data['to'])->endOfDay(),
                            ]);
                        }
                    }),

                // Filter berdasarkan nomor SO
                Filter::make('so_no')
                    ->form([
                        Forms\Components\TextInput::make('so_no')
                            ->label('SO No')
                            ->placeholder('Enter SO No')
                    ])
                    ->query(function ($query, $data) {
                        if (isset($data['so_no'])) {
                            $query->where('SO_No', 'like', '%' . $data['so_no'] . '%');
                        }
                    }),

                // Filter berdasarkan status SO
                /* SelectFilter::make('so_status')
                    ->label('SO Status')
                    ->options([
                        'SENT' => 'Sent',
                        'CANCELED' => 'Canceled',
                        'COMPLETED' => 'Completed',
                        'DELIVERED PARTIAL' => 'Delivered Partial',
                        'INVOICED' => 'Invoiced',
                        'ITEM INCOMPLETE' => 'Item Incomplete',
                        'OUTSTANDING' => 'Outstanding',
                        'PAYMENT' => 'Payment',
                        'TAKE ID' => 'Take ID',
                        'W/OFF' => 'Write Off',
                    ])
                    ->query(function ($query, $value) {
                        if ($value) {
                            $query->where('SO_Status', $value);
                        }
                    }), */

            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Action::make('replicate')
                    ->label('Replicate')
                    ->color('warning')
                    ->icon('heroicon-o-document-duplicate')
                    ->action(function ($record) {
                        $newRecord = $record->replicate();
                        $newRecord->forceFill([
                            /* 'SO_No' => $record->SO_No,
                            'SO_Date' => $record->SO_Date,
                            'SO_DebtorID' => $record->SO_DebtorID,
                            'SO_Target_CompletionDatePerPO' => $record->SO_Target_CompletionDatePerPO,
                            'SO_DebtorName' => $record->SO_DebtorName,
                            'SO_Agent' => $record->SO_Agent,
                            'SO_CustPONo' => $record->SO_CustPONo,
                            'SO_OIR_SentTo_Finance' => $record->SO_OIR_SentTo_Finance,
                            'SO_RQ_No' => $record->SO_RQ_No, */
                            //'SO_Status' => 'Replicate', // Default value
                        ]);
                        Log::info('New Record Before Save', $newRecord->toArray()); // Log data baru
                        $newRecord->SO_Status = '#Replicated#';

                        $newRecord->updated_by = Auth::user()->name;
                        $newRecord->updated_at = now();

                        //$newRecord->SO_Status = 'Pending'; // Contoh modifikasi
                        $newRecord->save();
                        //return redirect(TransactionalDataResource::getUrl('edit', ['record' => $newRecord->id]));
                        Log::info('New Record After Save', $newRecord->toArray()); // Log data setelah simpan

                        // $this->notify('success', 'Record successfully replicated.');
                    })
                    ->requiresConfirmation()
                    ->modalHeading('Replicate Record')
                    ->modalSubheading('Are you sure you want to replicate this record?'),
            ]);
    }
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTransactionalData::route('/'),
            'create' => Pages\CreateTransactionalData::route('/create'),
            'edit' => Pages\EditTransactionalData::route('/{record}/edit'),
        ];
    }
}
