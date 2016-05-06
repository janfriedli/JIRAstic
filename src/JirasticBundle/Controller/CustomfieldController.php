<?php
/**
 * Customfield Controller
 */
namespace JirasticBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use JirasticBundle\Entity\Customfield;
use JirasticBundle\Form\CustomfieldType;

/**
 * @package JirasticBundle\Controller
 * @author   Jan Friedli <jan.friedli@swisscom.com>
 * @license  https://opensource.org/licenses/GPL-3.0 Public License
 * @link     http://www.swisscom.ch
 *
 * Customfield controller.
 *
 * @Route("/admin/customfield")
 */
class CustomfieldController extends Controller
{
    /**
     * Lists all Customfield entities.
     *
     * @Route("/", name="admin_customfield_index")
     * @Method("GET")
     * @return Response
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $customfields = $em->getRepository('JirasticBundle:Customfield')->findAll();

        if (!$customfields) {
            return $this->redirectToRoute('admin_customfield_new');
        }

        return $this->render(
            'JirasticBundle:customfield:index.html.twig',
            array(
            'customfields' => $customfields,
            )
        );
    }

    /**
     * Creates a new Customfield entity.
     *
     * @Route("/new", name="admin_customfield_new")
     * @Method({"GET", "POST"})
     *
     * @param  Request $request The Request
     * @return  Response
     */
    public function newAction(Request $request)
    {
        $customfield = new Customfield();
        $form = $this->createForm($this->get("jirastic.form.type.customfield"), $customfield);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($customfield);
            $em->flush();

            $this->addFlash(
                'notice',
                'Your changes were saved!'
            );

            return $this->redirectToRoute('admin_customfield_index', array('id' => $customfield->getId()));
        }

        return $this->render(
            'JirasticBundle:customfield:new.html.twig',
            array(
                'customfield' => $customfield,
                'form' => $form->createView(),
            )
        );
    }

    /**
     * Displays a form to edit an existing Customfield entity.
     *
     * @Route("/{id}/edit", name="admin_customfield_edit")
     * @Method({"GET", "POST"})
     *
     * @param Request     $request     Request
     * @param Customfield $customfield Customfield
     * @return Response
     */
    public function editAction(Request $request, Customfield $customfield)
    {
        $editForm = $this->createForm($this->get("jirastic.form.type.customfield"), $customfield);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($customfield);
            $em->flush();

            $this->addFlash(
                'notice',
                'Your changes were saved!'
            );

            return $this->redirectToRoute('admin_customfield_index', array('id' => $customfield->getId()));
        }

        return $this->render(
            'JirasticBundle:customfield:edit.html.twig',
            array(
                'customfield' => $customfield,
                'edit_form' => $editForm->createView(),
            )
        );
    }
}
