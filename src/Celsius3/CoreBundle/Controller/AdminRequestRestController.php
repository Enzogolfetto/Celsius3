<?php

namespace Celsius3\CoreBundle\Controller;

use FOS\RestBundle\Controller\Annotations\Route;
use FOS\RestBundle\Controller\Annotations\Get;

/**
 * User controller.
 *
 * @Route("/admin/rest/requests")
 */
class AdminRequestRestController extends BaseInstanceDependentRestController
{

    /**
     * GET Route annotation.
     * @Get("/{order_id}", name="admin_rest_request_get", options={"expose"=true})
     */
    public function getRequestAction($order_id)
    {
        $dm = $this->getDocumentManager();

        $request = $dm->getRepository('Celsius3CoreBundle:Request')
                ->findOneBy(array(
            'order.id' => $order_id,
            'instance.id' => $this->getInstance()->getId(),
        ));

        if (!$request) {
            return $this->createNotFoundException('Request not found.');
        }

        $view = $this->view(array(
                    'data' => $request
                        ), 200)
                ->setFormat('json');

        return $this->handleView($view);
    }

}
