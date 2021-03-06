<?php
/**
 * StatusType Form
 */
namespace JirasticBundle\Form;

use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

/**
 * @package JirasticBundle\Form
 * @author   Jan Friedli <jan.friedli@swisscom.com>
 * @license  https://opensource.org/licenses/GPL-3.0 Public License
 * @link     http://www.swisscom.ch
 *
 * Class StatusType
 */
class StatusType extends AbstractType
{
    /**
     * @var array
     */
    private $classes;

    /**
     * StatusType constructor.
     * @param array $classes List of css class names
     */
    public function __construct($classes)
    {
        $this->classes = $classes;
    }

    /**
     * @param FormBuilderInterface $builder Form Builder
     * @param array                $options Options
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('title')
                ->add('titleShort')
                ->add('bgcolor', null, array( 'attr'=> array( 'class'=>'jscolor')))
                ->add('icon', TextType::class, array('attr' => array('class' => 'form-control icp icp-auto')))
                ->add(
                    'class',
                    ChoiceType::class,
                    array(
                        'choices' => $this->classes,
                        'label' => 'Bubble Style'
                    )
                )
                ->add('orderId')
                ->add(
                    'statusMapping',
                    EntityType::class,
                    array(
                        'class' => 'JirasticBundle:StatusMapping',
                        'choice_label' => 'name',
                        'multiple' => true,
                        'query_builder' => function (EntityRepository $repository) {
                            return $repository->createQueryBuilder('mapping')->orderBy('mapping.name', 'ASC');
                        }
                    )
                );
    }

    /**
     * @param OptionsResolver $resolver Resolver
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => 'JirasticBundle\Entity\Status'
            )
        );
    }
}
