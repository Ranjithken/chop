<?php

use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Extension\SandboxExtension;
use Twig\Markup;
use Twig\Sandbox\SecurityError;
use Twig\Sandbox\SecurityNotAllowedTagError;
use Twig\Sandbox\SecurityNotAllowedFilterError;
use Twig\Sandbox\SecurityNotAllowedFunctionError;
use Twig\Source;
use Twig\Template;

/* themes/custom/cirp/templates/layout/page.html.twig */
class __TwigTemplate_00843deefc5b5c0fa2d5591aa34388a7 extends Template
{
    private $source;
    private $macros = [];

    public function __construct(Environment $env)
    {
        parent::__construct($env);

        $this->source = $this->getSourceContext();

        $this->parent = false;

        $this->blocks = [
        ];
        $this->sandbox = $this->env->getExtension('\Twig\Extension\SandboxExtension');
        $this->checkSecurity();
    }

    protected function doDisplay(array $context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 46
        $context["nav_classes"] = ((("navbar navbar-expand-lg" . (((        // line 47
($context["b4_navbar_schema"] ?? null) != "none")) ? ((" navbar-" . $this->sandbox->ensureToStringAllowed(($context["b4_navbar_schema"] ?? null), 47, $this->source))) : (" "))) . (((        // line 48
($context["b4_navbar_schema"] ?? null) != "none")) ? ((((($context["b4_navbar_schema"] ?? null) == "dark")) ? (" text-light") : (" text-dark"))) : (" "))) . (((        // line 49
($context["b4_navbar_bg_schema"] ?? null) != "none")) ? ((" bg-" . $this->sandbox->ensureToStringAllowed(($context["b4_navbar_bg_schema"] ?? null), 49, $this->source))) : (" ")));
        // line 51
        echo "
";
        // line 53
        $context["footer_classes"] = (((" " . (((        // line 54
($context["b4_footer_schema"] ?? null) != "none")) ? ((" footer-" . $this->sandbox->ensureToStringAllowed(($context["b4_footer_schema"] ?? null), 54, $this->source))) : (" "))) . (((        // line 55
($context["b4_footer_schema"] ?? null) != "none")) ? ((((($context["b4_footer_schema"] ?? null) == "dark")) ? (" text-light") : (" text-dark"))) : (" "))) . (((        // line 56
($context["b4_footer_bg_schema"] ?? null) != "none")) ? ((" bg-" . $this->sandbox->ensureToStringAllowed(($context["b4_footer_bg_schema"] ?? null), 56, $this->source))) : (" ")));
        // line 58
        echo " ";
        if (twig_get_attribute($this->env, $this->source, ($context["page"] ?? null), "top_menu", [], "any", false, false, true, 58)) {
            // line 59
            echo "    <section class=\"row no-gutters d-none d-lg-block\">
      <div class=\" \">
        ";
            // line 61
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, ($context["page"] ?? null), "top_menu", [], "any", false, false, true, 61), 61, $this->source), "html", null, true);
            echo "
      </div>
    </section>
  ";
        }
        // line 65
        echo "
