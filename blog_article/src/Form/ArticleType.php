<?php

namespace App\Form;

use App\Entity\Article;
use App\Entity\Auteur;
use App\Repository\AuteurRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;




class ArticleType extends AbstractType
{

    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder
            ->add('titre')
            ->add('texte')
            ->add('auteur', EntityType::class, [
                'class' => Auteur::class,
                'query_builder' => function (AuteurRepository $er) {
                    return $er->createQueryBuilder('u');
                },
                'choice_label' => 'nom',
            ])
            ->add('createdAt', DateType::class, [
                'label' => 'Date de creation',
                'data' => new \DateTime("now")
            ])
            ->add('updatedAt', DateType::class, [
                'label' => 'Date de modification',
                'data' => new \DateTime("now")
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Article::class,
        ]);
    }
}
