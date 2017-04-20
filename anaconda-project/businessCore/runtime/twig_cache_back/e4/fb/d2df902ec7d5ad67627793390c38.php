<?php

/* //sav/customerProfileShow.html */
class __TwigTemplate_e4fbd2df902ec7d5ad67627793390c38 extends Twig_Template
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

<h2>History : Orders / Invoices</h2>

";
        // line 9
        echo $this->env->getExtension('TwigEsoterExt')->printFlashes();
        echo "

<input type=\"hidden\" name=\"currentURL\" id=\"currentURL\" value=\"";
        // line 11
        echo $this->getAttribute($this->getAttribute((isset($context["App"]) ? $context["App"] : null), "request"), "url");
        echo "\" />

<div class=\"grid-view\">
\t<table width=\"100%\" border=\"1\" cellspacing=\"0\" class=\"items\">
\t\t<tr>
\t\t\t<th colspan=\"8\">Order</th>
\t\t\t<th colspan=\"4\">Payment</th>
\t\t\t<th colspan=\"4\">Actions</th>
\t\t</tr>
\t\t<tr>
\t\t\t<th>ID Invoice</th>
\t\t\t<th>Date Invoice</th>
\t\t\t<th>Total</th>
\t\t\t<th>Status</th>
\t\t\t<th>Delivery</th>
\t\t\t<th>Ref Product</th>
\t\t\t<th>Qty</th>
\t\t\t<th>Amount</th>

\t\t\t<th>Payment Type</th>
\t\t\t<th>Num Check</th>
\t\t\t<th>Ref Bank</th>
\t\t\t<th>Ref Transaction</th>

\t\t\t<th>Product</th>
\t\t\t<th>Refund Status</th>
\t\t\t<th>Refund Date</th>
\t\t\t<th>Refund</th>
\t\t</tr>

