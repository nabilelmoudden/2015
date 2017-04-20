<?php

/* //fr_rinalda/tkt/rin_tkt/test.html */
class __TwigTemplate_ca1ac88ae6098ddc0cd167f8819b4e8a extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = false;

        $this->blocks = array(
            'cssbody' => array($this, 'block_cssbody'),
            'content' => array($this, 'block_content'),
        );
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        // line 1
        echo $this->env->getExtension('TwigEsoterExt')->loadCSS(($this->env->getExtension('TwigEsoterExt')->portViewDir() . "/css/global.css"));
        echo " ";
        echo $this->env->getExtension('TwigEsoterExt')->loadJS(($this->env->getExtension('TwigEsoterExt')->portViewDir() . "js/temp.js"));
        echo " ";
        echo $this->env->getExtension('TwigEsoterExt')->loadCSS(($this->env->getExtension('TwigEsoterExt')->productDir() . "/css/product.css"));
        echo "
<style type=\"text/css\">";
        // line 2
        $this->displayBlock('cssbody', $context, $blocks);
        // line 5
        echo "</style>
<div class=\"main_nml\" id=\"main_nml\"><img alt=\"Header\" src=\"";
        // line 6
        echo $this->env->getExtension('TwigEsoterExt')->productDir();
        echo "/images/header.jpg\" width=\"600\" />
<div class=\"content\" id=\"content\">";
        // line 7
        $this->displayBlock('content', $context, $blocks);
        echo "</div>
<img alt=\"Footer\" src=\"";
        // line 8
        echo $this->env->getExtension('TwigEsoterExt')->productDir();
        echo "/images/footer.jpg\" style=\" margin-bottom:-6px;\" width=\"600\" /></div>
";
    }

    // line 2
    public function block_cssbody($context, array $blocks = array())
    {
        // line 3
        echo "
    ";
    }

    // line 7
    public function block_content($context, array $blocks = array())
    {
        echo (isset($context["BDC"]) ? $context["BDC"] : null);
    }

    public function getTemplateName()
    {
        return "//fr_rinalda/tkt/rin_tkt/test.html";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  56 => 7,  51 => 3,  48 => 2,  42 => 8,  38 => 7,  34 => 6,  31 => 5,  29 => 2,  21 => 1,);
    }
}
