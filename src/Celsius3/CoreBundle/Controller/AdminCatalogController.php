<?php

namespace Celsius3\CoreBundle\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Celsius3\CoreBundle\Document\Catalog;
use Celsius3\CoreBundle\Form\Type\CatalogType;
use Celsius3\CoreBundle\Filter\Type\CatalogFilterType;

/**
 * Location controller.
 *
 * @Route("/admin/catalog")
 */
class AdminCatalogController extends BaseInstanceDependentController
{

    /**
     * Lists all Catalog documents.
     *
     * @Route("/", name="admin_catalog")
     * @Template()
     *
     * @return array
     */
    public function indexAction()
    {
        return $this
                ->baseIndex('Catalog',
                        $this
                                ->createForm(
                                        new CatalogFilterType(
                                                $this->getInstance())));
    }

    /**
     * Displays a form to create a new Catalog document.
     *
     * @Route("/new", name="admin_catalog_new")
     * @Template()
     *
     * @return array
     */
    public function newAction()
    {
        return $this
                ->baseNew('Catalog', new Catalog(),
                        new CatalogType($this->getInstance()));
    }

    /**
     * Creates a new Catalog document.
     *
     * @Route("/create", name="admin_catalog_create")
     * @Method("post")
     * @Template("Celsius3CoreBundle:AdminCatalog:new.html.twig")
     *
     * @return array
     */
    public function createAction()
    {
        return $this
                ->baseCreate('Catalog', new Catalog(),
                        new CatalogType($this->getInstance()), 'admin_catalog');
    }

    /**
     * Displays a form to edit an existing Catalog document.
     *
     * @Route("/{id}/edit", name="admin_catalog_edit")
     * @Template()
     * @param string $id The document ID
     *
     * @return array
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException If document doesn't exists
     */
    public function editAction($id)
    {
        return $this
                ->baseEdit('Catalog', $id,
                        new CatalogType($this->getInstance()));
    }

    /**
     * Edits an existing Catalog document.
     *
     * @Route("/{id}/update", name="admin_catalog_update")
     * @Method("post")
     * @Template("Celsius3CoreBundle:AdminCatalog:edit.html.twig")
     *
     * @param string $id The document ID
     *
     * @return array
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException If document doesn't exists
     */
    public function updateAction($id)
    {
        return $this
                ->baseUpdate('Catalog', $id,
                        new CatalogType($this->getInstance()), 'admin_catalog');
    }

    /**
     * Deletes a Catalog document.
     *
     * @Route("/{id}/delete", name="admin_catalog_delete")
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
        return $this->baseDelete('Catalog', $id, 'admin_catalog');
    }

}