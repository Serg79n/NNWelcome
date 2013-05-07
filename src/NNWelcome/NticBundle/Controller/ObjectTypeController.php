<?php

namespace NNWelcome\NticBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use NNWelcome\NticBundle\Entity\ObjectType;
use NNWelcome\NticBundle\Form\ObjectTypeType;


class ObjectTypeController extends Controller
{
    public function indexAction(Request $request)
    {
        $page = $request->query->get('page');
        
        if($page){
            $request->getSession()->set('admin_object_type_page', $page);
        }
        elseif($request->getSession()->has('admin_object_type_page')){
            $page = $request->getSession()->get('admin_object_type_page');
        }
        else{
            $page = 1;
        }
        
        $em = $this->getDoctrine()->getManager();
        $query = $em->getRepository('NticBundle:ObjectType')
                ->retriveObjectType($request);

        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $query,
            $page/*page number*/,
            20/*limit per page*/
        );

        $deleteForm = $this->createEmptyDeleteForm();
        
        return $this->render('NticBundle:ObjectType:index.html.twig', array(
            'pagination' => $pagination,
            'delete_form' => $deleteForm->createView()
        ));
    }

    public function newAction(Request $request)
    {
        $entity = new ObjectType();
        $form   = $this->createForm(new ObjectTypeType(), $entity);

        $page = 1;
        if($request->getSession()->has('admin_object_type_page')){
            $page = $request->getSession()->get('admin_object_type_page');
        }
        
        return $this->render('NticBundle:ObjectType:new.html.twig', array(
            'page' => $page,
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    public function createAction(Request $request)
    {
        $entity  = new ObjectType();
        $form = $this->createForm(new ObjectTypeType(), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            $this->get('session')->setFlash('notice',
                $this->get('translator')->trans('Entry is successfully saved!')
            );
            
            if($entity->getAction() == 'close')
                return $this->redirect($this->generateUrl('admin_object_type'));
            
            return $this->redirect($this->generateUrl('admin_object_type_edit', array('id' => $entity->getId())));
        }

        $this->get('session')->setFlash('error',
            $this->get('translator')->trans('Correct validation errors and try saving again.')
        );
        
        $page = 1;
        if($request->getSession()->has('admin_object_type_page')){
            $page = $request->getSession()->get('admin_object_type_page');
        }
        return $this->render('NticBundle:ObjectType:new.html.twig', array(
            'page' => $page,
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    public function editAction(Request $request, $id)
    {
        $page = 1;
        if($request->getSession()->has('admin_object_type_page')){
            $page = $request->getSession()->get('admin_object_type_page');
        }
        
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('NticBundle:ObjectType')->find($id);

        if (!$entity) {
            $this->get('session')->setFlash('warnin',
                $this->get('translator')->trans('Unable to find entry with id = "%id%".', array('%id%' => $id))
            );
            return $this->redirect($this->generateUrl('admin_object_type', array('page' => $page)));
        }

        $editForm = $this->createForm(new ObjectTypeType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('NticBundle:ObjectType:edit.html.twig', array(
            'page'        => $page,
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    public function updateAction(Request $request, $id)
    {
        $page = 1;
        if($request->getSession()->has('admin_object_type_page')){
            $page = $request->getSession()->get('admin_object_type_page');
        }
        
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('NticBundle:ObjectType')->find($id);

        if (!$entity) {
            $this->get('session')->setFlash('warnin',
                $this->get('translator')->trans('Unable to find entry with id = "%id%".', array('%id%' => $id))
            );
            return $this->redirect($this->generateUrl('admin_object_type', array('page' => $page)));
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new ObjectTypeType(), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            $this->get('session')->setFlash('notice',
                $this->get('translator')->trans('Object Type is successfully updated!')
            );
            
            if($entity->getAction() == 'close')
                return $this->redirect($this->generateUrl('admin_object_type'));
            
            return $this->redirect($this->generateUrl('admin_object_type_edit', array('id' => $entity->getId())));
        }

        $this->get('session')->setFlash('error',
            $this->get('translator')->trans('Correct validation errors and try saving again.')
        );
        
        return $this->render('NticBundle:ObjectType:edit.html.twig', array(
            'page'        => $page,
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    public function deleteAction(Request $request, $id)
    {
        $page = 1;
        if($request->getSession()->has('admin_object_type_page')){
            $page = $request->getSession()->get('admin_object_type_page');
        }
        
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('NticBundle:ObjectType')->find($id);

            if (!$entity) {
                $this->get('session')->setFlash('warnin',
                    $this->get('translator')->trans('Unable to find entry with id = "%id%".', array('%id%' => $id))
                );
                return $this->redirect($this->generateUrl('admin_object_type', array('page' => $page)));
            }

            $em->remove($entity);
            $em->flush();
            
            $this->get('session')->setFlash('notice',
                $this->get('translator')->trans('ObjectType is successfully deleted!')
            );
        }
        else{
            $this->get('session')->setFlash('error',
                $this->get('translator')->trans('Error deleting price.')
            );
        }

        return $this->redirect($this->generateUrl('admin_object_type', array('page' => $page)));
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
