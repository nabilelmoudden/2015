<?php

/* //contentManager/menu.html */
class __TwigTemplate_0e443158ce164457eb2872964a42b729 extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = $this->env->loadTemplate("views/backoffice.html");

        $this->blocks = array(
            'menu' => array($this, 'block_menu'),
            'container' => array($this, 'block_container'),
            'content' => array($this, 'block_content'),
        );
    }

    protected function doGetParent(array $context)
    {
        return "views/backoffice.html";
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
        $this->getAttribute((isset($context["this"]) ? $context["this"] : null), "widget", array(0 => "zii.widgets.CMenu", 1 => array("items" => array(0 => array("label" => "Login", "url" => array(0 => "/Login/login"), "visible" => $this->getAttribute($this->getAttribute((isset($context["App"]) ? $context["App"] : null), "user"), "isGuest")), 1 => array("label" => (("Logout (" . $this->getAttribute($this->getAttribute((isset($context["App"]) ? $context["App"] : null), "user"), "name")) . ")"), "url" => array(0 => "/Login/logout"), "visible" => (!$this->getAttribute($this->getAttribute((isset($context["App"]) ? $context["App"] : null), "user"), "isGuest")))))), "method");
        // line 12
        echo "</div>
";
    }

    // line 15
    public function block_container($context, array $blocks = array())
    {
        // line 16
        echo "<div class=\"container\">
\t";
        // line 17
        $this->displayBlock('content', $context, $blocks);
        // line 20
        echo "</div>
";
    }

    // line 17
    public function block_content($context, array $blocks = array())
    {
        // line 18
        echo "\t";
        echo (isset($context["content"]) ? $context["content"] : null);
        echo "
\t";
    }

    public function getTemplateName()
    {
        return "//contentManager/menu.html";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  60 => 18,  57 => 17,  52 => 20,  50 => 17,  47 => 16,  44 => 15,  39 => 12,  37 => 6,  33 => 4,  30 => 3,  89 => 34,  82 => 30,  70 => 20,  63 => 18,  51 => 16,  46 => 15,  40 => 13,  36 => 12,  27 => 6,  24 => 5,  22 => 2,  19 => 1,);
    }
}
