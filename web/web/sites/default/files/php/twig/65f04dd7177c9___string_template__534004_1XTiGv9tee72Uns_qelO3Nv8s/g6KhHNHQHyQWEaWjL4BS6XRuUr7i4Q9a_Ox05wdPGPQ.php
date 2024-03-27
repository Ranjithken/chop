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

/* __string_template__5340042b3fbe1a889649eeeb26b00997 */
class __TwigTemplate_f9c23ff6b75d55cc61917696e1004672 extends Template
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
        // line 1
        if ((($context["type"] ?? null) == "Press Releases")) {
            // line 2
            echo "<div class=\"content_type\">Press Release</div>
";
        } else {
            // line 4
            echo "<div class=\"content_type\">";
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["type"] ?? null), 4, $this->source), "html", null, true);
            echo "</div>
";
        }
        // line 6
        if ((($context["type"] ?? null) != "Blog Post")) {
            // line 7
            echo "<div class=\"content_image\">";
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["field_image"] ?? null), 7, $this->source), "html", null, true);
            echo "</div>
";
        } else {
            // line 9
            echo "<div class=\"content_image\">";
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["field_postthumbnail"] ?? null), 9, $this->source), "html", null, true);
            echo "</div>
";
        }
        // line 11
        if ((($context["type"] ?? null) == "Blog Post")) {
            // line 12
            echo "
   <p class=\"content_title\"><a href=\"";
            // line 13
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["field_blog_post_url"] ?? null), 13, $this->source), "html", null, true);
            echo "\" target=\"_blank\">";
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["title"] ?? null), 13, $this->source), "html", null, true);
            echo "</a></p>

  ";
        } elseif ((        // line 15
($context["type"] ?? null) == "Press Releases")) {
            // line 16
            echo "
   <p class=\"content_title\"><a href=\"";
            // line 17
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["field_link"] ?? null), 17, $this->source), "html", null, true);
            echo "\" target=\"_blank\">";
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["title"] ?? null), 17, $this->source), "html", null, true);
            echo "</a></p>

  ";
        } elseif ((        // line 19
($context["type"] ?? null) == "Page")) {
            // line 20
            echo "  <p class=\"content_title\"><a href=\"";
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["view_node"] ?? null), 20, $this->source), "html", null, true);
            echo "\">";
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["title"] ?? null), 20, $this->source), "html", null, true);
            echo "</a></p>
  
  ";
        } elseif ((        // line 22
($context["type"] ?? null) == "News")) {
            // line 23
            echo "  <p class=\"content_title\"><a href=\"";
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["field_link"] ?? null), 23, $this->source), "html", null, true);
            echo "\" target=\"_blank\">";
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["title"] ?? null), 23, $this->source), "html", null, true);
            echo "</a></p>
";
        } elseif ((        // line 24
($context["type"] ?? null) == "Resource")) {
            // line 25
            echo "  <p class=\"content_title\"><a href=\"";
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["field_external_url"] ?? null), 25, $this->source), "html", null, true);
            echo "\" target=\"_blank\">";
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["title"] ?? null), 25, $this->source), "html", null, true);
            echo "</a></p>
   ";
        } else {
            // line 27
            echo "   <p class=\"content_title\"><a href=\"";
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["view_node"] ?? null), 27, $this->source), "html", null, true);
            echo "\">";
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["title"] ?? null), 27, $this->source), "html", null, true);
            echo "</a></p>
   ";
        }
        $this->env->getExtension('\Drupal\Core\Template\TwigExtension')
            ->checkDeprecations($context, ["type", "field_image", "field_postthumbnail", "field_blog_post_url", "title", "field_link", "view_node", "field_external_url"]);    }

    /**
     * @codeCoverageIgnore
     */
    public function getTemplateName()
    {
        return "__string_template__5340042b3fbe1a889649eeeb26b00997";
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
        return array (  118 => 27,  110 => 25,  108 => 24,  101 => 23,  99 => 22,  91 => 20,  89 => 19,  82 => 17,  79 => 16,  77 => 15,  70 => 13,  67 => 12,  65 => 11,  59 => 9,  53 => 7,  51 => 6,  45 => 4,  41 => 2,  39 => 1,);
    }

    public function getSourceContext()
    {
        return new Source("", "__string_template__5340042b3fbe1a889649eeeb26b00997", "");
    }
    
    public function checkSecurity()
    {
        static $tags = array("if" => 1);
        static $filters = array("escape" => 4);
        static $functions = array();

        try {
            $this->sandbox->checkSecurity(
                ['if'],
                ['escape'],
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
