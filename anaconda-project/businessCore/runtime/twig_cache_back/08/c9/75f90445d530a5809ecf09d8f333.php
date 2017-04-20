<?php

/* //sav/customerProfileUpdate.html */
class __TwigTemplate_08c975f90445d530a5809ecf09d8f333 extends Twig_Template
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
        echo $this->env->getExtension('TwigEsoterExt')->pageTitle("SAV - Customer Profil");
        echo "

";
        // line 3
        echo $this->env->getExtension('TwigEsoterExt')->loadJS(($this->getAttribute((isset($context["App"]) ? $context["App"] : null), "baseUrl") . "/js/SAV/customerProfile.js"));
        echo "

<h1 id='apTitre'>Customer Profile - ";
        // line 5
        echo $this->getAttribute((isset($context["User"]) ? $context["User"] : null), "name", array(), "method");
        echo "</h1>

<div class=\"form\">

";
        // line 9
        $context["form"] = $this->getAttribute((isset($context["this"]) ? $context["this"] : null), "beginWidget", array(0 => "CActiveForm", 1 => array("id" => "user-form", "enableAjaxValidation" => false, "enableClientValidation" => true)), "method");
        // line 15
        echo "
\t";
        // line 16
        echo $this->env->getExtension('TwigEsoterExt')->printFlashes((isset($context["User"]) ? $context["User"] : null));
        echo "

\t<table width=\"100%\">
\t\t<tr>
\t\t\t<th width=\"200px\">";
        // line 20
        echo $this->getAttribute((isset($context["form"]) ? $context["form"] : null), "labelEx", array(0 => (isset($context["User"]) ? $context["User"] : null), 1 => "civility"), "method");
        echo "</th>
\t\t\t<td>";
        // line 21
        echo $this->getAttribute((isset($context["form"]) ? $context["form"] : null), "listBox", array(0 => (isset($context["User"]) ? $context["User"] : null), 1 => "civility", 2 => array("M" => "M", "Md" => "Md", "Mlle" => "Mlle"), 3 => array("size" => 1)), "method");
        echo "</td>
\t\t</tr>
\t\t<tr>
\t\t\t<th>";
        // line 24
        echo $this->getAttribute((isset($context["form"]) ? $context["form"] : null), "labelEx", array(0 => (isset($context["User"]) ? $context["User"] : null), 1 => "firstName"), "method");
        echo "</th>
\t\t\t<td>";
        // line 25
        echo $this->getAttribute((isset($context["form"]) ? $context["form"] : null), "textField", array(0 => (isset($context["User"]) ? $context["User"] : null), 1 => "firstName"), "method");
        echo "</td>
\t\t</tr>
\t\t<tr>
\t\t\t<th>";
        // line 28
        echo $this->getAttribute((isset($context["form"]) ? $context["form"] : null), "labelEx", array(0 => (isset($context["User"]) ? $context["User"] : null), 1 => "lastName"), "method");
        echo "</th>
\t\t\t<td>";
        // line 29
        echo $this->getAttribute((isset($context["form"]) ? $context["form"] : null), "textField", array(0 => (isset($context["User"]) ? $context["User"] : null), 1 => "lastName"), "method");
        echo "</td>
\t\t</tr>
\t\t<tr>
\t\t\t<th>";
        // line 32
        echo $this->getAttribute((isset($context["form"]) ? $context["form"] : null), "labelEx", array(0 => (isset($context["User"]) ? $context["User"] : null), 1 => "email"), "method");
        echo "</th>
\t\t\t<td>";
        // line 33
        echo $this->getAttribute((isset($context["form"]) ? $context["form"] : null), "textField", array(0 => (isset($context["User"]) ? $context["User"] : null), 1 => "email"), "method");
        echo "</td>
\t\t</tr>
\t\t<tr>
\t\t\t<th>";
        // line 36
        echo $this->getAttribute((isset($context["form"]) ? $context["form"] : null), "labelEx", array(0 => (isset($context["User"]) ? $context["User"] : null), 1 => "password"), "method");
        echo "</th>
\t\t\t<td>";
        // line 37
        echo $this->getAttribute((isset($context["form"]) ? $context["form"] : null), "textField", array(0 => (isset($context["User"]) ? $context["User"] : null), 1 => "password"), "method");
        echo "</td>
\t\t</tr>
\t\t<tr>
\t\t\t<th>";
        // line 40
        echo $this->getAttribute((isset($context["form"]) ? $context["form"] : null), "labelEx", array(0 => (isset($context["User"]) ? $context["User"] : null), 1 => "address"), "method");
        echo "</th>
\t\t\t<td>";
        // line 41
        echo $this->getAttribute((isset($context["form"]) ? $context["form"] : null), "textField", array(0 => (isset($context["User"]) ? $context["User"] : null), 1 => "address"), "method");
        echo "</td>
\t\t</tr>
\t\t<tr>
\t\t\t<th>";
        // line 44
        echo $this->getAttribute((isset($context["form"]) ? $context["form"] : null), "labelEx", array(0 => (isset($context["User"]) ? $context["User"] : null), 1 => "country"), "method");
        echo "</th>
\t\t\t<td>";
        // line 45
        echo $this->getAttribute((isset($context["form"]) ? $context["form"] : null), "textField", array(0 => (isset($context["User"]) ? $context["User"] : null), 1 => "country"), "method");
        echo "</td>
\t\t</tr>
\t\t<tr>
\t\t\t<th>";
        // line 48
        echo $this->getAttribute((isset($context["form"]) ? $context["form"] : null), "labelEx", array(0 => (isset($context["User"]) ? $context["User"] : null), 1 => "zipCode"), "method");
        echo "</th>
