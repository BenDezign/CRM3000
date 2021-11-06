<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;

class UserType extends AbstractType
{
    private TranslatorInterface $translator;

    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $entity = $builder->getData();
        $builder
            ->add('email')
            ->add('passwordForce', TextType::class, ['required' => false, 'mapped' => false])
            ->add('password', HiddenType::class, ['required' => false])
            ->add('name')
            ->add('roles', ChoiceType::class, ['choices'=>
                [
                    $this->translator->trans('simple') => 'ROLE_USER',
                    $this->translator->trans('administrator') => 'ROLE_ADMIN',
                    ],
                'data' => !empty($entity->getRoles()) ? $entity->getRoles()[0] : "",
                'placeholder' => 'Sélectionner un role',
                'required' => true,
                'mapped' => false
            ],
               )
            ->add('company')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
