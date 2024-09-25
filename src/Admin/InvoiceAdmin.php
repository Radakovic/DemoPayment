<?php

namespace App\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollectionInterface;
use Sonata\AdminBundle\Show\ShowMapper;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Validator\Constraints\NotBlank;

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
    protected function configureFormFields(FormMapper $form): void
    {
        $form->add(name: 'paymentMethod', type: ChoiceType::class, options: [
            'choices'  => [
                'Method 1' => 'METHOD 1',
                'Method 2' => 'METHOD 2',
                'Method 3' => 'METHOD 3',
            ],
            'constraints' => [new NotBlank()],
        ]);
        $form->add(name: 'description', type: TextareaType::class, options: [
            'label' => 'Invoice description',
            'required' => false,
            'attr' => [
                'rows' => 5,
            ],
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
    }
}