\t\t\t<td>";
        // line 49
        echo $this->getAttribute((isset($context["form"]) ? $context["form"] : null), "textField", array(0 => (isset($context["User"]) ? $context["User"] : null), 1 => "zipCode"), "method");
        echo "</td>
\t\t</tr>
\t\t<tr>
\t\t\t<th>";
        // line 52
        echo $this->getAttribute((isset($context["form"]) ? $context["form"] : null), "labelEx", array(0 => (isset($context["User"]) ? $context["User"] : null), 1 => "city"), "method");
        echo "</th>
\t\t\t<td>";
        // line 53
        echo $this->getAttribute((isset($context["form"]) ? $context["form"] : null), "textField", array(0 => (isset($context["User"]) ? $context["User"] : null), 1 => "city"), "method");
        echo "</td>
\t\t</tr>
\t\t<tr>
\t\t\t<th>";
        // line 56
        echo $this->getAttribute((isset($context["form"]) ? $context["form"] : null), "labelEx", array(0 => (isset($context["User"]) ? $context["User"] : null), 1 => "phone"), "method");
        echo "</th>
\t\t\t<td>";
        // line 57
        echo $this->getAttribute((isset($context["form"]) ? $context["form"] : null), "textField", array(0 => (isset($context["User"]) ? $context["User"] : null), 1 => "phone"), "method");
        echo "</td>
\t\t</tr>
\t\t<tr>
\t\t\t<th>";
        // line 60
        echo $this->getAttribute((isset($context["form"]) ? $context["form"] : null), "labelEx", array(0 => (isset($context["User"]) ? $context["User"] : null), 1 => "birthday"), "method");
        echo "</th>
\t\t\t<td>";
        // line 61
        echo $this->getAttribute((isset($context["form"]) ? $context["form"] : null), "dateField", array(0 => (isset($context["User"]) ? $context["User"] : null), 1 => "birthday", 2 => array("value" => $this->env->getExtension('TwigEsoterExt')->date($this->getAttribute((isset($context["User"]) ? $context["User"] : null), "birthday")))), "method");
        echo "</td>
\t\t</tr>
\t\t<tr>
\t\t\t<th>";
        // line 64
        echo $this->getAttribute((isset($context["form"]) ? $context["form"] : null), "labelEx", array(0 => (isset($context["User"]) ? $context["User"] : null), 1 => "visibleDesinscrire"), "method");
        echo "</th>
\t\t\t<td>";
        // line 65
        echo $this->getAttribute((isset($context["form"]) ? $context["form"] : null), "checkbox", array(0 => (isset($context["User"]) ? $context["User"] : null), 1 => "visibleDesinscrire"), "method");
        echo "</td>
\t\t</tr>
\t\t<tr>
\t\t\t<th>";
        // line 68
        echo $this->getAttribute((isset($context["form"]) ? $context["form"] : null), "labelEx", array(0 => (isset($context["User"]) ? $context["User"] : null), 1 => "savToMonitor"), "method");
        echo "</th>
\t\t\t<td>";
        // line 69
        echo $this->getAttribute((isset($context["form"]) ? $context["form"] : null), "checkbox", array(0 => (isset($context["User"]) ? $context["User"] : null), 1 => "savToMonitor"), "method");
        echo "</td>
\t\t</tr>
\t\t<tr>
\t\t\t<th>";
        // line 72
        echo $this->getAttribute((isset($context["form"]) ? $context["form"] : null), "labelEx", array(0 => (isset($context["User"]) ? $context["User"] : null), 1 => "savComments"), "method");
        echo "</th>
\t\t\t<td>";
        // line 73
        echo $this->getAttribute((isset($context["form"]) ? $context["form"] : null), "textArea", array(0 => (isset($context["User"]) ? $context["User"] : null), 1 => "savComments", 2 => array("style" => "width:100%;")), "method");
        echo "</td>
\t\t</tr>

\t\t<tr>
\t\t\t<td colspan=\"3\" style=\"text-align:center;\">
\t\t\t\t";
        // line 78
        echo $this->getAttribute((isset($context["CHtml"]) ? $context["CHtml"] : null), "button", array(0 => Yii::t("SAV", "cancel"), 1 => array("name" => "cancel", "onClick" => "cancelUpdateCustomer()")), "method");
        echo "
\t\t\t\t&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
\t\t\t\t";
        // line 80
        echo $this->getAttribute((isset($context["CHtml"]) ? $context["CHtml"] : null), "button", array(0 => Yii::t("SAV", "update"), 1 => array("name" => "update", "onClick" => "sendUpdateCustomer()")), "method");
        echo "
\t\t\t</td>
\t\t</tr>
\t</table>

";
        // line 85
        $this->getAttribute((isset($context["this"]) ? $context["this"] : null), "endWidget", array(), "method");
        // line 86
        echo "
</div>";
    }

    public function getTemplateName()
    {
        return "//sav/customerProfileUpdate.html";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  205 => 86,  203 => 85,  195 => 80,  190 => 78,  182 => 73,  178 => 72,  172 => 69,  168 => 68,  162 => 65,  158 => 64,  152 => 61,  148 => 60,  142 => 57,  138 => 56,  132 => 53,  128 => 52,  122 => 49,  118 => 48,  112 => 45,  108 => 44,  102 => 41,  98 => 40,  92 => 37,  88 => 36,  82 => 33,  78 => 32,  72 => 29,  68 => 28,  62 => 25,  58 => 24,  52 => 21,  48 => 20,  41 => 16,  38 => 15,  36 => 9,  29 => 5,  24 => 3,  19 => 1,);
    }
}
