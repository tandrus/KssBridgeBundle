<?php

namespace Scan\Bundle\KssBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Scan\Bundle\KssBundle\Model\Parser;

class ExampleController extends Controller
{
    /**
     * @Route("/")
     * @Template
     */
    public function indexAction()
    {
        return array();
    }

    /**
     * @Route("/styleguide")
     * @Template
     */
    public function styleguideAction()
    {
        $kss = new Parser($this->getRequest()->server->get('DOCUMENT_ROOT') . '/bundles/scankss/css');
        return array(
            'kss' => $kss,
        );
    }
}
