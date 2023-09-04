<?php

namespace App\Form;

use App\Entity\Festival;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use App\Entity\Departement;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class FestivalType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom')
            ->add('affiche')
            ->add('lieu')
            ->add('departement', EntityType::class, [
                'class' => Departement::class,
                'choice_label' => 'nom',
            ])
            ->add('artistes', null, [
                'choice_label' => 'nom',
                'expanded' => true,
                'multiple' => true,
                'by_reference' => false
            ])
            ->add('valider', SubmitType::class, [
                'attr' => ['class' => 'btn btn-success'],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Festival::class,
        ]);
    }
}
