<?php

/* //sav/refundTool.html */
class __TwigTemplate_a955a33da5fd5f89c773f1eb66c2f52f extends Twig_Template
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
        echo $this->env->getExtension('TwigEsoterExt')->pageTitle("SAV - Refund Tool");
        echo "

";
        // line 3
        echo $this->env->getExtension('TwigEsoterExt')->loadJS(($this->getAttribute((isset($context["App"]) ? $context["App"] : null), "baseUrl") . "/js/SAV/refundTool.js"));
        echo "
";
        // line 4
        echo $this->env->getExtension('TwigEsoterExt')->loadJS(($this->getAttribute((isset($context["App"]) ? $context["App"] : null), "baseUrl") . "/js/SAV/customerProfile.js"));
        echo "

";
        // line 6
        if (((isset($context["show"]) ? $context["show"] : null) == 1)) {
            // line 7
            echo "\t<h1>Refund Tool - Incomplete data client</h1>
";
        } elseif (((isset($context["show"]) ? $context["show"] : null) == 2)) {
            // line 9
            echo "\t<h1>Refund Tool - Client to monitor</h1>
";
        } else {
            // line 11
            echo "\t<h1>Refund Tool - All</h1>
";
        }
        // line 13
        echo "
";
        // line 14
        echo $this->env->getExtension('TwigEsoterExt')->printFlashes((isset($context["InvoiceUpdate"]) ? $context["InvoiceUpdate"] : null));
        echo "

";
        // line 16
        $this->getAttribute((isset($context["this"]) ? $context["this"] : null), "widget", array(0 => "zii.widgets.grid.CGridView", 1 => array("id" => "gridViewUser", "dataProvider" => (isset($context["Provider"]) ? $context["Provider"] : null), "filter" => (isset($context["Invoices"]) ? $context["Invoices"] : null), "columns" => array(0 => array("header" => "Information", "value" => "\$data->getUser()->name()"), 1 => "emailUser", 2 => array("name" => "creationDate", "value" => "( new DateTime( \$data->creationDate ) )->format( \"d-m-Y H:i\" )"), 3 => array("name" => "refundDate", "value" => "( new DateTime( \$data->refundDate ) )->format( \"d-m-Y H:i\" )"), 4 => "paymentProcessor", 5 => array("header" => "Ref Bank", "value" => ""), 6 => "ref1Transaction", 7 => array("header" => "Amount", "value" => "\$data->getTotalInvoice()"), 8 => "currency", 9 => "deliveryMode", 10 => array("name" => "invoiceStatus", "value" => "\$data->humanInvoiceStatus()"), 11 => array("name" => "refundStatus", "value" => "\$data->humanRefundStatus()"), 12 => array("class" => "CButtonColumn", "template" => "{view}{edit}", "buttons" => array("view" => array("url" => "Yii::App()->createUrl( \"/SAV/customerProfileUpdate\", array_merge( \$_GET, array( \"email\" => \$data->emailUser, \"partialRender\" => true ) ) )", "click" => "function() { return customerProfileUpdate( \$(this) ); }"), "edit" => array("label" => "Remboursé", "imageUrl" => ($this->getAttribute($this->getAttribute((isset($context["App"]) ? $context["App"] : null), "request"), "baseUrl") . "/images/update.png"), "url" => "Yii::App()->createUrl( \"/SAV/refundTool\", array_merge( \$_GET, array( \"id\" => \$data->primaryKey ) ) )", "click" => "function() { return askForRefund( \$(this) ); }", "visible" => "\$data->refundStatus == INVOICE_REFUND_IN_PROGRESS")))))), "method");
        // line 79
        echo "
<br />
<br />
<br />
<br />
<br />
<br />
<br />
<br />
<br />
<br />
<br />
<br />
<br />
<br />
<br />
<br />
<br />
<br />
<br />
<br />
<br />
<br />
<br />
<br />
<br />
<br />
<br />
<br />
<br />
<br />
<br />
<br />
<br />
<br />
<br />
<br />
<br />
<br />
<br />
<br />
<br />
<br />
<br />
<br />
<br />
<br />
<br />
<br />
<br />
<br />
<br />
<br />
<br />
<br />
<br />
<br />
<br />
<br />
<br />
<br />";
    }

    public function getTemplateName()
    {
        return "//sav/refundTool.html";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  57 => 79,  55 => 16,  50 => 14,  47 => 13,  43 => 11,  39 => 9,  35 => 7,  33 => 6,  28 => 4,  24 => 3,  19 => 1,);
    }
}
