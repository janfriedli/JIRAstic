<?php

namespace JirasticBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use JirasticBundle\Entity\Customfield;
use JirasticBundle\Form\CustomfieldType;

/**
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
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $customfields = $em->getRepository('JirasticBundle:Customfield')->findAll();

        return $this->render('JirasticBundle:customfield:index.html.twig', array(
            'customfields' => $customfields,
        ));
    }

    /**
     * Creates a new Customfield entity.
     *
     * @Route("/new", name="admin_customfield_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $customfield = new Customfield();
        $form = $this->createForm('JirasticBundle\Form\CustomfieldType', $customfield);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($customfield);
            $em->flush();

            return $this->redirectToRoute('admin_customfield_show', array('id' => $customfield->getId()));
        }

        return $this->render('JirasticBundle:customfield:new.html.twig', array(
            'customfield' => $customfield,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Customfield entity.
     *
     * @Route("/{id}", name="admin_customfield_show")
     * @Method("GET")
     */
    public function showAction(Customfield $customfield)
    {
        $deleteForm = $this->createDeleteForm($customfield);

        return $this->render('JirasticBundle:customfield:show.html.twig', array(
            'customfield' => $customfield,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Customfield entity.
     *
     * @Route("/{id}/edit", name="admin_customfield_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Customfield $customfield)
    {
        $deleteForm = $this->createDeleteForm($customfield);
        $editForm = $this->createForm('JirasticBundle\Form\CustomfieldType', $customfield);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($customfield);
            $em->flush();

            return $this->redirectToRoute('admin_customfield_edit', array('id' => $customfield->getId()));
        }

        return $this->render('JirasticBundle:customfield:edit.html.twig', array(
            'customfield' => $customfield,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Customfield entity.
     *
     * @Route("/{id}", name="admin_customfield_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Customfield $customfield)
    {
        $form = $this->createDeleteForm($customfield);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($customfield);
            $em->flush();
        }

        return $this->redirectToRoute('admin_customfield_index');
    }

    /**
     * Creates a form to delete a Customfield entity.
     *
     * @param Customfield $customfield The Customfield entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Customfield $customfield)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_customfield_delete', array('id' => $customfield->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
