<?php

/* fr_rinalda/nml_2/nml/default_templateProduct.html */
class __TwigTemplate_b262a4b7f11f1f2ccae26224d8ff1824 extends Twig_Template
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
        return "fr_rinalda/nml_2/nml/default_templateProduct.html";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  70 => 16,  67 => 15,  63 => 9,  60 => 8,  54 => 18,  51 => 17,  49 => 15,  44 => 13,  39 => 10,  37 => 8,  31 => 5,  25 => 2,  21 => 1,);
    }
}
