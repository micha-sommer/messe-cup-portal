<?php

namespace App\Form;

use App\Entity\EgaFemaleContestant;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EgaFemaleContestantType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $yearChoices = [];
        $year = $options['year'];
        $yearBegin = $year - 12;
        $yearEnd = $year - 10;
        for ($i = $yearBegin; $i <= $yearEnd; $i++) {
            $yearChoices[$i] = "" . $i;
        }

        $builder
            ->add('firstName', TextType::class)
            ->add('lastName', TextType::class)
            ->add('year', ChoiceType::class, ['choices' => $yearChoices])
            ->add('gender', HiddenType::class, [
                'data' => 'female',
            ])
            ->add('genderDisplay', ChoiceType::class, [
                'mapped' => false,
                'choices' => ['contestant.data.gender.female' => 'female'],
                'disabled' => true,
            ])
            ->add('ageCategoryDisplay', ChoiceType::class, [
                'mapped' => false,
                'choices' => ['contestant.data.age-category.ega' => 'ega'],
                'disabled' => true,
            ])
            ->add('weightCategory', ChoiceType::class, [
                'choices' => [
                    '-27' => '-27',
                    '-30' => '-30',
                    '-33' => '-33',
                    '-36' => '-36',
                    '-40' => '-40',
                    '-44' => '-44',
                    '-48' => '-48',
                    '-52' => '-52',
                    '-57' => '-57',
                    '+57' => '+57',
                ]
            ])
            ->add('comment', HiddenType::class, ['attr'=> ['class' => 'hidden-comment']])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => EgaFemaleContestant::class,
            'year' => null,
        ]);

        $resolver->setAllowedTypes('year', 'int');
    }
}
