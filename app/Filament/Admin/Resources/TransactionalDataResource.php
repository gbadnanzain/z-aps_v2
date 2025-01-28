<?php

namespace App\Filament\Admin\Resources;


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
use Filament\Support\Enums\FontWeight;
use Filament\Forms\Components\Tabs\Tab;
//use Filament\Forms\Components\Actions\Action;
use Filament\Tables\Columns\DateColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TabsFilter;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Columns\TextInputColumn;
use Filament\Tables\Columns\EditableTextColumn;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Admin\Resources\TransactionalDataResource\Pages;
use App\Filament\Resources\TransactionalDataResource\RelationManagers;
use Filament\Tables\Columns\DateTimeColumn;

class TransactionalDataResource extends Resource
{
    protected static ?string $model = TransactionalData::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Grid::make(300) // Mengatur jumlah kolom dalam grid
                    ->schema([
                        Forms\Components\TextInput::make('ID')
                            ->label('ID')
                            ->disabled()
                            ->unique(ignoreRecord: true)
                            ->columnSpan(6),
                        Forms\Components\TextInput::make('SO_No')
                            ->label('SO No.')
                            ->required()
                            ->columnSpan(6)
                            ->reactive() // Menjadikan input ini reaktif
                            ->afterStateUpdated(function (callable $set, $state) {
                                // Set nilai SO_ID berdasarkan perubahan di SO_No
                                $set('SO_ID', substr($state, 0, 4) . '/' . substr($state, -4));
                            }),
                        Forms\Components\TextInput::make('SO_ID')
                            ->label('SO ID')
                            ->required()
                            ->columnSpan(6),
                        //->extraAttributes(['style' => 'width: 100%;']),
                        Forms\Components\DatePicker::make('SO_Date')
                            ->label('SO Date')
                            ->required()
                            ->placeholder('Select a date')
                            ->displayFormat('Y-m-d')
                            ->columnSpan(4),
                        Forms\Components\TextInput::make('SO_DebtorID')
                            ->label('Debt. ID')
                            ->required()
                            ->columnSpan(4),
                        Forms\Components\DatePicker::make('SO_Target_CompletionDatePerPO')
                            ->label('Target Compl./PO')
                            ->required()
                            ->placeholder('Select a date')
                            ->displayFormat('Y-m-d')
                            ->columnSpan(8),
                        Forms\Components\TextInput::make('SO_DebtorName')
                            ->label('Debtor Name')
                            ->required()
                            ->columnSpan(6),
                        Forms\Components\TextInput::make('SO_Agent')
                            ->label('Agent')
                            ->required()
                            ->columnSpan(5),
                        Forms\Components\TextInput::make('SO_CustPONo')
                            ->label('Cust. PO No')
                            ->columnSpan(10),
                        TextInput::make('SO_Item_Description')
                            ->label('Description')
                            ->columnSpan(15),
                        TextInput::make('SO_LiftNo')
                            ->label('Lift No')
                            ->columnSpan(5),
                        TextInput::make('SO_Qty')
                            ->label('Qty')
                            ->columnSpan(5),
                        TextInput::make('SO_UOM')
                            ->label('UOM')
                            ->columnSpan(5),
                        TextInput::make('SO_OIR_SentTo_Finance')
                            ->label('OIR Sent to Fin.')
                            ->columnSpan(6),
                        TextInput::make('SO_RQ_No')
                            ->label('RQ No.')
                            ->columnSpan(4),
                        TextInput::make('PCH_PO_to_TELC_MS')
                            ->label('PO to TELC MS')
                            ->columnSpan(6),
                        DatePicker::make('PCH_ETA')
                            ->label('ETA')
                            ->columnSpan(7),
                        DatePicker::make('PCH_PO_ReceiveDate')
                            ->label('PO Receive Date')
                            ->columnSpan(7),
                        TextInput::make('PCH_Transfered_Qty')
                            ->label('Transf. Qty')
                            ->columnSpan(8),
                        TextInput::make('PCH_Doc')
                            ->label('Purchase Doc.')
                            ->columnSpan(6),
                        DatePicker::make('PCH_Date')
                            ->label('Purchase Date')
                            ->columnSpan(7),
                        DatePicker::make('PCH_Inform_Finance_on')
                            ->label('Inform Fin. on')
                            ->columnSpan(7),
                        TextInput::make('PCH_Remark')
                            ->label('Purchase Remark')
                            ->columnSpan(8),
                        TextInput::make('MTC_RQ_No')
                            ->label('MTC Req. No.')
                            ->columnSpan(6),
                        DatePicker::make('MTC_RQ_Date')
                            ->label('MTC Req. Date')
                            ->columnSpan(7),
                        TextInput::make('MTC_Job_Done')
                            ->label('Job Done')
                            ->columnSpan(8),
                        DatePicker::make('MTC_Target_Completion')
                            ->label('Target Compl. Date')
                            ->columnSpan(8),
                        TextInput::make('MTC_SBK')
                            ->label('SBK')
                            ->columnSpan(5),
                        TextInput::make('MTC_JO')
                            ->label('Job Order')
                            ->columnSpan(5),
                        TextInput::make('MTC_DN_DO')
                            ->label('DN / DO')
                            ->columnSpan(5),
                        TextInput::make('MTC_BA')
                            ->label('BA')
                            ->columnSpan(5),
                        TextInput::make('MTC_Other')
                            ->label('Other MTC Info')
                            ->columnSpan(8),
                        TextInput::make('MTC_Remarks')
                            ->label('MTC Remarks')
                            ->columnSpan(8),
                        TextInput::make('ACTG_Unit_Price')
                            ->label('Unit Price')
                            ->columnSpan(5),
                        TextInput::make('ACTG_Currency')
                            ->label('Currency')
                            ->columnSpan(5),
                        TextInput::make('ACTG_Currency_Rate')
                            ->label('Currency Rate')
                            ->columnSpan(7),
                        TextInput::make('ACTG_Local_Net_Total')
                            ->label('Local Net Total')
                            ->columnSpan(8),
                        TextInput::make('ACTG_Invoicing')
                            ->label('Invoicing')
                            ->columnSpan(8),
                        DatePicker::make('ACTG_Inv_Date')
                            ->label('Invoice Date')
                            ->columnSpan(5),
                        DatePicker::make('ACTG_Payment_Receipt')
                            ->label('Payment Recv.')
                            ->columnSpan(8),
                        DatePicker::make('ACTG_Payment_Rcpt_Date')
                            ->label('Payment Rec. Date')
                            ->columnSpan(8),
                        TextInput::make('ACTG_Remarks')
                            ->label('Accounting Remarks')
                            ->columnSpan(9),
                        Forms\Components\Select::make('SO_Status')
                            ->label('Status')
                            ->required()
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
                            ->columnSpan(10),
                        TextInput::make('updated_by')
                            ->label('Updated by')
                            ->disabled()
                            ->columnSpan(6),
                        TextInput::make('updated_at')
                            ->label('Updated at')
                            ->disabled()->columnSpan(6),
                    ]),
            ]);
    }

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([

                TextInputColumn::make('ID')
                    ->sortable()
                    ->searchable()

                    ->disabled()
                    ->label('ID'),
                TextInputColumn::make('SO_ID')
                    ->label('SO ID')
                    ->placeholder('Generated from SO_No')
                    ->default(fn($record) => substr($record->SO_No, 0, 4) . '/' . substr($record->SO_No, -4))
                    ->searchable(),
                TextInputColumn::make('SO_No')
                    ->sortable()
                    ->searchable()
                    ->label('Sales Order No')
                    ->placeholder('Enter SO No')
                    ->default(''),

                TextInputColumn::make('SO_Date')


                    ->sortable()
                    ->searchable()

                    ->label('Sales Order Date')
                    ->placeholder('Enter SO Date')
                    ->getStateUsing(fn($record) => \Carbon\Carbon::parse($record->SO_Date)->format('Y-m-d')),


                /* DateTimeColumn::make('SO_Date')
                    ->label('SO Date')
                    ->format('d/m/Y') // Format tanggal seperti "DD/MM/YYYY"
                    ->sortable()
                    ->searchable(),
 */
                TextInputColumn::make('SO_DebtorID')
                    ->sortable()
                    ->searchable()
                    ->label('Debtor ID')
                    ->placeholder('Enter Debtor ID')
                    ->default(''),

                TextInputColumn::make('SO_DebtorName')
                    //->weight(FontWeight::Bold)
                    ->sortable()
                    ->searchable()
                    ->label('Debtor Name')
                    ->placeholder('Enter Debtor Name')
                    ->default(''),

                TextInputColumn::make('SO_Agent')
                    //->weight(FontWeight::Bold)
                    ->sortable()
                    ->searchable()
                    ->label('Agent ')
                    ->placeholder('Enter Agent')
                    ->default(''),

                TextInputColumn::make('SO_CustPONo')
                    ->sortable()
                    ->searchable()
                    ->label('Customer PO No')
                    ->placeholder('Enter Customer PO No')
                    ->default(''),

                TextInputColumn::make('SO_Item_Description')
                    ->sortable()
                    ->searchable()
                    ->label('Item Description')
                    ->placeholder('Enter Item Description')
                    ->default(''),

                TextInputColumn::make('SO_LiftNo')
                    ->sortable()
                    ->searchable()
                    ->label('Lift No')
                    ->placeholder('Enter Lift No')
                    ->default(''),

                TextInputColumn::make('SO_Qty')
                    ->sortable()
                    ->searchable()
                    ->label('Quantity')
                    ->placeholder('Enter Quantity')
                    ->default(''),

                TextInputColumn::make('SO_UOM')
                    ->sortable()
                    ->searchable()
                    ->label('UOM')
                    ->placeholder('Enter UOM')
                    ->default(''),

                TextInputColumn::make('SO_OIR_SentTo_Finance')
                    ->sortable()
                    ->searchable()
                    ->label('OIR Sent to Finance')
                    ->placeholder('Enter OIR Sent to Finance')
                    ->default(''),

                TextInputColumn::make('SO_RQ_No')
                    ->label('Request No.'),
                TextInputColumn::make('SO_Status')
                    ->label('SO Status')
                    ->sortable()
                    ->searchable()
                /* SelectColumn::make('SO_Status')
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
                    ->searchable()
                    ->sortable() */,

                TextInputColumn::make('PCH_PO_to_TELC_MS')
                    ->label('PO to TELC MS'),

                TextInputColumn::make('PCH_ETA')
                    ->label('ETA'),
                TextInputColumn::make('PCH_PO_ReceiveDate')
                    ->getStateUsing(fn($record) => \Carbon\Carbon::parse($record->PCH_ETA)->format('Y-m-d'))
                    ->label('PO Receive Date')

                    ->columnSpan(1),
                TextInputColumn::make('PCH_Transfered_Qty')
                    ->label('Transf. Qty')
                    ->columnSpan(1),
                TextInputColumn::make('PCH_Doc')
                    ->label('Purchase Document')
                    ->columnSpan(1),
                TextInputColumn::make('PCH_Date')
                    ->getStateUsing(fn($record) => \Carbon\Carbon::parse($record->PCH_Date)->format('Y-m-d'))
                    ->label('Purchase Date')

                    ->columnSpan(1),
                TextInputColumn::make('PCH_Inform_Finance_on')
                    ->label('Inform Finance on')
                    ->columnSpan(1),
                TextInputColumn::make('PCH_Remark')
                    ->label('Purchase Remark')->columnSpan(1),

                TextInputColumn::make('MTC_RQ_No')
                    ->label('MTC Req. No.'),
                TextInputColumn::make('MTC_RQ_Date')
                    ->getStateUsing(fn($record) => \Carbon\Carbon::parse($record->MTC_RQ_Date)->format('Y-m-d'))
                    ->label('MTC Req. Date'),
                TextInputColumn::make('MTC_Job_Done')
                    ->label('Job Done'),
                TextInputColumn::make('MTC_Target_Completion')
                    ->getStateUsing(fn($record) => \Carbon\Carbon::parse($record->MTC_Target_Completion)->format('Y-m-d'))
                    ->label('Target Compl. Date'),
                TextInputColumn::make('MTC_SBK')
                    ->label('SBK'),
                TextInputColumn::make('MTC_JO')
                    ->label('Job Order'),
                TextInputColumn::make('MTC_DN_DO')
                    ->label('DN / DO'),
                TextInputColumn::make('MTC_BA')
                    ->label('BA'),
                TextInputColumn::make('MTC_Other')
                    ->label('Other MTC Info'),
                TextInputColumn::make('MTC_Remarks')
                    ->label('MTC Remarks'),
                TextInputColumn::make('ACTG_Unit_Price')
                    ->label('Unit Price'),
                TextInputColumn::make('ACTG_Currency')
                    ->label('Currency'),
                TextInputColumn::make('ACTG_Currency_Rate')
                    ->label('Currency Rate'),
                TextInputColumn::make('ACTG_Local_Net_Total')
                    ->label('Local Net Total'),
                TextInputColumn::make('ACTG_Invoicing')
                    ->label('Invoicing'),
                TextInputColumn::make('ACTG_Inv_Date')
                    ->getStateUsing(fn($record) => \Carbon\Carbon::parse($record->ACTG_Inv_Date)->format('Y-m-d'))
                    ->label('Invoice Date'),
                TextInputColumn::make('ACTG_Payment_Receipt')

                    ->label('Payment Receipt Date'),
                TextInputColumn::make('ACTG_Payment_Rcpt_Date')
                    ->getStateUsing(fn($record) => \Carbon\Carbon::parse($record->ACTG_Payment_Rcpt_Date)->format('Y-m-d'))
                    ->label('Payment Receipt Date'),
                TextInputColumn::make('ACTG_Remarks')
                    ->label('Accounting Remarks'),




                // Kolom lain ditambahkan di sini...
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
                Filter::make('SO_Status')
                    ->form([
                        Forms\Components\TextInput::make('SO_Status')
                            ->label('SO Status')
                            ->placeholder('Enter Status')
                    ])
                    ->query(function ($query, $data) {
                        if (isset($data['SO_Status'])) {
                            $query->where('SO_Status', 'like', '%' . $data['SO_Status'] . '%');
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
                /* Action::make('edit')
                    ->label('Edit Grid')
                    ->icon('heroicon-o-document') // Menggunakan ikon grid
                    ->tooltip('Edit this record')
                    ->color('success')
                    ->action(fn($record) => $this->editRecord($record))
                    ->modalHeading('Edit Record')
                    ->modalWidth('lg')
                    ->form([
                        TextInput::make('SO_Item_Description')
                            ->label('Description')
                            ->required(),

                        TextInput::make('SO_LiftNo')
                            ->label('Lift No')
                            ->required(),

                        TextInput::make('SO_Qty')
                            ->label('Qty')
                            ->numeric()
                            ->required(),

                        TextInput::make('SO_UOM')
                            ->label('UOM')
                            ->required(),

                        TextInput::make('SO_OIR_SentTo_Finance')
                            ->label('OIR Sent to Fin.')
                            ->required(),

                        TextInput::make('SO_RQ_No')
                            ->label('RQ No.')
                            ->required(),
                    ])
                    ->requiresConfirmation(), */


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

    public static function getRelations(): array
    {
        return [
            //
        ];
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
