<?php

namespace NNWelcome\NticBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use NNWelcome\NticBundle\Entity\EntityType;
use NNWelcome\NticBundle\Form\EntityTypeType;

/**
 * EntityType controller.
 *
 * @Route("/entitytype")
 */
class EntityTypeController extends Controller
{
    /**
     * Lists all EntityType entities.
     *
     * @Route("/", name="entitytype")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('NticBundle:EntityType')->findAll();

        return array(
            'entities' => $entities,
        );
    }

    /**
     * Finds and displays a EntityType entity.
     *
     * @Route("/{id}/show", name="entitytype_show")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('NticBundle:EntityType')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find EntityType entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to create a new EntityType entity.
     *
     * @Route("/new", name="entitytype_new")
     * @Template()
     */
    public function newAction()
    {
        $entity = new EntityType();
        $form   = $this->createForm(new EntityTypeType(), $entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a new EntityType entity.
     *
     * @Route("/create", name="entitytype_create")
     * @Method("POST")
     * @Template("NticBundle:EntityType:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity  = new EntityType();
        $form = $this->createForm(new EntityTypeType(), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('admin_entity'));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Displays a form to edit an existing EntityType entity.
     *
     * @Route("/{id}/edit", name="entitytype_edit")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('NticBundle:EntityType')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find EntityType entity.');
        }

        $editForm = $this->createForm(new EntityTypeType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing EntityType entity.
     *
     * @Route("/{id}/update", name="entitytype_update")
     * @Method("POST")
     * @Template("NticBundle:EntityType:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('NticBundle:EntityType')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find EntityType entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new EntityTypeType(), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('admin_entity_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a EntityType entity.
     *
     * @Route("/{id}/delete", name="entitytype_delete")
     * @Method("POST")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('NticBundle:EntityType')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find EntityType entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('admin_entity_type'));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
}
