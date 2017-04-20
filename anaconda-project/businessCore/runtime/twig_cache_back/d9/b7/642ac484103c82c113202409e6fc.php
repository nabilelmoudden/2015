<?php

/* en_alisha/nml_2/nml/default_templateProduct.html */
class __TwigTemplate_d9b7642ac484103c82c113202409e6fc extends Twig_Template
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
        echo "
";
        // line 2
        echo $this->env->getExtension('TwigEsoterExt')->loadJS(($this->env->getExtension('TwigEsoterExt')->portViewDir() . "js/temp.js"));
        echo "


";
        // line 5
        echo $this->env->getExtension('TwigEsoterExt')->loadCSS(($this->env->getExtension('TwigEsoterExt')->productDir() . "/css/product.css"));
        echo "

<style type='text/css'>
    ";
        // line 8
        $this->displayBlock('cssbody', $context, $blocks);
        // line 10
        echo "</style>

<div id=\"main_nml\" class=\"main_nml\">
    <img src=\"";
        // line 13
        echo $this->env->getExtension('TwigEsoterExt')->productDir();
        echo "/images/header.jpg\" width=\"600\" alt=\"Header\" >
    <div id=\"content\" class=\"content\">
        ";
        // line 15
        $this->displayBlock('content', $context, $blocks);
        // line 17
        echo "    </div>
    <img src=\"";
        // line 18
        echo $this->env->getExtension('TwigEsoterExt')->productDir();
        echo "/images/footer.jpg\" width=\"600\" alt=\"Footer\" style=\" margin-bottom:-6px;\">
</div>";
    }

    // line 8
    public function block_cssbody($context, array $blocks = array())
    {
        // line 9
        echo "    ";
    }

    // line 15
    public function block_content($context, array $blocks = array())
    {
        // line 16
        echo "        ";
    }

    public function getTemplateName()
    {
        return "en_alisha/nml_2/nml/default_templateProduct.html";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  70 => 16,  67 => 15,  63 => 9,  60 => 8,  54 => 18,  51 => 17,  49 => 15,  44 => 13,  39 => 10,  31 => 5,  25 => 2,  21 => 1,  350 => 289,  330 => 272,  296 => 241,  285 => 233,  277 => 228,  88 => 42,  65 => 22,  57 => 17,  50 => 13,  43 => 9,  40 => 8,  37 => 8,  30 => 4,  27 => 3,);
    }
}