\t\t";
        // line 41
        $context['_parent'] = (array) $context;
        $context['_seq'] = twig_ensure_traversable((isset($context["Invoices"]) ? $context["Invoices"] : null));
        $context['loop'] = array(
          'parent' => $context['_parent'],
          'index0' => 0,
          'index'  => 1,
          'first'  => true,
        );
        if (is_array($context['_seq']) || (is_object($context['_seq']) && $context['_seq'] instanceof Countable)) {
            $length = count($context['_seq']);
            $context['loop']['revindex0'] = $length - 1;
            $context['loop']['revindex'] = $length;
            $context['loop']['length'] = $length;
            $context['loop']['last'] = 1 === $length;
        }
        foreach ($context['_seq'] as $context["_key"] => $context["Invoice"]) {
            // line 42
            echo "\t\t\t<tr class=\"";
            if ((($this->getAttribute((isset($context["loop"]) ? $context["loop"] : null), "index") % 2) == 0)) {
                echo "odd";
            } else {
                echo "even";
            }
            echo "\" style=\"text-align:center;\">
\t\t\t\t<td rowspan=\"";
            // line 43
            echo twig_length_filter($this->env, $this->getAttribute((isset($context["Invoice"]) ? $context["Invoice"] : null), "RecordInvoice"));
            echo "\">";
            echo $this->getAttribute((isset($context["Invoice"]) ? $context["Invoice"] : null), "id");
            echo "</td>
\t\t\t\t<td rowspan=\"";
            // line 44
            echo twig_length_filter($this->env, $this->getAttribute((isset($context["Invoice"]) ? $context["Invoice"] : null), "RecordInvoice"));
            echo "\">";
            echo $this->getAttribute((isset($context["Invoice"]) ? $context["Invoice"] : null), "creationDate");
            echo "</td>
\t\t\t\t<td rowspan=\"";
            // line 45
            echo twig_length_filter($this->env, $this->getAttribute((isset($context["Invoice"]) ? $context["Invoice"] : null), "RecordInvoice"));
            echo "\">";
            echo $this->getAttribute((isset($context["Invoice"]) ? $context["Invoice"] : null), "getTotalInvoice", array(), "method");
            echo "</td>
\t\t\t\t<td rowspan=\"";
            // line 46
            echo twig_length_filter($this->env, $this->getAttribute((isset($context["Invoice"]) ? $context["Invoice"] : null), "RecordInvoice"));
            echo "\">";
            echo $this->getAttribute((isset($context["Invoice"]) ? $context["Invoice"] : null), "invoiceStatus");
            echo "</td>
\t\t\t\t<td rowspan=\"";
            // line 47
            echo twig_length_filter($this->env, $this->getAttribute((isset($context["Invoice"]) ? $context["Invoice"] : null), "RecordInvoice"));
            echo "\"></td>

\t\t\t\t<td>";
            // line 49
            echo $this->getAttribute($this->getAttribute($this->getAttribute((isset($context["Invoice"]) ? $context["Invoice"] : null), "RecordInvoice"), 0, array(), "array"), "refProduct");
            echo "</td>
\t\t\t\t<td>";
            // line 50
            echo $this->getAttribute($this->getAttribute($this->getAttribute((isset($context["Invoice"]) ? $context["Invoice"] : null), "RecordInvoice"), 0, array(), "array"), "qty");
            echo "</td>
\t\t\t\t<td>";
            // line 51
            echo $this->getAttribute($this->getAttribute($this->getAttribute((isset($context["Invoice"]) ? $context["Invoice"] : null), "RecordInvoice"), 0, array(), "array"), "priceATI");
            echo "</td>

\t\t\t\t<td rowspan=\"";
            // line 53
            echo twig_length_filter($this->env, $this->getAttribute((isset($context["Invoice"]) ? $context["Invoice"] : null), "RecordInvoice"));
            echo "\">";
            echo $this->getAttribute((isset($context["Invoice"]) ? $context["Invoice"] : null), "paymentProcessor");
            echo "</td>
\t\t\t\t<td rowspan=\"";
            // line 54
            echo twig_length_filter($this->env, $this->getAttribute((isset($context["Invoice"]) ? $context["Invoice"] : null), "RecordInvoice"));
            echo "\">";
            echo $this->getAttribute((isset($context["Invoice"]) ? $context["Invoice"] : null), "numCheck");
            echo "</td>
\t\t\t\t<td rowspan=\"";
            // line 55
            echo twig_length_filter($this->env, $this->getAttribute((isset($context["Invoice"]) ? $context["Invoice"] : null), "RecordInvoice"));
            echo "\">";
            echo $this->getAttribute((isset($context["Invoice"]) ? $context["Invoice"] : null), "ref2Transaction");
            echo "</td>
\t\t\t\t<td rowspan=\"";
            // line 56
            echo twig_length_filter($this->env, $this->getAttribute((isset($context["Invoice"]) ? $context["Invoice"] : null), "RecordInvoice"));
            echo "\">";
            echo $this->getAttribute((isset($context["Invoice"]) ? $context["Invoice"] : null), "ref1Transaction");
            echo "</td>

\t\t\t\t<td rowspan=\"";
            // line 58
            echo twig_length_filter($this->env, $this->getAttribute((isset($context["Invoice"]) ? $context["Invoice"] : null), "RecordInvoice"));
            echo "\">";
            echo twig_join_filter($this->getAttribute((isset($context["Invoice"]) ? $context["Invoice"] : null), "getProductsRef", array(), "method"), ", ");
            echo "</td>
\t\t\t\t<td rowspan=\"";
            // line 59
            echo twig_length_filter($this->env, $this->getAttribute((isset($context["Invoice"]) ? $context["Invoice"] : null), "RecordInvoice"));
            echo "\">";
            echo $this->getAttribute((isset($context["Invoice"]) ? $context["Invoice"] : null), "humanRefundStatus", array(), "method");
            echo "</td>
\t\t\t\t<td rowspan=\"";
            // line 60
            echo twig_length_filter($this->env, $this->getAttribute((isset($context["Invoice"]) ? $context["Invoice"] : null), "RecordInvoice"));
            echo "\">";
            echo $this->getAttribute((isset($context["Invoice"]) ? $context["Invoice"] : null), "refundDate");
            echo "</td>
\t\t\t\t<td rowspan=\"";
            // line 61
            echo twig_length_filter($this->env, $this->getAttribute((isset($context["Invoice"]) ? $context["Invoice"] : null), "RecordInvoice"));
            echo "\">
\t\t\t\t\t";
            // line 62
            if ((($this->getAttribute((isset($context["Invoice"]) ? $context["Invoice"] : null), "refundStatus") == twig_constant("INVOICE_REFUND_NOT_ASKED")) || ($this->getAttribute((isset($context["Invoice"]) ? $context["Invoice"] : null), "refundStatus") == null))) {
                // line 63
                echo "\t\t\t\t\t\t<input type=\"button\" name=\"askToRefund\" value=\"Ask to refund\" onClick='updateRefundStatus( ";
                echo $this->getAttribute((isset($context["Invoice"]) ? $context["Invoice"] : null), "id");
                echo ", ";
                echo twig_constant("INVOICE_REFUND_IN_PROGRESS");
                echo " );' />
\t\t\t\t\t";
            } elseif (($this->getAttribute((isset($context["Invoice"]) ? $context["Invoice"] : null), "refundStatus") == twig_constant("INVOICE_REFUND_IN_PROGRESS"))) {
                // line 65
                echo "\t\t\t\t\t\t<input type=\"button\" name=\"cancelRefund\" value=\"Cancel refund\" onClick='updateRefundStatus( ";
                echo $this->getAttribute((isset($context["Invoice"]) ? $context["Invoice"] : null), "id");
                echo ", ";
                echo twig_constant("INVOICE_CADDIE");
                echo " );' />
\t\t\t\t\t";
            }
            // line 67
            echo "\t\t\t\t</td>
\t\t\t</tr>
\t\t\t";
            // line 69
            $context['_parent'] = (array) $context;
            $context['_seq'] = twig_ensure_traversable($this->getAttribute((isset($context["Invoice"]) ? $context["Invoice"] : null), "RecordInvoice"));
            $context['loop'] = array(
              'parent' => $context['_parent'],
              'index0' => 0,
              'index'  => 1,
              'first'  => true,
            );
            foreach ($context['_seq'] as $context["index"] => $context["Prod"]) {
                if (((isset($context["index"]) ? $context["index"] : null) != 0)) {
                    // line 70
                    echo "\t\t\t\t<tr class=\"";
                    if ((($this->getAttribute((isset($context["loop"]) ? $context["loop"] : null), "index") % 2) == 0)) {
                        echo "odd";
                    } else {
                        echo "even";
                    }
                    echo "\" style=\"text-align:center;\">
\t\t\t\t\t<td>";
                    // line 71
                    echo $this->getAttribute((isset($context["Prod"]) ? $context["Prod"] : null), "refProduct");
                    echo "</td>
\t\t\t\t\t<td>";
                    // line 72
                    echo $this->getAttribute((isset($context["Prod"]) ? $context["Prod"] : null), "qty");
                    echo "</td>
\t\t\t\t\t<td>";
                    // line 73
                    echo $this->getAttribute((isset($context["Prod"]) ? $context["Prod"] : null), "priceATI");
                    echo "</td>
\t\t\t\t</tr>
\t\t\t";
                    ++$context['loop']['index0'];
                    ++$context['loop']['index'];
                    $context['loop']['first'] = false;
                }
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['index'], $context['Prod'], $context['_parent'], $context['loop']);
            $context = array_merge($_parent, array_intersect_key($context, $_parent));
            // line 76
            echo "\t\t";
            ++$context['loop']['index0'];
            ++$context['loop']['index'];
            $context['loop']['first'] = false;
            if (isset($context['loop']['length'])) {
                --$context['loop']['revindex0'];
                --$context['loop']['revindex'];
                $context['loop']['last'] = 0 === $context['loop']['revindex0'];
            }
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['Invoice'], $context['_parent'], $context['loop']);
        $context = array_merge($_parent, array_intersect_key($context, $_parent));
        // line 77
        echo "\t</table>
</div>


";
    }

    public function getTemplateName()
    {
        return "//sav/customerProfileShow.html";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  266 => 77,  252 => 76,  239 => 73,  235 => 72,  231 => 71,  222 => 70,  211 => 69,  207 => 67,  199 => 65,  191 => 63,  189 => 62,  185 => 61,  179 => 60,  173 => 59,  167 => 58,  160 => 56,  154 => 55,  148 => 54,  142 => 53,  137 => 51,  133 => 50,  129 => 49,  124 => 47,  118 => 46,  112 => 45,  106 => 44,  100 => 43,  91 => 42,  74 => 41,  41 => 11,  36 => 9,  29 => 5,  24 => 3,  19 => 1,);
    }
}
