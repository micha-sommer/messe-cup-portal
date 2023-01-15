<?php

namespace App\Form;

use App\Entity\MesseMaleContestant;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MesseMaleContestantType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $yearChoices = [];
        $year = $options['year'];
        $yearBegin = $year - 15;
        $yearEnd = $year - 13;
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
                'choices' => ['contestant.data.age-category.messe' => 'messe'],
                'disabled' => true,
            ])
            ->add('weightCategory', ChoiceType::class, [
                'choices' => [
                    '-37' => '-37',
                    '-40' => '-40',
                    '-43' => '-43',
                    '-46' => '-46',
                    '-50' => '-50',
                    '-55' => '-55',
                    '-60' => '-60',
                    '-66' => '-66',
                    '-73' => '-73',
                    '+73' => '+73',
                ]
            ])
            ->add('comment', HiddenType::class, ['attr'=> ['class' => 'hidden-comment']])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => MesseMaleContestant::class,
            'year' => null,
        ]);

        $resolver->setAllowedTypes('year', 'int');
    }
}
