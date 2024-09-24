<?php

namespace App\Form;

use App\Entity\Invoice;
use App\Entity\Order;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
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
            ->add('order', OrderType::class, [
//                'mapped' => false,
//                'data' => 'testsssss'
            ])
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
    }
}
