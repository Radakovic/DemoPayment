<?php

namespace App\Form;

use App\Entity\Invoice;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class InvoiceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
                $invoice = $event->getData();
                assert($invoice instanceof Invoice);
                $form = $event->getForm();
                $form->add('amount', MoneyType::class, [
                    'currency' => $invoice->getOrder()?->getCurrency() ?? 'USD',
                    'divisor' => 100,
                    'mapped' => false,
                    'data' => $invoice->getOrder()?->getAmount() ?? 0,
                    'disabled' => true,
                    'priority' => 100
                ]);
            })
            ->add('paymentMethod', ChoiceType::class, [
                'priority' => 90,
                'choices'  => [
                    'Method 1' => 'METHOD 1',
                    'Method 2' => 'METHOD 2',
                    'Method 3' => 'METHOD 3',
                ],
            ])
            ->add('description', TextareaType::class, [
                'required' => false,
            ])
            ->add('payer', PayerType::class, [
                'mapped' => false,
            ])
            ->add('submit', SubmitType::class);
//            ->add('clientIp', TextType::class, [])
//            ->add('notificationUrl', TextType::class, []);
    }
}
