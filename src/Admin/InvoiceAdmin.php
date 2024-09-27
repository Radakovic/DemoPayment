<?php

namespace App\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Route\RouteCollectionInterface;
use Sonata\AdminBundle\Show\ShowMapper;

class InvoiceAdmin extends AbstractAdmin
{
    protected function configureListFields(ListMapper $list): void
    {
        $list->addIdentifier('id');
        $list->add(name: 'order.amount', fieldDescriptionOptions: [
            'template' => 'admin/invoice/show__amount_field.html.twig'
        ]);
        $list->add('order.currency');
        $list->add('order.country');
        $list->add('payment_method');
        $list->add('status');
        $list->add('createdAt');
        $list->add('expirationDate');
    }
    protected function configureShowFields(ShowMapper $show): void
    {
        $show->add('id');
        $show->add('payment_method');
        $show->add('status');
        $show->add(name: 'request', fieldDescriptionOptions: [
            'template' => 'admin/invoice/show__invoice_request_field.html.twig',
        ]);
        $show->add('expirationDate');
        $show->add('createdAt');
        $show->add('description');
        $show->add(name: 'callbacks', fieldDescriptionOptions: [
            'template' => 'admin/invoice/show__invoice_callbacks_list.html.twig'
        ]);
    }

    protected function configureDatagridFilters(DatagridMapper $filter): void
    {
        $filter->add('status', null, ["show_filter" => true]);
    }

    protected function configureRoutes(RouteCollectionInterface $collection): void
    {
        $collection->remove('delete');
        $collection->remove('create');
        $collection->remove('edit');
    }
}
