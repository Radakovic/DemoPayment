<?php

namespace App\Form;

use App\Entity\Invoice;
use App\Entity\Order;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OrderType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
                $order = $event->getData();
                assert($order instanceof Order);
                $form = $event->getForm();
                $form->add('amount', MoneyType::class, [
                    'currency' => $order->getCurrency(),
                    'divisor' => 100,
                    'data' => $order->getAmount(),
                    'disabled' => true,
                ]);
            })
            ->add('id', HiddenType::class)
            ->add('country', HiddenType::class)
            ->add('currency', HiddenType::class);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Order::class,
        ]);
    }
}
