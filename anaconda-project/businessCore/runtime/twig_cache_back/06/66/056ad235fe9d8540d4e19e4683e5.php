<?php

/* fr_rinalda/tkt/rin_tkt/default_templateProduct.html */
class __TwigTemplate_0666056ad235fe9d8540d4e19e4683e5 extends Twig_Template
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
    }

    public function getTemplateName()
    {
        return "fr_rinalda/tkt/rin_tkt/default_templateProduct.html";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  56 => 7,  51 => 3,  48 => 2,  42 => 8,  38 => 7,  34 => 6,  31 => 5,  21 => 1,  278 => 205,  274 => 204,  268 => 201,  260 => 196,  253 => 192,  240 => 182,  219 => 164,  189 => 137,  157 => 108,  123 => 77,  98 => 55,  82 => 42,  71 => 34,  58 => 24,  45 => 14,  29 => 2,  26 => 1,);
    }
}
