<?php

/*
 * Celsius3 - Order management
 * Copyright (C) 2014 PrEBi <info@prebi.unlp.edu.ar>
 *
 * This file is part of Celsius3.
 *
 * Celsius3 is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Celsius3 is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Celsius3. If not, see <http://www.gnu.org/licenses/>.
 */

namespace Celsius3\CoreBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations\Route;
use FOS\RestBundle\Controller\Annotations\Get;
use JMS\Serializer\SerializationContext;

/**
 * User controller.
 *
 * @Route("/public/rest")
 */
class PublicRestController extends BaseInstanceDependentRestController
{

    /**
     * GET Route annotation.
     * @Get("/years_interval", name="public_rest_get_users_count_data_for_interval", options={"expose"=true})
     */
    public function getUsersCountDataForInterval(Request $request)
    {
        $instance = $request->query->get('instance');
        $initialYear = $request->query->get('initialYear');
        $finalYear = $request->query->get('finalYear');
        $newUsers = $this->getDoctrine()->getManager()->getRepository('Celsius3CoreBundle:BaseUser')->countNewUsersForInterval($instance, $initialYear, $finalYear);
        $activeUsers = $this->getDoctrine()->getManager()->getRepository('Celsius3CoreBundle:Request')->countActiveUsersForInterval($instance, $initialYear, $finalYear);
        $result = array();
        $suma = 0;
        foreach ($newUsers as $count) {
            $result[$count['year']]['newUsers'] = $count['newUsers'];
            $result[$count['year']]['totalUsers'] = $suma += $count['newUsers'];
        }
        foreach ($activeUsers as $count) {
            $result[$count['year']]['activeUsers'] = $count['activeUsers'];
        }
        ksort($result, SORT_NUMERIC);
        $values = array();
        $values['newUsers'][] = 'New Users';
        $values['activeUsers'][] = 'Active Users';
        $values['totalUsers'][] = 'Total Users';
        foreach ($result as $year => $count) {
            $values['categories'][] = $year;
            $values['newUsers'][] = (isset($count['newUsers'])) ? $count['newUsers'] : 0;
            $values['activeUsers'][] = (isset($count['activeUsers'])) ? $count['activeUsers'] : 0;
            $values['totalUsers'][] = (isset($count['totalUsers'])) ? $count['totalUsers'] : 0;
        }
        $view = $this->view($values, 200)->setFormat('json');
        return $this->handleView($view);
    }

    /**
     * GET Route annotation.
     * @Get("/year", name="public_rest_get_users_count_data_for_year", options={"expose"=true})
     */
    public function getUsersCountDataForYear(Request $request)
    {
        $instance = $request->query->get('instance');
        $year = $request->query->get('year');
        $newUsers = $this->getDoctrine()->getManager()->getRepository('Celsius3CoreBundle:BaseUser')->countNewUsersForYear($instance, $year);
        $activeUsers = $this->getDoctrine()->getManager()->getRepository('Celsius3CoreBundle:Request')->countActiveUsersForYear($instance, $year);
        $result = array();
        $suma = 0;
        foreach ($newUsers as $count) {
            $result[$count['month']]['newUsers'] = $count['newUsers'];
            $result[$count['month']]['totalUsers'] = $suma += $count['newUsers'];
        }
        foreach ($activeUsers as $count) {
            $result[$count['month']]['activeUsers'] = $count['activeUsers'];
        }
        ksort($result);
        $values = array();
        $values['newUsers'][] = 'New Users';
        $values['activeUsers'][] = 'Active Users';
        $values['totalUsers'][] = 'Total Users';
        foreach ($result as $month => $count) {
            $values['categories'][] = \DateTime::createFromFormat('!m', $month)->format('F');
            $values['newUsers'][] = (isset($count['newUsers'])) ? $count['newUsers'] : 0;
            $values['activeUsers'][] = (isset($count['activeUsers'])) ? $count['activeUsers'] : 0;
            $values['totalUsers'][] = (isset($count['totalUsers'])) ? $count['totalUsers'] : 0;
        }
        $view = $this->view($values, 200)->setFormat('json');
        return $this->handleView($view);
    }

    /**
     * GET Route annotation.
     * @Get("/years", name="public_rest_get_years_data", options={"expose"=true})
     */
    public function getYearsData(Request $request)
    {
        $instance = $request->query->get('instance');
        $userYears = $this->getDoctrine()->getManager()->getRepository('Celsius3CoreBundle:BaseUser')->getYears($instance);
        $requestYears = $this->getDoctrine()->getManager()->getRepository('Celsius3CoreBundle:Request')->getYears($instance);
        foreach ($userYears as $year) {
            $data[] = $year['year'];
        }
        foreach ($requestYears as $year) {
            $data[] = $year['year'];
        }
        $data = array_unique($data);
        sort($data);
        $view = $this->view($data, 200)->setFormat('json');
        return $this->handleView($view);
    }

