<?php

/* WebProfilerBundle:Collector:request.html.twig */
class __TwigTemplate_fe070e93164fc7fe24792598d72ef806 extends Twig_Template
{
    protected $parent;

    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->blocks = array(
            'toolbar' => array($this, 'block_toolbar'),
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
    public function block_toolbar($context, array $blocks = array())
    {
        // line 4
        echo "<span style=\"white-space:nowrap; color:#2f2f2f; display:inline-block; min-height:24px; border-right:1px solid #cdcdcd; padding:5px 10px 5px 6px; \">
    <img width=\"28\" height=\"28\" alt=\"Request\" style=\"vertical-align: middle; margin-right: 10px;\" src=\"data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABwAAAAcCAYAAAByDd+UAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAABOZJREFUeNrsVmsspFcY9jHMMsxKE9sxM1SUNHRpSNR9bbQqrrNUCG1E1z3ZyIpq+dEfIiToRtomEiFi/zQrWdlZFFstDYK632ZRu2yxFNu4GzOY6fPKN80YVu1m41dP8uac75zz3t/3OR+jVqv1LnLo613wuHCFnJcdTExM8LOzs+9vb28HaPZMTU1bSkpKohwcHDbPI3xwcPBMhUx+fv6V4eHhG0KhsMfd3V2srYwGfQ8MDPiWlZUtYHjZ2dlJYcBfOFK/qof6YHTs7OxsUigU4rW1tQNjY+NHpzGMjo6my2SyQJVKxcH6mzt37gRmZWXJcKQ6j0IGVcpUV1e/W1NT0y6Xyy1fNScwbCkmJuZaQkLCU11PTwspFc0lhPHW6yijQXzET3LOG9JLYWFhywiTan9//1jVisVilbe3946FhYVieXmZ297ezsN87I6hoaEqODj4BatQfh6FKlipCA8Pf1ZbW2urOQgICNjKzMxsNzExofxQVZrHx8c7FRQUXOvp6THW3CM+GCo/dw4lEsnP6+vrx6rRxsZGWVlZWcHhcH7a2tpaKCwsdElOTpbZ2tqKNjc3I5KSkj5fWVk51lLm5ubNUqk0CsudM3O4sbHxke6mj4/PYyirr6urm4+MjPy1u7v7bkpKyi9VVVV/8/l8qa+v71NdHsj5BJPwP0OKKj2BNmhwCuPs2NiYBOGyoL2DgwPzycnJT7G8y+Vyn2B+T5uHlUN3pzV7rq6uJz3EONG0s7OzXEyHKKZ1Ho93cGQZh6MODQ1dof3FxUXDUwSRHOPY2NjoiIiI22R3XFzcFyEhIT9ibfKvh6jCuY6Ojne0mYEmPsgV19nZ+Y/S0tIHQ0NDHo6OjqNOTk4j8JKP3Hyoq5DkUNEyDHMTwBEI427v7u6KDAwMFNgXgGY0jf/1/Py8pKGh4X00P18jwMrKqjE3N/dLKBJTh4CWR0ZGloqKir6Hhz6ae1FRUZsovMcI+RByzjQ1NcXv7e2ZaBuDtpIhNf2BgYFfkULHxsbGzwBROYeHh/o6KLKAy40I6zJwVLi6uhoKYW9r34EHqpycnO9aWlquA+pccK7n6em5nZqa+ufMzAwfcoU7OzsGkLWOar9Opf28q6tLoKuMRRHx3NxcyllVR3y9vb3CtLS0uoyMjKsU1vT09GZra+s2tJcFKjwVxliiX0ddXFz0SYkB+moQL4T8daBNIBDsARAGkC8F4TLtwYBZTA9Bj2DwUdG1tbU5jI+Pc0ihHJaMFRcXP0Tejg5R9nqJiYmnGkD7dE5DJBIdIGTEN15eXv4xQncEBhUVFbdQod9GR0f/MDU1ZUV7CDWvv7/fnmGtegvkBYbI1tZWLzc3t+dAGDXQxV9XIYS1mpmZMX19fSI/P7/fAQT3sT2pVCo94cHN+vr6D+DNZW2evLy8J7hbj+U9DvukrIE6URwv0HtNWO8iVJYeHh5XgZtXNIz4XrG3t7+H5RLu8TDPkzKQ0sjIqA+NzkxPT6dCoUdQUNAagUFzc/NlROQ33Kml1mC0/trIUyOWCIhtQDdAniAei5HdICnoGfu0KVlSsw+BAD0Yir6V+Pv7L5AcGMCBkTXkEDnCnPGbSL0kYuHKiBW8SlVNjC/hoeRas3zbhIisIcRzhFLMG/4vZVgFBqwyFRsJleb5Yv7/EX7T4x8BBgDTTU7fbnA/yAAAAABJRU5ErkJggg==\"/>
    <span style=\"color: ";
        // line 6
        echo (($this->getAttribute($this->getContext($context, 'collector', '6'), "route", array(), "any", false, 6)) ? ("#759e1a") : ("#a33"));
        echo "\">";
        echo twig_escape_filter($this->env, (($this->getAttribute($this->getContext($context, 'collector', '6'), "route", array(), "any", false, 6)) ? ($this->getAttribute($this->getContext($context, 'collector', '6'), "route", array(), "any", false, 6)) : ("NONE")), "html");
        echo "</span>
    <span style=\"margin: 0pt; padding: 0pt; color: #979696;\">|</span>
    ";
        // line 8
        echo twig_escape_filter($this->env, $this->getAttribute($this->getContext($context, 'collector', '8'), "format", array(), "any", false, 8), "html");
        echo "
    <span style=\"margin: 0pt; padding: 0pt; color: #979696;\">|</span>
    <span style=\"color: ";
        // line 10
        echo (((200 == $this->getAttribute($this->getContext($context, 'collector', '10'), "statuscode", array(), "any", false, 10))) ? ("#759e1a") : ("#a33"));
        echo "\">";
        echo twig_escape_filter($this->env, $this->getAttribute($this->getContext($context, 'collector', '10'), "statuscode", array(), "any", false, 10), "html");
        echo "</span>
    <span style=\"margin: 0pt; padding: 0pt; color: #979696;\">|</span>
    ";
        // line 12
        echo twig_escape_filter($this->env, $this->getAttribute($this->getContext($context, 'collector', '12'), "contenttype", array(), "any", false, 12), "html");
        echo "
</span>
";
    }

    // line 16
    public function block_menu($context, array $blocks = array())
    {
        // line 17
        echo "<span class=\"label\">
    <span class=\"icon\"><img src=\"";
        // line 18
        echo twig_escape_filter($this->env, $this->env->getExtension('templating')->getAssetUrl("bundles/webprofiler/images/profiler/request.png"), "html");
        echo "\" alt=\"\" /></span>
    <strong>Request</strong>
</span>
";
    }

    // line 23
    public function block_panel($context, array $blocks = array())
    {
        // line 24
        echo "    <h2>Request GET Parameters</h2>

    ";
        // line 26
        if (twig_length_filter($this->env, $this->getAttribute($this->getAttribute($this->getContext($context, 'collector', '26'), "requestquery", array(), "any", false, 26), "all", array(), "any", false, 26))) {
            // line 27
            echo "        ";
            $template = "WebProfilerBundle:Profiler:bag.html.twig";
            if ($template instanceof Twig_Template) {
                $template->display(array("bag" => $this->getAttribute($this->getContext($context, 'collector', '27'), "requestquery", array(), "any", false, 27)));
            } else {
                echo $this->env->getExtension('templating')->getTemplating()->render($template, array("bag" => $this->getAttribute($this->getContext($context, 'collector', '27'), "requestquery", array(), "any", false, 27)));
            }
            // line 28
            echo "    ";
        } else {
            // line 29
            echo "        <p>
            <em>No GET parameters</em>
        </p>
    ";
        }
        // line 33
        echo "
    <h2>Request POST Parameters</h2>

    ";
        // line 36
        if (twig_length_filter($this->env, $this->getAttribute($this->getAttribute($this->getContext($context, 'collector', '36'), "requestrequest", array(), "any", false, 36), "all", array(), "any", false, 36))) {
            // line 37
            echo "        ";
            $template = "WebProfilerBundle:Profiler:bag.html.twig";
            if ($template instanceof Twig_Template) {
                $template->display(array("bag" => $this->getAttribute($this->getContext($context, 'collector', '37'), "requestrequest", array(), "any", false, 37)));
            } else {
                echo $this->env->getExtension('templating')->getTemplating()->render($template, array("bag" => $this->getAttribute($this->getContext($context, 'collector', '37'), "requestrequest", array(), "any", false, 37)));
            }
            // line 38
            echo "    ";
        } else {
            // line 39
            echo "        <p>
            <em>No POST parameters</em>
        </p>
    ";
        }
        // line 43
        echo "
    <h2>Request Attributes</h2>

    ";
        // line 46
        if (twig_length_filter($this->env, $this->getAttribute($this->getAttribute($this->getContext($context, 'collector', '46'), "requestattributes", array(), "any", false, 46), "all", array(), "any", false, 46))) {
            // line 47
            echo "        ";
            $template = "WebProfilerBundle:Profiler:bag.html.twig";
            if ($template instanceof Twig_Template) {
                $template->display(array("bag" => $this->getAttribute($this->getContext($context, 'collector', '47'), "requestattributes", array(), "any", false, 47)));
            } else {
                echo $this->env->getExtension('templating')->getTemplating()->render($template, array("bag" => $this->getAttribute($this->getContext($context, 'collector', '47'), "requestattributes", array(), "any", false, 47)));
            }
            // line 48
            echo "    ";
        } else {
            // line 49
            echo "        <p>
            <em>No attributes</em>
        </p>
    ";
        }
        // line 53
        echo "
    <h2>Request Cookies</h2>

    ";
        // line 56
        if (twig_length_filter($this->env, $this->getAttribute($this->getAttribute($this->getContext($context, 'collector', '56'), "requestcookies", array(), "any", false, 56), "all", array(), "any", false, 56))) {
            // line 57
            echo "        ";
            $template = "WebProfilerBundle:Profiler:bag.html.twig";
            if ($template instanceof Twig_Template) {
                $template->display(array("bag" => $this->getAttribute($this->getContext($context, 'collector', '57'), "requestcookies", array(), "any", false, 57)));
            } else {
                echo $this->env->getExtension('templating')->getTemplating()->render($template, array("bag" => $this->getAttribute($this->getContext($context, 'collector', '57'), "requestcookies", array(), "any", false, 57)));
            }
            // line 58
            echo "    ";
        } else {
            // line 59
            echo "        <p>
            <em>No cookies</em>
        </p>
    ";
        }
        // line 63
        echo "
    <h2>Requests Headers</h2>

    ";
        // line 66
        $template = "WebProfilerBundle:Profiler:bag.html.twig";
        if ($template instanceof Twig_Template) {
            $template->display(array("bag" => $this->getAttribute($this->getContext($context, 'collector', '66'), "requestheaders", array(), "any", false, 66)));
        } else {
            echo $this->env->getExtension('templating')->getTemplating()->render($template, array("bag" => $this->getAttribute($this->getContext($context, 'collector', '66'), "requestheaders", array(), "any", false, 66)));
        }
        // line 67
        echo "
    <h2>Requests Server Parameters</h2>

    ";
        // line 70
        $template = "WebProfilerBundle:Profiler:bag.html.twig";
        if ($template instanceof Twig_Template) {
            $template->display(array("bag" => $this->getAttribute($this->getContext($context, 'collector', '70'), "requestserver", array(), "any", false, 70)));
        } else {
            echo $this->env->getExtension('templating')->getTemplating()->render($template, array("bag" => $this->getAttribute($this->getContext($context, 'collector', '70'), "requestserver", array(), "any", false, 70)));
        }
        // line 71
        echo "
    <h2>Response Headers</h2>

    ";
        // line 74
        $template = "WebProfilerBundle:Profiler:bag.html.twig";
        if ($template instanceof Twig_Template) {
            $template->display(array("bag" => $this->getAttribute($this->getContext($context, 'collector', '74'), "responseheaders", array(), "any", false, 74)));
        } else {
            echo $this->env->getExtension('templating')->getTemplating()->render($template, array("bag" => $this->getAttribute($this->getContext($context, 'collector', '74'), "responseheaders", array(), "any", false, 74)));
        }
        // line 75
        echo "
    <h2>Response Session Attributes</h2>

    ";
        // line 78
        if (twig_length_filter($this->env, $this->getAttribute($this->getContext($context, 'collector', '78'), "sessionattributes", array(), "any", false, 78))) {
            // line 79
            echo "        <table>
            <tr>
                <th>Key</th>
                <th>Value</th>
            </tr>

            ";
            // line 85
            $context['_parent'] = (array) $context;
            $context['_seq'] = twig_ensure_traversable($this->getAttribute($this->getContext($context, 'collector', '85'), "sessionattributes", array(), "any", false, 85));
            foreach ($context['_seq'] as $context['key'] => $context['value']) {
                // line 86
                echo "                <tr>
                    <th>";
                // line 87
                echo twig_escape_filter($this->env, $this->getContext($context, 'key', '87'), "html");
                echo "</th>
                    <td>";
                // line 88
                echo twig_escape_filter($this->env, $this->env->getExtension('templating')->dump($this->getContext($context, 'value', '88')), "html");
                echo "</td>
                </tr>
            ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['key'], $context['value'], $context['_parent'], $context['loop']);
            $context = array_merge($_parent, array_intersect_key($context, $_parent));
            // line 91
            echo "        </table>
    ";
        } else {
            // line 93
            echo "        <em>No request session attributes</em>
    ";
        }
    }

    public function getTemplateName()
    {
        return "WebProfilerBundle:Collector:request.html.twig";
    }
}
