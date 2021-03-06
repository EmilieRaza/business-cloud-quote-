<?php

namespace App\Manager\Front;

use App\Entity\User;
use App\Form\Front\AgentType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\Exception\LogicException;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\Routing\Exception\InvalidParameterException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * 
 */
class AgentManager
{
    const NUMBER_BY_PAGE = 15;
    
    /**
     * @var RequestStack 
     */
    private $requestStack;
    
    /**
     * @var SessionInterface
     */
    private $session;
    
    /**
     * @var EntityManagerInterface
     */
    private $em;
    
    /**
     * @var UrlGeneratorInterface 
     */
    private $urlGenerator;
    
    /**
     * @var TranslatorInterface
     */
    private $translator;
    
    /** 
     * @param RequestStack $requestStack
     * @param SessionInterface $session
     * @param EntityManagerInterface $em
     * @param UrlGeneratorInterface $urlGenerator
     * @param TranslatorInterface $translator
     */
    public function __construct(RequestStack $requestStack,
        SessionInterface $session,
        EntityManagerInterface $em,
        UrlGeneratorInterface $urlGenerator,
        TranslatorInterface $translator
    ) {
        $this->requestStack = $requestStack;
        $this->session = $session;
        $this->em = $em;
        $this->urlGenerator = $urlGenerator;
        $this->translator = $translator;
    }
    
    /**
     * Configure the filter form
     * 
     *  Set the filter's default fields, save and retrieve the last searche in session.
     *  
     * @param FormInterface $form
     * @return \Symfony\Component\Form\FormInterface
     */
    public function configFormFilter(FormInterface $form)
    {
        $request = $this->requestStack->getCurrentRequest();
        $page = $request->get('page');
        if (!$page) { $page = $this->session->get('front_agent_page', 1); }
        $this->session->set('front_agent_page', $page);
        if($request->isMethod('POST') && $request->query->get('front_agent_search')) {
            $form->submit($request->query->get('front_agent_search'));
        } elseif(!$form->getData()) {
            $form->setData($this->getDefaultFormSearchData());
        }
        if ($form->isSubmitted() && $form->isValid()) {
            $this->session->set('front_agent_search', $form->get('search')->getData());
            $this->session->set('front_agent_number_by_page', $form->get('number_by_page')->getData());
        }
        return $form;
    }
    
    /**
     * Get the default data from the filter form
     * 
     *  Get saved data in session or default filter form.
     *  
     * @return array
     */
    public function getDefaultFormSearchData()
    {
        return [ 
            'search' => $this->session->get('front_agent_search', null),
            'number_by_page' => $this->session->get('front_agent_number_by_page', self::NUMBER_BY_PAGE),
        ];
    }

    /**
     * Get query data
     * 
     *  Transform filter form data into an array compatible with url parameters.
     *  The returned array must be merged with the parameters of the route.
     * @param array $data
     * @return array
     */
    public function getQueryData(array $data)
    {
        $queryData['filter'] = [];
        foreach ($data as $key => $value) {
            if (null === $value) {
                $queryData['filter'][$key] = '';
            } else {
                $queryData['filter'][$key] = $value;
            }
        }
        return $queryData;
    }
    
}
