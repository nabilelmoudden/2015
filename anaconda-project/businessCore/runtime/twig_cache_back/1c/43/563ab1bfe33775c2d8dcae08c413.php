<?php

/* //common/redirectPaymentProcessor.html */
class __TwigTemplate_1c43563ab1bfe33775c2d8dcae08c413 extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = false;

        $this->blocks = array(
        );
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        // line 1
        echo "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">
<html xmlns=\"http://www.w3.org/1999/xhtml\" xml:lang=\"fr\" lang=\"fr\">
\t<head>
\t\t<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />
\t\t<meta name=\"language\" content=\"fr\" />
\t\t<meta name=\"robots\" content=\"noindex, nofollow, noarchive\" />

\t\t<style type=\"text/css\">
\t\t\tbody{
\t\t\t\tpadding:100px;
\t\t\t}
\t\t\t.div1{
\t\t\t\twidth:700px;
\t\t\t\theight:500px;
\t\t\t\tfont-family: monospace;
\t\t\t\tcolor:black;
\t\t\t\tfont-size: 13px;
\t\t\t\ttext-align:center;
\t\t\t}
\t\t</style>
\t</head>
\t<body>
\t\t<form action=\"";
        // line 23
        echo (isset($context["url"]) ? $context["url"] : null);
        echo "\" method=\"post\" name=\"paymentForm\">

\t\t\t";
        // line 25
        $context['_parent'] = (array) $context;
        $context['_seq'] = twig_ensure_traversable((isset($context["GET"]) ? $context["GET"] : null));
        foreach ($context['_seq'] as $context["name"] => $context["value"]) {
            // line 26
            echo "\t\t\t\t<input type=\"hidden\" name=\"";
            echo (isset($context["name"]) ? $context["name"] : null);
            echo "\" value=\"";
            echo (isset($context["value"]) ? $context["value"] : null);
            echo "\" />
\t\t\t";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['name'], $context['value'], $context['_parent'], $context['loop']);
        $context = array_merge($_parent, array_intersect_key($context, $_parent));
        // line 28
        echo "
\t\t\t<div class=\"div1\">
\t\t\t\tYou will be, automatically, redirected in 3 seconds.<br/><br/>

\t\t\t\t<!--img src=\"progressbar.gif\" border=\"0\" align=\"center\"/><br/><br/ -->

\t\t\t\tIf not, follow this link : <a href=\"javascript:document.forms['paymentForm'].submit()\" alt=\"Redirection to register form\">Redirect</a>
\t\t\t</div>

\t\t</form>

\t\t<script type=\"text/javascript\">
\t\t\tdocument.forms[\"paymentForm\"].submit();
\t\t</script>

\t</body>
</html>";
    }

    public function getTemplateName()
    {
        return "//common/redirectPaymentProcessor.html";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  63 => 28,  52 => 26,  48 => 25,  43 => 23,  19 => 1,);
    }
}
