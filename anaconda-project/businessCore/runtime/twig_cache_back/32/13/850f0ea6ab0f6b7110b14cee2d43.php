<?php

/* backoffice.html */
class __TwigTemplate_3213850f0ea6ab0f6b7110b14cee2d43 extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = false;

        $this->blocks = array(
            'menu' => array($this, 'block_menu'),
            'container' => array($this, 'block_container'),
        );
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        // line 1
        echo "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">

<html xmlns=\"http://www.w3.org/1999/xhtml\" xml:lang=\"fr\" lang=\"fr\">
    <head>
\t\t<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />
\t\t<meta name=\"language\" content=\"fr\" />

\t\t<title>";
        // line 8
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["this"]) ? $context["this"] : null), "pageTitle"));
        echo "</title>
    </head>
    <body>
\t\t<div id=\"page\">

\t\t\t<!-- Menu -->
\t\t\t";
        // line 14
        $this->displayBlock('menu', $context, $blocks);
        // line 16
        echo "
\t\t\t<!-- content -->
\t\t\t";
        // line 18
        $this->displayBlock('container', $context, $blocks);
        // line 20
        echo "
\t\t</div>
    </body>
</html>";
    }

    // line 14
    public function block_menu($context, array $blocks = array())
    {
        // line 15
        echo "\t\t\t";
    }

    // line 18
    public function block_container($context, array $blocks = array())
    {
        // line 19
        echo "\t\t\t";
    }

    public function getTemplateName()
    {
        return "backoffice.html";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  61 => 18,  57 => 15,  54 => 14,  47 => 20,  41 => 16,  21 => 1,  72 => 22,  69 => 21,  64 => 19,  62 => 21,  59 => 20,  56 => 19,  51 => 16,  45 => 18,  43 => 13,  39 => 14,  37 => 6,  30 => 8,  42 => 69,  40 => 13,  35 => 10,  33 => 4,  27 => 4,  22 => 2,  19 => 1,);
    }
}
