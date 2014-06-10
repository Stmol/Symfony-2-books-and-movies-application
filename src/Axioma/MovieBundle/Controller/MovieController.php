<?php

namespace Axioma\MovieBundle\Controller;

use Axioma\MovieBundle\Entity\Movie;
use Axioma\MovieBundle\Form\MovieType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Movie controller.
 *
 */
class MovieController extends Controller
{
    /**
     * Num of movies for paginator
     */
    const MOVIE_PER_PAGE = 50;

    /**
     * Translator domain name
     */
    const TRANSLATOR_DOMAIN = 'amb';

    /**
     * Lists all Movie entities.
     *
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(Request $request)
    {
        $page = $request->query->get('page', 1);
        $offset = ($page - 1) * self::MOVIE_PER_PAGE;

        $movies = $this->getMovieManager()
            ->findMoviesLimited(self::MOVIE_PER_PAGE, $offset);

        return $this->render('AxiomaMovieBundle:Movie:index.html.twig', [
            'movies' => $movies,
        ]);
    }

    /**
     * Finds and displays a Movie entity.
     *
     * @param $id
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function showAction($id)
    {
        $movie = $this->getMovieManager()->findMovieById($id);

        if (!$movie) {
            throw $this->createNotFoundException();
        }

        return $this->render('AxiomaMovieBundle:Movie:show.html.twig', [
            'movie' => $movie,
        ]);
    }

    /**
     * Displays a form to create a new Movie entity.
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function newAction()
    {
        $movie = $this->getMovieManager()->createMovie();
        $form = $this->createCreateForm($movie);

        return $this->render('AxiomaMovieBundle:Movie:new.html.twig', [
            'movie' => $movie,
            'form'  => $form->createView(),
        ]);
    }

    /**
     * Displays a form to edit an existing Movie entity.
     *
     */
    public function editAction($id)
    {
        $movie = $this->getMovieManager()->findMovieById($id);

        if (!$movie) {
            throw $this->createNotFoundException();
        }

        $form = $this->createEditForm($movie);

        return $this->render('AxiomaMovieBundle:Movie:edit.html.twig', [
            'entity' => $movie,
            'form'   => $form->createView(),
        ]);
    }

    /**
     * @param $id
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function deleteFormAction($id)
    {
        $form = $this->createDeleteForm($id);

        return $this->render('AxiomaMovieBundle:Movie:deleteForm.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * Creates a new Movie entity.
     *
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function createAction(Request $request)
    {
        $movie = $this->getMovieManager()->createMovie();
        $form = $this->createCreateForm($movie);

        if ($form->handleRequest($request)->isValid()) {
            $this->getMovieManager()->saveMovie($movie);

            return $this->redirect($this->generateUrl('movies_show', ['id' => $movie->getId()]));
        }

        return $this->render('AxiomaMovieBundle:Movie:new.html.twig', [
            'form'   => $form->createView(),
        ]);
    }

    /**
     * Edits an existing Movie entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $movie = $this->getMovieManager()->findMovieById($id);

        if (!$movie) {
            throw $this->createNotFoundException();
        }

        $form = $this->createEditForm($movie);

        if ($form->handleRequest($request)->isValid()) {
            $this->getMovieManager()->saveMovie($movie);

            return $this->redirect($this->generateUrl('movies_show', ['id' => $id]));
        }

        return $this->render('AxiomaMovieBundle:Movie:edit.html.twig',[
            'form' => $form->createView(),
        ]);
    }

    /**
     * Deletes a Movie entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);

        if ($form->handleRequest($request)->isValid()) {
            $movie = $this->getMovieManager()->findMovieById($id);

            if (!$movie) {
                throw $this->createNotFoundException();
            }

            $this->getMovieManager()->deleteMovie($movie);
        }

        return $this->redirect($this->generateUrl('movies_index'));
    }

    /**
    * Creates a form to create a Movie entity.
    *
    * @param Movie $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createCreateForm(Movie $entity)
    {
        $form = $this->createForm(new MovieType(), $entity, [
            'action' => $this->generateUrl('movies_create'),
            'method' => 'POST',
        ]);

        $form
            ->add('submit', 'submit', ['label' => $this->trans('actions.add')])
        ;

        return $form;
    }

    /**
    * Creates a form to edit a Movie entity.
    *
    * @param Movie $movie The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Movie $movie)
    {
        $form = $this->createForm(new MovieType(), $movie, [
            'action' => $this->generateUrl('movies_update', ['id' => $movie->getId()]),
            'method' => 'PUT',
        ]);

        $form
            ->add('submit', 'submit', ['label' => $this->trans('actions.save')])
        ;

        return $form;
    }

    /**
     * Creates a form to delete a Movie entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('movies_delete', ['id' => $id]))
            ->setMethod('DELETE')
            ->add('submit', 'submit', ['label' => $this->trans('actions.delete')])
            ->getForm()
        ;
    }

    /**
     * @return \Axioma\MovieBundle\EntityManager\MovieManager
     */
    private function getMovieManager()
    {
        return $this->get('axioma.manager.movie');
    }

    /**
     * @param $message
     * @param array $options
     *
     * @return mixed
     */
    private function trans($message, array $options = [])
    {
        return $this->get('translator')->trans($message, $options, self::TRANSLATOR_DOMAIN);
    }
}
