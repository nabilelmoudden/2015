<?php

/* //ap/affiliatePlatformShow.html */
class __TwigTemplate_463fd829c07d8d0f4572b51bbcc6dd4a extends Twig_Template
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
        if (($this->getAttribute((isset($context["AP"]) ? $context["AP"] : null), "id") > 0)) {
            // line 2
            echo "\t<h1 id=\"apTitre\">";
            echo Yii::t("AP", "txtUpdate");
            echo " \"";
            echo $this->getAttribute((isset($context["AP"]) ? $context["AP"] : null), "label");
            echo "\"</h1>
";
        } else {
            // line 4
            echo "\t<h1 id=\"apTitre\">";
            echo Yii::t("AP", "txtCreate");
            echo "</h1>
";
        }
        // line 6
        echo "
";
        // line 7
        $context["form"] = $this->getAttribute((isset($context["this"]) ? $context["this"] : null), "beginWidget", array(0 => "CActiveForm", 1 => array("id" => "APForm", "enableAjaxValidation" => false, "enableClientValidation" => true)), "method");
        // line 13
        echo "
\t";
        // line 14
        echo $this->env->getExtension('TwigEsoterExt')->printFlashes();
        echo "

\t<table width=\"100%\" border=\"0\">
\t\t<tr valign=\"top\">
\t\t\t<th>
\t\t\t\tLabel :
\t\t\t</th>
\t\t\t<td>
\t\t\t\t";
        // line 22
        echo $this->getAttribute((isset($context["form"]) ? $context["form"] : null), "textField", array(0 => (isset($context["AP"]) ? $context["AP"] : null), 1 => "label", 2 => array("style" => "width: 99%;")), "method");
        echo "
\t\t\t</td>
\t\t\t<th rowspan=\"7\" style=\"color:#34c6ff;\">
\t\t\t\t<img id=\"infoToolTip\" src=\"";
        // line 25
        echo $this->env->getExtension('TwigEsoterExt')->adminViewDir();
        echo "/images/help.png\" title=\"<img src='";
        echo $this->env->getExtension('TwigEsoterExt')->adminViewDir();
        echo "/images/help-TC.jpg'>\" />
\t\t\t\tTracking Code :
\t\t\t\t<br />
\t\t\t\t<br />
\t\t\t\t<br />
\t\t\t\t<img id=\"infoToolTip\" src=\"";
        // line 30
        echo $this->env->getExtension('TwigEsoterExt')->adminViewDir();
        echo "/images/info.png\" title=\"__ID__ : Identifiant de l'internaute<br />__SUBID__ : Sub ID\" />
\t\t\t</th>
\t\t\t<td rowspan=\"7\">
\t\t\t\t<img class=\"infoToolTip\" src=\"";
        // line 33
        echo $this->env->getExtension('TwigEsoterExt')->adminViewDir();
        echo "/images/help.png\" title=\"Ce code de tracking est exécuté lorsqu'un internaute se transforme en Lead dans un site de promo.\tCe code de tracking est beaucoup utilisé.\" />
\t\t\t\tTC Lead Promo :<br />
\t\t\t\t";
        // line 35
        echo $this->getAttribute((isset($context["CHtml"]) ? $context["CHtml"] : null), "textArea", array(0 => "Business\\TrackingCode[TCLeadPromo]", 1 => $this->getAttribute((isset($context["TC"]) ? $context["TC"] : null), "TCLeadPromo"), 2 => array("style" => "width: 99%;")), "method");
        echo "
\t\t\t\t<br />
\t\t\t\t<img class=\"infoToolTip\" src=\"";
        // line 37
        echo $this->env->getExtension('TwigEsoterExt')->adminViewDir();
        echo "/images/help.png\" title=\"Ce code de tracking est exécuté lorsqu'une internaute arrive sur un site promo. Ce code est peu utilisé car peu de plateformes en ont besoin.\" />
