<?php

namespace Celsius3\CoreBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Celsius3\CoreBundle\Document\Institution;
use Celsius3\CoreBundle\Form\Type\InstitutionType;
use Celsius3\CoreBundle\Filter\Type\InstitutionFilterType;

/**
 * Location controller.
 *
 * @Route("/admin/institution")
 */
class AdminInstitutionController extends BaseInstanceDependentController
{

    /**
     * Lists all Institution documents.
     *
     * @Route("/", name="admin_institution")
     * @Template()
     *
     * @return array
     */
    public function indexAction()
    {
        return $this->baseIndex('Institution', $this->createForm(new InstitutionFilterType($this->getInstance())));
    }

    /**
     * Displays a form to create a new Institution document.
     *
     * @Route("/new", name="admin_institution_new")
     * @Template()
     *
     * @return array
     */
    public function newAction()
    {
        return $this->baseNew('Institution', new Institution(), new InstitutionType($this->getDocumentManager(), $this->getInstance()));
    }

    /**
     * Creates a new Institution document.
     *
     * @Route("/create", name="admin_institution_create")
     * @Method("post")
     * @Template("Celsius3CoreBundle:AdminInstitution:new.html.twig")
     *
     * @return array
     */
    public function createAction()
    {
        return $this->baseCreate('Institution', new Institution(), new InstitutionType($this->getDocumentManager(), $this->getInstance()), 'admin_institution');
    }

    /**
     * Displays a form to edit an existing Institution document.
     *
     * @Route("/{id}/edit", name="admin_institution_edit")
     * @Template()
     * @param string $id The document ID
     *
     * @return array
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException If document doesn't exists
     */
    public function editAction($id)
    {
        return $this->baseEdit('Institution', $id, new InstitutionType($this->getDocumentManager(), $this->getInstance()));
    }

    /**
     * Edits an existing Institution document.
     *
     * @Route("/{id}/update", name="admin_institution_update")
     * @Method("post")
     * @Template("Celsius3CoreBundle:AdminInstitution:edit.html.twig")
     *
     * @param string $id The document ID
     *
     * @return array
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException If document doesn't exists
     */
    public function updateAction($id)
    {
        return $this->baseUpdate('Institution', $id, new InstitutionType($this->getDocumentManager(), $this->getInstance()), 'admin_institution');
    }

    /**
     * Deletes a Institution document.
     *
     * @Route("/{id}/delete", name="admin_institution_delete")
     * @Method("post")
     *
     * @param string $id The document ID
     *
     * @return array
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException If document doesn't exists
     */
    public function deleteAction($id)
    {
        return $this->baseDelete('Institution', $id, 'admin_institution');
    }

}
