<?php

/* //sav/menu.html */
class __TwigTemplate_032462cd51118217e6e3513b5c8d6df7 extends Twig_Template
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
        echo $this->env->getExtension('TwigEsoterExt')->menuWithPorteur(array(0 => array("label" => "Refund Tool", "url" => array(0 => "/SAV/refundTool"), "visible" => (!$this->getAttribute($this->getAttribute((isset($context["App"]) ? $context["App"] : null), "user"), "isGuest")), "items" => array(0 => array("label" => "All", "url" => array(0 => "/SAV/refundTool")), 1 => array("label" => "Incomplete data client", "url" => array(0 => "/SAV/refundTool", "incomplete" => "true")), 2 => array("label" => "Client to monitor", "url" => array(0 => "/SAV/refundTool", "toMonitor" => "true")))), 1 => array("label" => "Validate Orders", "url" => array(0 => "/SAV/validateOrder"), "visible" => (!$this->getAttribute($this->getAttribute((isset($context["App"]) ? $context["App"] : null), "user"), "isGuest")), "items" => array(0 => array("label" => "Pending", "url" => array(0 => "/SAV/validateOrder")), 1 => array("label" => "Complete", "url" => array(0 => "/SAV/validateOrder", "complete" => "true")))), 2 => array("label" => "Customer Profile", "url" => array(0 => "/SAV/customerProfile"), "visible" => (!$this->getAttribute($this->getAttribute((isset($context["App"]) ? $context["App"] : null), "user"), "isGuest")), "items" => array(0 => array("label" => "All", "url" => array(0 => "/SAV/customerProfile")), 1 => array("label" => "To Monitor", "url" => array(0 => "/SAV/customerProfile", "monitor" => "true"))))));
        // line 25
        echo "

\t";
        // line 27
        if ((!$this->getAttribute($this->getAttribute((isset($context["App"]) ? $context["App"] : null), "user"), "isGuest"))) {
            // line 28
            echo "\t\t<img src=\"";
            echo $this->env->getExtension('TwigEsoterExt')->portDir();
            echo "/images/80.jpg\" />
\t";
        }
        // line 30
        echo "</div>
";
    }

    // line 33
    public function block_container($context, array $blocks = array())
    {
        // line 34
        echo "<div class=\"container\">
\t";
        // line 35
        $this->displayBlock('content', $context, $blocks);
        // line 38
        echo "</div>
";
    }

    // line 35
    public function block_content($context, array $blocks = array())
    {
        // line 36
        echo "\t";
        echo (isset($context["content"]) ? $context["content"] : null);
        echo "
\t";
    }

    public function getTemplateName()
    {
        return "//sav/menu.html";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  72 => 36,  64 => 38,  62 => 35,  56 => 33,  43 => 27,  39 => 25,  33 => 4,  30 => 3,  90 => 38,  88 => 37,  80 => 32,  73 => 28,  69 => 35,  65 => 26,  59 => 34,  55 => 22,  51 => 30,  45 => 28,  41 => 17,  37 => 6,  32 => 13,  29 => 11,  27 => 6,  19 => 1,);
    }
}
