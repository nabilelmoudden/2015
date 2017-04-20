<?php

/* //ap/menu.html */
class __TwigTemplate_3efeb4bd8a4485672b7e588ebbbdb3ca extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = $this->env->loadTemplate("backoffice.html");

        $this->blocks = array(
            'menu' => array($this, 'block_menu'),
            'container' => array($this, 'block_container'),
            'content' => array($this, 'block_content'),
        );
    }

    protected function doGetParent(array $context)
    {
        return "backoffice.html";
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        $this->parent->display($context, array_merge($this->blocks, $blocks));
    }

    // line 3
    public function block_menu($context, array $blocks = array())
    {
        // line 4
        echo "<!-- mainmenu -->
<div class=\"divMenu\">
\t";
        // line 6
        echo $this->env->getExtension('TwigEsoterExt')->menuWithPorteur(array(0 => array("label" => "Affiliate Platform", "url" => array(0 => "/AP/affiliatePlatform"), "visible" => (!$this->getAttribute($this->getAttribute((isset($context["App"]) ? $context["App"] : null), "user"), "isGuest"))), 1 => array("label" => "Campaign", "url" => array(0 => "/AP/campaign"), "visible" => (!$this->getAttribute($this->getAttribute((isset($context["App"]) ? $context["App"] : null), "user"), "isGuest"))), 2 => array("label" => "Promo Site", "url" => array(0 => "/AP/promoSite"), "visible" => (!$this->getAttribute($this->getAttribute((isset($context["App"]) ? $context["App"] : null), "user"), "isGuest"))), 3 => array("label" => "Hard/Soft Bounce", "url" => array(0 => "/AP/HSBounce"), "visible" => (!$this->getAttribute($this->getAttribute((isset($context["App"]) ? $context["App"] : null), "user"), "isGuest"))), 4 => array("label" => "Config", "url" => array(0 => "/AP/ConfigPorteur"), "visible" => (!$this->getAttribute($this->getAttribute((isset($context["App"]) ? $context["App"] : null), "user"), "isGuest"))), 5 => array("label" => "Mode Test", "url" => array(0 => "/AP/setTestMode"), "visible" => (!$this->getAttribute($this->getAttribute((isset($context["App"]) ? $context["App"] : null), "user"), "isGuest")))));
        // line 17
        echo "

\t";
        // line 19
        if ((!$this->getAttribute($this->getAttribute((isset($context["App"]) ? $context["App"] : null), "user"), "isGuest"))) {
            // line 20
            echo "\t\t<img src=\"";
            echo $this->env->getExtension('TwigEsoterExt')->portDir();
            echo "/images/80.jpg\" />
\t";
        }
        // line 22
        echo "</div>
";
    }

    // line 25
    public function block_container($context, array $blocks = array())
    {
        // line 26
        echo "<div class=\"container\">
\t";
        // line 27
        $this->displayBlock('content', $context, $blocks);
        // line 30
        echo "</div>
";
    }

    // line 27
    public function block_content($context, array $blocks = array())
    {
        // line 28
        echo "\t";
        echo (isset($context["content"]) ? $context["content"] : null);
        echo "
\t";
    }

    public function getTemplateName()
    {
        return "//ap/menu.html";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  72 => 28,  69 => 27,  64 => 30,  62 => 27,  59 => 26,  56 => 25,  51 => 22,  45 => 20,  43 => 19,  39 => 17,  37 => 6,  30 => 3,  40 => 13,  35 => 10,  33 => 4,  27 => 4,  22 => 2,  19 => 1,);
    }
}
