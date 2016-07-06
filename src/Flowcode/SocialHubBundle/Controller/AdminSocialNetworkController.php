<?php

namespace Flowcode\SocialHubBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Flowcode\SocialHubBundle\Entity\SocialNetwork;
use Flowcode\SocialHubBundle\Form\Type\SocialNetworkType;
use Doctrine\ORM\QueryBuilder;

/**
 * SocialNetwork controller.
 *
 * @Route("/admin/socialnetwork")
 */
class AdminSocialNetworkController extends Controller
{
    /**
     * Lists all SocialNetwork entities.
     *
     * @Route("/", name="admin_socialnetwork")
     * @Method("GET")
     * @Template()
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $qb = $em->getRepository('FlowcodeSocialHubBundle:SocialNetwork')->createQueryBuilder('s');
        $this->addQueryBuilderSort($qb, 'socialnetwork');
        $paginator = $this->get('knp_paginator')->paginate($qb, $request->query->get('page', 1), 20);

        return array(
            'paginator' => $paginator,
        );
    }

    /**
     * Finds and displays a SocialNetwork entity.
     *
     * @Route("/{id}/show", name="admin_socialnetwork_show", requirements={"id"="\d+"})
     * @Method("GET")
     * @Template()
     */
    public function showAction(SocialNetwork $socialnetwork)
    {
        $editForm = $this->createForm(new SocialNetworkType(), $socialnetwork, array(
            'action' => $this->generateUrl('admin_socialnetwork_update', array('id' => $socialnetwork->getid())),
            'method' => 'PUT',
        ));
        $deleteForm = $this->createDeleteForm($socialnetwork->getId(), 'admin_socialnetwork_delete');

        return array(

            'socialnetwork' => $socialnetwork, 'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),

        );
    }

    /**
     * Displays a form to create a new SocialNetwork entity.
     *
     * @Route("/new", name="admin_socialnetwork_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $socialnetwork = new SocialNetwork();
        $form = $this->createForm(new SocialNetworkType(), $socialnetwork);

        return array(
            'socialnetwork' => $socialnetwork,
            'form' => $form->createView(),
        );
    }

    /**
     * Creates a new SocialNetwork entity.
     *
     * @Route("/create", name="admin_socialnetwork_create")
     * @Method("POST")
     * @Template("FlowcodeSocialHubBundle:SocialNetwork:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $socialnetwork = new SocialNetwork();
        $form = $this->createForm(new SocialNetworkType(), $socialnetwork);
        if ($form->handleRequest($request)->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($socialnetwork);
            $em->flush();

            return $this->redirect($this->generateUrl('admin_socialnetwork_show', array('id' => $socialnetwork->getId())));
        }

        return array(
            'socialnetwork' => $socialnetwork,
            'form' => $form->createView(),
        );
    }

    /**
     * Edits an existing SocialNetwork entity.
     *
     * @Route("/{id}/update", name="admin_socialnetwork_update", requirements={"id"="\d+"})
     * @Method("PUT")
     * @Template("FlowcodeSocialHubBundle:SocialNetwork:edit.html.twig")
     */
    public function updateAction(SocialNetwork $socialnetwork, Request $request)
    {
        $editForm = $this->createForm(new SocialNetworkType(), $socialnetwork, array(
            'action' => $this->generateUrl('admin_socialnetwork_update', array('id' => $socialnetwork->getid())),
            'method' => 'PUT',
        ));
        if ($editForm->handleRequest($request)->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirect($this->generateUrl('admin_socialnetwork_show', array('id' => $socialnetwork->getId())));
        }
        $deleteForm = $this->createDeleteForm($socialnetwork->getId(), 'admin_socialnetwork_delete');

        return array(
            'socialnetwork' => $socialnetwork,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }


    /**
     * Save order.
     *
     * @Route("/order/{field}/{type}", name="admin_socialnetwork_sort")
     */
    public function sortAction($field, $type)
    {
        $this->setOrder('socialnetwork', $field, $type);

        return $this->redirect($this->generateUrl('admin_socialnetwork'));
    }

    /**
     * @param string $name session name
     * @param string $field field name
     * @param string $type sort type ("ASC"/"DESC")
     */
    protected function setOrder($name, $field, $type = 'ASC')
    {
        $this->getRequest()->getSession()->set('sort.' . $name, array('field' => $field, 'type' => $type));
    }

    /**
     * @param  string $name
     * @return array
     */
    protected function getOrder($name)
    {
        $session = $this->getRequest()->getSession();

        return $session->has('sort.' . $name) ? $session->get('sort.' . $name) : null;
    }

    /**
     * @param QueryBuilder $qb
     * @param string $name
     */
    protected function addQueryBuilderSort(QueryBuilder $qb, $name)
    {
        $alias = current($qb->getDQLPart('from'))->getAlias();
        if (is_array($order = $this->getOrder($name))) {
            $qb->orderBy($alias . '.' . $order['field'], $order['type']);
        }
    }

    /**
     * Deletes a SocialNetwork entity.
     *
     * @Route("/{id}/delete", name="admin_socialnetwork_delete", requirements={"id"="\d+"})
     * @Method("DELETE")
     */
    public function deleteAction(SocialNetwork $socialnetwork, Request $request)
    {
        $form = $this->createDeleteForm($socialnetwork->getId(), 'admin_socialnetwork_delete');
        if ($form->handleRequest($request)->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($socialnetwork);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('admin_socialnetwork'));
    }

    /**
     * Create Delete form
     *
     * @param integer $id
     * @param string $route
     * @return \Symfony\Component\Form\Form
     */
    protected function createDeleteForm($id, $route)
    {
        return $this->createFormBuilder(null, array('attr' => array('id' => 'delete')))
            ->setAction($this->generateUrl($route, array('id' => $id)))
            ->setMethod('DELETE')
            ->getForm();
    }

}
