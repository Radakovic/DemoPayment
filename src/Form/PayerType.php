<?php

namespace App\Form;

use Ramsey\Uuid\Uuid;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class PayerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('document', HiddenType::class, [
                'data' => Uuid::uuid4()->toString(),
            ])
            ->add('firstName', TextType::class, [])
            ->add('lastName', TextType::class, [])
            ->add('phone', TextType::class, [])
            ->add('email', TextType::class, []);
    }
}
