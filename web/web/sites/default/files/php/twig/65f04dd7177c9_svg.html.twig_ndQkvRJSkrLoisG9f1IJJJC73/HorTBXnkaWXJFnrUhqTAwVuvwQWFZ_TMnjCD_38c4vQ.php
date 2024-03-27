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

/* @cirp/content/svg.html.twig */
class __TwigTemplate_5ae2e3f834ac83815d871650786b53df extends Template
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
        echo "<!-- ALL Site SVG Image Configuration -->
<svg xmlns=\"http://www.w3.org/2000/svg\" xmlns:xlink=\"http://www.w3.org/1999/xlink\" width=\"0\" height=\"0\" style=\"position:absolute\">
  <defs>
    <!-- search-icon-->
    <svg width=\"49\" height=\"40\" viewBox=\"0 0 49 40\"><defs><style>.a{fill:#d01c65;}.b{fill:#fff;}</style></defs><g id=\"search\" transform=\"translate(14 9)\"><rect class=\"a\" width=\"49\" height=\"40\" transform=\"translate(-14 -9)\"/><path class=\"b\" d=\"M19.922,22.194l-4.972-4.972a9.5,9.5,0,1,1,1.375-1.184L21.2,20.914a.9.9,0,1,1-1.28,1.28ZM1.751,9.48A7.733,7.733,0,1,0,9.484,1.751,7.741,7.741,0,0,0,1.751,9.48Z\" transform=\"translate(0 0)\"/></g></svg>

 <!-- facebook icon-->
    <svg width=\"20\" height=\"20\" viewBox=\"0 0 20 20\"><defs><style>.a2{fill:#fff;}</style></defs><g id=\"facebook\" transform=\"translate(-6.624 -6.623)\"><path class=\"a2\" d=\"M126.773,109.466h-14.61a2.7,2.7,0,0,0-2.695,2.695v14.61a2.7,2.7,0,0,0,2.695,2.695h7.206l.012-7.147h-1.857a.438.438,0,0,1-.438-.436l-.009-2.3a.438.438,0,0,1,.438-.44h1.854v-2.226a3.637,3.637,0,0,1,3.882-3.99h1.891a.438.438,0,0,1,.438.438V115.3a.438.438,0,0,1-.438.438h-1.161c-1.253,0-1.5.6-1.5,1.469v1.927h2.754a.438.438,0,0,1,.435.49l-.273,2.3a.438.438,0,0,1-.435.387H122.5l-.013,7.147h4.288a2.7,2.7,0,0,0,2.7-2.7v-14.61a2.7,2.7,0,0,0-2.695-2.695Z\" transform=\"translate(-102.844 -102.843)\"/></g></svg>


    <!--- linkedin icon-->
    <svg width=\"20\" height=\"20\" viewBox=\"0 0 20 20\"><defs><style>.a2{fill:#fff;}</style></defs><g id=\"linkedin\" transform=\"translate(-20.032 -20.031)\"><path class=\"a2\" d=\"M127.66,109.468H111.278a1.809,1.809,0,0,0-1.809,1.809v16.382a1.809,1.809,0,0,0,1.809,1.809H127.66a1.809,1.809,0,0,0,1.809-1.809V111.277a1.809,1.809,0,0,0-1.809-1.809Zm-12,17.269a.526.526,0,0,1-.527.527H112.89a.527.527,0,0,1-.527-.527v-9.395a.527.527,0,0,1,.527-.527h2.241a.527.527,0,0,1,.527.527v9.395Zm-1.647-10.807a2.129,2.129,0,1,1,2.129-2.129,2.129,2.129,0,0,1-2.129,2.129ZM127.37,126.78a.484.484,0,0,1-.484.484h-2.405a.484.484,0,0,1-.484-.484v-4.407c0-.657.193-2.881-1.718-2.881-1.482,0-1.783,1.522-1.843,2.2v5.083a.484.484,0,0,1-.484.484h-2.326a.484.484,0,0,1-.484-.484V117.3a.484.484,0,0,1,.484-.484h2.326a.484.484,0,0,1,.484.484v.82a3.3,3.3,0,0,1,3.105-1.462c3.851,0,3.829,3.6,3.829,5.575v4.547h0Z\" transform=\"translate(-89.437 -89.437)\"/></g></svg>

    <!-- twitter icon-->
    <svg  width=\"20\" height=\"16.636\" viewBox=\"0 0 20 16.636\"><defs><style>.a2{fill:#fff;}</style></defs><g id=\"twitter\" transform=\"translate(-7.647 -10.369)\"><path class=\"a2\" d=\"M129.148,136.049a7.9,7.9,0,0,1-1.379.465,4.3,4.3,0,0,0,1.135-1.807.226.226,0,0,0-.33-.263,7.954,7.954,0,0,1-2.108.871.533.533,0,0,1-.132.016.559.559,0,0,1-.369-.141,4.312,4.312,0,0,0-2.853-1.077,4.608,4.608,0,0,0-1.366.211,4.178,4.178,0,0,0-2.816,3.01,4.6,4.6,0,0,0-.1,1.588.155.155,0,0,1-.039.121.16.16,0,0,1-.119.054h-.015a11.306,11.306,0,0,1-7.771-4.152.226.226,0,0,0-.37.029,4.317,4.317,0,0,0,.7,5.241,3.867,3.867,0,0,1-.984-.381.226.226,0,0,0-.335.195,4.318,4.318,0,0,0,2.519,3.977h-.092a3.859,3.859,0,0,1-.726-.069.226.226,0,0,0-.257.29,4.321,4.321,0,0,0,3.414,2.939,7.956,7.956,0,0,1-4.456,1.353h-.5a.33.33,0,0,0-.323.249.341.341,0,0,0,.164.377,11.834,11.834,0,0,0,5.947,1.6,11.994,11.994,0,0,0,5.082-1.078,11.222,11.222,0,0,0,3.718-2.786,12.215,12.215,0,0,0,2.277-3.813,12.047,12.047,0,0,0,.774-4.173v-.066a.734.734,0,0,1,.275-.571,8.526,8.526,0,0,0,1.714-1.882.225.225,0,0,0-.279-.331Z\" transform=\"translate(-101.818 -123.745)\"/></g></svg>


    <!--heart icon-->
    <svg  width=\"15\" height=\"13.345\" viewBox=\"0 0 15 13.345\"><defs><style>.a2{fill:#fff;}</style></defs><path class=\"a2\" id=\"heart\" d=\"M13.811,1.306A4.034,4.034,0,0,0,10.81,0,3.774,3.774,0,0,0,8.453.814a4.822,4.822,0,0,0-.953.995A4.819,4.819,0,0,0,6.548.814,3.773,3.773,0,0,0,4.191,0a4.034,4.034,0,0,0-3,1.306A4.689,4.689,0,0,0,0,4.508,5.583,5.583,0,0,0,1.488,8.163a31.724,31.724,0,0,0,3.724,3.5c.516.44,1.1.938,1.708,1.469a.881.881,0,0,0,1.16,0c.607-.531,1.193-1.03,1.709-1.47a31.706,31.706,0,0,0,3.724-3.5A5.582,5.582,0,0,0,15,4.508a4.688,4.688,0,0,0-1.19-3.2Zm0,0\" transform=\"translate(0)\"/></svg>

