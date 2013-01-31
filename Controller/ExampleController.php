<?php

namespace Scan\Bundle\KssBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Scan\Kss\Parser;

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
     * @Route("/{reference}/{title}", requirements={"reference" = "(\d+\.?)+"})
     * @Template
     */
    public function referenceAction($reference, $title = null)
    {
        $parser = $this->getKssParser();
        $section = $parser->getSection($reference);
        if (!$section->getReference()) {
            throw $this->createNotFoundException('Reference ' . $reference . ' does not exist in the Styleguide!');
        }
        $sections = $parser->getSectionChildren($section->getReference());

        return array(
            'section' => $section,
            'sections' => $sections
        );
    }

    /**
     * @Route("/fragment/menu")
     * @Template
     */
    public function menuAction()
    {
        $links = array();
        $links[] = array(
            'name' => 'Home',
            'title' => '',
            'url' => $this->generateUrl('scan_kss_example_index'),
        );

        $parser = $this->getKssParser();
        $sections = $parser->getTopLevelSections();
        foreach ($sections as $section) {
            $link = array(
                'name' => $section->getReference() . '. ' . $section->getTitle(),
                'title' => $section->getDescription(),
                'url' => $this->generateUrl(
                    'scan_kss_example_reference',
                    array(
                        'reference' => $section->getReference(),
                        'title' => strtolower(preg_replace('/\W+/', '-', $section->getTitle())),
                    )
                ),
            );
            $links[] = $link;
        }

        return array(
            'links' => $links,
        );
    }

    /**
     * Returns a KSS Parser loaded with the CSS files from the bundle
     *
     * @return Parser
     */
    protected function getKssParser()
    {
        return new Parser(__DIR__ . '/../Resources/public/css');
    }
}
