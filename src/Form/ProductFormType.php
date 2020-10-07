<?php

namespace App\Form;

use App\Entity\Product;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nameProduct', null, array(
                'attr' => array('class' => 'form-group')
            ))
            ->add('purshaseDate', DateType::class, [
                'widget' => 'single_text'
            ])
            ->add('quantity')
            ->add('expirationDate', DateType::class, [
                'widget' => 'single_text'
            ])
            ->add('category')
            ->add('submit', SubmitType::class, array(
                'attr' => array('class' => 'btn btn-primary')
            ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}
