<?php

namespace App\Form;


use App\Entity\Registration;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MesseFemaleContestantsListType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options) : void
    {
        $builder
            ->add('contestants', CollectionType::class, [
                'property_path' => 'messeFemaleContestants',
                'entry_type' => MesseFemaleContestantType::class,
                'entry_options' => ['label' => false, 'year' =>  $options['year']],
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver) : void
    {
        $resolver->setDefaults([
            'data_class' => Registration::class,
            'year' => null,
        ]);

        $resolver->setAllowedTypes('year', 'int');
    }
}
