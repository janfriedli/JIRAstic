<?php
/**
 * Board Config Controller
 */
namespace JirasticBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use JirasticBundle\Entity\Board;

/**
 * @package JirasticBundle\Controller
 * @author   Jan Friedli <jan.friedli@swisscom.com>
 * @license  https://opensource.org/licenses/GPL-3.0 Public License
 * @link     http://www.swisscom.ch
 *
 * Class BoardController
 *
 * @Route("/admin/board")
 */
class BoardController extends Controller
{
    /**
     * Lists all Board entities.
     *
     * @Route("/", name="admin_board_index")
     * @Method("GET")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $boards = $em->getRepository('JirasticBundle:Board')->findAll();

        return $this->render(
            'board/index.html.twig',
            array(
                'boards' => $boards,
            )
        );
    }

    /**
     * Creates a new Board entity.
     *
     * @Route("/new", name="admin_board_new")
     * @Method({"GET", "POST"})
     *
     * @param Request $request Request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function newAction(Request $request)
    {
        $board = new Board();
        $form = $this->createForm($this->get('jirastic.form.type.board'), $board);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($board);
            $em->flush();

            return $this->redirectToRoute('admin_board_show', array('id' => $board->getId()));
        }

        return $this->render(
            'board/new.html.twig',
            array(
                'board' => $board,
                'form' => $form->createView(),
            )
        );
    }

    /**
     * Displays a form to edit an existing Board entity.
     *
     * @Route("/{id}/edit", name="admin_board_edit")
     * @Method({"GET", "POST"})
     *
     * @param Request $request Request
     * @param Board   $board   Board
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function editAction(Request $request, Board $board)
    {
        $deleteForm = $this->createDeleteForm($board);
        $editForm = $this->createForm($this->get('jirastic.form.type.board'), $board);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($board);
            $em->flush();

            return $this->redirectToRoute('admin_board_index');
        }

        return $this->render(
            'board/edit.html.twig',
            array(
                'board' => $board,
                'edit_form' => $editForm->createView(),
                'delete_form' => $deleteForm->createView(),
            )
        );
    }

    /**
     * Deletes a Board entity.
     *
     * @Route("/{id}", name="admin_board_delete")
     * @Method("DELETE")
     *
     * @param Request $request Request
     * @param Board   $board   Board
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction(Request $request, Board $board)
    {
        $form = $this->createDeleteForm($board);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($board);
            $em->flush();
        }

        return $this->redirectToRoute('admin_board_index');
    }

    /**
     * Creates a form to delete a Board entity.
     *
     * @param Board $board The Board entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Board $board)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_board_delete', array('id' => $board->getId())))
            ->setMethod('DELETE')
            ->getForm();
    }
}
