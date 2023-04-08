<?php

namespace App\Form;

use App\Entity\EgaMaleContestant;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EgaMaleContestantType extends AbstractType
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
                'data' => 'male',
            ])
            ->add('genderDisplay', ChoiceType::class, [
                'mapped' => false,
                'choices' => ['contestant.data.gender.male' => 'male'],
                'disabled' => true,
            ])
            ->add('ageCategoryDisplay', ChoiceType::class, [
                'mapped' => false,
                'choices' => ['contestant.data.age-category.ega' => 'ega'],
                'disabled' => true,
            ])
            ->add('weightCategory', ChoiceType::class, [
                'choices' => [
                    '-28' => '-28',
                    '-31' => '-31',
                    '-34' => '-34',
                    '-37' => '-37',
                    '-40' => '-40',
                    '-43' => '-43',
                    '-46' => '-46',
                    '-50' => '-50',
                    '-55' => '-55',
                    '+55' => '+55',
                ]
            ])
            ->add('comment', HiddenType::class, ['attr'=> ['class' => 'hidden-comment']])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => EgaMaleContestant::class,
            'year' => null,
        ]);

        $resolver->setAllowedTypes('year', 'int');
    }
}
