<?php


namespace App\DataTables;

use App\Models\Contract\Contract_Transfer_History;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class ContractTransferHistoryDataTable extends DataTable
{
    /**
     * Build DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     * @return \Yajra\DataTables\EloquentDataTable
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('action', 'contract.transfer.action')
            ->addColumn('transfer_amount', function ($row) {
                return number_format($row->transfer_amount, 0);
            })
            ->rawColumns(['action'])
            ->setRowId('id');
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Payment $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Contract_Transfer_History $model): QueryBuilder
    {
        // Start building the query with eager loading relationships
        $query = $model->newQuery()->with(['contract', 'contract.building', 'oldcustomer', 'newcustomer']);
        
        // Apply contract ID filter if present (exact match)
        if ($contractId = request('contract_id')) {
            $query->where('id', '=', $contractId);  // Use '=' for exact matching
        }
    
        // Return the query builder instance
        return $query;
    }
    

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('transfer-table')
            ->language([
                'sUrl' =>  url('/') . '/../lang/' . __(LaravelLocalization::getCurrentLocale()) . '/datatable.json'
            ])
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->orderBy(1)
            ->selectStyleSingle();
    }

    /**
     * Get the dataTable columns definition.
     *
     * @return array
     */
    public function getColumns(): array
    {
        return [
            Column::computed('action')
                ->exportable(false)
                ->printable(false)
                ->width(60)
                ->title(__('word.action'))
                ->addClass('text-center'),
            Column::make('id')->title(__('word.transfer_id'))->class('text-center'),
            Column::make('transfer_date')->title(__('word.transfer_date'))->class('text-center'),

            Column::make('contract_id')->title(__('word.contract_id'))->data('contract_id')->class('text-center'),
            Column::make('contract_date')->title(__('word.contract_date'))->data('contract.contract_date')->name('contract.contract_date')->class('text-center'),
            Column::make('building_number')->title(__('word.building_number'))->data('contract.building.building_number')->name('contract.building.building_number')->class('text-center'),

            Column::make('newcustomer')->title(__('word.newcustomer'))->data('newcustomer.customer_full_name')->name('newcustomer.customer_full_name')->class('text-center'),
            Column::make('oldcustomer')->title(__('word.oldcustomer'))->data('oldcustomer.customer_full_name')->name('oldcustomer.customer_full_name')->class('text-center'),
            Column::make('transfer_amount')->title(__('word.transfer_amount'))->class('text-center'),
            Column::make('transfer_note')->title(__('word.transfer_note'))->class('text-center'),
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename(): string
    {
        return 'transfer' . date('YmdHis');
    }
}
