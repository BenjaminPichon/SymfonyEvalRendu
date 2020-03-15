<?php

namespace App\Form;

use App\Entity\Produit;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class ProduitType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom', TextType::class, [
                'label'=> false,
                'attr'=>['class'=>'form-control', 'placeholder'=>'Entrez le nom du produit']
            ])
            ->add('photo', FileType::class,[
                'label'=> false,
                'attr'=>['class'=>'form-control-file']
            ])
            ->add('quantite', IntegerType::class,[
                
                'label'=> false,
                'attr'=>['class'=>'form-control', 'placeholder'=>'Quel stock']
            ])
            ->add('prix', textType::class,[
                'label'=> false,
                'attr'=>['class'=>'form-control', 'placeholder'=>'Quel est le prix du produit ?']
            ])
            ->add('submit',SubmitType::class,['attr'=>['class'=>'btn btn-primary']])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Produit::class,
        ]);
    }
}
