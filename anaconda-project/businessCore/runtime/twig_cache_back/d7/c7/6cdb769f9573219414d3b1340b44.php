<?php

/* //product/productShow.html */
class __TwigTemplate_d7c76cdb769f9573219414d3b1340b44 extends Twig_Template
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
        if (($this->getAttribute((isset($context["Prod"]) ? $context["Prod"] : null), "id") > 0)) {
            // line 2
            echo "
\t<h1 id=\"apTitre\">";
            // line 3
            echo Yii::t("product", "txtUpdateProd");
            echo " \"";
            echo $this->getAttribute((isset($context["Prod"]) ? $context["Prod"] : null), "description");
            echo "\"</h1>

";
        } else {
            // line 6
            echo "
\t<h1 id=\"apTitre\">";
            // line 7
            echo Yii::t("product", "txtCreateProd");
            echo "</h1>

";
        }
        // line 10
        echo "


";
        // line 13
        $context["form"] = $this->getAttribute((isset($context["this"]) ? $context["this"] : null), "beginWidget", array(0 => "CActiveForm", 1 => array("id" => "ProdForm", "enableAjaxValidation" => false, "enableClientValidation" => true)), "method");
        // line 24
        echo "


\t";
        // line 27
        echo $this->env->getExtension('TwigEsoterExt')->printFlashes();
        echo "



\t";
        // line 31
        echo $this->getAttribute((isset($context["CHtml"]) ? $context["CHtml"] : null), "hiddenField", array(0 => "id", 1 => $this->getAttribute((isset($context["Sub"]) ? $context["Sub"] : null), "id")), "method");
        echo "

\t";
        // line 33
        echo $this->getAttribute((isset($context["CHtml"]) ? $context["CHtml"] : null), "hiddenField", array(0 => "idProd", 1 => $this->getAttribute((isset($context["Prod"]) ? $context["Prod"] : null), "id")), "method");
        echo "



\t<table width=\"100%\" border=\"0\">

\t\t<tr>

\t\t\t<th>

\t\t\t\tPosition :

\t\t\t</th>

\t\t\t<td>

\t\t\t\t";
        // line 49
        echo $this->getAttribute((isset($context["form"]) ? $context["form"] : null), "textField", array(0 => (isset($context["Sub"]) ? $context["Sub"] : null), 1 => "position", 2 => array("style" => "width: 99%;")), "method");
        echo "

\t\t\t</td>



\t\t\t<th rowspan=\"15\" valign=\"top\">

\t\t\t\tBDC Fields :

\t\t\t</th>

\t\t\t<td rowspan=\"15\" valign=\"top\" id=\"tdForBdcFields\">

\t\t\t</td>

\t\t</tr>

\t\t<tr>

\t\t\t<th>

\t\t\t\tReference :

\t\t\t</th>

\t\t\t<td>

\t\t\t\t";
        // line 77
        echo $this->getAttribute((isset($context["form"]) ? $context["form"] : null), "textField", array(0 => (isset($context["Prod"]) ? $context["Prod"] : null), 1 => "ref", 2 => array("style" => "width: 99%;")), "method");
        echo "

\t\t\t</td>

\t\t</tr>

\t\t<tr>

\t\t\t<th>

\t\t\t\tDescription :

\t\t\t</th>

\t\t\t<td>

\t\t\t\t";
        // line 93
        echo $this->getAttribute((isset($context["form"]) ? $context["form"] : null), "textField", array(0 => (isset($context["Prod"]) ? $context["Prod"] : null), 1 => "description", 2 => array("style" => "width: 99%;")), "method");
        echo "

\t\t\t</td>

\t\t</tr>

\t\t<tr>

\t\t\t<th>

\t\t\t\tType :

\t\t\t</th>

\t\t\t<td>

\t\t\t\t";
        // line 109
        echo $this->getAttribute((isset($context["form"]) ? $context["form"] : null), "listBox", array(0 => (isset($context["Prod"]) ? $context["Prod"] : null), 1 => "productType", 2 => array(1 => "Virtual", 2 => "Physical", 3 => "eBook"), 3 => array("size" => 1)), "method");
        echo "