<section class=\"fixed-top\">
 ";
        // line 67
        if (twig_get_attribute($this->env, $this->source, ($context["page"] ?? null), "top_menu", [], "any", false, false, true, 67)) {
            // line 68
            echo "    <section class=\"row  no-gutters row no-gutters d-none d-lg-block\">
      <div class=\" \">
        ";
            // line 70
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, ($context["page"] ?? null), "top_menu", [], "any", false, false, true, 70), 70, $this->source), "html", null, true);
            echo "
      </div>
    </section>
  ";
        }
        // line 74
        echo "<header >
  <div class=\"container\">
  <div class=\"top-mast \">
    ";
        // line 77
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, ($context["page"] ?? null), "header", [], "any", false, false, true, 77), 77, $this->source), "html", null, true);
        echo "
     <div class=\"right-mast d-xs-block d-lg-none search-area\">
      ";
        // line 79
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, ($context["page"] ?? null), "primary_menu", [], "any", false, false, true, 79), 79, $this->source), "html", null, true);
        echo "
        <div class=\" d-xs-block d-lg-none search-area\">
          <div data-toggle=\"collapse\" data-target=\"#mobileSearch\">
            <div class=\"mobile-search\"><i class=\"fa fa-search\"></i></div>
          </div>
        </div>
      </div>
    </div>
    <div class=\"row\">
      <div class=\"container\">
        <div id=\"mobileSearch\" class=\"collapse\">

  ";
        // line 91
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, Drupal\twig_tweak\TwigTweakExtension::drupalBlock("views_exposed_filter_block:apache_solr_search-solr_results_view", array(), false), "html", null, true);
        echo "
        </div>
      </div>
    </div>

  ";
        // line 96
        if (((twig_get_attribute($this->env, $this->source, ($context["page"] ?? null), "primary_menu", [], "any", false, false, true, 96) || twig_get_attribute($this->env, $this->source, ($context["page"] ?? null), "nav_main", [], "any", false, false, true, 96)) || twig_get_attribute($this->env, $this->source, ($context["page"] ?? null), "secondary_menu", [], "any", false, false, true, 96))) {
            // line 97
            echo "  <nav class=\"p-0 ";
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["nav_classes"] ?? null), 97, $this->source), "html", null, true);
            echo " hidden-sm hidden-xs\" >

  <div class=\"collapse navbar-collapse desktop-show col-12 col-md-auto p-0 justify-content \" id=\"navbarSupportedContent\">
        ";
            // line 100
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, ($context["page"] ?? null), "primary_menu", [], "any", false, false, true, 100), 100, $this->source), "html", null, true);
            echo "

        ";
            // line 102
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, ($context["page"] ?? null), "secondary_menu", [], "any", false, false, true, 102), 102, $this->source), "html", null, true);
            echo "
      </div>
  </nav>
  ";
        }
        // line 106
        echo "  </div>
</header>
</section>
<main role=\"main\" class=\"container-fluid\">
  <a id=\"main-content\" tabindex=\"-1\"></a>";
        // line 111
        echo "
  ";
        // line 113
        $context["sidebar_first_classes"] = (((twig_get_attribute($this->env, $this->source, ($context["page"] ?? null), "sidebar_first", [], "any", false, false, true, 113) && twig_get_attribute($this->env, $this->source, ($context["page"] ?? null), "sidebar_second", [], "any", false, false, true, 113))) ? ("col-12 col-sm-6 col-lg-3") : ("col-12 col-lg-3"));
        // line 115
        echo "
  ";
        // line 117
        $context["sidebar_second_classes"] = (((twig_get_attribute($this->env, $this->source, ($context["page"] ?? null), "sidebar_first", [], "any", false, false, true, 117) && twig_get_attribute($this->env, $this->source, ($context["page"] ?? null), "sidebar_second", [], "any", false, false, true, 117))) ? ("col-12 col-sm-6 col-lg-3") : ("col-12 col-lg-3"));
        // line 119
        echo "
  ";
        // line 121
        $context["content_classes"] = (((twig_get_attribute($this->env, $this->source, ($context["page"] ?? null), "sidebar_first", [], "any", false, false, true, 121) && twig_get_attribute($this->env, $this->source, ($context["page"] ?? null), "sidebar_second", [], "any", false, false, true, 121))) ? ("col-12 col-lg-6") : ((((twig_get_attribute($this->env, $this->source, ($context["page"] ?? null), "sidebar_first", [], "any", false, false, true, 121) || twig_get_attribute($this->env, $this->source, ($context["page"] ?? null), "sidebar_second", [], "any", false, false, true, 121))) ? ("col-12 col-lg-9") : ("col-12"))));
        // line 123
        echo "

  <div class=\"";
        // line 125
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["b4_top_container"] ?? null), 125, $this->source), "html", null, true);
        echo "\">

    <div class=\"row no-gutters\">
      ";
        // line 128
        if (twig_get_attribute($this->env, $this->source, ($context["page"] ?? null), "sidebar_first", [], "any", false, false, true, 128)) {
            // line 129
            echo "        <div class=\"order-2 order-lg-1 ";
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["sidebar_first_classes"] ?? null), 129, $this->source), "html", null, true);
            echo "\">
          ";
            // line 130
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, ($context["page"] ?? null), "sidebar_first", [], "any", false, false, true, 130), 130, $this->source), "html", null, true);
            echo "
        </div>
      ";
        }
        // line 133
        echo "      <div class=\"order-1 order-lg-2 ";
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["content_classes"] ?? null), 133, $this->source), "html", null, true);
        echo "\">
        ";
        // line 134
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, ($context["page"] ?? null), "content", [], "any", false, false, true, 134), 134, $this->source), "html", null, true);
        echo "
      </div>
      ";
        // line 136
        if (twig_get_attribute($this->env, $this->source, ($context["page"] ?? null), "sidebar_second", [], "any", false, false, true, 136)) {
            // line 137
            echo "        <div class=\"order-3 ";
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["sidebar_second_classes"] ?? null), 137, $this->source), "html", null, true);
            echo "\">
          ";
            // line 138
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, ($context["page"] ?? null), "sidebar_second", [], "any", false, false, true, 138), 138, $this->source), "html", null, true);
            echo "
        </div>
      ";
        }
        // line 141
        echo "    </div>
  </div>

