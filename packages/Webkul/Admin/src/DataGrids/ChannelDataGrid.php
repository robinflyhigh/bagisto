<?php

namespace Webkul\Admin\DataGrids;

use Webkul\Ui\DataGrid\DataGrid;
use Webkul\Core\Repositories\ChannelRepository;

class ChannelDataGrid extends DataGrid
{
    /**
     * Assign primary key.
     */
    protected $index = 'id';

    /**
     * Sort order.
     */
    protected $sortOrder = 'desc';

    /**
     * Filter Locale.
     */
    protected $locale;

    /**
     * ChannelRepository $channelRepository
     *
     * @var \Webkul\Core\Repositories\ChannelRepository
     */
    protected $channelRepository;

    /**
     * Create a new datagrid instance.
     *
     * @param  \Webkul\Core\Repositories\ChannelRepository  $channelRepository
     * @return void
     */
    public function __construct(
        ChannelRepository $channelRepository
    )
    {
        parent::__construct();

        $this->locale = request()->get('locale') ?? app()->getLocale();

        $this->channelRepository = $channelRepository;
    }

    public function prepareQueryBuilder()
    {
        $queryBuilder = $this->channelRepository->query()
            ->leftJoin('channel_translations', 'channel_translations.channel_id', '=', 'channels.id')
            ->addSelect('channels.id', 'channels.code', 'channel_translations.locale', 'channel_translations.name as translated_name', 'channels.hostname')
            ->where('channel_translations.locale', '=', $this->locale);

        $this->setQueryBuilder($queryBuilder);
    }

    public function addColumns()
    {
        $this->addColumn([
            'index'      => 'id',
            'label'      => trans('admin::app.datagrid.id'),
            'type'       => 'number',
            'searchable' => false,
            'sortable'   => true,
            'filterable' => true,
        ]);

        $this->addColumn([
            'index'      => 'code',
            'label'      => trans('admin::app.datagrid.code'),
            'type'       => 'string',
            'searchable' => true,
            'sortable'   => true,
            'filterable' => true,
        ]);

        $this->addColumn([
            'index'      => 'translated_name',
            'label'      => trans('admin::app.datagrid.name'),
            'type'       => 'string',
            'searchable' => true,
            'sortable'   => true,
            'filterable' => true,
        ]);

        $this->addColumn([
            'index'      => 'hostname',
            'label'      => trans('admin::app.datagrid.hostname'),
            'type'       => 'string',
            'sortable'   => true,
            'searchable' => true,
            'filterable' => true,
        ]);
    }

    public function prepareActions()
    {
        $this->addAction([
            'title'  => trans('admin::app.datagrid.edit'),
            'method' => 'GET',
            'route'  => 'admin.channels.edit',
            'icon'   => 'icon pencil-lg-icon',
        ]);

        $this->addAction([
            'title'        => trans('admin::app.datagrid.delete'),
            'method'       => 'POST',
            'route'        => 'admin.channels.delete',
            'confirm_text' => trans('ui::app.datagrid.massaction.delete', ['resource' => 'product']),
            'icon'         => 'icon trash-icon',
        ]);
    }
}