\t\t\t\tTC Landing Page Promo :<br />
\t\t\t\t";
        // line 39
        echo $this->getAttribute((isset($context["CHtml"]) ? $context["CHtml"] : null), "textArea", array(0 => "Business\\TrackingCode[TCLandingPagePromo]", 1 => $this->getAttribute((isset($context["TC"]) ? $context["TC"] : null), "TCLandingPagePromo"), 2 => array("style" => "width: 99%;")), "method");
        echo "
\t\t\t\t<br />
\t\t\t\t<img class=\"infoToolTip\" src=\"";
        // line 41
        echo $this->env->getExtension('TwigEsoterExt')->adminViewDir();
        echo "/images/help.png\" title=\"Ce code de tracking est exécuté lorsqu'un internaute arrive sur le bon de commande de la VP.\" />
\t\t\t\tTC PrePurchase :<br />
\t\t\t\t";
        // line 43
        echo $this->getAttribute((isset($context["CHtml"]) ? $context["CHtml"] : null), "textArea", array(0 => "Business\\TrackingCode[TCPrePurchase]", 1 => $this->getAttribute((isset($context["TC"]) ? $context["TC"] : null), "TCPrePurchase"), 2 => array("style" => "width: 99%;")), "method");
        echo "
\t\t\t\t<br />
\t\t\t\t<img class=\"infoToolTip\" src=\"";
        // line 45
        echo $this->env->getExtension('TwigEsoterExt')->adminViewDir();
        echo "/images/help.png\" title=\"Ce code de tracking est exécuté lorsqu'un internaute achete la VP.\" />
\t\t\t\tTC Purchase :<br />
\t\t\t\t";
        // line 47
        echo $this->getAttribute((isset($context["CHtml"]) ? $context["CHtml"] : null), "textArea", array(0 => "Business\\TrackingCode[TCPurchase]", 1 => $this->getAttribute((isset($context["TC"]) ? $context["TC"] : null), "TCPurchase"), 2 => array("style" => "width: 99%;")), "method");
        echo "
\t\t\t\t<br />
\t\t\t\t<img class=\"infoToolTip\" src=\"";
        // line 49
        echo $this->env->getExtension('TwigEsoterExt')->adminViewDir();
        echo "/images/help.png\" title=\"Ce code de tracking est exécuté lorsqu'un internaute se transforme en Lead dans la chaîne vg. En effet, certaines plateformes d'affiliations nous ont demandé de rediriger les internautes vers la page vgldv plutôt que sur un site de promo. Ce code est quasiment jamais utilisé car les plateforme ne redirige plus vers la VGLDV.\" />
\t\t\t\tTC Lead :<br />
\t\t\t\t";
        // line 51
        echo $this->getAttribute((isset($context["CHtml"]) ? $context["CHtml"] : null), "textArea", array(0 => "Business\\TrackingCode[TCLead]", 1 => $this->getAttribute((isset($context["TC"]) ? $context["TC"] : null), "TCLead"), 2 => array("style" => "width: 99%;")), "method");
        echo "
\t\t\t\t<br />
\t\t\t\t<img class=\"infoToolTip\" src=\"";
        // line 53
        echo $this->env->getExtension('TwigEsoterExt')->adminViewDir();
        echo "/images/help.png\" title=\"Il s'agit d'un code de tracking qui est exécuté sur la page d'arrivée vgldv. En effet, certaines plateformes d'affiliations nous ont demandé de rediriger les internautes vers la page vgldv plutôt que sur un site de promo. Ce code est quasiment jamais utilisé car les plateforme ne redirige plus vers la VGLDV et car peu de plateformes ont besoin d'un code de tracking sur la page d'arrivée.\" />