</main>

";
        // line 146
        if (twig_get_attribute($this->env, $this->source, ($context["page"] ?? null), "footer", [], "any", false, false, true, 146)) {
            // line 147
            echo "<footer class=\"mt-auto ";
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["footer_classes"] ?? null), 147, $this->source), "html", null, true);
            echo "\">
  <div class=\"";
            // line 148
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["b4_top_container"] ?? null), 148, $this->source), "html", null, true);
            echo "\">
    ";
            // line 149
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, ($context["page"] ?? null), "footer", [], "any", false, false, true, 149), 149, $this->source), "html", null, true);
            echo "
  </div>
</footer>
";
        }
        // line 153
        echo "
<a class=\"scroll-to-top\"  id=\"go-to-top\">
  <i class=\"fa fa-angle-up\"></i>
</a>

<footer class=\"mt-auto ";
        // line 158
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["footer_classes"] ?? null), 158, $this->source), "html", null, true);
        echo "\">

    <div class=\"container\">
      <div class=\"row\">
        ";
        // line 162
        if (twig_get_attribute($this->env, $this->source, ($context["page"] ?? null), "footer_first", [], "any", false, false, true, 162)) {
            // line 163
            echo "          <div class=\"order-1 order-lg-1 col-12 col-md-3\">
            ";
            // line 164
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, ($context["page"] ?? null), "footer_first", [], "any", false, false, true, 164), 164, $this->source), "html", null, true);
            echo "
          </div>
        ";
        }
        // line 167
        echo "          ";
        if (twig_get_attribute($this->env, $this->source, ($context["page"] ?? null), "footer_second", [], "any", false, false, true, 167)) {
            // line 168
            echo "          <div class=\"order-2 col-12 col-md-3\">
            ";
            // line 169
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, ($context["page"] ?? null), "footer_second", [], "any", false, false, true, 169), 169, $this->source), "html", null, true);
            echo "
          </div>
        ";
        }
        // line 172
        echo "        ";
        if (twig_get_attribute($this->env, $this->source, ($context["page"] ?? null), "footer_third", [], "any", false, false, true, 172)) {
            // line 173
            echo "          <div class=\"order-3 col-12 col-md-3\">
            ";
            // line 174
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, ($context["page"] ?? null), "footer_third", [], "any", false, false, true, 174), 174, $this->source), "html", null, true);
            echo "
          </div>
        ";
        }
        // line 177
        echo "        ";
        if (twig_get_attribute($this->env, $this->source, ($context["page"] ?? null), "footer_fourth", [], "any", false, false, true, 177)) {
            // line 178
            echo "          <div class=\"order-4 col-12 col-md-3\">
            ";
            // line 179
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, ($context["page"] ?? null), "footer_fourth", [], "any", false, false, true, 179), 179, $this->source), "html", null, true);
            echo "
          </div>
        ";
        }
        // line 182
        echo "      </div>

      <div class=\"row copyright pt-20 d-xs-block d-lg-none \">
      ";
        // line 185
        if (twig_get_attribute($this->env, $this->source, ($context["page"] ?? null), "copyright", [], "any", false, false, true, 185)) {
            // line 186
            echo "          <div class=\"order-4 col-12 col-md-12\">
            ";
            // line 187
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, ($context["page"] ?? null), "copyright", [], "any", false, false, true, 187), 187, $this->source), "html", null, true);
            echo "
          </div>
        ";
        }
        // line 190
        echo "      </div>
    </div>