\t\t\t</td>

\t\t</tr>

\t\t<tr>

\t\t\t<th>

\t\t\t\tQuantity :

\t\t\t</th>

\t\t\t<td>

\t\t\t\t";
        // line 125
        echo $this->getAttribute((isset($context["form"]) ? $context["form"] : null), "textField", array(0 => (isset($context["Prod"]) ? $context["Prod"] : null), 1 => "qty", 2 => array("size" => "6")), "method");
        echo "

\t\t\t</td>

\t\t</tr>

\t\t<tr>

\t\t\t<th>

\t\t\t\tAmount :

\t\t\t</th>

\t\t\t<td>

\t\t\t\t";
        // line 141
        echo $this->getAttribute((isset($context["form"]) ? $context["form"] : null), "textField", array(0 => (isset($context["Prod"]) ? $context["Prod"] : null), 1 => "amountBif", 2 => array("size" => "10")), "method");
        echo "

\t\t\t</td>

\t\t</tr>

\t\t<tr>

\t\t\t<th>

\t\t\t\tCT :

\t\t\t</th>

\t\t\t<td>

\t\t\t\t";
        // line 157
        echo $this->getAttribute((isset($context["form"]) ? $context["form"] : null), "listBox", array(0 => (isset($context["Prod"]) ? $context["Prod"] : null), 1 => "isCT", 2 => array(0 => "No", 1 => "Yes"), 3 => array("size" => 1)), "method");
        echo "

\t\t\t</td>



\t\t</tr>

\t\t<tr>

\t\t\t<th>

\t\t\t\tStat Title :

\t\t\t</th>

\t\t\t<td>

\t\t\t\t";
        // line 175
        echo $this->getAttribute((isset($context["form"]) ? $context["form"] : null), "textField", array(0 => (isset($context["Prod"]) ? $context["Prod"] : null), 1 => "titleStat", 2 => array("style" => "width: 99%;")), "method");
        echo "

\t\t\t</td>

\t\t</tr>

\t\t<tr>

\t\t\t<th>

\t\t\t\tStat Abr :

\t\t\t</th>

\t\t\t<td>

\t\t\t\t";
        // line 191
        echo $this->getAttribute((isset($context["form"]) ? $context["form"] : null), "textField", array(0 => (isset($context["Prod"]) ? $context["Prod"] : null), 1 => "abrStat", 2 => array("style" => "width: 99%;")), "method");
        echo "

\t\t\t</td>

\t\t</tr>

\t\t<tr>

\t\t\t<th>

\t\t\t\tPayment Processor Set :

\t\t\t</th>

\t\t\t<td>

\t\t\t\t";
        // line 207
        echo $this->getAttribute((isset($context["form"]) ? $context["form"] : null), "listBox", array(0 => (isset($context["Prod"]) ? $context["Prod"] : null), 1 => "idPaymentProcessorSet", 2 => $this->getAttribute((isset($context["CHtml"]) ? $context["CHtml"] : null), "listData", array(0 => (isset($context["lPPSet"]) ? $context["lPPSet"] : null), 1 => "id", 2 => "label"), "method"), 3 => array("size" => 1, "id" => "Product_idPaymentProcessorSet", "onChange" => "setBdcFields( this.value );")), "method");
        echo "

\t\t\t</td>

\t\t</tr>

\t\t<tr>

\t\t\t<th>

\t\t\t\tPrice Model :

\t\t\t</th>

\t\t\t<td>

\t\t\t\t";
        // line 223
        echo $this->getAttribute((isset($context["form"]) ? $context["form"] : null), "listBox", array(0 => (isset($context["Prod"]) ? $context["Prod"] : null), 1 => "priceModel", 2 => array("free" => "Free", "standard" => "Standard", "prevBased" => "Previous Based"), 3 => array("size" => 1, "onChange" => "showParamPriceModel( this.value );")), "method");
        echo "

\t\t\t</td>

\t\t</tr>

\t\t<tr>

\t\t\t<th>

\t\t\t\tPrice Model Param :

