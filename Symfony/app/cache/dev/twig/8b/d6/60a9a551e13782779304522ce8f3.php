<?php

/* WebProfilerBundle:Profiler:layout.html.twig */
class __TwigTemplate_8bd660a9a551e13782779304522ce8f3 extends Twig_Template
{
    protected $parent;

    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->blocks = array(
            'panel' => array($this, 'block_panel'),
            'body' => array($this, 'block_body'),
        );
    }

    public function getParent(array $context)
    {
        if (null === $this->parent) {
            $this->parent = $this->env->loadTemplate("WebProfilerBundle:Profiler:base.html.twig");
        }

        return $this->parent;
    }

    public function display(array $context, array $blocks = array())
    {
        $context = array_merge($this->env->getGlobals(), $context);

        $this->getParent($context)->display($context, array_merge($this->blocks, $blocks));
    }

    // line 70
    public function block_panel($context, array $blocks = array())
    {
        echo "";
    }

    // line 3
    public function block_body($context, array $blocks = array())
    {
        // line 4
        echo "
    ";
        // line 5
        echo $this->env->getExtension('templating')->renderAction("WebProfilerBundle:Profiler:toolbar", array("token" => $this->getContext($context, 'token', '5'), "position" => "normal"), array());
        // line 6
        echo "
    <div id=\"content\">

        <div class=\"header clear_fix\">
            <h1>
                <img src=\"";
        // line 11
        echo twig_escape_filter($this->env, $this->env->getExtension('templating')->getAssetUrl("bundles/webprofiler/images/profiler/logo_symfony_profiler.gif"), "html");
        echo "\" alt=\"Symfony profiler\"/>
            </h1>

            <div class=\"search\">
                <form method=\"get\" action=\"http://symfony.com/search\">
                    <div class=\"form_row\">
                        <label for=\"search_id\">
                            <img src=\"";
        // line 18
        echo twig_escape_filter($this->env, $this->env->getExtension('templating')->getAssetUrl("bundles/webprofiler/images/profiler/grey_magnifier.png"), "html");
        echo "\" alt=\"Search on Symfony website\"/>
                        </label>

                        <input name=\"q\" id=\"search_id\" type=\"text\" placeholder=\"Search on Symfony website\"/>

                        <button type=\"submit\">
                            <span class=\"border_l\">
                                <span class=\"border_r\">
                                    <span class=\"btn_bg\">OK</span>
                                </span>
                            </span>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div class=\"resume\">
            <p>
                <strong><a href=\"";
        // line 37
        echo twig_escape_filter($this->env, $this->getAttribute($this->getContext($context, 'profiler', '37'), "url", array(), "any", false, 37), "html");
        echo "\">";
        echo twig_escape_filter($this->env, $this->getAttribute($this->getContext($context, 'profiler', '37'), "url", array(), "any", false, 37), "html");
        echo "</a></strong>
                <span class=\"date\">
                    <strong>by ";
        // line 39
        echo twig_escape_filter($this->env, $this->getAttribute($this->getContext($context, 'profiler', '39'), "ip", array(), "any", false, 39), "html");
        echo "</strong> at <strong>";
        echo twig_escape_filter($this->env, twig_date_format_filter($this->getAttribute($this->getContext($context, 'profiler', '39'), "time", array(), "any", false, 39), "r"), "html");
        echo "</strong>
                </span>
            </p>
        </div>

        <div class=\"main\">
    
            <div class=\"clear_fix\">

                <div class=\"navigation\">
            
                    ";
        // line 50
        if (twig_test_defined("templates", $context)) {
            // line 51
            echo "                        <ul class=\"menu_profiler\">
                            ";
            // line 52
            $context['_parent'] = (array) $context;
            $context['_seq'] = twig_ensure_traversable($this->getContext($context, 'templates', '52'));
            foreach ($context['_seq'] as $context['name'] => $context['template']) {
                // line 53
                echo "                                ";
                ob_start();
                echo twig_escape_filter($this->env, $this->getAttribute($this->getContext($context, 'template', '53'), "renderBlock", array("menu", array("collector" => $this->getAttribute($this->getContext($context, 'profiler', '53'), "get", array($this->getContext($context, 'name', '53'), ), "method", false, 53)), ), "method", false, 53), "html");
                $context['menu'] = new Twig_Markup(ob_get_clean());
                // line 54
                echo "                                ";
                if (($this->getContext($context, 'menu', '54') != "")) {
                    // line 55
                    echo "                                    <li class=\"";
                    echo twig_escape_filter($this->env, $this->getContext($context, 'name', '55'), "html");
                    if (($this->getContext($context, 'name', '55') == $this->getContext($context, 'panel', '55'))) {
                        echo " selected";
                    }
                    echo "\">
                                        <a href=\"";
                    // line 56
                    echo twig_escape_filter($this->env, $this->env->getExtension('templating')->getPath("_profiler_panel", array("token" => $this->getContext($context, 'token', '56'), "panel" => $this->getContext($context, 'name', '56'))), "html");
                    echo "\">";
                    echo $this->getContext($context, 'menu', '56');
                    echo "</a>
                                    </li>
                                ";
                }
                // line 59
                echo "                            ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['name'], $context['template'], $context['_parent'], $context['loop']);
            $context = array_merge($_parent, array_intersect_key($context, $_parent));
            // line 60
            echo "                        </ul>
                    ";
        }
        // line 62
        echo "
                    ";
        // line 63
        echo $this->env->getExtension('templating')->renderAction("WebProfilerBundle:Profiler:searchBar", array("token" => $this->getContext($context, 'token', '63')), array());
        // line 64
        echo "                    
                    ";
        // line 65
        $template = "WebProfilerBundle:Profiler:admin.html.twig";
        if ($template instanceof Twig_Template) {
            $template->display(array("token" => $this->getContext($context, 'token', '65')));
        } else {
            echo $this->env->getExtension('templating')->getTemplating()->render($template, array("token" => $this->getContext($context, 'token', '65')));
        }
        // line 66
        echo "
                </div>

                <div class=\"collector_content\">
                    ";
        // line 70
        $this->displayBlock('panel', $context, $blocks);
        // line 71
        echo "                </div>
            </div>
        </div>
    </div>
";
    }

    public function getTemplateName()
    {
        return "WebProfilerBundle:Profiler:layout.html.twig";
    }
}