\t <!--shoutmike-->
\t<svg width=\"24.001\" height=\"21.492\" viewBox=\"0 0 24.001 21.492\"><defs><style>.a3{fill:#786452;}</style></defs><path class=\"a3\" id=\"calendar\" d=\"M4.756,20.35a14.179,14.179,0,0,1-1.348-5.927V14.28H7.739v1.714a5.153,5.153,0,0,0,.568,2.5,2.011,2.011,0,0,1-.072,2,1.991,1.991,0,0,1-1.7,1A1.943,1.943,0,0,1,4.756,20.35ZM18.6,19.063l-3.62-3.784a6.443,6.443,0,0,0-4.543-1.927H10.3V6.212h.212a6.155,6.155,0,0,0,4.543-1.927L18.671.5A1.406,1.406,0,0,1,19.807,0a1.573,1.573,0,0,1,1.562,1.713V17.851a1.672,1.672,0,0,1-1.632,1.713A1.407,1.407,0,0,1,18.6,19.063ZM3.408,13.423A3.5,3.5,0,0,1,0,9.854a3.5,3.5,0,0,1,3.408-3.57H9.441v7.14ZM22.292,6.712A3.4,3.4,0,0,1,24,9.782a3.535,3.535,0,0,1-1.7,3.069Z\"/></svg>

  </defs>
</svg>

<!-- ALL Site SVG Image Configuration -->";
    }

    /**
     * @codeCoverageIgnore
     */
    public function getTemplateName()
    {
        return "@cirp/content/svg.html.twig";
    }

    /**
     * @codeCoverageIgnore
     */
    public function getDebugInfo()
    {
        return array (  39 => 1,);
    }

    public function getSourceContext()
    {
        return new Source("", "@cirp/content/svg.html.twig", "D:\\xampp\\htdocs\\cvpd10\\web\\themes\\custom\\cirp\\templates\\content\\svg.html.twig");
    }
    
    public function checkSecurity()
    {
        static $tags = array();
        static $filters = array();
        static $functions = array();

        try {
            $this->sandbox->checkSecurity(
                [],
                [],
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