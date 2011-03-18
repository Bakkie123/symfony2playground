<?php

/* WebProfilerBundle:Profiler:toolbar.html.twig */
class __TwigTemplate_ae0c3afde48e9b44fc992cf5d61c355e extends Twig_Template
{
    public function display(array $context, array $blocks = array())
    {
        $context = array_merge($this->env->getGlobals(), $context);

        // line 1
        echo "<!-- START of Symfony2 Web Debug Toolbar -->
";
        // line 2
        if (("normal" != $this->getContext($context, 'position', '2'))) {
            // line 3
            echo "    <div style=\"clear: both; height: 40px;\"></div>
";
        }
        // line 5
        echo "
<div class=\"sf-toolbarreset\" 
    ";
        // line 7
        if (("normal" != $this->getContext($context, 'position', '7'))) {
            // line 8
            echo "    style=\"position: ";
            echo twig_escape_filter($this->env, $this->getContext($context, 'position', '8'), "html");
            echo "; 
        background-color: #f7f7f7;
            background-image: -moz-linear-gradient(-90deg, #e4e4e4, #ffffff);
            background-image: -webkit-gradient(linear, 0% 0%, 0% 100%, from(#e4e4e4), to(#ffffff));
            bottom: 0;
            left:0;
            margin:0;
            padding: 0;
            z-index: 6000000;
            width: 100%;
            border-top: 1px solid #bbb;
            font: 11px Verdana, Arial, sans-serif;
            color: #2f2f2f;\"
    ";
        }
        // line 22
        echo ">

    <span style=\"display: inline-block; min-height: 24px; width: 40px; float: right;\">&nbsp;</span>

    ";
        // line 26
        $context['_parent'] = (array) $context;
        $context['_seq'] = twig_ensure_traversable($this->getContext($context, 'templates', '26'));
        foreach ($context['_seq'] as $context['name'] => $context['template']) {
            // line 27
            echo "        ";
            echo twig_escape_filter($this->env, $this->getAttribute($this->getContext($context, 'template', '27'), "renderblock", array("toolbar", array("collector" => $this->getAttribute($this->getContext($context, 'profiler', '27'), "get", array($this->getContext($context, 'name', '27'), ), "method", false, 27), "profiler_url" => $this->getContext($context, 'profiler_url', '27')), ), "method", false, 27), "html");
            echo "
    ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['name'], $context['template'], $context['_parent'], $context['loop']);
        $context = array_merge($_parent, array_intersect_key($context, $_parent));
        // line 29
        echo "
    ";
        // line 30
        if (("normal" != $this->getContext($context, 'position', '30'))) {
            // line 31
            echo "        <span style=\"display:block; position:absolute; top:12px; right:10px; width:14px; height:14px;\">
            <img width=\"14\" height=\"14\" alt=\"Hide Toolbar\" src=\"data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAA4AAAAOCAYAAAAfSC3RAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAPxJREFUeNqckk0LAVEUhq+PlJXfoGarrGytTLNSFkqUKCsbW7IlfoBSyoYSEVEWs7QS5U8opfwDpfGOzqnTdW289TT3o6cz99wbcF1XUbLgBi7qO0lggZU/sW1bBWmjB7bgBIqa5ND6ErR5MUiVmjQPgYmQM2ANIjTvgLQ/CIMHeIpNlhOgAaKGX/9UPIISyUrITYNUAwcWFR06r8nKII1lRc4OdH9IIynpoiOapKdCTfwSHepe9IcYoevISTFhkF5gSF8pz0GKRUuT/AYVQB1UDXKcxQ3oC6nETwuZgrKQB2DBD8BPi+7zDs7a+WbgCmJgz4sBz/PUP3kLMAAw2DR3cUcXrwAAAABJRU5ErkJggg==\" onclick=\"this.parentNode.parentNode.previousSibling.style.display = this.parentNode.parentNode.style.display = 'none';\" />
        </span>
    ";
        }
        // line 35
        echo "</div>
<!-- END of Symfony2 Web Debug Toolbar -->
";
    }

    public function getTemplateName()
    {
        return "WebProfilerBundle:Profiler:toolbar.html.twig";
    }
}
