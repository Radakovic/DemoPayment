<?php

namespace App\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;

class MerchantOrderAdmin extends AbstractAdmin
{
    protected function configureListFields(ListMapper $list): void
    {
        $list->add('id');
    }
}