\t\t\t\tTC Landing Page :<br />
\t\t\t\t";
        // line 55
        echo $this->getAttribute((isset($context["CHtml"]) ? $context["CHtml"] : null), "textArea", array(0 => "Business\\TrackingCode[TCLandingPage]", 1 => $this->getAttribute((isset($context["TC"]) ? $context["TC"] : null), "TCLandingPage"), 2 => array("style" => "width: 99%;")), "method");
        echo "
\t\t\t\t<br />
\t\t\t\t<img class=\"infoToolTip\" src=\"";
        // line 57
        echo $this->env->getExtension('TwigEsoterExt')->adminViewDir();
        echo "/images/help.png\" title=\"Il s'agit d'un code de tracking qui est exécuté sur la page de validation du double OPTIN\" />
\t\t\t\tTC Dual Optin :<br />
\t\t\t\t";
        // line 59
        echo $this->getAttribute((isset($context["CHtml"]) ? $context["CHtml"] : null), "textArea", array(0 => "Business\\TrackingCode[TCDualOptin]", 1 => $this->getAttribute((isset($context["TC"]) ? $context["TC"] : null), "TCDualOptin"), 2 => array("style" => "width: 99%;")), "method");
        echo "
\t\t\t\t<br />
\t\t\t</td>
\t\t</tr>
\t\t<tr valign=\"top\">
\t\t\t<th>
\t\t\t\tDescription :
\t\t\t</th>
\t\t\t<td>
\t\t\t\t";
        // line 68
        echo $this->getAttribute((isset($context["form"]) ? $context["form"] : null), "textArea", array(0 => (isset($context["AP"]) ? $context["AP"] : null), 1 => "description", 2 => array("style" => "width: 99%;")), "method");
        echo "
\t\t\t</td>
\t\t</tr>
\t\t<tr valign=\"top\">
\t\t\t<th style=\"color:#34c6ff;\">
\t\t\t\t<img class=\"infoToolTip\" src=\"";
        // line 73
        echo $this->env->getExtension('TwigEsoterExt')->adminViewDir();
        echo "/images/help.png\" title=\"<img src='";
        echo $this->env->getExtension('TwigEsoterExt')->adminViewDir();
        echo "/images/help-SP.jpg'>\" />