\t\t\t</th>

\t\t\t<td>

\t\t\t\t<div class=\"paramPriceModel\" id=\"param_free\" style=\"display:none;\">

\t\t\t\t\tAucun parametre necessaire

\t\t\t\t</div>



\t\t\t\t<div class=\"paramPriceModel\" id=\"param_standard\" style=\"display:none;\">

\t\t\t\t\tAucun parametre necessaire

\t\t\t\t</div>



\t\t\t\t<div class=\"paramPriceModel\" id=\"param_prevBased\" style=\"display:none;\">

\t\t\t\t\tPrevious product : ";
        // line 257
        echo $this->getAttribute((isset($context["CHtml"]) ? $context["CHtml"] : null), "listBox", array(0 => "paramPriceModel[prevBased][prevRefProduct]", 1 => $this->getAttribute((isset($context["Prod"]) ? $context["Prod"] : null), "getParamPriceModel", array(0 => "prevRefProduct"), "method"), 2 => $this->getAttribute((isset($context["CHtml"]) ? $context["CHtml"] : null), "listData", array(0 => (isset($context["lSub"]) ? $context["lSub"] : null), 1 => "Product.ref", 2 => "Product.description"), "method"), 3 => array("size" => 1)), "method");
        echo "

\t\t\t\t</div>

\t\t\t</td>

\t\t</tr>



\t\t<tr>

\t\t\t<td colspan=\"4\" style=\"text-align:center;\">

\t\t\t\t";
        // line 271
        echo $this->getAttribute((isset($context["CHtml"]) ? $context["CHtml"] : null), "button", array(0 => Yii::t("common", "cancel"), 1 => array("name" => "cancel", "onClick" => "SecondDialog.close();")), "method");
        echo "

\t\t\t\t&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;



\t\t\t\t";
        // line 277
        if (($this->getAttribute((isset($context["Prod"]) ? $context["Prod"] : null), "id") > 0)) {
            // line 278
            echo "
\t\t\t\t\t";
            // line 279
            echo $this->getAttribute((isset($context["CHtml"]) ? $context["CHtml"] : null), "button", array(0 => Yii::t("common", "update"), 1 => array("name" => "cancel", "onClick" => "SecondDialog.sendForm( \"ProdForm\", \"SecondDialog\", \"gridViewSubCamp\" )")), "method");
            echo "

\t\t\t\t";
        } else {
            // line 282
            echo "
\t\t\t\t\t";
            // line 283
            echo $this->getAttribute((isset($context["CHtml"]) ? $context["CHtml"] : null), "button", array(0 => Yii::t("common", "create"), 1 => array("name" => "cancel", "onClick" => "SecondDialog.sendForm( \"ProdForm\", \"SecondDialog\", \"gridViewSubCamp\" )")), "method");
            echo "

\t\t\t\t";
        }
        // line 286
        echo "
\t\t\t</td>

\t\t</tr>

\t</table>



";
        // line 295
        $this->getAttribute((isset($context["this"]) ? $context["this"] : null), "endWidget", array(), "method");
        // line 296
        echo "


<script type=\"text/javascript\">

\tshowParamPriceModel( '";
        // line 301
        echo $this->getAttribute((isset($context["Prod"]) ? $context["Prod"] : null), "priceModel");
        echo "' );
\t
\tsetBdcFields( \$('#Product_idPaymentProcessorSet').val() );

</script>";
    }

    public function getTemplateName()
    {
        return "//product/productShow.html";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  391 => 301,  384 => 296,  382 => 295,  371 => 286,  365 => 283,  362 => 282,  356 => 279,  353 => 278,  351 => 277,  342 => 271,  325 => 257,  288 => 223,  269 => 207,  250 => 191,  231 => 175,  210 => 157,  191 => 141,  172 => 125,  153 => 109,  134 => 93,  115 => 77,  84 => 49,  65 => 33,  60 => 31,  53 => 27,  48 => 24,  46 => 13,  41 => 10,  35 => 7,  32 => 6,  24 => 3,  21 => 2,  19 => 1,);
    }
}
