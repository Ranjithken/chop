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

/* themes/custom/cirp/templates/slick-wrapper.html.twig */
class __TwigTemplate_3c099c9690873996514068785a0ee0bd extends Template
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
        // line 15
        echo "<div class=\"container recent-articles\">
  <div class=\"border-top col-12\">
  ";
        // line 18
        $context["classes"] = ["slick-wrapper", ((twig_get_attribute($this->env, $this->source,         // line 20
($context["settings"] ?? null), "nav", [], "any", false, false, true, 20)) ? ("slick-wrapper--asnavfor") : ("")), ((twig_get_attribute($this->env, $this->source,         // line 21
($context["settings"] ?? null), "skin", [], "any", false, false, true, 21)) ? (("slick-wrapper--" . \Drupal\Component\Utility\Html::getClass($this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, ($context["settings"] ?? null), "skin", [], "any", false, false, true, 21), 21, $this->source)))) : ("")), ((twig_get_attribute($this->env, $this->source,         // line 22
($context["settings"] ?? null), "skin_thumbnail", [], "any", false, false, true, 22)) ? (("slick-wrapper--" . \Drupal\Component\Utility\Html::getClass($this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, ($context["settings"] ?? null), "skin_thumbnail", [], "any", false, false, true, 22), 22, $this->source)))) : ("")), ((twig_get_attribute($this->env, $this->source,         // line 23
($context["settings"] ?? null), "vertical", [], "any", false, false, true, 23)) ? ("slick-wrapper--v") : ("")), ((twig_get_attribute($this->env, $this->source,         // line 24
($context["settings"] ?? null), "vertical_tn", [], "any", false, false, true, 24)) ? ("slick-wrapper--v-tn") : ("")), ((twig_get_attribute($this->env, $this->source,         // line 25
($context["settings"] ?? null), "thumbnail_position", [], "any", false, false, true, 25)) ? (("slick-wrapper--tn-" . \Drupal\Component\Utility\Html::getClass($this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, ($context["settings"] ?? null), "thumbnail_position", [], "any", false, false, true, 25), 25, $this->source)))) : ("")), ((twig_in_filter("over", twig_get_attribute($this->env, $this->source,         // line 26
($context["settings"] ?? null), "thumbnail_position", [], "any", false, false, true, 26))) ? ("slick-wrapper--tn-overlay") : ("")), ((twig_in_filter("over", twig_get_attribute($this->env, $this->source,         // line 27
($context["settings"] ?? null), "thumbnail_position", [], "any", false, false, true, 27))) ? (("slick-wrapper--tn-" . twig_replace_filter($this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, ($context["settings"] ?? null), "thumbnail_position", [], "any", false, false, true, 27), 27, $this->source), ["over-" => ""]))) : (""))];
        // line 30
        echo "  ";
        ob_start(function () { return ''; });
        // line 31
        echo "    ";
        if (twig_get_attribute($this->env, $this->source, ($context["settings"] ?? null), "nav", [], "any", false, false, true, 31)) {
            // line 32
            echo "      <div";
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->extensions['Drupal\Core\Template\TwigExtension']->withoutFilter($this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, ($context["attributes"] ?? null), "addClass", [($context["classes"] ?? null)], "method", false, false, true, 32), 32, $this->source), "id"), "html", null, true);
            echo ">
        ";
            // line 33
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["items"] ?? null), 33, $this->source), "html", null, true);
            echo "
      </div>
    ";
        } else {
            // line 36
            echo "      ";
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["items"] ?? null), 36, $this->source), "html", null, true);
            echo "
    ";
        }
        // line 38
        echo "  ";
        $___internal_parse_0_ = ('' === $tmp = ob_get_clean()) ? '' : new Markup($tmp, $this->env->getCharset());
        // line 30
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar(twig_spaceless($___internal_parse_0_));
        // line 39
        echo "  </div>
</div>";
        $this->env->getExtension('\Drupal\Core\Template\TwigExtension')
            ->checkDeprecations($context, ["settings", "attributes", "items"]);    }

    /**
     * @codeCoverageIgnore
     */
    public function getTemplateName()
    {
        return "themes/custom/cirp/templates/slick-wrapper.html.twig";
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
        return array (  81 => 39,  79 => 30,  76 => 38,  70 => 36,  64 => 33,  59 => 32,  56 => 31,  53 => 30,  51 => 27,  50 => 26,  49 => 25,  48 => 24,  47 => 23,  46 => 22,  45 => 21,  44 => 20,  43 => 18,  39 => 15,);
    }

    public function getSourceContext()
    {
        return new Source("", "themes/custom/cirp/templates/slick-wrapper.html.twig", "D:\\xampp\\htdocs\\cvpd10\\web\\themes\\custom\\cirp\\templates\\slick-wrapper.html.twig");
    }
    
    public function checkSecurity()
    {
        static $tags = array("set" => 18, "apply" => 30, "if" => 31);
        static $filters = array("clean_class" => 21, "replace" => 27, "escape" => 32, "without" => 32, "spaceless" => 30);
        static $functions = array();

        try {
            $this->sandbox->checkSecurity(
                ['set', 'apply', 'if'],
                ['clean_class', 'replace', 'escape', 'without', 'spaceless'],
                []
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
