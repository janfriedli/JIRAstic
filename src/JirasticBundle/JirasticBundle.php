<?php
/**
 * JIRAstic Bundle
 */
namespace JirasticBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * @package JirasticBundle
 * @author   Jan Friedli <jan.friedli@swisscom.com>
 * @license  https://opensource.org/licenses/GPL-3.0 Public License
 * @link     http://www.swisscom.ch
 *
 * Class JirasticBundle
 */
class JirasticBundle extends Bundle
{
    /**
     * Used for the FOSUSerBundle template overriding
     * @return string
     */
    public function getParent()
    {
        return 'FOSUserBundle';
    }
}
