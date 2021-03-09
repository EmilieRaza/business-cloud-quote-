<?php

namespace App\Controller\Front;

use App\Entity\ContactMessage;
use App\Form\Front\AgentFilterType;
use App\Form\Front\ContactMessageType;
use App\Mailer\Mailer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;


class PageController extends AbstractController
{
    
// -------------------------------------CLIENT-----------------------------------
    /**
     * @Route("/", name="agent_client_liste")
     */
    public function clientListe()
    {
        return $this->render('front/page/index.html.twig', [
            'slug'=>'client_liste',
        ]);
    }

     /**
     * @Route("/client-create", name="agent_client_create")
     */
    public function clientCreate()
    {
        return $this->render('front/page/client/client_create.html.twig', [
            'slug'=>'client_create',
        ]);
    }
    
     /**
     * @Route("/client-update", name="agent_client_update")
     */
    public function clientUpdate()
    {
        return $this->render('front/page/client/client_update.html.twig', [
            'slug'=>'client_update',
        ]);
    }
     /**
     * @Route("/client-read", name="agent_client_read")
     */
    public function clientRead()
    {
        return $this->render('front/page/client/client_read.html.twig', [
            'slug'=>'client_read',
        ]);
    }
// -------------------------------------PROSPECT-----------------------------------

/**
     * @Route("/prospect-liste", name="agent_prospect_liste")
     */
    public function prospectListe()
    {
        return $this->render('front/page/prospect/prospect_liste.html.twig', [
            'slug'=>'prospect_liste',
        ]);
    }

     /**
     * @Route("/prospect-create", name="agent_prospect_create")
     */
    public function prospectCreate()
    {
        return $this->render('front/page/prospect/prospect_create.html.twig', [
            'slug'=>'prospect_create',
        ]);
    }
    
     /**
     * @Route("/prospect-update", name="agent_prospect_update")
     */
    public function prospectUpdate()
    {
        return $this->render('front/page/prospect/prospect_update.html.twig', [
            'slug'=>'prospect_update',
        ]);
    }
     /**
     * @Route("/prospect-read", name="agent_prospect_read")
     */
    public function prospectRead()
    {
        return $this->render('front/page/prospect/prospect_read.html.twig', [
            'slug'=>'prospect_read',
        ]);
    }
    // -------------------------------------ARTICLES-----------------------------------

/**
     * @Route("/article-liste", name="agent_article_liste")
     */
    public function articleListe()
    {
        return $this->render('front/page/article/article_liste.html.twig', [
            'slug'=>'article_liste',
        ]);
    }

     /**
     * @Route("/article-create", name="agent_article_create")
     */
    public function articleCreate()
    {
        return $this->render('front/page/article/article_create.html.twig', [
            'slug'=>'article_create',
        ]);
    }
    
     /**
     * @Route("/article-update", name="agent_article_update")
     */
    public function articleUpdate()
    {
        return $this->render('front/page/article/article_update.html.twig', [
            'slug'=>'article_update',
        ]);
    }
     /**
     * @Route("/article-read", name="agent_article_read")
     */
    public function particleRead()
    {
        return $this->render('front/page/article/article_read.html.twig', [
            'slug'=>'article_read',
        ]);
    }
     // -------------------------------------FACTURES-----------------------------------

/**
     * @Route("/facture-liste", name="agent_facture_liste")
     */
    public function factureListe()
    {
        return $this->render('front/page/facture/facture_liste.html.twig', [
            'slug'=>'facture_liste',
        ]);
    }

     /**
     * @Route("/facture-create", name="agent_facture_create")
     */
    public function factureCreate()
    {
        return $this->render('front/page/facture/facture_create.html.twig', [
            'slug'=>'facture_create',
        ]);
    }
    

     /**
     * @Route("/facture-read", name="agent_facture_read")
     */
    public function factureRead()
    {
        return $this->render('front/page/facture/facture_read.html.twig', [
            'slug'=>'facture_read',
        ]);
    }

     // -------------------------------------DEVIS-----------------------------------

/**
     * @Route("/devis-liste", name="agent_devis_liste")
     */
    public function devisListe()
    {
        return $this->render('front/page/devis/devis_liste.html.twig', [
            'slug'=>'devis_liste',
        ]);
    }

     /**
     * @Route("/devis-create", name="agent_devis_create")
     */
    public function devisCreate()
    {
        return $this->render('front/page/devis/devis_create.html.twig', [
            'slug'=>'devis_create',
        ]);
    }
    

     /**
     * @Route("/devis-read", name="agent_devis_read")
     */
    public function devisRead()
    {
        return $this->render('front/page/devis/devis_read.html.twig', [
            'slug'=>'devis_read',
        ]);
    }
    /**
     * @Route("/devis-update", name="agent_devis_update")
     */
    public function devisUpdate()
    {
        return $this->render('front/page/devis/devis_update.html.twig', [
            'slug'=>'devis_update',
        ]);
    }
    /**
     * @Route("/echeances-clients", name="agent_echeance")
     */
    public function echeance()
    {
        return $this->render('front/page/echeance.html.twig', [
            'slug'=>'echeance',
        ]);
    }

 /**
     * @Route("/messagerie", name="agent_messagerie")
     */
    public function agentMessagerie()
    {
        return $this->render('front/page/messagerie.html.twig', [
            'slug'=>'messagerie',
        ]);
    }

    /**
     * @Route("/factures-impayees", name="agent_factures_impayees")
     */
    public function facturesImpayees()
    {
        return $this->render('front/page/facture/factures_impayees.html.twig', [
            'slug'=>'factures-impayees',
        ]);
    }
    /**
     * @Route("/factures-payees", name="agent_factures_payees")
     */
    public function facturePayees()
    {
        return $this->render('front/page/facture/factures_payees.html.twig', [
            'slug'=>'factures-payees',
        ]);
    }
}
