<?php

namespace NNWelcome\NticBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use NNWelcome\NticBundle\Entity\Price;
use NNWelcome\NticBundle\Form\PriceType;


class PriceController extends Controller
{
    public function indexAction(Request $request)
    {
        $page = $request->query->get('page');
        
        if($page){
            $request->getSession()->set('admin_price_page', $page);
        }
        elseif($request->getSession()->has('admin_price_page')){
            $page = $request->getSession()->get('admin_price_page');
        }
        else{
            $page = 1;
        }
        
        $em = $this->getDoctrine()->getManager();
        $query = $em->getRepository('NticBundle:Price')
                ->retrivePrice($request);

        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $query,
            $page/*page number*/,
            20/*limit per page*/
        );

        $deleteForm = $this->createEmptyDeleteForm();
        
        return $this->render('NticBundle:Price:index.html.twig', array(
            'pagination' => $pagination,
            'delete_form' => $deleteForm->createView()
        ));
    }

    public function newAction(Request $request)
    {
        $entity = new Price();
        $form   = $this->createForm(new PriceType(), $entity);

        $page = 1;
        if($request->getSession()->has('admin_price_page')){
            $page = $request->getSession()->get('admin_price_page');
        }
        
        return $this->render('NticBundle:Price:new.html.twig', array(
            'page' => $page,
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    public function createAction(Request $request)
    {
        $entity  = new Price();
        $form = $this->createForm(new PriceType(), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            $this->get('session')->setFlash('notice',
                $this->get('translator')->trans('Entry is successfully saved!')
            );
            
            if($entity->getAction() == 'close')
                return $this->redirect($this->generateUrl('admin_price'));
            
            return $this->redirect($this->generateUrl('admin_price_edit', array('id' => $entity->getId())));
        }

        $this->get('session')->setFlash('error',
            $this->get('translator')->trans('Correct validation errors and try saving again.')
        );
        
        $page = 1;
        if($request->getSession()->has('admin_price_page')){
            $page = $request->getSession()->get('admin_price_page');
        }
        return $this->render('NticBundle:Price:new.html.twig', array(
            'page' => $page,
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    public function editAction(Request $request, $id)
    {
        $page = 1;
        if($request->getSession()->has('admin_price_page')){
            $page = $request->getSession()->get('admin_price_page');
        }
        
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('NticBundle:Price')->find($id);

        if (!$entity) {
            $this->get('session')->setFlash('warnin',
                $this->get('translator')->trans('Unable to find entry with id = "%id%".', array('%id%' => $id))
            );
            return $this->redirect($this->generateUrl('admin_price', array('page' => $page)));
        }

        $editForm = $this->createForm(new PriceType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('NticBundle:Price:edit.html.twig', array(
            'page'        => $page,
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    public function updateAction(Request $request, $id)
    {
        $page = 1;
        if($request->getSession()->has('admin_price_page')){
            $page = $request->getSession()->get('admin_price_page');
        }
        
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('NticBundle:Price')->find($id);

        if (!$entity) {
            $this->get('session')->setFlash('warnin',
                $this->get('translator')->trans('Unable to find entry with id = "%id%".', array('%id%' => $id))
            );
            return $this->redirect($this->generateUrl('admin_price', array('page' => $page)));
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new PriceType(), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            $this->get('session')->setFlash('notice',
                $this->get('translator')->trans('Price is successfully updated!')
            );
            
            if($entity->getAction() == 'close')
                return $this->redirect($this->generateUrl('admin_price'));
            
            return $this->redirect($this->generateUrl('admin_price_edit', array('id' => $entity->getId())));
        }

        $this->get('session')->setFlash('error',
            $this->get('translator')->trans('Correct validation errors and try saving again.')
        );
        
        return $this->render('NticBundle:Price:edit.html.twig', array(
            'page'        => $page,
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    public function deleteAction(Request $request, $id)
    {
        $page = 1;
        if($request->getSession()->has('admin_price_page')){
            $page = $request->getSession()->get('admin_price_page');
        }
        
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('NticBundle:Price')->find($id);

            if (!$entity) {
                $this->get('session')->setFlash('warnin',
                    $this->get('translator')->trans('Unable to find entry with id = "%id%".', array('%id%' => $id))
                );
                return $this->redirect($this->generateUrl('admin_price', array('page' => $page)));
            }

            $em->remove($entity);
            $em->flush();
            
            $this->get('session')->setFlash('notice',
                $this->get('translator')->trans('Price is successfully deleted!')
            );
        }
        else{
            $this->get('session')->setFlash('error',
                $this->get('translator')->trans('Error deleting price.')
            );
        }

        return $this->redirect($this->generateUrl('admin_price', array('page' => $page)));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
    
    private function createEmptyDeleteForm() {
        return $this->createFormBuilder()
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
}
