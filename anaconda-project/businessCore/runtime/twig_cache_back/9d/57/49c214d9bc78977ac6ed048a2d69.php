<?php

/* //login.html */
class __TwigTemplate_9d5749c214d9bc78977ac6ed048a2d69 extends Twig_Template
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
        echo "<h1>Login</h1>

<div class=\"form\">
\t";
        // line 4
        $context["form"] = $this->getAttribute((isset($context["this"]) ? $context["this"] : null), "beginWidget", array(0 => "CActiveForm", 1 => array("id" => "login-form", "enableClientValidation" => true, "clientOptions" => array("validateOnSubmit" => true))), "method");
        // line 9
        echo "
\t\t<table>
\t\t\t<tr>
\t\t\t\t<th>";
        // line 12
        echo $this->getAttribute((isset($context["form"]) ? $context["form"] : null), "labelEx", array(0 => (isset($context["model"]) ? $context["model"] : null), 1 => "username"), "method");
        echo "</th>
\t\t\t\t<td>";
        // line 13
        echo $this->getAttribute((isset($context["form"]) ? $context["form"] : null), "textField", array(0 => (isset($context["model"]) ? $context["model"] : null), 1 => "username"), "method");
        echo "</td>
\t\t\t\t<td>";
        // line 14
        echo $this->getAttribute((isset($context["form"]) ? $context["form"] : null), "error", array(0 => (isset($context["model"]) ? $context["model"] : null), 1 => "username"), "method");
        echo "</td>
\t\t\t</tr>
\t\t\t<tr>
\t\t\t\t<th>";
        // line 17
        echo $this->getAttribute((isset($context["form"]) ? $context["form"] : null), "labelEx", array(0 => (isset($context["model"]) ? $context["model"] : null), 1 => "password"), "method");
        echo "</th>
\t\t\t\t<td>";
        // line 18
        echo $this->getAttribute((isset($context["form"]) ? $context["form"] : null), "passwordField", array(0 => (isset($context["model"]) ? $context["model"] : null), 1 => "password"), "method");
        echo "</td>
\t\t\t\t<td>";
        // line 19
        echo $this->getAttribute((isset($context["form"]) ? $context["form"] : null), "error", array(0 => (isset($context["model"]) ? $context["model"] : null), 1 => "password"), "method");
        echo "</td>
\t\t\t</tr>
\t\t\t<tr>
\t\t\t\t<th>";
        // line 22
        echo $this->getAttribute((isset($context["form"]) ? $context["form"] : null), "label", array(0 => (isset($context["model"]) ? $context["model"] : null), 1 => "rememberMe"), "method");
        echo "</th>
\t\t\t\t<td>";
        // line 23
        echo $this->getAttribute((isset($context["form"]) ? $context["form"] : null), "checkBox", array(0 => (isset($context["model"]) ? $context["model"] : null), 1 => "rememberMe"), "method");
        echo "</td>
\t\t\t\t<td>";
        // line 24
        echo $this->getAttribute((isset($context["form"]) ? $context["form"] : null), "error", array(0 => (isset($context["model"]) ? $context["model"] : null), 1 => "rememberMe"), "method");
        echo "</td>
\t\t\t</tr>
\t\t\t<tr>
\t\t\t\t<td colspan=\"2\" style=\"text-align:center;\">
\t\t\t\t\t";
        // line 28
        echo $this->getAttribute((isset($context["CHtml"]) ? $context["CHtml"] : null), "submitButton", array(0 => "Login"), "method");
        echo "
\t\t\t\t</td>
\t\t\t</tr>
\t\t</table>

\t";
        // line 33
        $this->getAttribute((isset($context["this"]) ? $context["this"] : null), "endWidget", array(), "method");
        // line 34
        echo "</div>
";
    }

    public function getTemplateName()
    {
        return "//login.html";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  84 => 34,  82 => 33,  74 => 28,  67 => 24,  63 => 23,  59 => 22,  53 => 19,  49 => 18,  45 => 17,  39 => 14,  35 => 13,  31 => 12,  26 => 9,  24 => 4,  19 => 1,);
    }
}
