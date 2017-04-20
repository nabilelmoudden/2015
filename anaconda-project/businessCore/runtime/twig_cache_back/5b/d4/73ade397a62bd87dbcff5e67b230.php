<?php

/* fr_rinalda/nml/nml/default_templateProduct.html */
class __TwigTemplate_5bd473ade397a62bd87dbcff5e67b230 extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = false;

        $this->blocks = array(
            'arr' => array($this, 'block_arr'),
            'assen' => array($this, 'block_assen'),
            'content' => array($this, 'block_content'),
        );
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        // line 1
        echo $this->env->getExtension('TwigEsoterExt')->loadCSS(($this->env->getExtension('TwigEsoterExt')->productDir() . "/css/nml.css"));
        echo "
";
        // line 2
        echo $this->env->getExtension('TwigEsoterExt')->loadJS("jquery_easing_scrol_hid.js");
        echo "

<style type=\"text/css\">
";
        // line 5
        $this->displayBlock('arr', $context, $blocks);
        // line 7
        echo "</style>

<div id=\"fl_bouton\">
    <a href=\"#BDC\" onClick=\"document.getElementById('BDC').style.display = 'block'\" >
    ";
        // line 11
        $this->displayBlock('assen', $context, $blocks);
        // line 13
        echo "    </a>
    </div>

<div class=\"main_nml\">
\t<img src=\"";
        // line 17
        echo $this->env->getExtension('TwigEsoterExt')->productDir();
        echo "/images/header.jpg\" width=\"600\" alt=\"Header\" >
\t<div id=\"content\" class=\"content\">
    \t
\t\t";
        // line 20
        $this->displayBlock('content', $context, $blocks);
        // line 23
        echo "\t</div>
\t<img src=\"";
        // line 24
        echo $this->env->getExtension('TwigEsoterExt')->productDir();
        echo "/images/footer.jpg\" width=\"600\" alt=\"Footer\" style=\" margin-bottom:-6px;\">
</div>";
    }

    // line 5
    public function block_arr($context, array $blocks = array())
    {
    }

    // line 11
    public function block_assen($context, array $blocks = array())
    {
        // line 12
        echo "      ";
    }

    // line 20
    public function block_content($context, array $blocks = array())
    {
        // line 21
        echo "        
\t\t";
    }

    public function getTemplateName()
    {
        return "fr_rinalda/nml/nml/default_templateProduct.html";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  80 => 21,  73 => 12,  70 => 11,  65 => 5,  59 => 24,  54 => 20,  42 => 13,  40 => 11,  34 => 7,  32 => 5,  26 => 2,  22 => 1,  254 => 167,  234 => 150,  206 => 125,  197 => 119,  186 => 115,  180 => 112,  174 => 109,  116 => 54,  93 => 34,  87 => 31,  82 => 29,  77 => 20,  67 => 20,  63 => 19,  56 => 23,  51 => 12,  48 => 17,  41 => 9,  38 => 8,  31 => 5,  28 => 4,);
    }
}
