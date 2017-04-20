<?php

/* //sav/login.html */
class __TwigTemplate_213b54cc67bf52ae3203bde64cee702e extends Twig_Template
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
        echo $this->env->getExtension('TwigEsoterExt')->pageTitle("SAV - Login");
        echo "

<h1>Login</h1>

<div class=\"form\">
\t";
        // line 6
        $context["form"] = $this->getAttribute((isset($context["this"]) ? $context["this"] : null), "beginWidget", array(0 => "CActiveForm", 1 => array("id" => "login-form", "enableClientValidation" => true, "clientOptions" => array("validateOnSubmit" => true))), "method");
        // line 11
        echo "
\t\t";
        // line 13
        echo "
\t\t<table>
\t\t\t<tr>
\t\t\t\t<th>";
        // line 16
        echo $this->getAttribute((isset($context["form"]) ? $context["form"] : null), "labelEx", array(0 => (isset($context["model"]) ? $context["model"] : null), 1 => "username"), "method");
        echo "</th>
\t\t\t\t<td>";
        // line 17
        echo $this->getAttribute((isset($context["form"]) ? $context["form"] : null), "textField", array(0 => (isset($context["model"]) ? $context["model"] : null), 1 => "username"), "method");
        echo "</td>
\t\t\t\t<td>";
        // line 18
        echo $this->getAttribute((isset($context["form"]) ? $context["form"] : null), "error", array(0 => (isset($context["model"]) ? $context["model"] : null), 1 => "username"), "method");
        echo "</td>
\t\t\t</tr>
\t\t\t<tr>
\t\t\t\t<th>";
        // line 21
        echo $this->getAttribute((isset($context["form"]) ? $context["form"] : null), "labelEx", array(0 => (isset($context["model"]) ? $context["model"] : null), 1 => "password"), "method");
        echo "</th>
\t\t\t\t<td>";
        // line 22
        echo $this->getAttribute((isset($context["form"]) ? $context["form"] : null), "passwordField", array(0 => (isset($context["model"]) ? $context["model"] : null), 1 => "password"), "method");
        echo "</td>
\t\t\t\t<td>";
        // line 23
        echo $this->getAttribute((isset($context["form"]) ? $context["form"] : null), "error", array(0 => (isset($context["model"]) ? $context["model"] : null), 1 => "password"), "method");
        echo "</td>
\t\t\t</tr>
\t\t\t<tr>
\t\t\t\t<th>";
        // line 26
        echo $this->getAttribute((isset($context["form"]) ? $context["form"] : null), "label", array(0 => (isset($context["model"]) ? $context["model"] : null), 1 => "rememberMe"), "method");
        echo "</th>
\t\t\t\t<td>";
        // line 27
        echo $this->getAttribute((isset($context["form"]) ? $context["form"] : null), "checkBox", array(0 => (isset($context["model"]) ? $context["model"] : null), 1 => "rememberMe"), "method");
        echo "</td>
\t\t\t\t<td>";
        // line 28
        echo $this->getAttribute((isset($context["form"]) ? $context["form"] : null), "error", array(0 => (isset($context["model"]) ? $context["model"] : null), 1 => "rememberMe"), "method");
        echo "</td>
\t\t\t</tr>
\t\t\t<tr>
\t\t\t\t<td colspan=\"2\" style=\"text-align:center;\">
\t\t\t\t\t";
        // line 32
        echo $this->getAttribute((isset($context["CHtml"]) ? $context["CHtml"] : null), "submitButton", array(0 => "Login"), "method");
        echo "
\t\t\t\t</td>
\t\t\t</tr>
\t\t</table>

\t";
        // line 37
        $this->getAttribute((isset($context["this"]) ? $context["this"] : null), "endWidget", array(), "method");
        // line 38
        echo "</div>
";
    }

    public function getTemplateName()
    {
        return "//sav/login.html";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  90 => 38,  88 => 37,  80 => 32,  73 => 28,  69 => 27,  65 => 26,  59 => 23,  55 => 22,  51 => 21,  45 => 18,  41 => 17,  37 => 16,  32 => 13,  29 => 11,  27 => 6,  19 => 1,);
    }
}
