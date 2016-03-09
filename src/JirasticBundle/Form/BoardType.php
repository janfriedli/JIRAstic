<?php
/**
 * Board Form
 */
namespace JirasticBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

/**
 * @package JirasticBundle\Form
 * @author   Jan Friedli <jan.friedli@swisscom.com>
 * @license  https://opensource.org/licenses/GPL-3.0 Public License
 * @link     http://www.swisscom.ch
 *
 * Class BoardType
 */
class BoardType extends AbstractType
{

    /**
     * BoardType constructor.
     * @param StatusType $statusType Status Type
     */
    public function __construct(StatusType $statusType)
    {
        $this->statusType = $statusType;
    }

    /**
     * @param FormBuilderInterface $builder Builder
     * @param array                $options Options
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add(
            'statuses',
            CollectionType::class,
            array(
                'entry_type' => $this->statusType,
                'allow_add'    => true,
                'allow_delete' => true,
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
                'data_class' => 'JirasticBundle\Entity\Board'
            )
        );
    }
}