</footer>

";
        $this->env->getExtension('\Drupal\Core\Template\TwigExtension')
            ->checkDeprecations($context, ["b4_navbar_schema", "b4_navbar_bg_schema", "b4_footer_schema", "b4_footer_bg_schema", "page", "b4_top_container"]);    }

    /**
     * @codeCoverageIgnore
     */
    public function getTemplateName()
    {
        return "themes/custom/cirp/templates/layout/page.html.twig";
    }

    /**
     * @codeCoverageIgnore
     */
    public function isTraitable()
    {
        return false;
    }

    /**
     * @codeCoverageIgnore
     */
    public function getDebugInfo()
    {
        return array (  306 => 190,  300 => 187,  297 => 186,  295 => 185,  290 => 182,  284 => 179,  281 => 178,  278 => 177,  272 => 174,  269 => 173,  266 => 172,  260 => 169,  257 => 168,  254 => 167,  248 => 164,  245 => 163,  243 => 162,  236 => 158,  229 => 153,  222 => 149,  218 => 148,  213 => 147,  211 => 146,  204 => 141,  198 => 138,  193 => 137,  191 => 136,  186 => 134,  181 => 133,  175 => 130,  170 => 129,  168 => 128,  162 => 125,  158 => 123,  156 => 121,  153 => 119,  151 => 117,  148 => 115,  146 => 113,  143 => 111,  137 => 106,  130 => 102,  125 => 100,  118 => 97,  116 => 96,  108 => 91,  93 => 79,  88 => 77,  83 => 74,  76 => 70,  72 => 68,  70 => 67,  66 => 65,  59 => 61,  55 => 59,  52 => 58,  50 => 56,  49 => 55,  48 => 54,  47 => 53,  44 => 51,  42 => 49,  41 => 48,  40 => 47,  39 => 46,);
    }

    public function getSourceContext()
    {
        return new Source("", "themes/custom/cirp/templates/layout/page.html.twig", "D:\\xampp\\htdocs\\cvpd10\\web\\themes\\custom\\cirp\\templates\\layout\\page.html.twig");
    }
    
    public function checkSecurity()
    {
        static $tags = array("set" => 46, "if" => 58);
        static $filters = array("escape" => 61);
        static $functions = array("drupal_block" => 91);

        try {
            $this->sandbox->checkSecurity(
                ['set', 'if'],
                ['escape'],
                ['drupal_block']
            );
        } catch (SecurityError $e) {
            $e->setSourceContext($this->source);

            if ($e instanceof SecurityNotAllowedTagError && isset($tags[$e->getTagName()])) {
                $e->setTemplateLine($tags[$e->getTagName()]);
            } elseif ($e instanceof SecurityNotAllowedFilterError && isset($filters[$e->getFilterName()])) {
                $e->setTemplateLine($filters[$e->getFilterName()]);
            } elseif ($e instanceof SecurityNotAllowedFunctionError && isset($functions[$e->getFunctionName()])) {
                $e->setTemplateLine($functions[$e->getFunctionName()]);
            }

            throw $e;
        }

    }
}
