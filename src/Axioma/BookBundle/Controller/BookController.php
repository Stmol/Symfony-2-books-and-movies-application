<?php

namespace Axioma\BookBundle\Controller;

use Axioma\BookBundle\Entity\Book;
use Axioma\BookBundle\Form\BookType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Book controller.
 *
 */
class BookController extends Controller
{
    const BOOKS_PER_PAGE = 50;

    /**
     * Lists all Book entities.
     *
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(Request $request)
    {
        // TODO: Paginator
        $page = $request->query->get('page', 1);
        $offset = ($page - 1) * self::BOOKS_PER_PAGE;

        $books = $this->getBookManager()
            ->findBooksLimited(self::BOOKS_PER_PAGE, $offset);

        return $this->render('AxiomaBookBundle:Book:index.html.twig', [
            'books' => $books,
        ]);
    }

    /**
     * Finds and displays a Book entity.
     *
     * @param $id
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function showAction($id)
    {
        $book = $this->getBookManager()
            ->findBookById($id);

        if (!$book) {
            throw $this->createNotFoundException();
        }

        return $this->render('AxiomaBookBundle:Book:show.html.twig', [
            'book' => $book,
        ]);
    }

    /**
     * Displays a form to create a new Book entity.
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function newAction()
    {
        $book = $this->getBookManager()->createBook();
        $form = $this->createCreateForm($book);

        return $this->render('AxiomaBookBundle:Book:new.html.twig', [
            'book' => $book,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Displays a form to edit a Book entity.
     *
     * @param $id
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function editAction($id)
    {
        $book = $this->getBookManager()->findBookById($id);

        if (!$book) {
            throw $this->createNotFoundException();
        }

        $form = $this->createEditForm($book);
        $deleteForm = $this->createDeleteForm($book->getId());

        return $this->render('AxiomaBookBundle:Book:edit.html.twig', [
            'form'        => $form->createView(),
            'delete_form' => $deleteForm->createView(),
        ]);
    }

    /**
     * Render delete form
     *
     * @param $id
     *
     * @return \Symfony\Component\Form\FormView
     */
    public function deleteFormAction($id)
    {
        $form = $this->createDeleteForm($id);

        return $this->render('AxiomaBookBundle:Book:deleteForm.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * Creates a new Book entity.
     *
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function createAction(Request $request)
    {
        $book = new Book();
        $form = $this->createCreateForm($book);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $this->getBookManager()->saveBook($book);

            return $this->redirect($this->generateUrl('books_show', ['id' => $book->getId()]));
        }

        return $this->render('AxiomaBookBundle:Book:new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * Edits an existing Book entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $book = $this->getBookManager()->findBookById($id);

        if (!$book) {
            throw $this->createNotFoundException();
        }

        $editForm = $this->createEditForm($book);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $this->getBookManager()->saveBook($book);

            return $this->redirect($this->generateUrl('books_show', ['id' => $id]));
        }

        return $this->render('AxiomaBookBundle:Book:edit.html.twig', [
            'book' => $book,
            'form' => $editForm->createView(),
        ]);
    }

    /**
     * Deletes a Book entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);

        if ($form->handleRequest($request)->isValid()) {
            $book = $this->getBookManager()->findBookById($id);

            if (!$book) {
                throw $this->createNotFoundException();
            }

            $this->getBookManager()->deleteBook($book);
        }

        return $this->redirect($this->generateUrl('books_index'));
    }

    /**
    * Creates a form to create a Book entity.
    *
    * @param Book $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createCreateForm(Book $entity)
    {
        $form = $this->createForm(new BookType(), $entity, array(
            'action' => $this->generateUrl('books_create'),
            'method' => 'POST',
        ));

        $form
            ->add('submit', 'submit', array('label' => 'actions.create'))
        ;

        return $form;
    }

    /**
     * Creates a form to delete a Book entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('books_delete', ['id' => $id]))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'actions.delete'))
            ->getForm()
        ;
    }

    /**
    * Creates a form to edit a Book entity.
    *
    * @param Book $book The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Book $book)
    {
        $form = $this->createForm(new BookType(), $book, [
            'action' => $this->generateUrl('books_update', ['id' => $book->getId()]),
            'method' => 'PUT',
        ]);

        $form
            ->add('submit', 'submit', array('label' => 'actions.save'))
        ;

        return $form;
    }

    /**
     * @return \Axioma\BookBundle\EntityManager\BookManager
     */
    private function getBookManager()
    {
        return $this->get('axioma.manager.book');
    }
}
