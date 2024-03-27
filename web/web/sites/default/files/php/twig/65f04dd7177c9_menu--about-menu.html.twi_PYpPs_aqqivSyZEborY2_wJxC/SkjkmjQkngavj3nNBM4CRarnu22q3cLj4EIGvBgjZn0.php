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

/* themes/custom/cirp/templates/navigation/menu--about-menu.html.twig */
class __TwigTemplate_f8adffd8cc96ac599e16b68c3485e067 extends Template
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
        // line 21
        $macros["menus"] = $this->macros["menus"] = $this;
        // line 22
        echo "
";
        // line 27
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar(twig_call_macro($macros["menus"], "macro_menu_links", [($context["items"] ?? null), ($context["attributes"] ?? null), 0], 27, $context, $this->getSourceContext()));
        echo "

";
        $this->env->getExtension('\Drupal\Core\Template\TwigExtension')
            ->checkDeprecations($context, ["_self", "items", "attributes", "menu_level"]);    }

    // line 29
    public function macro_menu_links($__items__ = null, $__attributes__ = null, $__menu_level__ = null, ...$__varargs__)
    {
        $macros = $this->macros;
        $context = $this->env->mergeGlobals([
            "items" => $__items__,
            "attributes" => $__attributes__,
            "menu_level" => $__menu_level__,
            "varargs" => $__varargs__,
        ]);

        $blocks = [];

        ob_start(function () { return ''; });
        try {
            // line 30
            echo "  ";
            $macros["menus"] = $this;
            // line 31
            echo "  ";
            // line 32
            $context["submenu_classes"] = ["nav", "navbar-nav", "sub-nav"];
            // line 39
            echo "  ";
            // line 40
            $context["menu_classes"] = ["nav", "navbar-nav", "parent-nav", "about-menu"];
            // line 48
            echo "  ";
            if (($context["items"] ?? null)) {
                // line 49
                echo "    ";
                if ((($context["menu_level"] ?? null) == 0)) {
                    // line 50
                    echo "<ul";
                    echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, ($context["attributes"] ?? null), "addClass", [($context["menu_classes"] ?? null)], "method", false, false, true, 50), 50, $this->source), "html", null, true);
                    echo " ";
                    echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["menu_level"] ?? null), 50, $this->source), "html", null, true);
                    echo ">
  ";
                } elseif ((                // line 51
($context["menu_level"] ?? null) == 3)) {
                    // line 52
                    echo "  <ul ";
                    echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["attributes"] ?? null), "removeClass", [($context["menu_classes"] ?? null)], "method", false, false, true, 52), "addClass", [($context["submenu_classes"] ?? null)], "method", false, false, true, 52), 52, $this->source), "html", null, true);
                    echo " ";
                    echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["menu_level"] ?? null), 52, $this->source), "html", null, true);
                    echo ">  
  ";
                } else {
                    // line 54
                    echo "<ul ";
                    echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["attributes"] ?? null), "removeClass", [($context["submenu_classes"] ?? null)], "method", false, false, true, 54), "addClass", [($context["menu_classes"] ?? null)], "method", false, false, true, 54), 54, $this->source), "html", null, true);
                    echo " ";
                    echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["menu_level"] ?? null), 54, $this->source), "html", null, true);
                    echo ">
  ";
                }
                // line 56
                echo "  ";
                $context['_parent'] = $context;
                $context['_seq'] = twig_ensure_traversable(($context["items"] ?? null));
                foreach ($context['_seq'] as $context["_key"] => $context["item"]) {
                    // line 57
                    echo "\t";
                    if ((($context["menu_level"] ?? null) == 0)) {
                        // line 58
                        echo "\t<h5 class=";
                        echo $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar(((twig_get_attribute($this->env, $this->source, $context["item"], "in_active_trail", [], "any", false, false, true, 58)) ? ("active") : ("hidden")));
                        echo ">";
                        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, $context["item"], "title", [], "any", false, false, true, 58), 58, $this->source), "html", null, true);
                        echo "</h5>

 ";
                        // line 61
                        $context["classes_link"] = ["nav-link", "sub-link", ((twig_get_attribute($this->env, $this->source,                         // line 64
$context["item"], "is_expanded", [], "any", false, false, true, 64)) ? ("dropdown-toggle") : ("")), ((twig_get_attribute($this->env, $this->source,                         // line 65
$context["item"], "is_collapsed", [], "any", false, false, true, 65)) ? ("dropdown-toggles navicon") : ("")), ((twig_get_attribute($this->env, $this->source,                         // line 66
$context["item"], "in_active_trail", [], "any", false, false, true, 66)) ? ("active is-active") : (""))];
                        // line 69
                        echo "    ";
                        // line 70
                        $context["classes_li"] = ["nav-item", "parent-li"];
                        // line 75
                        echo "    ";
                    } elseif ((($context["menu_level"] ?? null) == 1)) {
                        // line 76
                        echo "    ";
                        // line 77
                        $context["classes_link"] = ["nav-link", "child-link", ((twig_get_attribute($this->env, $this->source,                         // line 80
$context["item"], "is_expanded", [], "any", false, false, true, 80)) ? ("dropdown-toggle") : ("")), ((twig_get_attribute($this->env, $this->source,                         // line 81
$context["item"], "is_collapsed", [], "any", false, false, true, 81)) ? ("dropdown-toggles navicon") : ("")), ((twig_get_attribute($this->env, $this->source,                         // line 82
$context["item"], "in_active_trail", [], "any", false, false, true, 82)) ? ("active is-active") : (""))];
                        // line 85
                        echo "     ";
                        // line 86
                        $context["classes_li"] = ["nav-item", "child-li"];
                        // line 91
                        echo "    ";
                    } elseif ((($context["menu_level"] ?? null) == 2)) {
                        // line 92
                        echo "    ";
                        // line 93
                        $context["classes_link"] = ["nav-link", "subchild-link", ((twig_get_attribute($this->env, $this->source,                         // line 96
$context["item"], "is_expanded", [], "any", false, false, true, 96)) ? ("dropdown-toggle") : ("")), ((twig_get_attribute($this->env, $this->source,                         // line 97
$context["item"], "is_collapsed", [], "any", false, false, true, 97)) ? ("dropdown-toggles navicon") : ("")), ((twig_get_attribute($this->env, $this->source,                         // line 98
$context["item"], "in_active_trail", [], "any", false, false, true, 98)) ? ("active is-active") : (""))];
                        // line 101
                        echo "     ";
                        // line 102
                        $context["classes_li"] = ["nav-item", "subchild-li"];
                        // line 107
                        echo "  ";
                    } else {
                        // line 108
                        echo "   ";
                        // line 109
                        $context["classes_link"] = ["nav-link", "subchildchild-link", ((twig_get_attribute($this->env, $this->source,                         // line 112
$context["item"], "is_expanded", [], "any", false, false, true, 112)) ? ("dropdown-toggle") : ("")), ((twig_get_attribute($this->env, $this->source,                         // line 113
$context["item"], "is_collapsed", [], "any", false, false, true, 113)) ? ("dropdown-toggles navicon") : ("")), ((twig_get_attribute($this->env, $this->source,                         // line 114
$context["item"], "in_active_trail", [], "any", false, false, true, 114)) ? ("active is-active") : (""))];
                        // line 117
                        echo "    ";
                        // line 118
                        $context["classes_li"] = ["nav-item", "subchildchild-li"];
                        // line 123
                        echo "  ";
                    }
                    // line 124
                    echo "

    <li";
                    // line 126
                    echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, $context["item"], "attributes", [], "any", false, false, true, 126), "addClass", [($context["classes_li"] ?? null)], "method", false, false, true, 126), 126, $this->source), "html", null, true);
                    echo ">
      ";
                    // line 127
                    echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->extensions['Drupal\Core\Template\TwigExtension']->getLink($this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, $context["item"], "title", [], "any", false, false, true, 127), 127, $this->source), $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, $context["item"], "url", [], "any", false, false, true, 127), 127, $this->source), ["class" => ($context["classes_link"] ?? null)]), "html", null, true);
                    echo "
      ";
                    // line 128
                    if (twig_get_attribute($this->env, $this->source, $context["item"], "below", [], "any", false, false, true, 128)) {
                        // line 129
                        echo "
      ";
                        // line 130
                        if ((($context["menu_level"] ?? null) != 0)) {
                            // line 131
                            echo "
         ";
                        }
                        // line 133
                        echo "        ";
                        echo $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar(twig_call_macro($macros["menus"], "macro_menu_links", [twig_get_attribute($this->env, $this->source, $context["item"], "below", [], "any", false, false, true, 133), ($context["attributes"] ?? null), (($context["menu_level"] ?? null) + 1)], 133, $context, $this->getSourceContext()));
                        echo "
      ";
                    }
                    // line 135
                    echo "    </li>
  ";
                }
                $_parent = $context['_parent'];
                unset($context['_seq'], $context['_iterated'], $context['_key'], $context['item'], $context['_parent'], $context['loop']);
                $context = array_intersect_key($context, $_parent) + $_parent;
                // line 137
                echo "</ul>
  ";
            }

            return ('' === $tmp = ob_get_contents()) ? '' : new Markup($tmp, $this->env->getCharset());
        } finally {
            ob_end_clean();
        }
    }

    /**
     * @codeCoverageIgnore
     */
    public function getTemplateName()
    {
        return "themes/custom/cirp/templates/navigation/menu--about-menu.html.twig";
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
        return array (  215 => 137,  208 => 135,  202 => 133,  198 => 131,  196 => 130,  193 => 129,  191 => 128,  187 => 127,  183 => 126,  179 => 124,  176 => 123,  174 => 118,  172 => 117,  170 => 114,  169 => 113,  168 => 112,  167 => 109,  165 => 108,  162 => 107,  160 => 102,  158 => 101,  156 => 98,  155 => 97,  154 => 96,  153 => 93,  151 => 92,  148 => 91,  146 => 86,  144 => 85,  142 => 82,  141 => 81,  140 => 80,  139 => 77,  137 => 76,  134 => 75,  132 => 70,  130 => 69,  128 => 66,  127 => 65,  126 => 64,  125 => 61,  117 => 58,  114 => 57,  109 => 56,  101 => 54,  93 => 52,  91 => 51,  84 => 50,  81 => 49,  78 => 48,  76 => 40,  74 => 39,  72 => 32,  70 => 31,  67 => 30,  52 => 29,  44 => 27,  41 => 22,  39 => 21,);
    }

    public function getSourceContext()
    {
        return new Source("", "themes/custom/cirp/templates/navigation/menu--about-menu.html.twig", "D:\\xampp\\htdocs\\cvpd10\\web\\themes\\custom\\cirp\\templates\\navigation\\menu--about-menu.html.twig");
    }
    
    public function checkSecurity()
    {
        static $tags = array("import" => 21, "macro" => 29, "set" => 32, "if" => 48, "for" => 56);
        static $filters = array("escape" => 50);
        static $functions = array("link" => 127);

        try {
            $this->sandbox->checkSecurity(
                ['import', 'macro', 'set', 'if', 'for'],
                ['escape'],
                ['link']
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
