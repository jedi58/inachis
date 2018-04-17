<?php

namespace App\Form;

use App\Entity\Category;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Translation\TranslatorInterface;

class CategoryType extends AbstractType
{
    private $translator;
    private $router;

    public function __construct(TranslatorInterface $translator, RouterInterface $router)
    {
        $this->translator = $translator;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            // {% if post.categories is not empty %}
            //     {% for category in post.categories %}
            //           <option selected value="{{ category.id }}">{{  category.title }}</option>
            //     {% endfor %}
            // {% endif %}
//            ->add('categories', ChoiceType::class, [
//                'attr' => [
//                    'aria-labelledby' => 'categories_label',
//                    'aria-required' => 'false',
//                    'class' => 'js-select',
//                    'data-placeholder' => $this->translator->trans('admin.placeholder.post.categories', [], 'messages'),
//                    'data-tip-content' => $this->translator->trans('admin.tip.content.post.categories', [], 'messages'),
//                    'data-url' => $this->router->generate('app_admindialog_getcategorymanagerlistcontent'),
//                ],
//                'label' => $this->translator->trans('admin.label.post.categories', [], 'messages'),
//                'label_attr' => [
//                    'id' => 'categories_label',
//                ],
//                'multiple' => true,
//                'required' => false,
//            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Category::class,
        ]);
    }
}