    /**
     * GET Route annotation.
     * @Get("/", name="public_rest_get_requests_origin_data", options={"expose"=true})
     */
    public function getOriginRequestsCountData(Request $request)
    {
        $instance = $request->query->get('instance');
        $type = $request->query->get('type');
        $country = $request->query->get('country');
        $institution = $request->query->get('institution');
        $requestRepository = $this->getDoctrine()->getManager()->getRepository('Celsius3CoreBundle:Institution');
        $counts = $requestRepository->countRequestsOrigin($instance, $type, $country, $institution);
        uasort($counts, function($a, $b) {
            if ($a['requestsCount'] == $b['requestsCount']) {
                return 0;
            }
            return ($a['requestsCount'] > $b['requestsCount']) ? -1 : 1;
        });
        $data = array();
        $data['requestsCount'][] = 'Requests';
        $i = 0;
        while ($i < 10) {
            list(, $count) = each($counts);
            $data['requestsCount'][] = $count['requestsCount'];
            $data['countries'][] = (Integer) $count['institutionCountry'];
            $data['categories'][] = $count['name'];
            $data['ids'][] = (Integer) $count['id'];
            $i++;
        }
        $view = $this->view($data, 200)->setFormat('json');
        return $this->handleView($view);
    }

    /**
     * GET Route annotation.
     * @Get("/requests_count_for_interval", name="public_rest_get_requests_count_data_for_interval", options={"expose"=true})
     */
    public function getRequestsCountDataForInterval(Request $request)
    {
        $instance = $request->query->get('instance');
                
        $initialYear = $request->query->get('initialYear');
        $finalYear = $request->query->get('finalYear');
        
        $result = $this->getDoctrine()->getManager()
                ->getRepository('Celsius3CoreBundle:State')
                ->findRequestsStateCountForInterval($instance,$initialYear,$finalYear);
        
        $values = array();
        foreach ($result as $count) {
            $values[$count['year']][$count['stateType']][] = $count['requestsCount'];
            if(isset($values[$count['year']]['totalPages'])){
                $values[$count['year']]['totalPages'] += $count['totalPages'];
            } else {
                $values[$count['year']]['totalPages'] = $count['totalPages'];
            }
        }
        
        ksort($values);
        
        $data = array();
        $data['created'][] = 'Created';
        $data['cancelled'][] = 'Cancelled';
        $data['delivered'][] = 'Delivered';
        $data['totalPages'][] = 'Total Pages';
        foreach ($values as $key => $val){
            $data['categories'][] = $key;
            $data['created'][] = $val['created'];
            $data['cancelled'][] = $val['cancelled'];
            $data['delivered'][] = $val['delivered'];
            $data['totalPages'][] = $val['totalPages'];
        }
        
        $view = $this->view($data, 200)->setFormat('json');
        return $this->handleView($view);
    }

    /**
     * GET Route annotation.
     * @Get("/requests_count_for_year", name="public_rest_get_requests_count_data_for_year", options={"expose"=true})
     */
    public function getRequestsCountDataForYear(Request $request)
    {
        $instance = $request->query->get('instance');
        $year = $request->query->get('year');
        
        $result = $this->getDoctrine()->getManager()->getRepository('Celsius3CoreBundle:State')->findRequestsStateCountForYear($instance,$year);

        $values = array();
        $values['created'][] = 'Created';
        $values['cancelled'][] = 'Cancelled';
        $values['delivered'][] = 'Delivered';
        foreach ($result as $count) {
            $values['categories'][] = $count['year'];
            $values[$count['stateType']][] = $count['requestsCount'];
        }
        
        $view = $this->view($values, 200)->setFormat('json');
        return $this->handleView($view);
    }
    
    
    
    /**
     * GET Route annotation.
     * @Get("/requests_destiny_distribution_for_interval", name="public_rest_get_requests_destiny_distribution_data_for_interval", options={"expose"=true})
     */
    public function getRequestsDestinyDistributionDataForInterval(Request $request){
        $instance = $request->query->get('instance');
        $initialYear = $request->query->get('initialYear');
        $finalYear = $request->query->get('finalYear');
        $type = $request->query->get('type');
        
        $result = $this->getDoctrine()->getManager()
                ->getRepository('Celsius3CoreBundle:State')
                ->findRequestsDestinyDistributionForInterval($instance,$type,$initialYear,$finalYear);
        
        $values = array();
        foreach ($result as $count) {
            $values[$count['countryName']][$count['stateType']][] = $count['requestsCount'];
        }
        
        ksort($values);
        
        $data = array();
        $data['created'][] = 'Created';
        $data['cancelled'][] = 'Cancelled';
        $data['delivered'][] = 'Delivered';
        foreach ($values as $key => $val){
            $data['categories'][] = $key;
            $data['created'][] = (isset($val['created'])) ? $val['created'] : 0;
            $data['cancelled'][] = (isset($val['cancelled'])) ? $val['cancelled'] : 0;
            $data['delivered'][] = (isset($val['delivered'])) ? $val['delivered'] : 0;
        }
        
        $view = $this->view($data, 200)->setFormat('json');
        return $this->handleView($view);
    }
}
