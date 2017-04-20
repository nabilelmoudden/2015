<?php

/* fr_rinalda/Strate_Centrale/fr_rin_vp/default_templateProduct.html */
class __TwigTemplate_a8b3626c213a8207c6e642cb9c5f7417 extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = false;

        $this->blocks = array(
            'content' => array($this, 'block_content'),
        );
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        // line 1
        echo $this->env->getExtension('TwigEsoterExt')->loadCSS(($this->env->getExtension('TwigEsoterExt')->productDir() . "/css/product.css"));
        echo "

<div id=\"main\">
\t<img src=\"";
        // line 4
        echo $this->env->getExtension('TwigEsoterExt')->productDir();
        echo "/images/header.jpg\" width=\"600\" alt=\"Header\" >
\t<div id=\"content\" class=\"content\">
\t\t";
        // line 6
        $this->displayBlock('content', $context, $blocks);
        // line 8
        echo "\t</div>
\t<img src=\"";
        // line 9
        echo $this->env->getExtension('TwigEsoterExt')->productDir();
        echo "/images/footer.jpg\" width=\"600\" alt=\"Footer\" style=\" margin-bottom:-6px;\">
</div>";
    }

    // line 6
    public function block_content($context, array $blocks = array())
    {
        // line 7
        echo "\t\t";
    }

    public function getTemplateName()
    {
        return "fr_rinalda/Strate_Centrale/fr_rin_vp/default_templateProduct.html";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  45 => 7,  42 => 6,  36 => 9,  33 => 8,  31 => 6,  20 => 1,  294 => 223,  289 => 221,  283 => 218,  275 => 213,  268 => 209,  256 => 200,  234 => 181,  204 => 154,  174 => 127,  142 => 98,  102 => 61,  87 => 49,  76 => 41,  61 => 29,  44 => 15,  29 => 4,  26 => 4,);
    }
}