\t\t\t\tPromo Site :
\t\t\t</th>
\t\t\t<td>
\t\t\t\t<select name=\"Business\\PromoSite[id]\">
\t\t\t\t\t<option value=\"0\">Default</option>
\t\t\t\t\t";
        // line 79
        $context['_parent'] = (array) $context;
        $context['_seq'] = twig_ensure_traversable((isset($context["lPromoSite"]) ? $context["lPromoSite"] : null));
        foreach ($context['_seq'] as $context["_key"] => $context["PromoSite"]) {
            // line 80
            echo "\t\t\t\t\t\t<option ";
            if (($this->getAttribute((isset($context["PS"]) ? $context["PS"] : null), "id") == $this->getAttribute((isset($context["PromoSite"]) ? $context["PromoSite"] : null), "id"))) {
                echo "selected";
            }
            echo " value=\"";
            echo $this->getAttribute((isset($context["PromoSite"]) ? $context["PromoSite"] : null), "id");
            echo "\">";
            echo $this->getAttribute((isset($context["PromoSite"]) ? $context["PromoSite"] : null), "label");
            echo "</option>
\t\t\t\t\t";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['PromoSite'], $context['_parent'], $context['loop']);
        $context = array_merge($_parent, array_intersect_key($context, $_parent));
        // line 82
        echo "\t\t\t\t</select>
\t\t\t</td>
\t\t</tr>
\t\t<tr valign=\"top\">
\t\t\t<th>
\t\t\t\tSite :
\t\t\t</th>
\t\t\t<td>
\t\t\t\t<select name=\"Business\\AffiliatePlatform[idSite]\">
\t\t\t\t\t";
        // line 91
        $context['_parent'] = (array) $context;
        $context['_seq'] = twig_ensure_traversable((isset($context["lSite"]) ? $context["lSite"] : null));
        foreach ($context['_seq'] as $context["_key"] => $context["Site"]) {
            // line 92
            echo "\t\t\t\t\t\t<option ";
            if (($this->getAttribute((isset($context["AP"]) ? $context["AP"] : null), "idSite") == $this->getAttribute((isset($context["Site"]) ? $context["Site"] : null), "id"))) {
                echo "selected";
            }
            echo " value=\"";
            echo $this->getAttribute((isset($context["Site"]) ? $context["Site"] : null), "id");
            echo "\">";
            echo $this->getAttribute((isset($context["Site"]) ? $context["Site"] : null), "country");
            echo " ( ";
            echo $this->getAttribute((isset($context["Site"]) ? $context["Site"] : null), "code");
            echo " )</option>
\t\t\t\t\t";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['Site'], $context['_parent'], $context['loop']);
        $context = array_merge($_parent, array_intersect_key($context, $_parent));
        // line 94
        echo "\t\t\t\t</select>
\t\t\t</td>
\t\t</tr>
\t\t<tr valign=\"top\">
\t\t\t<th>Gestionnaire Strategique :</th>
\t\t\t<td>
\t\t\t\t<select name=\"Business\\idManagerS\">
\t\t\t\t\t<option value=\"-1\">Choix</option>
\t\t\t\t\t";
        // line 102
        $context['_parent'] = (array) $context;
        $context['_seq'] = twig_ensure_traversable((isset($context["lAdmin"]) ? $context["lAdmin"] : null));
        foreach ($context['_seq'] as $context["_key"] => $context["Admin"]) {
            // line 103
            echo "\t\t\t\t\t\t<option ";
            if (($this->getAttribute((isset($context["ManagerS"]) ? $context["ManagerS"] : null), "idUser") == $this->getAttribute((isset($context["Admin"]) ? $context["Admin"] : null), "id"))) {
                echo "selected";
            }
            echo " value=\"";
            echo $this->getAttribute((isset($context["Admin"]) ? $context["Admin"] : null), "id");
            echo "\">";
            echo $this->getAttribute((isset($context["Admin"]) ? $context["Admin"] : null), "name", array(), "method");
            echo "</option>
\t\t\t\t\t";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['Admin'], $context['_parent'], $context['loop']);
        $context = array_merge($_parent, array_intersect_key($context, $_parent));
        // line 105
        echo "\t\t\t\t</select>
\t\t\t\t&nbsp;
\t\t\t\t";
        // line 107
        if ((isset($context["ManagerS"]) ? $context["ManagerS"] : null)) {
            // line 108
            echo "\t\t\t\t\t( ";
            echo $this->getAttribute($this->getAttribute((isset($context["ManagerS"]) ? $context["ManagerS"] : null), "getDateStart", array(), "method"), "format", array(0 => "d-m-Y"), "method");
            echo " )
\t\t\t\t";
        }
        // line 110
        echo "\t\t\t</td>
\t\t</tr>
\t\t<tr valign=\"top\">
\t\t\t<th>Gestionnaire Operationnel :</th>
\t\t\t<td>
\t\t\t\t<select name=\"Business\\idManagerO\">
\t\t\t\t\t<option value=\"-1\">Choix</option>
\t\t\t\t\t";
        // line 117
        $context['_parent'] = (array) $context;
        $context['_seq'] = twig_ensure_traversable((isset($context["lAdmin"]) ? $context["lAdmin"] : null));
        foreach ($context['_seq'] as $context["_key"] => $context["Admin"]) {
            // line 118
            echo "\t\t\t\t\t\t<option ";
            if (($this->getAttribute((isset($context["ManagerO"]) ? $context["ManagerO"] : null), "idUser") == $this->getAttribute((isset($context["Admin"]) ? $context["Admin"] : null), "id"))) {
                echo "selected";
            }
            echo " value=\"";
            echo $this->getAttribute((isset($context["Admin"]) ? $context["Admin"] : null), "id");
            echo "\">";
            echo $this->getAttribute((isset($context["Admin"]) ? $context["Admin"] : null), "name", array(), "method");
            echo "</option>
\t\t\t\t\t";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['Admin'], $context['_parent'], $context['loop']);
        $context = array_merge($_parent, array_intersect_key($context, $_parent));
        // line 120
        echo "\t\t\t\t</select>
\t\t\t\t&nbsp;
\t\t\t\t";
        // line 122
        if ((isset($context["ManagerO"]) ? $context["ManagerO"] : null)) {
            // line 123
            echo "\t\t\t\t\t( ";
            echo $this->getAttribute($this->getAttribute((isset($context["ManagerO"]) ? $context["ManagerO"] : null), "getDateStart", array(), "method"), "format", array(0 => "d-m-Y"), "method");
            echo " )
\t\t\t\t";
        }
        // line 125
        echo "\t\t\t</td>
\t\t</tr>
\t\t<tr valign=\"top\">
\t\t\t<th>
\t\t\t\tDouble Optin :
\t\t\t</th>
\t\t\t<td>
\t\t\t\t";
        // line 132
        echo $this->getAttribute((isset($context["form"]) ? $context["form"] : null), "checkBox", array(0 => (isset($context["AP"]) ? $context["AP"] : null), 1 => "isDualOptin"), "method");
        echo "
\t\t\t</td>
\t\t</tr>

\t\t<tr>
\t\t\t<td colspan=\"4\" style=\"text-align:center;\">
\t\t\t\t";
        // line 138
        echo $this->getAttribute((isset($context["CHtml"]) ? $context["CHtml"] : null), "button", array(0 => Yii::t("common", "cancel"), 1 => array("name" => "cancel", "onClick" => "MainDialog.close();")), "method");
        echo "
\t\t\t\t&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

\t\t\t\t";
        // line 141
        if (($this->getAttribute((isset($context["AP"]) ? $context["AP"] : null), "id") > 0)) {
            // line 142
            echo "\t\t\t\t\t";
            echo $this->getAttribute((isset($context["CHtml"]) ? $context["CHtml"] : null), "button", array(0 => Yii::t("common", "update"), 1 => array("name" => "cancel", "onClick" => "sendAffiliatePlatform()")), "method");
            echo "
\t\t\t\t";
        } else {
            // line 144
            echo "\t\t\t\t\t";
            echo $this->getAttribute((isset($context["CHtml"]) ? $context["CHtml"] : null), "button", array(0 => Yii::t("common", "create"), 1 => array("name" => "cancel", "onClick" => "sendAffiliatePlatform()")), "method");
            echo "
\t\t\t\t";
        }
        // line 146
        echo "\t\t\t</td>
\t\t</tr>
\t</table>

";
        // line 150
        $this->getAttribute((isset($context["this"]) ? $context["this"] : null), "endWidget", array(), "method");
    }

    public function getTemplateName()
    {
        return "//ap/affiliatePlatformShow.html";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  348 => 150,  342 => 146,  336 => 144,  330 => 142,  328 => 141,  322 => 138,  313 => 132,  304 => 125,  298 => 123,  296 => 122,  292 => 120,  277 => 118,  273 => 117,  264 => 110,  258 => 108,  256 => 107,  252 => 105,  237 => 103,  233 => 102,  223 => 94,  206 => 92,  202 => 91,  191 => 82,  176 => 80,  172 => 79,  161 => 73,  153 => 68,  141 => 59,  136 => 57,  131 => 55,  126 => 53,  121 => 51,  116 => 49,  111 => 47,  106 => 45,  101 => 43,  96 => 41,  91 => 39,  86 => 37,  81 => 35,  76 => 33,  70 => 30,  60 => 25,  54 => 22,  43 => 14,  40 => 13,  38 => 7,  35 => 6,  29 => 4,  21 => 2,  19 => 1,);
    }
}
