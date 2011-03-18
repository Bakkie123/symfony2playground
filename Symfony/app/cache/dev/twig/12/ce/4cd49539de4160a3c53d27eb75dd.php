<?php

/* WebProfilerBundle:Collector:events.html.twig */
class __TwigTemplate_12ce4cd49539de4160a3c53d27eb75dd extends Twig_Template
{
    protected $parent;

    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->blocks = array(
            'menu' => array($this, 'block_menu'),
            'panel' => array($this, 'block_panel'),
        );
    }

    public function getParent(array $context)
    {
        if (null === $this->parent) {
            $this->parent = $this->env->loadTemplate("WebProfilerBundle:Profiler:layout.html.twig");
        }

        return $this->parent;
    }

    public function display(array $context, array $blocks = array())
    {
        $context = array_merge($this->env->getGlobals(), $context);

        $this->getParent($context)->display($context, array_merge($this->blocks, $blocks));
    }

    // line 3
    public function block_menu($context, array $blocks = array())
    {
        // line 4
        echo "<span class=\"label\">
    <span class=\"icon\"><img src=\"";
        // line 5
        echo twig_escape_filter($this->env, $this->env->getExtension('templating')->getAssetUrl("bundles/webprofiler/images/profiler/events.png"), "html");
        echo "\" alt=\"\" /></span>
    <strong>Events</strong>
</span>
";
    }

    // line 10
    public function block_panel($context, array $blocks = array())
    {
        // line 11
        echo "    <h2>Called Listeners</h2>

    <table>
        <tr>
            <th>Event</th>
            <th>Caller</th>
            <th>Listener</th>
        </tr>
        ";
        // line 19
        $context['_parent'] = (array) $context;
        $context['_seq'] = twig_ensure_traversable($this->getAttribute($this->getContext($context, 'collector', '19'), "calledlisteners", array(), "any", false, 19));
        foreach ($context['_seq'] as $context['_key'] => $context['elements']) {
            // line 20
            echo "            <tr>
                <td><code>";
            // line 21
            echo twig_escape_filter($this->env, $this->getAttribute($this->getContext($context, 'elements', '21'), "event", array(), "any", false, 21), "html");
            echo "</code></td>
                <td><code>";
            // line 22
            echo $this->env->getExtension('templating')->abbrClass($this->getAttribute($this->getContext($context, 'elements', '22'), "caller", array(), "any", false, 22));
            echo "</code></td>
                <td><code>";
            // line 23
            echo $this->env->getExtension('templating')->abbrMethod($this->getAttribute($this->getContext($context, 'elements', '23'), "listener", array(), "any", false, 23));
            echo "</code></td>
            </tr>
        ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['elements'], $context['_parent'], $context['loop']);
        $context = array_merge($_parent, array_intersect_key($context, $_parent));
        // line 26
        echo "    </table>

    ";
        // line 28
        if ($this->getAttribute($this->getContext($context, 'collector', '28'), "notcalledlisteners", array(), "any", false, 28)) {
            // line 29
            echo "        <h2>Not Called Listeners</h2>

        <table>
            <tr>
                <th>Event</th>
                <th>Listener</th>
            </tr>
            ";
            // line 36
            $context['_parent'] = (array) $context;
            $context['_seq'] = twig_ensure_traversable($this->getAttribute($this->getContext($context, 'collector', '36'), "notcalledlisteners", array(), "any", false, 36));
            foreach ($context['_seq'] as $context['_key'] => $context['elements']) {
                // line 37
                echo "                <tr>
                    <td><code>";
                // line 38
                echo twig_escape_filter($this->env, $this->getAttribute($this->getContext($context, 'elements', '38'), "event", array(), "any", false, 38), "html");
                echo "</code></td>
                    <td><code>";
                // line 39
                echo $this->env->getExtension('templating')->abbrMethod($this->getAttribute($this->getContext($context, 'elements', '39'), "listener", array(), "any", false, 39));
                echo "</code></td>
                </tr>
            ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['elements'], $context['_parent'], $context['loop']);
            $context = array_merge($_parent, array_intersect_key($context, $_parent));
            // line 42
            echo "        </table>
    ";
        }
    }

    public function getTemplateName()
    {
        return "WebProfilerBundle:Collector:events.html.twig";
    }
}
