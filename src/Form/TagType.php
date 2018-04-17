<?php

namespace App\Form;

use App\Entity\Tag;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TagType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            // {% if post.tags is not empty %}
            //     {% for tag in post.tags %}
            //         <option selected>{{  tag.title }}</option>
            //     {% endfor %}
            // {% endif %}
            ->add('tags', ChoiceType::class, [
                'attr' => [
                    'aria-labelledby' => 'categories_label',
                    'aria-required' => 'false',
                    'class' => 'js-select',
                    'data-tags' => 'true',
//                    'data-tip-content' => $this->translator->trans('admin.tip.content.post.tags', [], 'messages'),
                ],
                'label' => 'Tags',
//                'label' => $this->translator->trans('admin.label.post.tags', [], 'messages'),
                'label_attr' => [

                ],
                'multiple' => true,
                'required' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Tag::class,
        ]);
    }
}
