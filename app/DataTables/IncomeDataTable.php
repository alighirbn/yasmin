<?php

namespace App\DataTables;

use App\Models\Cash\Income;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class IncomeDataTable extends DataTable
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
            ->addColumn('action', 'income.action')
            ->addColumn('income_amount', function ($row) {
                return number_format($row->income_amount, 0);
            })
            ->addColumn('approved', function ($row) {
                return $row->approved ? __('word.approved') : __('word.pending');
            })
            ->rawColumns(['action'])
            ->setRowId('id');
    }
    protected $onlyPending;

    public function onlyPending($onlyPending = null)
    {
        $this->onlyPending = $onlyPending;
        return $this;
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Cash\income $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Income $model): QueryBuilder
    {
        // Get the base query with relationships (if any)
        $query = $model->newQuery()->with(['income_type']);

        if ($this->onlyPending) {
            $query->where('approved', false);
        }
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
            ->setTableId('income-table')
            ->language([
                'sUrl' => url('/') . '/../lang/' . __(LaravelLocalization::getCurrentLocale()) . '/datatable.json'
            ])
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->orderBy(1, 'asc')
            /* ->parameters([
                'dom' => 'B<"clear">lfrtip',
                'scrollX' => false,
                'buttons' => [
                    [
                        'extend'  => 'print',
                        'className'    => 'btn btn-outline-dark'
                   ],
                   [
                        'extend'  => 'reset',
                        'className'    => 'btn btn-outline-dark'
                   ],
                   [
                        'extend'  => 'reload',
                        'className'    => 'btn btn-outline-dark'
                   ],
                    [
                         'extend'  => 'export',
                         'className'    => 'btn btn-outline-dark',
                         'buttons' => [
                                       'csv',
                                       'excel',
                                       'pdf',
                                  ],
                    ],
                    'colvis'
                ]
            ]) */
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
            Column::make('id')->title(__('word.income_id'))->class('text-center'),
            Column::make('income_date')->title(__('word.income_date'))->class('text-center'),
            Column::make('income_type')->title(__('word.income_type_id'))->data('income_type.income_type')->name('income_type.income_type')->class('text-center'),
            Column::make('income_amount')->title(__('word.income_amount'))->class('text-center'),
            Column::make('income_note')->title(__('word.income_note'))->class('text-center'),
            Column::make('approved')
                ->title(__('word.approve_status'))
                ->class('text-center')
                ->orderable(false) // Disable sorting
                ->searchable(false) // Disable searching
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename(): string
    {
        return 'Income_' . date('YmdHis');
    }
}
