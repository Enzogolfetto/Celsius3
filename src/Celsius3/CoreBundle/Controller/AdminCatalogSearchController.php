<?php

namespace Celsius3\CoreBundle\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Celsius3\CoreBundle\Document\CatalogSearch;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * CatalogSearch controller.
 *
 * @Route("/admin/catalogsearch")
 */
class AdminCatalogSearchController extends BaseInstanceDependentController
{
    /**
     * @Route("/", name="admin_catalog_search_mark", options={"expose"=true})
     * @Template()
     *
     * @return array
     */
    public function markAction()
    {
        $order = $this->getDocumentManager()
                ->getRepository('Celsius3CoreBundle:Order')
                ->find($this->getRequest()->query->get('order_id'));

        if (!$order) {
            return $this->createNotFoundException('Order not found.');
        }

        $catalog = $this->getDocumentManager()
                ->getRepository('Celsius3CoreBundle:Catalog')
                ->find($this->getRequest()->query->get('catalog_id'));

        if (!$catalog) {
            return $this->createNotFoundException('Catalog not found.');
        }

        $instance = $this->getDocumentManager()
                ->getRepository('Celsius3CoreBundle:Instance')
                ->find($this->getRequest()->query->get('instance_id'));

        if (!$instance) {
            return $this->createNotFoundException('Instance not found.');
        }

        if (!$this->getRequest()->query->has('result')) {
            return $this->createNotFoundException('Result not found.');
        }

        $document = $this->getDocumentManager()
                ->getRepository('Celsius3CoreBundle:CatalogSearch')
                ->findOneBy(
                        array('catalog.id' => $catalog->getId(),
                                'order.id' => $order->getId(),
                                'instance.id' => $instance->getId(),));

        if (!$document) {
            $document = new CatalogSearch();
            $document->setCatalog($catalog);
            $document->setOrder($order);
            $document->setInstance($instance);
        }

        $document->setAdmin($this->getUser());
        $document->setDate(date('Y-m-d H:i:s'));
        $document->setResult($this->getRequest()->query->get('result'));

        $this->getDocumentManager()->persist($document);
        $this->getDocumentManager()->flush();

        $response = new JsonResponse();
        $response->setData(array('date' => $document->getDate(),));

        return $response;
    }
}