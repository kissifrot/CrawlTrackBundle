<?php

namespace WebDL\CrawltrackBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use WebDL\CrawltrackBundle\Entity\Crawler;
use WebDL\CrawltrackBundle\Form\CrawlerType;

/**
 * Crawler controller.
 *
 */
class CrawlerController extends Controller
{

    /**
     * Lists all Crawler entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $crawlers = $em->getRepository('WebDLCrawltrackBundle:Crawler')->findAll();

        return $this->render('WebDLCrawltrackBundle:Crawler:index.html.twig', array(
            'crawlers' => $crawlers,
        ));
    }

    /**
     * Creates a new Crawler entity.
     *
     */
    public function createAction(Request $request)
    {
        $crawler = new Crawler();
        $form = $this->createCreateForm($crawler);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($crawler);
            $em->flush();

            return $this->redirect($this->generateUrl('crawler_show', array('id' => $crawler->getId())));
        }

        return $this->render('WebDLCrawltrackBundle:Crawler:new.html.twig', array(
            'crawler' => $crawler,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a Crawler entity.
     *
     * @param Crawler $crawler The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Crawler $crawler)
    {
        $form = $this->createForm(new CrawlerType(), $crawler, array(
            'action' => $this->generateUrl('crawler_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Crawler entity.
     *
     */
    public function newAction()
    {
        $crawler = new Crawler();

        $form   = $this->createCreateForm($crawler);

        return $this->render('WebDLCrawltrackBundle:Crawler:new.html.twig', array(
            'crawler' => $crawler,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Crawler entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $crawler = $em->getRepository('WebDLCrawltrackBundle:Crawler')->find($id);

        if (!$crawler) {
            throw $this->createNotFoundException('Unable to find Crawler entity.');
        }

        $totalVisitsHits = $em->getRepository('WebDLCrawltrackBundle:CrawlerVisit')->getForSpecificCrawlerTotal($crawler);
        $totalVisitsPages = $em->getRepository('WebDLCrawltrackBundle:CrawlerVisit')->getPagesForSpecificCrawlerTotal($crawler);

        $totalPages = $em->getRepository('WebDLCrawltrackBundle:CrawledPage')->getTotalCount();

        $chartCategories = $chartHits = $chartPages  = array();
        foreach($visitsHits as $ind => $hit) {
            $chartCategories[] = $hit['dateVisit'];
            $chartHits[] = (int)$hit['nb'];
            $chartPages[] = (int)$visitsPages[$ind]['nb_pages'];
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('WebDLCrawltrackBundle:Crawler:show.html.twig', array(
            'crawler'      => $crawler,
            'delete_form' => $deleteForm->createView(),
            'visitsData' => array(
                'totalHits' => $totalVisitsHits,
                'totalPages' => $totalVisitsPages,
            ),
            'chartData' => array(
                'categories' => json_encode($chartCategories),
                'hits' => json_encode($chartHits),
                'pages' => json_encode($chartPages),
            ),
            'totalPages' => $totalPages
        ));
    }

    /**
     * Displays a form to edit an existing Crawler entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $crawler = $em->getRepository('WebDLCrawltrackBundle:Crawler')->find($id);

        if (!$crawler) {
            throw $this->createNotFoundException('Unable to find Crawler entity.');
        }

        $editForm = $this->createEditForm($crawler);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('WebDLCrawltrackBundle:Crawler:edit.html.twig', array(
            'crawler'      => $crawler,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
    * Creates a form to edit a Crawler entity.
    *
    * @param Crawler $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Crawler $entity)
    {
        $form = $this->createForm(new CrawlerType(), $entity, array(
            'action' => $this->generateUrl('crawler_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }

    /**
     * Edits an existing Crawler entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $crawler = $em->getRepository('WebDLCrawltrackBundle:Crawler')->find($id);

        if (!$crawler) {
            throw $this->createNotFoundException('Unable to find Crawler entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($crawler);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('crawler_edit', array('id' => $id)));
        }

        return $this->render('WebDLCrawltrackBundle:Crawler:edit.html.twig', array(
            'crawler'      => $crawler,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Toggles a Crawler entity.
     *
     */
    public function toggleAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        /** @var Crawler $entity */
        $crawler = $em->getRepository('WebDLCrawltrackBundle:Crawler')->find($id);

        if (!$crawler) {
            throw $this->createNotFoundException('Unable to find Crawler entity.');
        }

        $crawler->setIsActive($entity->getIsActive() ? false : true);

        $em->persist($entity);
        $em->flush();

        return $this->redirect($this->generateUrl('crawler'));
    }


    /**
     * Deletes a Crawler entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $crawler = $em->getRepository('WebDLCrawltrackBundle:Crawler')->find($id);

            if (!$crawler) {
                throw $this->createNotFoundException('Unable to find Crawler entity.');
            }

            $em->remove($crawler);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('crawler'));
    }

    /**
     * Creates a form to delete a Crawler entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('crawler_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
