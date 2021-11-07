<?php

namespace App\Form;

use App\Entity\FactureDetail;
use App\Entity\Tva;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FactureDetailType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('Libelle')
            ->add('Tva', EntityType::class ,['class' => Tva::class ,
                'choice_label' => 'Taux' ,
                'placeholder' => 'Tva ...' ,
                'required' => false])
            ->add('PU')
            ->add('Qtt')
            ->add('ReferenceDet')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => FactureDetail::class,
        ]);
    }
}
