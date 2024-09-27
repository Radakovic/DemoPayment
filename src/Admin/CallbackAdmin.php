<?php

namespace App\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Route\RouteCollectionInterface;
use Sonata\AdminBundle\Show\ShowMapper;

class CallbackAdmin extends AbstractAdmin
{
    protected function configureListFields(ListMapper $list): void
    {
        $list->addIdentifier('id');
        $list->add('invoice');
        $list->add(name: 'response', fieldDescriptionOptions: [
            'template' => 'admin/callback/show__callback_response_list_field.html.twig',
        ]);
        $list->add('createdAt');
    }

    protected function configureShowFields(ShowMapper $show): void
    {
        $show->add('id');
        $show->add('invoice');
        $show->add(name: 'request', fieldDescriptionOptions: [
            'template' => 'admin/callback/show__callback_request_field.html.twig',
        ]);
        $show->add(name: 'response', fieldDescriptionOptions: [
            'template' => 'admin/callback/show__callback_response_field.html.twig',
        ]);
        $show->add('createdAt');
    }

    /**
     * @codeCoverageIgnore
     */
    protected function configureRoutes(RouteCollectionInterface $collection): void
    {
        $collection->remove('delete');
        $collection->remove('create');
        $collection->remove('edit');
    }
}
