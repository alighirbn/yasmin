<?php

namespace App\DataTables;

use App\Models\Building\Building;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class BuildingDataTable extends DataTable
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
            ->addColumn('action', 'building.building.action')
            ->rawColumns(['action'])
            ->setRowId('id');
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Building $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Building $model): QueryBuilder
    {
        return $model->newQuery()->with(['building_type', 'building_category', 'classification']);
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('building-table')
            ->language([
                'sUrl' => url('/') . '/../lang/' . __(LaravelLocalization::getCurrentLocale()) . '/datatable.json'
            ])
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->orderBy(1)
            /*   ->parameters([
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
            Column::make('building_number')->title(__('word.building_number'))->class('text-center'),
            Column::make('building_category')->title(__('word.category_name'))->data('building_category.category_name')->name('building_category.category_name')->class('text-center'),
            Column::make('building_type')->title(__('word.type_name'))->data('building_type.type_name')->name('building_type.type_name')->class('text-center'),
            Column::make('classification')->title(__('word.classification'))->data('classification.name')->name('classification.name')->class('text-center'),
            Column::make('house_number')->title(__('word.house_number'))->class('text-center'),
            Column::make('block_number')->title(__('word.block_number'))->class('text-center'),
            Column::make('building_area')->title(__('word.building_area'))->class('text-center'),
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename(): string
    {
        return 'Building_' . date('YmdHis');
    }
}
