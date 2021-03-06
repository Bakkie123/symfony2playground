<?php
namespace Symfony\Component\Routing
{
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\Matcher\UrlMatcherInterface;
interface RouterInterface extends UrlMatcherInterface, UrlGeneratorInterface
{
}
}
namespace Symfony\Component\Routing\Matcher
{
interface UrlMatcherInterface
{
    function match($url);
}
}
namespace Symfony\Component\Routing\Matcher
{
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;
class UrlMatcher implements UrlMatcherInterface
{
    protected $routes;
    protected $defaults;
    protected $context;
    public function __construct(RouteCollection $routes, array $context = array(), array $defaults = array())
    {
        $this->routes = $routes;
        $this->context = $context;
        $this->defaults = $defaults;
    }
    public function setContext(array $context = array())
    {
        $this->context = $context;
    }
    public function match($url)
    {
        $url = $this->normalizeUrl($url);
        foreach ($this->routes->all() as $name => $route) {
            $compiledRoute = $route->compile();
            if (isset($this->context['method']) && (($req = $route->getRequirement('_method')) && !preg_match(sprintf('#^(%s)$#xi', $req), $this->context['method']))) {
                continue;
            }
                        if ('' !== $compiledRoute->getStaticPrefix() && 0 !== strpos($url, $compiledRoute->getStaticPrefix())) {
                continue;
            }
            if (!preg_match($compiledRoute->getRegex(), $url, $matches)) {
                continue;
            }
            return array_merge($this->mergeDefaults($matches, $route->getDefaults()), array('_route' => $name));
        }
        return false;
    }
    protected function mergeDefaults($params, $defaults)
    {
        $parameters = array_merge($this->defaults, $defaults);
        foreach ($params as $key => $value) {
            if (!is_int($key)) {
                                $parameters[$key] = str_replace('%2F', '/', urldecode($value));
            }
        }
        return $parameters;
    }
    protected function normalizeUrl($url)
    {
                if (false !== $pos = strpos($url, '?')) {
            $url = substr($url, 0, $pos);
        }
                return preg_replace('#/+#', '/', $url);
    }
}
}
namespace Symfony\Component\Routing\Generator
{
interface UrlGeneratorInterface
{
    function generate($name, array $parameters, $absolute = false);
}
}
namespace Symfony\Component\Routing\Generator
{
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;
class UrlGenerator implements UrlGeneratorInterface
{
    protected $routes;
    protected $defaults;
    protected $context;
    protected $cache;
    public function __construct(RouteCollection $routes, array $context = array(), array $defaults = array())
    {
        $this->routes = $routes;
        $this->context = $context;
        $this->defaults = $defaults;
        $this->cache = array();
    }
    public function setContext(array $context = array())
    {
        $this->context = $context;
    }
    public function generate($name, array $parameters, $absolute = false)
    {
        if (null === $route = $this->routes->get($name)) {
            throw new \InvalidArgumentException(sprintf('Route "%s" does not exist.', $name));
        }
        if (!isset($this->cache[$name])) {
            $this->cache[$name] = $route->compile();
        }
        return $this->doGenerate($this->cache[$name]->getVariables(), $route->getDefaults(), $route->getRequirements(), $this->cache[$name]->getTokens(), $parameters, $name, $absolute);
    }
    protected function doGenerate($variables, $defaults, $requirements, $tokens, $parameters, $name, $absolute)
    {
        $defaults = array_merge($this->defaults, $defaults);
        $tparams = array_merge($defaults, $parameters);
                if ($diff = array_diff_key($variables, $tparams)) {
            throw new \InvalidArgumentException(sprintf('The "%s" route has some missing mandatory parameters (%s).', $name, implode(', ', $diff)));
        }
        $url = '';
        $optional = true;
        foreach ($tokens as $token) {
            if ('variable' === $token[0]) {
                if (false === $optional || !isset($defaults[$token[3]]) || (isset($parameters[$token[3]]) && $parameters[$token[3]] != $defaults[$token[3]])) {
                                        if (isset($requirements[$token[3]]) && !preg_match('#^'.$requirements[$token[3]].'$#', $tparams[$token[3]])) {
                        throw new \InvalidArgumentException(sprintf('Parameter "%s" for route "%s" must match "%s" ("%s" given).', $token[3], $name, $requirements[$token[3]], $tparams[$token[3]]));
                    }
                                        $url = $token[1].str_replace('%2F', '%252F', urlencode($tparams[$token[3]])).$url;
                    $optional = false;
                }
            } elseif ('text' === $token[0]) {
                $url = $token[1].$token[2].$url;
                $optional = false;
            } else {
                                if ($segment = call_user_func_array(array($this, 'generateFor'.ucfirst(array_shift($token))), array_merge(array($optional, $tparams), $token))) {
                    $url = $segment.$url;
                    $optional = false;
                }
            }
        }
        if (!$url) {
            $url = '/';
        }
                if ($extra = array_diff_key($parameters, $variables, $defaults)) {
            $url .= '?'.http_build_query($extra);
        }
        $url = (isset($this->context['base_url']) ? $this->context['base_url'] : '').$url;
        if ($absolute && isset($this->context['host'])) {
            $isSecure = (isset($this->context['is_secure']) && $this->context['is_secure']);
            $port = isset($this->context['port']) ? $this->context['port'] : 80;
            $urlBeginning = 'http'.($isSecure ? 's' : '').'://'.$this->context['host'];
            if (($isSecure && $port != 443) || (!$isSecure && $port != 80)) {
                $urlBeginning .= ':'.$port;
            }
            $url = $urlBeginning.$url;
        }
        return $url;
    }
}
}
namespace Symfony\Component\Routing
{
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\Config\ConfigCache;
class Router implements RouterInterface
{
    protected $matcher;
    protected $generator;
    protected $options;
    protected $defaults;
    protected $context;
    protected $loader;
    protected $collection;
    protected $resource;
    public function __construct(LoaderInterface $loader, $resource, array $options = array(), array $context = array(), array $defaults = array())
    {
        $this->loader = $loader;
        $this->resource = $resource;
        $this->context = $context;
        $this->defaults = $defaults;
        $this->options = array(
            'cache_dir'              => null,
            'debug'                  => false,
            'generator_class'        => 'Symfony\\Component\\Routing\\Generator\\UrlGenerator',
            'generator_base_class'   => 'Symfony\\Component\\Routing\\Generator\\UrlGenerator',
            'generator_dumper_class' => 'Symfony\\Component\\Routing\\Generator\\Dumper\\PhpGeneratorDumper',
            'generator_cache_class'  => 'ProjectUrlGenerator',
            'matcher_class'          => 'Symfony\\Component\\Routing\\Matcher\\UrlMatcher',
            'matcher_base_class'     => 'Symfony\\Component\\Routing\\Matcher\\UrlMatcher',
            'matcher_dumper_class'   => 'Symfony\\Component\\Routing\\Matcher\\Dumper\\PhpMatcherDumper',
            'matcher_cache_class'    => 'ProjectUrlMatcher',
            'resource_type'          => null,
        );
                if ($diff = array_diff(array_keys($options), array_keys($this->options))) {
            throw new \InvalidArgumentException(sprintf('The Router does not support the following options: \'%s\'.', implode('\', \'', $diff)));
        }
        $this->options = array_merge($this->options, $options);
    }
    public function getRouteCollection()
    {
        if (null === $this->collection) {
            $this->collection = $this->loader->load($this->resource, $this->options['resource_type']);
        }
        return $this->collection;
    }
    public function setContext(array $context = array())
    {
        $this->getMatcher()->setContext($context);
        $this->getGenerator()->setContext($context);
    }
    public function generate($name, array $parameters = array(), $absolute = false)
    {
        return $this->getGenerator()->generate($name, $parameters, $absolute);
    }
    public function match($url)
    {
        return $this->getMatcher()->match($url);
    }
    public function getMatcher()
    {
        if (null !== $this->matcher) {
            return $this->matcher;
        }
        if (null === $this->options['cache_dir'] || null === $this->options['matcher_cache_class']) {
            return $this->matcher = new $this->options['matcher_class']($this->getRouteCollection(), $this->context, $this->defaults);
        }
        $class = $this->options['matcher_cache_class'];
        $cache = new ConfigCache($this->options['cache_dir'], $class, $this->options['debug']);
        if (!$cache->isFresh($class)) {
            $dumper = new $this->options['matcher_dumper_class']($this->getRouteCollection());
            $options = array(
                'class'      => $class,
                'base_class' => $this->options['matcher_base_class'],
            );
            $cache->write($dumper->dump($options), $this->getRouteCollection()->getResources());
        }
        require_once $cache;
        return $this->matcher = new $class($this->context, $this->defaults);
    }
    public function getGenerator()
    {
        if (null !== $this->generator) {
            return $this->generator;
        }
        if (null === $this->options['cache_dir'] || null === $this->options['generator_cache_class']) {
            return $this->generator = new $this->options['generator_class']($this->getRouteCollection(), $this->context, $this->defaults);
        }
        $class = $this->options['generator_cache_class'];
        $cache = new ConfigCache($this->options['cache_dir'], $class, $this->options['debug']);
        if (!$cache->isFresh($class)) {
            $dumper = new $this->options['generator_dumper_class']($this->getRouteCollection());
            $options = array(
                'class'      => $class,
                'base_class' => $this->options['generator_base_class'],
            );
            $cache->write($dumper->dump($options), $this->getRouteCollection()->getResources());
        }
        require_once $cache;
        return $this->generator = new $class($this->context, $this->defaults);
    }
}
}
namespace Symfony\Component\HttpFoundation
{
use Symfony\Component\HttpFoundation\SessionStorage\SessionStorageInterface;
class Session implements \Serializable
{
    protected $storage;
    protected $attributes;
    protected $oldFlashes;
    protected $started;
    protected $options;
    public function __construct(SessionStorageInterface $storage, array $options = array())
    {
        $this->storage = $storage;
        $this->options = $options;
        $this->attributes = array('_flash' => array(), '_locale' => $this->getDefaultLocale());
        $this->started = false;
    }
    public function start()
    {
        if (true === $this->started) {
            return;
        }
        $this->storage->start();
        $this->attributes = $this->storage->read('_symfony2');
        if (!isset($this->attributes['_flash'])) {
            $this->attributes['_flash'] = array();
        }
        if (!isset($this->attributes['_locale'])) {
            $this->attributes['_locale'] = $this->getDefaultLocale();
        }
                $this->oldFlashes = array_flip(array_keys($this->attributes['_flash']));
        $this->started = true;
    }
    public function has($name)
    {
        return array_key_exists($name, $this->attributes);
    }
    public function get($name, $default = null)
    {
        return array_key_exists($name, $this->attributes) ? $this->attributes[$name] : $default;
    }
    public function set($name, $value)
    {
        if (false === $this->started) {
            $this->start();
        }
        $this->attributes[$name] = $value;
    }
    public function getAttributes()
    {
        return $this->attributes;
    }
    public function setAttributes(array $attributes)
    {
        if (false === $this->started) {
            $this->start();
        }
        $this->attributes = $attributes;
    }
    public function remove($name)
    {
        if (false === $this->started) {
            $this->start();
        }
        if (array_key_exists($name, $this->attributes)) {
            unset($this->attributes[$name]);
        }
    }
    public function clear()
    {
        if (false === $this->started) {
            $this->start();
        }
        $this->attributes = array();
    }
    public function invalidate()
    {
        $this->clear();
        $this->storage->regenerate();
    }
    public function migrate()
    {
        $this->storage->regenerate();
    }
    public function getId()
    {
        return $this->storage->getId();
    }
    public function getLocale()
    {
        return $this->attributes['_locale'];
    }
    public function setLocale($locale)
    {
        if (false === $this->started) {
            $this->start();
        }
        $this->attributes['_locale'] = $locale;
    }
    public function getFlashes()
    {
        return isset($this->attributes['_flash']) ? $this->attributes['_flash'] : array();
    }
    public function setFlashes($values)
    {
        if (false === $this->started) {
            $this->start();
        }
        $this->attributes['_flash'] = $values;
    }
    public function getFlash($name, $default = null)
    {
        return array_key_exists($name, $this->attributes['_flash']) ? $this->attributes['_flash'][$name] : $default;
    }
    public function setFlash($name, $value)
    {
        if (false === $this->started) {
            $this->start();
        }
        $this->attributes['_flash'][$name] = $value;
        unset($this->oldFlashes[$name]);
    }
    public function hasFlash($name)
    {
        return array_key_exists($name, $this->attributes['_flash']);
    }
    public function removeFlash($name)
    {
        unset($this->attributes['_flash'][$name]);
    }
    public function clearFlashes()
    {
        $this->attributes['_flash'] = array();
    }
    public function save()
    {
        if (true === $this->started) {
            if (isset($this->attributes['_flash'])) {
                $this->attributes['_flash'] = array_diff_key($this->attributes['_flash'], $this->oldFlashes);
            }
            $this->storage->write('_symfony2', $this->attributes);
        }
    }
    public function __destruct()
    {
        $this->save();
    }
    public function serialize()
    {
        return serialize(array($this->storage, $this->options));
    }
    public function unserialize($serialized)
    {
        list($this->storage, $this->options) = unserialize($serialized);
        $this->attributes = array();
        $this->started = false;
    }
    protected function getDefaultLocale()
    {
        return isset($this->options['default_locale']) ? $this->options['default_locale'] : 'en';
    }
}
}
namespace Symfony\Component\HttpFoundation\SessionStorage
{
interface SessionStorageInterface
{
    function start();
    function getId();
    function read($key);
    function remove($key);
    function write($key, $data);
    function regenerate($destroy = false);
}
}
namespace Symfony\Bundle\FrameworkBundle\Templating
{
use Symfony\Component\Templating\EngineInterface as BaseEngineInterface;
use Symfony\Component\HttpFoundation\Response;
interface EngineInterface extends BaseEngineInterface
{
    function renderResponse($view, array $parameters = array(), Response $response = null);
}
}
namespace Symfony\Component\Templating
{
interface TemplateNameParserInterface
{
    function parse($name);
}
}
namespace Symfony\Component\Templating
{
use Symfony\Component\Templating\TemplateReferenceInterface;
use Symfony\Component\Templating\TemplateReference;
class TemplateNameParser implements TemplateNameParserInterface
{
    public function parse($name)
    {
        if ($name instanceof TemplateReferenceInterface) {
            return $name;
        }
        $engine = null;
        if (false !== $pos = strrpos($name, '.')) {
            $engine = substr($name, $pos + 1);
        }
        return new TemplateReference($name, $engine);
    }
}
}
namespace Symfony\Component\Templating
{
interface EngineInterface
{
    function render($name, array $parameters = array());
    function exists($name);
    function supports($name);
}
}
namespace Symfony\Component\Config
{
interface FileLocatorInterface
{
    function locate($name, $currentPath = null, $first = true);
}
}
namespace Symfony\Component\Templating
{
interface TemplateReferenceInterface
{
    function all();
    function set($name, $value);
    function get($name);
    function getSignature();
    function getPath();
}
}
namespace Symfony\Component\Templating
{
class TemplateReference implements TemplateReferenceInterface
{
    protected $parameters;
    public function  __construct($name = null, $engine = null)
    {
        $this->parameters = array(
            'name'      => $name,
            'engine'    => $engine,
        );
    }
    public function __toString()
    {
        return json_encode($this->parameters);
    }
    public function getSignature()
    {
        return md5(serialize($this->parameters));
    }
    public function set($name, $value)
    {
        if (array_key_exists($name, $this->parameters)) {
            $this->parameters[$name] = $value;
        } else {
            throw new \InvalidArgumentException(sprintf('The template does not support the "%s" parameter.', $name));
        }
        return $this;
    }
    public function get($name)
    {
        if (array_key_exists($name, $this->parameters)) {
            return $this->parameters[$name];
        }
        throw new \InvalidArgumentException(sprintf('The template does not support the "%s" parameter.', $name));
    }
    public function all()
    {
        return $this->parameters;
    }
    public function getPath()
    {
        return $this->parameters['name'];
    }
}
}
namespace Symfony\Bundle\FrameworkBundle\Templating
{
use Symfony\Component\Templating\TemplateReference as BaseTemplateReference;
class TemplateReference extends BaseTemplateReference
{
    public function __construct($bundle = null, $controller = null, $name = null, $format = null, $engine = null)
    {
        $this->parameters = array(
            'bundle'        => $bundle,
            'controller'    => $controller,
            'name'          => $name,
            'format'        => $format,
            'engine'        => $engine,
        );
    }
    public function getPath()
    {
        $controller = $this->get('controller');
        $path = (empty($controller) ? '' : $controller.'/').$this->get('name').'.'.$this->get('format').'.'.$this->get('engine');
        return empty($this->parameters['bundle']) ? 'views/'.$path : '@'.$this->get('bundle').'/Resources/views/'.$path;
    }
}
}
namespace Symfony\Bundle\FrameworkBundle\Templating
{
use Symfony\Component\Templating\TemplateNameParser as BaseTemplateNameParser;
use Symfony\Component\Templating\TemplateReferenceInterface;
use Symfony\Component\HttpKernel\KernelInterface;
class TemplateNameParser extends BaseTemplateNameParser
{
    protected $kernel;
    protected $cache;
    public function __construct(KernelInterface $kernel)
    {
        $this->kernel = $kernel;
        $this->cache = array();
    }
    public function parse($name)
    {
        if ($name instanceof TemplateReferenceInterface) {
            return $name;
        } else if (isset($this->cache[$name])) {
            return $this->cache[$name];
        }
                $name = str_replace(':/', ':', preg_replace('#/{2,}#', '/', strtr($name, '\\', '/')));
        if (false !== strpos($name, '..')) {
            throw new \RuntimeException(sprintf('Template name "%s" contains invalid characters.', $name));
        }
        $parts = explode(':', $name);
        if (3 !== count($parts)) {
            throw new \InvalidArgumentException(sprintf('Template name "%s" is not valid (format is "bundle:section:template.format.engine").', $name));
        }
        $elements = explode('.', $parts[2]);
        if (3 !== count($elements)) {
            throw new \InvalidArgumentException(sprintf('Template name "%s" is not valid (format is "bundle:section:template.format.engine").', $name));
        }
        $template = new TemplateReference($parts[0], $parts[1], $elements[0], $elements[1], $elements[2]);
        if ($template->get('bundle')) {
            try {
                $this->kernel->getBundle($template->get('bundle'));
            } catch (\Exception $e) {
                throw new \InvalidArgumentException(sprintf('Template name "%s" is not valid.', $name), 0, $e);
            }
        }
        return $this->cache[$name] = $template;
    }
    public function parseFromFilename($file)
    {
        $parts = explode('/', strtr($file, '\\', '/'));
        $elements = explode('.', array_pop($parts));
        if (3 !== count($elements)) {
            return false;
        }
        return new TemplateReference('', implode('/', $parts), $elements[0], $elements[1], $elements[2]);
    }
}
}
namespace Symfony\Bundle\FrameworkBundle\Templating\Loader
{
use Symfony\Component\Config\FileLocatorInterface;
use Symfony\Component\Templating\TemplateReferenceInterface;
class TemplateLocator implements FileLocatorInterface
{
    protected $locator;
    protected $path;
    protected $cache;
    public function __construct(FileLocatorInterface $locator, $path)
    {
        $this->locator = $locator;
        $this->path = $path;
        $this->cache = array();
    }
    public function locate($template, $currentPath = null, $first = true)
    {
        $key = $template->getSignature();
        if (isset($this->cache[$key])) {
            return $this->cache[$key];
        }
        try {
            return $this->cache[$key] = $this->locator->locate($template->getPath(), $this->path);
        } catch (\InvalidArgumentException $e) {
            throw new \InvalidArgumentException(sprintf('Unable to find template "%s" in "%s".', json_encode($template), $this->path), 0, $e);
        }
    }
}
}
namespace Symfony\Component\HttpFoundation
{
class Response
{
    public $headers;
    protected $content;
    protected $version;
    protected $statusCode;
    protected $statusText;
    protected $charset;
    static public $statusTexts = array(
        100 => 'Continue',
        101 => 'Switching Protocols',
        200 => 'OK',
        201 => 'Created',
        202 => 'Accepted',
        203 => 'Non-Authoritative Information',
        204 => 'No Content',
        205 => 'Reset Content',
        206 => 'Partial Content',
        300 => 'Multiple Choices',
        301 => 'Moved Permanently',
        302 => 'Found',
        303 => 'See Other',
        304 => 'Not Modified',
        305 => 'Use Proxy',
        307 => 'Temporary Redirect',
        400 => 'Bad Request',
        401 => 'Unauthorized',
        402 => 'Payment Required',
        403 => 'Forbidden',
        404 => 'Not Found',
        405 => 'Method Not Allowed',
        406 => 'Not Acceptable',
        407 => 'Proxy Authentication Required',
        408 => 'Request Timeout',
        409 => 'Conflict',
        410 => 'Gone',
        411 => 'Length Required',
        412 => 'Precondition Failed',
        413 => 'Request Entity Too Large',
        414 => 'Request-URI Too Long',
        415 => 'Unsupported Media Type',
        416 => 'Requested Range Not Satisfiable',
        417 => 'Expectation Failed',
        418 => 'I\'m a teapot',
        500 => 'Internal Server Error',
        501 => 'Not Implemented',
        502 => 'Bad Gateway',
        503 => 'Service Unavailable',
        504 => 'Gateway Timeout',
        505 => 'HTTP Version Not Supported',
    );
    public function __construct($content = '', $status = 200, $headers = array())
    {
        $this->setContent($content);
        $this->setStatusCode($status);
        $this->setProtocolVersion('1.0');
        $this->headers = new ResponseHeaderBag($headers);
    }
    public function __toString()
    {
        $content = '';
        if (!$this->headers->has('Content-Type')) {
            $this->headers->set('Content-Type', 'text/html; charset='.(null === $this->charset ? 'UTF-8' : $this->charset));
        }
                $content .= sprintf('HTTP/%s %s %s', $this->version, $this->statusCode, $this->statusText)."\n";
                foreach ($this->headers->all() as $name => $values) {
            foreach ($values as $value) {
                $content .= "$name: $value\n";
            }
        }
        $content .= "\n".$this->getContent();
        return $content;
    }
    public function __clone()
    {
        $this->headers = clone $this->headers;
    }
    public function sendHeaders()
    {
        if (!$this->headers->has('Content-Type')) {
            $this->headers->set('Content-Type', 'text/html; charset='.(null === $this->charset ? 'UTF-8' : $this->charset));
        }
                header(sprintf('HTTP/%s %s %s', $this->version, $this->statusCode, $this->statusText));
                foreach ($this->headers->all() as $name => $values) {
            foreach ($values as $value) {
                header($name.': '.$value);
            }
        }
                foreach ($this->headers->getCookies() as $cookie) {
            setcookie($cookie->getName(), $cookie->getValue(), $cookie->getExpire(), $cookie->getPath(), $cookie->getDomain(), $cookie->isSecure(), $cookie->isHttpOnly());
        }
    }
    public function sendContent()
    {
        echo $this->content;
    }
    public function send()
    {
        $this->sendHeaders();
        $this->sendContent();
    }
    public function setContent($content)
    {
        $this->content = $content;
    }
    public function getContent()
    {
        return $this->content;
    }
    public function setProtocolVersion($version)
    {
        $this->version = $version;
    }
    public function getProtocolVersion()
    {
        return $this->version;
    }
    public function setStatusCode($code, $text = null)
    {
        $this->statusCode = (int) $code;
        if ($this->isInvalid()) {
            throw new \InvalidArgumentException(sprintf('The HTTP status code "%s" is not valid.', $code));
        }
        $this->statusText = false === $text ? '' : (null === $text ? self::$statusTexts[$this->statusCode] : $text);
    }
    public function getStatusCode()
    {
        return $this->statusCode;
    }
    public function setCharset($charset)
    {
        $this->charset = $charset;
    }
    public function getCharset()
    {
        return $this->charset;
    }
    public function isCacheable()
    {
        if (!in_array($this->statusCode, array(200, 203, 300, 301, 302, 404, 410))) {
            return false;
        }
        if ($this->headers->hasCacheControlDirective('no-store') || $this->headers->getCacheControlDirective('private')) {
            return false;
        }
        return $this->isValidateable() || $this->isFresh();
    }
    public function isFresh()
    {
        return $this->getTtl() > 0;
    }
    public function isValidateable()
    {
        return $this->headers->has('Last-Modified') || $this->headers->has('ETag');
    }
    public function setPrivate()
    {
        $this->headers->removeCacheControlDirective('public');
        $this->headers->addCacheControlDirective('private');
    }
    public function setPublic()
    {
        $this->headers->addCacheControlDirective('public');
        $this->headers->removeCacheControlDirective('private');
    }
    public function mustRevalidate()
    {
        return $this->headers->hasCacheControlDirective('must-revalidate') || $this->headers->has('must-proxy-revalidate');
    }
    public function getDate()
    {
        if (null === $date = $this->headers->getDate('Date')) {
            $date = new \DateTime(null, new \DateTimeZone('UTC'));
            $this->headers->set('Date', $date->format('D, d M Y H:i:s').' GMT');
        }
        return $date;
    }
    public function getAge()
    {
        if ($age = $this->headers->get('Age')) {
            return $age;
        }
        return max(time() - $this->getDate()->format('U'), 0);
    }
    public function expire()
    {
        if ($this->isFresh()) {
            $this->headers->set('Age', $this->getMaxAge());
        }
    }
    public function getExpires()
    {
        return $this->headers->getDate('Expires');
    }
    public function setExpires(\DateTime $date = null)
    {
        if (null === $date) {
            $this->headers->remove('Expires');
        } else {
            $date = clone $date;
            $date->setTimezone(new \DateTimeZone('UTC'));
            $this->headers->set('Expires', $date->format('D, d M Y H:i:s').' GMT');
        }
    }
    public function getMaxAge()
    {
        if ($age = $this->headers->getCacheControlDirective('s-maxage')) {
            return $age;
        }
        if ($age = $this->headers->getCacheControlDirective('max-age')) {
            return $age;
        }
        if (null !== $this->getExpires()) {
            return $this->getExpires()->format('U') - $this->getDate()->format('U');
        }
        return null;
    }
    public function setMaxAge($value)
    {
        $this->headers->addCacheControlDirective('max-age', $value);
    }
    public function setSharedMaxAge($value)
    {
        $this->headers->addCacheControlDirective('s-maxage', $value);
    }
    public function getTtl()
    {
        if ($maxAge = $this->getMaxAge()) {
            return $maxAge - $this->getAge();
        }
        return null;
    }
    public function setTtl($seconds)
    {
        $this->setSharedMaxAge($this->getAge() + $seconds);
    }
    public function setClientTtl($seconds)
    {
        $this->setMaxAge($this->getAge() + $seconds);
    }
    public function getLastModified()
    {
        return $this->headers->getDate('Last-Modified');
    }
    public function setLastModified(\DateTime $date = null)
    {
        if (null === $date) {
            $this->headers->remove('Last-Modified');
        } else {
            $date = clone $date;
            $date->setTimezone(new \DateTimeZone('UTC'));
            $this->headers->set('Last-Modified', $date->format('D, d M Y H:i:s').' GMT');
        }
    }
    public function getEtag()
    {
        return $this->headers->get('ETag');
    }
    public function setEtag($etag = null, $weak = false)
    {
        if (null === $etag) {
            $this->headers->remove('Etag');
        } else {
            if (0 !== strpos($etag, '"')) {
                $etag = '"'.$etag.'"';
            }
            $this->headers->set('ETag', (true === $weak ? 'W/' : '').$etag);
        }
    }
    public function setCache(array $options)
    {
        if ($diff = array_diff(array_keys($options), array('etag', 'last_modified', 'max_age', 's_maxage', 'private', 'public'))) {
            throw new \InvalidArgumentException(sprintf('Response does not support the following options: "%s".', implode('", "', array_keys($diff))));
        }
        if (isset($options['etag'])) {
            $this->setEtag($options['etag']);
        }
        if (isset($options['last_modified'])) {
            $this->setLastModified($options['last_modified']);
        }
        if (isset($options['max_age'])) {
            $this->setMaxAge($options['max_age']);
        }
        if (isset($options['s_maxage'])) {
            $this->setSharedMaxAge($options['s_maxage']);
        }
        if (isset($options['public'])) {
            if ($options['public']) {
                $this->setPublic();
            } else {
                $this->setPrivate();
            }
        }
        if (isset($options['private'])) {
            if ($options['private']) {
                $this->setPrivate();
            } else {
                $this->setPublic();
            }
        }
    }
    public function setNotModified()
    {
        $this->setStatusCode(304);
        $this->setContent(null);
                foreach (array('Allow', 'Content-Encoding', 'Content-Language', 'Content-Length', 'Content-MD5', 'Content-Type', 'Last-Modified') as $header) {
            $this->headers->remove($header);
        }
    }
    public function hasVary()
    {
        return (Boolean) $this->headers->get('Vary');
    }
    public function getVary()
    {
        if (!$vary = $this->headers->get('Vary')) {
            return array();
        }
        return is_array($vary) ? $vary : preg_split('/[\s,]+/', $vary);
    }
    public function setVary($headers, $replace = true)
    {
        $this->headers->set('Vary', $headers, $replace);
    }
    public function isNotModified(Request $request)
    {
        $lastModified = $request->headers->get('If-Modified-Since');
        $notModified = false;
        if ($etags = $request->getEtags()) {
            $notModified = (in_array($this->getEtag(), $etags) || in_array('*', $etags)) && (!$lastModified || $this->headers->get('Last-Modified') == $lastModified);
        } elseif ($lastModified) {
            $notModified = $lastModified == $this->headers->get('Last-Modified');
        }
        if ($notModified) {
            $this->setNotModified();
        }
        return $notModified;
    }
        public function isInvalid()
    {
        return $this->statusCode < 100 || $this->statusCode >= 600;
    }
    public function isInformational()
    {
        return $this->statusCode >= 100 && $this->statusCode < 200;
    }
    public function isSuccessful()
    {
        return $this->statusCode >= 200 && $this->statusCode < 300;
    }
    public function isRedirection()
    {
        return $this->statusCode >= 300 && $this->statusCode < 400;
    }
    public function isClientError()
    {
        return $this->statusCode >= 400 && $this->statusCode < 500;
    }
    public function isServerError()
    {
        return $this->statusCode >= 500 && $this->statusCode < 600;
    }
    public function isOk()
    {
        return 200 === $this->statusCode;
    }
    public function isForbidden()
    {
        return 403 === $this->statusCode;
    }
    public function isNotFound()
    {
        return 404 === $this->statusCode;
    }
    public function isRedirect()
    {
        return in_array($this->statusCode, array(301, 302, 303, 307));
    }
    public function isEmpty()
    {
        return in_array($this->statusCode, array(201, 204, 304));
    }
    public function isRedirected($location)
    {
        return $this->isRedirect() && $location == $this->headers->get('Location');
    }
}
}
namespace Symfony\Component\HttpFoundation
{
class ResponseHeaderBag extends HeaderBag
{
    protected $computedCacheControl = array();
    public function __construct(array $headers = array())
    {
        parent::__construct($headers);
        if (!isset($this->headers['cache-control'])) {
            $this->set('cache-control', '');
        }
    }
    public function replace(array $headers = array())
    {
        parent::replace($headers);
        if (!isset($this->headers['cache-control'])) {
            $this->set('cache-control', '');
        }
    }
    public function set($key, $values, $replace = true)
    {
        parent::set($key, $values, $replace);
                if (in_array(strtr(strtolower($key), '_', '-'), array('cache-control', 'etag', 'last-modified', 'expires'))) {
            $computed = $this->computeCacheControlValue();
            $this->headers['cache-control'] = array($computed);
            $this->computedCacheControl = $this->parseCacheControl($computed);
        }
    }
    public function remove($key)
    {
        parent::remove($key);
        if ('cache-control' === strtr(strtolower($key), '_', '-')) {
            $this->computedCacheControl = array();
        }
    }
    public function hasCacheControlDirective($key)
    {
        return array_key_exists($key, $this->computedCacheControl);
    }
    public function getCacheControlDirective($key)
    {
        return array_key_exists($key, $this->computedCacheControl) ? $this->computedCacheControl[$key] : null;
    }
    public function clearCookie($name, $path = null, $domain = null)
    {
        $this->setCookie(new Cookie($name, null, 1, $path, $domain));
    }
    protected function computeCacheControlValue()
    {
        if (!$this->cacheControl && !$this->has('ETag') && !$this->has('Last-Modified') && !$this->has('Expires')) {
            return 'no-cache';
        }
        if (!$this->cacheControl) {
                        return 'private, must-revalidate';
        }
        $header = $this->getCacheControlHeader();
        if (isset($this->cacheControl['public']) || isset($this->cacheControl['private'])) {
            return $header;
        }
                if (!isset($this->cacheControl['s-maxage'])) {
            return $header.', private';
        }
        return $header;
    }
}}
namespace Symfony\Component\HttpKernel
{
use Symfony\Component\EventDispatcher\EventInterface;
use Symfony\Component\HttpFoundation\Response;
class ResponseListener
{
    protected $charset;
    public function __construct($charset)
    {
        $this->charset = $charset;
    }
    public function filter(EventInterface $event, Response $response)
    {
        if (HttpKernelInterface::MASTER_REQUEST !== $event->get('request_type')) {
            return $response;
        }
        if (null === $response->getCharset()) {
            $response->setCharset($this->charset);
        }
        if ($response->headers->has('Content-Type')) {
            return $response;
        }
        $request = $event->get('request');
        $format = $request->getRequestFormat();
        if ((null !== $format) && $mimeType = $request->getMimeType($format)) {
            $response->headers->set('Content-Type', $mimeType);
        }
        return $response;
    }
}
}
namespace Symfony\Component\HttpKernel\Controller
{
use Symfony\Component\HttpKernel\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;
class ControllerResolver implements ControllerResolverInterface
{
    protected $logger;
    public function __construct(LoggerInterface $logger = null)
    {
        $this->logger = $logger;
    }
    public function getController(Request $request)
    {
        if (!$controller = $request->attributes->get('_controller')) {
            if (null !== $this->logger) {
                $this->logger->err('Unable to look for the controller as the "_controller" parameter is missing');
            }
            return false;
        }
        if ($controller instanceof \Closure) {
            return $controller;
        }
        list($controller, $method) = $this->createController($controller);
        if (!method_exists($controller, $method)) {
            throw new \InvalidArgumentException(sprintf('Method "%s::%s" does not exist.', get_class($controller), $method));
        }
        if (null !== $this->logger) {
            $this->logger->info(sprintf('Using controller "%s::%s"', get_class($controller), $method));
        }
        return array($controller, $method);
    }
    public function getArguments(Request $request, $controller)
    {
        $attributes = $request->attributes->all();
        if (is_array($controller)) {
            $r = new \ReflectionMethod($controller[0], $controller[1]);
            $repr = sprintf('%s::%s()', get_class($controller[0]), $controller[1]);
        } else {
            $r = new \ReflectionFunction($controller);
            $repr = 'Closure';
        }
        $arguments = array();
        foreach ($r->getParameters() as $param) {
            if (array_key_exists($param->getName(), $attributes)) {
                $arguments[] = $attributes[$param->getName()];
            } elseif ($param->isDefaultValueAvailable()) {
                $arguments[] = $param->getDefaultValue();
            } else {
                throw new \RuntimeException(sprintf('Controller "%s" requires that you provide a value for the "$%s" argument (because there is no default value or because there is a non optional argument after this one).', $repr, $param->getName()));
            }
        }
        return $arguments;
    }
    protected function createController($controller)
    {
        if (false === strpos($controller, '::')) {
            throw new \InvalidArgumentException(sprintf('Unable to find controller "%s".', $controller));
        }
        list($class, $method) = explode('::', $controller);
        if (!class_exists($class)) {
            throw new \InvalidArgumentException(sprintf('Class "%s" does not exist.', $class));
        }
        return array(new $class(), $method);
    }
}
}
namespace Symfony\Component\HttpKernel\Controller
{
use Symfony\Component\HttpFoundation\Request;
interface ControllerResolverInterface
{
    function getController(Request $request);
    function getArguments(Request $request, $controller);
}
}
namespace Symfony\Bundle\FrameworkBundle
{
use Symfony\Component\HttpKernel\Log\LoggerInterface;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\EventDispatcher\EventInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
class RequestListener
{
    protected $router;
    protected $logger;
    protected $container;
    public function __construct(ContainerInterface $container, RouterInterface $router, LoggerInterface $logger = null)
    {
        $this->container = $container;
        $this->router = $router;
        $this->logger = $logger;
    }
    public function handle(EventInterface $event)
    {
        $request = $event->get('request');
        $master = HttpKernelInterface::MASTER_REQUEST === $event->get('request_type');
        $this->initializeSession($request, $master);
        $this->initializeRequestAttributes($request, $master);
    }
    protected function initializeSession(Request $request, $master)
    {
        if (!$master) {
            return;
        }
                if (null === $request->getSession() && $this->container->has('session')) {
            $request->setSession($this->container->get('session'));
        }
                if ($request->hasSession()) {
            $request->getSession()->start();
        }
    }
    protected function initializeRequestAttributes(Request $request, $master)
    {
        if ($master) {
                                    $this->router->setContext(array(
                'base_url'  => $request->getBaseUrl(),
                'method'    => $request->getMethod(),
                'host'      => $request->getHost(),
                'port'      => $request->getPort(),
                'is_secure' => $request->isSecure(),
            ));
        }
        if ($request->attributes->has('_controller')) {
                        return;
        }
                if (false !== $parameters = $this->router->match($request->getPathInfo())) {
            if (null !== $this->logger) {
                $this->logger->info(sprintf('Matched route "%s" (parameters: %s)', $parameters['_route'], json_encode($parameters)));
            }
            $request->attributes->add($parameters);
            if ($locale = $request->attributes->get('_locale')) {
                $request->getSession()->setLocale($locale);
            }
        } elseif (null !== $this->logger) {
            $this->logger->err(sprintf('No route found for %s', $request->getPathInfo()));
        }
    }
}
}
namespace Symfony\Bundle\FrameworkBundle\Controller
{
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\HttpKernel\Log\LoggerInterface;
class ControllerNameParser
{
    protected $kernel;
    protected $logger;
    public function __construct(KernelInterface $kernel, LoggerInterface $logger = null)
    {
        $this->kernel = $kernel;
        $this->logger = $logger;
    }
    public function parse($controller)
    {
        if (3 != count($parts = explode(':', $controller))) {
            throw new \InvalidArgumentException(sprintf('The "%s" controller is not a valid a:b:c controller string.', $controller));
        }
        list($bundle, $controller, $action) = $parts;
        $class = null;
        $logs = array();
        foreach ($this->kernel->getBundle($bundle, false) as $b) {
            $try = $b->getNamespace().'\\Controller\\'.$controller.'Controller';
            if (!class_exists($try)) {
                if (null !== $this->logger) {
                    $logs[] = sprintf('Failed finding controller "%s:%s" from namespace "%s" (%s)', $bundle, $controller, $b->getNamespace(), $try);
                }
            } else {
                $class = $try;
                break;
            }
        }
        if (null === $class) {
            if (null !== $this->logger) {
                foreach ($logs as $log) {
                    $this->logger->info($log);
                }
            }
            throw new \InvalidArgumentException(sprintf('Unable to find controller "%s:%s".', $bundle, $controller));
        }
        return $class.'::'.$action.'Action';
    }
}
}
namespace Symfony\Bundle\FrameworkBundle\Controller
{
use Symfony\Component\HttpKernel\Log\LoggerInterface;
use Symfony\Component\HttpKernel\Controller\ControllerResolver as BaseControllerResolver;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\ControllerNameParser;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
class ControllerResolver extends BaseControllerResolver
{
    protected $container;
    protected $parser;
    public function __construct(ContainerInterface $container, ControllerNameParser $parser, LoggerInterface $logger = null)
    {
        $this->container = $container;
        $this->parser = $parser;
        parent::__construct($logger);
    }
    protected function createController($controller)
    {
        if (false === strpos($controller, '::')) {
            $count = substr_count($controller, ':');
            if (2 == $count) {
                                $controller = $this->parser->parse($controller);
            } elseif (1 == $count) {
                                list($service, $method) = explode(':', $controller);
                return array($this->container->get($service), $method);
            } else {
                throw new \LogicException(sprintf('Unable to parse the controller name "%s".', $controller));
            }
        }
        list($class, $method) = explode('::', $controller);
        if (!class_exists($class)) {
            throw new \InvalidArgumentException(sprintf('Class "%s" does not exist.', $class));
        }
        $controller = new $class();
        if ($controller instanceof ContainerAwareInterface) {
            $controller->setContainer($this->container);
        }
        return array($controller, $method);
    }
}
}
namespace Symfony\Bundle\FrameworkBundle\Controller
{
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\DependencyInjection\ContainerAware;
class Controller extends ContainerAware
{
    public function generateUrl($route, array $parameters = array(), $absolute = false)
    {
        return $this->container->get('router')->generate($route, $parameters, $absolute);
    }
    public function forward($controller, array $path = array(), array $query = array())
    {
        return $this->container->get('http_kernel')->forward($controller, $path, $query);
    }
    public function renderView($view, array $parameters = array())
    {
        return $this->container->get('templating')->render($view, $parameters);
    }
    public function render($view, array $parameters = array(), Response $response = null)
    {
        return $this->container->get('templating')->renderResponse($view, $parameters, $response);
    }
    public function has($id)
    {
        return $this->container->has($id);
    }
    public function get($id)
    {
        return $this->container->get($id);
    }
}
}
namespace Symfony\Component\EventDispatcher
{
interface EventInterface
{
    function getSubject();
    function getName();
    function setProcessed();
    function isProcessed();
    function all();
    function has($name);
    function get($name);
    function set($name, $value);
}
}
namespace Symfony\Component\EventDispatcher
{
class Event implements EventInterface
{
    protected $processed = false;
    protected $subject;
    protected $name;
    protected $parameters;
    public function __construct($subject, $name, $parameters = array())
    {
        $this->subject = $subject;
        $this->name = $name;
        $this->parameters = $parameters;
    }
    public function getSubject()
    {
        return $this->subject;
    }
    public function getName()
    {
        return $this->name;
    }
    public function setProcessed()
    {
        $this->processed = true;
    }
    public function isProcessed()
    {
        return $this->processed;
    }
    public function all()
    {
        return $this->parameters;
    }
    public function has($name)
    {
        return array_key_exists($name, $this->parameters);
    }
    public function get($name)
    {
        if (!array_key_exists($name, $this->parameters)) {
            throw new \InvalidArgumentException(sprintf('The event "%s" has no "%s" parameter.', $this->name, $name));
        }
        return $this->parameters[$name];
    }
    public function set($name, $value)
    {
        $this->parameters[$name] = $value;
    }
}
}
namespace Symfony\Component\EventDispatcher
{
interface EventDispatcherInterface
{
    function connect($name, $listener, $priority = 0);
    function disconnect($name, $listener = null);
    function notify(EventInterface $event);
    function notifyUntil(EventInterface $event);
    function filter(EventInterface $event, $value);
    function hasListeners($name);
    function getListeners($name);
}
}
namespace Symfony\Component\EventDispatcher
{
class EventDispatcher implements EventDispatcherInterface
{
    protected $listeners = array();
    public function connect($name, $listener, $priority = 0)
    {
        if (!isset($this->listeners[$name][$priority])) {
            if (!isset($this->listeners[$name])) {
                $this->listeners[$name] = array();
            }
            $this->listeners[$name][$priority] = array();
        }
        $this->listeners[$name][$priority][] = $listener;
    }
    public function disconnect($name, $listener = null)
    {
        if (!isset($this->listeners[$name])) {
            return;
        }
        if (null === $listener) {
            unset($this->listeners[$name]);
            return;
        }
        foreach ($this->listeners[$name] as $priority => $callables) {
            foreach ($callables as $i => $callable) {
                if ($listener === $callable) {
                    unset($this->listeners[$name][$priority][$i]);
                }
            }
        }
    }
    public function notify(EventInterface $event)
    {
        foreach ($this->getListeners($event->getName()) as $listener) {
            call_user_func($listener, $event);
        }
    }
    public function notifyUntil(EventInterface $event)
    {
        foreach ($this->getListeners($event->getName()) as $listener) {
            $ret = call_user_func($listener, $event);
            if ($event->isProcessed()) {
                return $ret;
            }
        }
    }
    public function filter(EventInterface $event, $value)
    {
        foreach ($this->getListeners($event->getName()) as $listener) {
            $value = call_user_func($listener, $event, $value);
        }
        return $value;
    }
    public function hasListeners($name)
    {
        return (Boolean) count($this->getListeners($name));
    }
    public function getListeners($name)
    {
        if (!isset($this->listeners[$name])) {
            return array();
        }
        krsort($this->listeners[$name]);
        return call_user_func_array('array_merge', $this->listeners[$name]);
    }
}
}
namespace Symfony\Bundle\FrameworkBundle
{
use Symfony\Component\EventDispatcher\EventDispatcher as BaseEventDispatcher;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\EventDispatcher\EventInterface;
class EventDispatcher extends BaseEventDispatcher
{
    protected $container;
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }
    public function registerKernelListeners(array $listeners)
    {
        $this->listeners = $listeners;
    }
    public function notify(EventInterface $event)
    {
        foreach ($this->getListeners($event->getName()) as $listener) {
            if (is_array($listener) && is_string($listener[0])) {
                $listener[0] = $this->container->get($listener[0]);
            }
            call_user_func($listener, $event);
        }
    }
    public function notifyUntil(EventInterface $event)
    {
        foreach ($this->getListeners($event->getName()) as $listener) {
            if (is_array($listener) && is_string($listener[0])) {
                $listener[0] = $this->container->get($listener[0]);
            }
            $ret = call_user_func($listener, $event);
            if ($event->isProcessed()) {
                return $ret;
            }
        }
    }
    public function filter(EventInterface $event, $value)
    {
        foreach ($this->getListeners($event->getName()) as $listener) {
            if (is_array($listener) && is_string($listener[0])) {
                $listener[0] = $this->container->get($listener[0]);
            }
            $value = call_user_func($listener, $event, $value);
        }
        return $value;
    }
}
}
namespace
{
class Twig_Environment
{
    const VERSION = '1.0.0-RC2';
    protected $charset;
    protected $loader;
    protected $debug;
    protected $autoReload;
    protected $cache;
    protected $lexer;
    protected $parser;
    protected $compiler;
    protected $baseTemplateClass;
    protected $extensions;
    protected $parsers;
    protected $visitors;
    protected $filters;
    protected $tests;
    protected $functions;
    protected $globals;
    protected $runtimeInitialized;
    protected $loadedTemplates;
    protected $strictVariables;
    protected $unaryOperators;
    protected $binaryOperators;
    protected $templateClassPrefix = '__TwigTemplate_';
    protected $functionCallbacks;
    protected $filterCallbacks;
    public function __construct(Twig_LoaderInterface $loader = null, $options = array())
    {
        if (null !== $loader) {
            $this->setLoader($loader);
        }
        $options = array_merge(array(
            'debug'               => false,
            'charset'             => 'UTF-8',
            'base_template_class' => 'Twig_Template',
            'strict_variables'    => false,
            'autoescape'          => true,
            'cache'               => false,
            'auto_reload'         => null,
            'optimizations'       => -1,
        ), $options);
        $this->debug              = (bool) $options['debug'];
        $this->charset            = $options['charset'];
        $this->baseTemplateClass  = $options['base_template_class'];
        $this->autoReload         = null === $options['auto_reload'] ? $this->debug : (bool) $options['auto_reload'];
        $this->extensions         = array(
            'core'      => new Twig_Extension_Core(),
            'escaper'   => new Twig_Extension_Escaper((bool) $options['autoescape']),
            'optimizer' => new Twig_Extension_Optimizer($options['optimizations']),
        );
        $this->strictVariables    = (bool) $options['strict_variables'];
        $this->runtimeInitialized = false;
        $this->setCache($options['cache']);
        $this->functionCallbacks = array();
        $this->filterCallbacks = array();
    }
    public function getBaseTemplateClass()
    {
        return $this->baseTemplateClass;
    }
    public function setBaseTemplateClass($class)
    {
        $this->baseTemplateClass = $class;
    }
    public function enableDebug()
    {
        $this->debug = true;
    }
    public function disableDebug()
    {
        $this->debug = false;
    }
    public function isDebug()
    {
        return $this->debug;
    }
    public function enableAutoReload()
    {
        $this->autoReload = true;
    }
    public function disableAutoReload()
    {
        $this->autoReload = false;
    }
    public function isAutoReload()
    {
        return $this->autoReload;
    }
    public function enableStrictVariables()
    {
        $this->strictVariables = true;
    }
    public function disableStrictVariables()
    {
        $this->strictVariables = false;
    }
    public function isStrictVariables()
    {
        return $this->strictVariables;
    }
    public function getCache()
    {
        return $this->cache;
    }
    public function setCache($cache)
    {
        $this->cache = $cache ? $cache : false;
    }
    public function getCacheFilename($name)
    {
        if (false === $this->cache) {
            return false;
        }
        $class = substr($this->getTemplateClass($name), strlen($this->templateClassPrefix));
        return $this->getCache().'/'.substr($class, 0, 2).'/'.substr($class, 2, 2).'/'.substr($class, 4).'.php';
    }
    public function getTemplateClass($name)
    {
        return $this->templateClassPrefix.md5($this->loader->getCacheKey($name));
    }
    public function getTemplateClassPrefix()
    {
        return $this->templateClassPrefix;
    }
    public function loadTemplate($name)
    {
        $cls = $this->getTemplateClass($name);
        if (isset($this->loadedTemplates[$cls])) {
            return $this->loadedTemplates[$cls];
        }
        if (!class_exists($cls, false)) {
            if (false === $cache = $this->getCacheFilename($name)) {
                eval('?>'.$this->compileSource($this->loader->getSource($name), $name));
            } else {
                if (!file_exists($cache) || ($this->isAutoReload() && !$this->loader->isFresh($name, filemtime($cache)))) {
                    $this->writeCacheFile($cache, $this->compileSource($this->loader->getSource($name), $name));
                }
                require_once $cache;
            }
        }
        if (!$this->runtimeInitialized) {
            $this->initRuntime();
        }
        return $this->loadedTemplates[$cls] = new $cls($this);
    }
    public function clearTemplateCache()
    {
        $this->loadedTemplates = array();
    }
    public function clearCacheFiles()
    {
        if (false === $this->cache) {
            return;
        }
        foreach (new RecursiveIteratorIterator(new RecursiveDirectoryIterator($this->cache), RecursiveIteratorIterator::LEAVES_ONLY) as $file) {
            if ($file->isFile()) {
                @unlink($file->getPathname());
            }
        }
    }
    public function getLexer()
    {
        if (null === $this->lexer) {
            $this->lexer = new Twig_Lexer($this);
        }
        return $this->lexer;
    }
    public function setLexer(Twig_LexerInterface $lexer)
    {
        $this->lexer = $lexer;
    }
    public function tokenize($source, $name = null)
    {
        return $this->getLexer()->tokenize($source, $name);
    }
    public function getParser()
    {
        if (null === $this->parser) {
            $this->parser = new Twig_Parser($this);
        }
        return $this->parser;
    }
    public function setParser(Twig_ParserInterface $parser)
    {
        $this->parser = $parser;
    }
    public function parse(Twig_TokenStream $tokens)
    {
        return $this->getParser()->parse($tokens);
    }
    public function getCompiler()
    {
        if (null === $this->compiler) {
            $this->compiler = new Twig_Compiler($this);
        }
        return $this->compiler;
    }
    public function setCompiler(Twig_CompilerInterface $compiler)
    {
        $this->compiler = $compiler;
    }
    public function compile(Twig_NodeInterface $node)
    {
        return $this->getCompiler()->compile($node)->getSource();
    }
    public function compileSource($source, $name = null)
    {
        return $this->compile($this->parse($this->tokenize($source, $name)));
    }
    public function setLoader(Twig_LoaderInterface $loader)
    {
        $this->loader = $loader;
    }
    public function getLoader()
    {
        return $this->loader;
    }
    public function setCharset($charset)
    {
        $this->charset = $charset;
    }
    public function getCharset()
    {
        return $this->charset;
    }
    public function initRuntime()
    {
        $this->runtimeInitialized = true;
        foreach ($this->getExtensions() as $extension) {
            $extension->initRuntime($this);
        }
    }
    public function hasExtension($name)
    {
        return isset($this->extensions[$name]);
    }
    public function getExtension($name)
    {
        if (!isset($this->extensions[$name])) {
            throw new Twig_Error_Runtime(sprintf('The "%s" extension is not enabled.', $name));
        }
        return $this->extensions[$name];
    }
    public function addExtension(Twig_ExtensionInterface $extension)
    {
        $this->extensions[$extension->getName()] = $extension;
    }
    public function removeExtension($name)
    {
        unset($this->extensions[$name]);
    }
    public function setExtensions(array $extensions)
    {
        foreach ($extensions as $extension) {
            $this->addExtension($extension);
        }
    }
    public function getExtensions()
    {
        return $this->extensions;
    }
    public function addTokenParser(Twig_TokenParserInterface $parser)
    {
        if (null === $this->parsers) {
            $this->getTokenParsers();
        }
        $this->parsers->addTokenParser($parser);
    }
    public function getTokenParsers()
    {
        if (null === $this->parsers) {
            $this->parsers = new Twig_TokenParserBroker;
            foreach ($this->getExtensions() as $extension) {
                $parsers = $extension->getTokenParsers();
                foreach($parsers as $parser) {
                    if ($parser instanceof Twig_TokenParserInterface) {
                        $this->parsers->addTokenParser($parser);
                    } else if ($parser instanceof Twig_TokenParserBrokerInterface) {
                        $this->parsers->addTokenParserBroker($parser);
                    } else {
                        throw new Twig_Error_Runtime('getTokenParsers() must return an array of Twig_TokenParserInterface or Twig_TokenParserBrokerInterface instances');
                    }
                }
            }
        }
        return $this->parsers;
    }
    public function addNodeVisitor(Twig_NodeVisitorInterface $visitor)
    {
        if (null === $this->visitors) {
            $this->getNodeVisitors();
        }
        $this->visitors[] = $visitor;
    }
    public function getNodeVisitors()
    {
        if (null === $this->visitors) {
            $this->visitors = array();
            foreach ($this->getExtensions() as $extension) {
                $this->visitors = array_merge($this->visitors, $extension->getNodeVisitors());
            }
        }
        return $this->visitors;
    }
    public function addFilter($name, Twig_FilterInterface $filter)
    {
        if (null === $this->filters) {
            $this->loadFilters();
        }
        $this->filters[$name] = $filter;
    }
    public function getFilter($name)
    {
        if (null === $this->filters) {
            $this->loadFilters();
        }
        if (isset($this->filters[$name])) {
            return $this->filters[$name];
        }
        foreach ($this->filterCallbacks as $callback) {
            if (false !== $filter = call_user_func($callback, $name)) {
                return $filter;
            }
        }
        return false;
    }
    public function registerUndefinedFilterCallback($callable)
    {
        $this->filterCallbacks[] = $callable;
    }
    protected function loadFilters()
    {
        $this->filters = array();
        foreach ($this->getExtensions() as $extension) {
            $this->filters = array_merge($this->filters, $extension->getFilters());
        }
    }
    public function addTest($name, Twig_TestInterface $test)
    {
        if (null === $this->tests) {
            $this->getTests();
        }
        $this->tests[$name] = $test;
    }
    public function getTests()
    {
        if (null === $this->tests) {
            $this->tests = array();
            foreach ($this->getExtensions() as $extension) {
                $this->tests = array_merge($this->tests, $extension->getTests());
            }
        }
        return $this->tests;
    }
    public function addFunction($name, Twig_FunctionInterface $function)
    {
        if (null === $this->functions) {
            $this->loadFunctions();
        }
        $this->functions[$name] = $function;
    }
    public function getFunction($name)
    {
        if (null === $this->functions) {
            $this->loadFunctions();
        }
        if (isset($this->functions[$name])) {
            return $this->functions[$name];
        }
        foreach ($this->functionCallbacks as $callback) {
            if (false !== $function = call_user_func($callback, $name)) {
                return $function;
            }
        }
        return false;
    }
    public function registerUndefinedFunctionCallback($callable)
    {
        $this->functionCallbacks[] = $callable;
    }
    protected function loadFunctions() {
        $this->functions = array();
        foreach ($this->getExtensions() as $extension) {
            $this->functions = array_merge($this->functions, $extension->getFunctions());
        }
    }
    public function addGlobal($name, $value)
    {
        if (null === $this->globals) {
            $this->getGlobals();
        }
        $this->globals[$name] = $value;
    }
    public function getGlobals()
    {
        if (null === $this->globals) {
            $this->globals = array();
            foreach ($this->getExtensions() as $extension) {
                $this->globals = array_merge($this->globals, $extension->getGlobals());
            }
        }
        return $this->globals;
    }
    public function getUnaryOperators()
    {
        if (null === $this->unaryOperators) {
            $this->initOperators();
        }
        return $this->unaryOperators;
    }
    public function getBinaryOperators()
    {
        if (null === $this->binaryOperators) {
            $this->initOperators();
        }
        return $this->binaryOperators;
    }
    protected function initOperators()
    {
        $this->unaryOperators = array();
        $this->binaryOperators = array();
        foreach ($this->getExtensions() as $extension) {
            $operators = $extension->getOperators();
            if (!$operators) {
                continue;
            }
            if (2 !== count($operators)) {
                throw new InvalidArgumentException(sprintf('"%s::getOperators()" does not return a valid operators array.', get_class($extension)));
            }
            $this->unaryOperators = array_merge($this->unaryOperators, $operators[0]);
            $this->binaryOperators = array_merge($this->binaryOperators, $operators[1]);
        }
    }
    protected function writeCacheFile($file, $content)
    {
        if (!is_dir(dirname($file))) {
            mkdir(dirname($file), 0777, true);
        }
        $tmpFile = tempnam(dirname($file), basename($file));
        if (false !== @file_put_contents($tmpFile, $content)) {
                        if (@rename($tmpFile, $file) || (@copy($tmpFile, $file) && unlink($tmpFile))) {
                chmod($file, 0644);
                return;
            }
        }
        throw new Twig_Error_Runtime(sprintf('Failed to write cache file "%s".', $file));
    }
}
}
namespace
{
interface Twig_ExtensionInterface
{
    function initRuntime(Twig_Environment $environment);
    function getTokenParsers();
    function getNodeVisitors();
    function getFilters();
    function getTests();
    function getFunctions();
    function getOperators();
    function getGlobals();
    function getName();
}
}
namespace
{
abstract class Twig_Extension implements Twig_ExtensionInterface
{
    public function initRuntime(Twig_Environment $environment)
    {
    }
    public function getTokenParsers()
    {
        return array();
    }
    public function getNodeVisitors()
    {
        return array();
    }
    public function getFilters()
    {
        return array();
    }
    public function getTests()
    {
        return array();
    }
    public function getFunctions()
    {
        return array();
    }
    public function getOperators()
    {
        return array();
    }
    public function getGlobals()
    {
        return array();
    }
}
}
namespace
{
class Twig_Extension_Core extends Twig_Extension
{
    public function getTokenParsers()
    {
        return array(
            new Twig_TokenParser_For(),
            new Twig_TokenParser_If(),
            new Twig_TokenParser_Extends(),
            new Twig_TokenParser_Include(),
            new Twig_TokenParser_Block(),
            new Twig_TokenParser_Filter(),
            new Twig_TokenParser_Macro(),
            new Twig_TokenParser_Import(),
            new Twig_TokenParser_From(),
            new Twig_TokenParser_Set(),
            new Twig_TokenParser_Spaceless(),
        );
    }
    public function getFilters()
    {
        $filters = array(
                        'date'    => new Twig_Filter_Function('twig_date_format_filter'),
            'format'  => new Twig_Filter_Function('sprintf'),
            'replace' => new Twig_Filter_Function('twig_strtr'),
                        'url_encode'  => new Twig_Filter_Function('twig_urlencode_filter'),
            'json_encode' => new Twig_Filter_Function('json_encode'),
                        'title'      => new Twig_Filter_Function('twig_title_string_filter', array('needs_environment' => true)),
            'capitalize' => new Twig_Filter_Function('twig_capitalize_string_filter', array('needs_environment' => true)),
            'upper'      => new Twig_Filter_Function('strtoupper'),
            'lower'      => new Twig_Filter_Function('strtolower'),
            'striptags'  => new Twig_Filter_Function('strip_tags'),
                        'join'    => new Twig_Filter_Function('twig_join_filter'),
            'reverse' => new Twig_Filter_Function('twig_reverse_filter'),
            'length'  => new Twig_Filter_Function('twig_length_filter', array('needs_environment' => true)),
            'sort'    => new Twig_Filter_Function('twig_sort_filter'),
            'merge'   => new Twig_Filter_Function('twig_array_merge'),
                        'default' => new Twig_Filter_Function('twig_default_filter'),
            'keys'    => new Twig_Filter_Function('twig_get_array_keys_filter'),
                        'escape' => new Twig_Filter_Function('twig_escape_filter', array('needs_environment' => true, 'is_safe_callback' => 'twig_escape_filter_is_safe')),
            'e'      => new Twig_Filter_Function('twig_escape_filter', array('needs_environment' => true, 'is_safe_callback' => 'twig_escape_filter_is_safe')),
        );
        if (function_exists('mb_get_info')) {
            $filters['upper'] = new Twig_Filter_Function('twig_upper_filter', array('needs_environment' => true));
            $filters['lower'] = new Twig_Filter_Function('twig_lower_filter', array('needs_environment' => true));
        }
        return $filters;
    }
    public function getFunctions()
    {
        return array(
            'range'    => new Twig_Function_Method($this, 'getRange'),
            'constant' => new Twig_Function_Method($this, 'getConstant'),
            'cycle'    => new Twig_Function_Method($this, 'getCycle'),
        );
    }
    public function getRange($start, $end, $step = 1)
    {
        return range($start, $end, $step);
    }
    public function getConstant($value)
    {
        return constant($value);
    }
    public function getCycle($values, $i)
    {
        if (!is_array($values) && !$values instanceof ArrayAccess) {
            return $values;
        }
        return $values[$i % count($values)];
    }
    public function getTests()
    {
        return array(
            'even'        => new Twig_Test_Function('twig_test_even'),
            'odd'         => new Twig_Test_Function('twig_test_odd'),
            'defined'     => new Twig_Test_Function('twig_test_defined'),
            'sameas'      => new Twig_Test_Function('twig_test_sameas'),
            'none'        => new Twig_Test_Function('twig_test_none'),
            'divisibleby' => new Twig_Test_Function('twig_test_divisibleby'),
            'constant'    => new Twig_Test_Function('twig_test_constant'),
            'empty'       => new Twig_Test_Function('twig_test_empty'),
        );
    }
    public function getOperators()
    {
        return array(
            array(
                'not' => array('precedence' => 50, 'class' => 'Twig_Node_Expression_Unary_Not'),
                '-'   => array('precedence' => 50, 'class' => 'Twig_Node_Expression_Unary_Neg'),
                '+'   => array('precedence' => 50, 'class' => 'Twig_Node_Expression_Unary_Pos'),
            ),
            array(
                'or'     => array('precedence' => 10, 'class' => 'Twig_Node_Expression_Binary_Or', 'associativity' => Twig_ExpressionParser::OPERATOR_LEFT),
                'and'    => array('precedence' => 15, 'class' => 'Twig_Node_Expression_Binary_And', 'associativity' => Twig_ExpressionParser::OPERATOR_LEFT),
                '=='     => array('precedence' => 20, 'class' => 'Twig_Node_Expression_Binary_Equal', 'associativity' => Twig_ExpressionParser::OPERATOR_LEFT),
                '!='     => array('precedence' => 20, 'class' => 'Twig_Node_Expression_Binary_NotEqual', 'associativity' => Twig_ExpressionParser::OPERATOR_LEFT),
                '<'      => array('precedence' => 20, 'class' => 'Twig_Node_Expression_Binary_Less', 'associativity' => Twig_ExpressionParser::OPERATOR_LEFT),
                '>'      => array('precedence' => 20, 'class' => 'Twig_Node_Expression_Binary_Greater', 'associativity' => Twig_ExpressionParser::OPERATOR_LEFT),
                '>='     => array('precedence' => 20, 'class' => 'Twig_Node_Expression_Binary_GreaterEqual', 'associativity' => Twig_ExpressionParser::OPERATOR_LEFT),
                '<='     => array('precedence' => 20, 'class' => 'Twig_Node_Expression_Binary_LessEqual', 'associativity' => Twig_ExpressionParser::OPERATOR_LEFT),
                'not in' => array('precedence' => 20, 'class' => 'Twig_Node_Expression_Binary_NotIn', 'associativity' => Twig_ExpressionParser::OPERATOR_LEFT),
                'in'     => array('precedence' => 20, 'class' => 'Twig_Node_Expression_Binary_In', 'associativity' => Twig_ExpressionParser::OPERATOR_LEFT),
                '+'      => array('precedence' => 30, 'class' => 'Twig_Node_Expression_Binary_Add', 'associativity' => Twig_ExpressionParser::OPERATOR_LEFT),
                '-'      => array('precedence' => 30, 'class' => 'Twig_Node_Expression_Binary_Sub', 'associativity' => Twig_ExpressionParser::OPERATOR_LEFT),
                '~'      => array('precedence' => 40, 'class' => 'Twig_Node_Expression_Binary_Concat', 'associativity' => Twig_ExpressionParser::OPERATOR_LEFT),
                '*'      => array('precedence' => 60, 'class' => 'Twig_Node_Expression_Binary_Mul', 'associativity' => Twig_ExpressionParser::OPERATOR_LEFT),
                '/'      => array('precedence' => 60, 'class' => 'Twig_Node_Expression_Binary_Div', 'associativity' => Twig_ExpressionParser::OPERATOR_LEFT),
                '//'     => array('precedence' => 60, 'class' => 'Twig_Node_Expression_Binary_FloorDiv', 'associativity' => Twig_ExpressionParser::OPERATOR_LEFT),
                '%'      => array('precedence' => 60, 'class' => 'Twig_Node_Expression_Binary_Mod', 'associativity' => Twig_ExpressionParser::OPERATOR_LEFT),
                'is'     => array('precedence' => 100, 'callable' => array($this, 'parseTestExpression'), 'associativity' => Twig_ExpressionParser::OPERATOR_LEFT),
                'is not' => array('precedence' => 100, 'callable' => array($this, 'parseNotTestExpression'), 'associativity' => Twig_ExpressionParser::OPERATOR_LEFT),
                '..'     => array('precedence' => 110, 'class' => 'Twig_Node_Expression_Binary_Range', 'associativity' => Twig_ExpressionParser::OPERATOR_LEFT),
                '**'     => array('precedence' => 200, 'class' => 'Twig_Node_Expression_Binary_Power', 'associativity' => Twig_ExpressionParser::OPERATOR_RIGHT),
            ),
        );
    }
    public function parseNotTestExpression(Twig_Parser $parser, $node)
    {
        return new Twig_Node_Expression_Unary_Not($this->parseTestExpression($parser, $node), $parser->getCurrentToken()->getLine());
    }
    public function parseTestExpression(Twig_Parser $parser, $node)
    {
        $stream = $parser->getStream();
        $name = $stream->expect(Twig_Token::NAME_TYPE);
        $arguments = null;
        if ($stream->test(Twig_Token::PUNCTUATION_TYPE, '(')) {
            $arguments = $parser->getExpressionParser()->parseArguments();
        }
        return new Twig_Node_Expression_Test($node, $name->getValue(), $arguments, $parser->getCurrentToken()->getLine());
    }
    public function getName()
    {
        return 'core';
    }
}
function twig_date_format_filter($date, $format = 'F j, Y H:i')
{
    if (!$date instanceof DateTime) {
        $date = new DateTime((ctype_digit($date) ? '@' : '').$date);
    }
    return $date->format($format);
}
function twig_urlencode_filter($url, $raw = false)
{
    if ($raw) {
        return rawurlencode($url);
    }
    return urlencode($url);
}
function twig_array_merge($arr1, $arr2)
{
    if (!is_array($arr1) || !is_array($arr2)) {
        throw new Twig_Error_Runtime('The merge filter only work with arrays or hashes.');
    }
    return array_merge($arr1, $arr2);
}
function twig_join_filter($value, $glue = '')
{
    return implode($glue, (array) $value);
}
function twig_default_filter($value, $default = '')
{
    return twig_test_empty($value) ? $default : $value;
}
function twig_get_array_keys_filter($array)
{
    if (is_object($array) && $array instanceof Traversable) {
        return array_keys(iterator_to_array($array));
    }
    if (!is_array($array)) {
        return array();
    }
    return array_keys($array);
}
function twig_reverse_filter($array)
{
    if (is_object($array) && $array instanceof Traversable) {
        return array_reverse(iterator_to_array($array));
    }
    if (!is_array($array)) {
        return array();
    }
    return array_reverse($array);
}
function twig_sort_filter($array)
{
    asort($array);
    return $array;
}
function twig_in_filter($value, $compare)
{
    if (is_array($compare)) {
        return in_array($value, $compare);
    } elseif (is_string($compare)) {
        return false !== strpos($compare, (string) $value);
    } elseif (is_object($compare) && $compare instanceof Traversable) {
        return in_array($value, iterator_to_array($compare, false));
    }
    return false;
}
function twig_strtr($pattern, $replacements)
{
    return str_replace(array_keys($replacements), array_values($replacements), $pattern);
}
function twig_escape_filter(Twig_Environment $env, $string, $type = 'html')
{
    if (is_object($string) && $string instanceof Twig_Markup) {
        return $string;
    }
    if (!is_string($string) && !(is_object($string) && method_exists($string, '__toString'))) {
        return $string;
    }
    switch ($type) {
        case 'js':
                                    $charset = $env->getCharset();
            if ('UTF-8' != $charset) {
                $string = _twig_convert_encoding($string, 'UTF-8', $charset);
            }
            if (null === $string = preg_replace_callback('#[^\p{L}\p{N} ]#u', '_twig_escape_js_callback', $string)) {
                throw new Twig_Error_Runtime('The string to escape is not a valid UTF-8 string.');
            }
            if ('UTF-8' != $charset) {
                $string = _twig_convert_encoding($string, $charset, 'UTF-8');
            }
            return $string;
        case 'html':
            return htmlspecialchars($string, ENT_QUOTES, $env->getCharset());
        default:
            throw new Twig_Error_Runtime(sprintf('Invalid escape type "%s".', $type));
    }
}
function twig_escape_filter_is_safe(Twig_Node $filterArgs)
{
    foreach ($filterArgs as $arg) {
        if ($arg instanceof Twig_Node_Expression_Constant) {
            return array($arg->getAttribute('value'));
        } else {
            return array();
        }
        break;
    }
    return array('html');
}
if (function_exists('iconv')) {
    function _twig_convert_encoding($string, $to, $from)
    {
        return iconv($from, $to, $string);
    }
} elseif (function_exists('mb_convert_encoding')) {
    function _twig_convert_encoding($string, $to, $from)
    {
        return mb_convert_encoding($string, $to, $from);
    }
} else {
    function _twig_convert_encoding($string, $to, $from)
    {
        throw new Twig_Error_Runtime('No suitable convert encoding function (use UTF-8 as your encoding or install the iconv or mbstring extension).');
    }
}
function _twig_escape_js_callback($matches)
{
    $char = $matches[0];
        if (!isset($char[1])) {
        return '\\x'.substr('00'.bin2hex($char), -2);
    }
        $char = _twig_convert_encoding($char, 'UTF-16BE', 'UTF-8');
    return '\\u'.substr('0000'.bin2hex($char), -4);
}
if (function_exists('mb_get_info')) {
    function twig_length_filter(Twig_Environment $env, $thing)
    {
        return is_scalar($thing) ? mb_strlen($thing, $env->getCharset()) : count($thing);
    }
    function twig_upper_filter(Twig_Environment $env, $string)
    {
        if (null !== ($charset = $env->getCharset())) {
            return mb_strtoupper($string, $charset);
        }
        return strtoupper($string);
    }
    function twig_lower_filter(Twig_Environment $env, $string)
    {
        if (null !== ($charset = $env->getCharset())) {
            return mb_strtolower($string, $charset);
        }
        return strtolower($string);
    }
    function twig_title_string_filter(Twig_Environment $env, $string)
    {
        if (null !== ($charset = $env->getCharset())) {
            return mb_convert_case($string, MB_CASE_TITLE, $charset);
        }
        return ucwords(strtolower($string));
    }
    function twig_capitalize_string_filter(Twig_Environment $env, $string)
    {
        if (null !== ($charset = $env->getCharset())) {
            return mb_strtoupper(mb_substr($string, 0, 1, $charset)).
                         mb_strtolower(mb_substr($string, 1, mb_strlen($string), $charset), $charset);
        }
        return ucfirst(strtolower($string));
    }
}
else
{
    function twig_length_filter(Twig_Environment $env, $thing)
    {
        return is_scalar($thing) ? strlen($thing) : count($thing);
    }
    function twig_title_string_filter(Twig_Environment $env, $string)
    {
        return ucwords(strtolower($string));
    }
    function twig_capitalize_string_filter(Twig_Environment $env, $string)
    {
        return ucfirst(strtolower($string));
    }
}
function twig_ensure_traversable($seq)
{
    if (is_array($seq) || (is_object($seq) && $seq instanceof Traversable)) {
        return $seq;
    } else {
        return array();
    }
}
function twig_test_sameas($value, $test)
{
    return $value === $test;
}
function twig_test_none($value)
{
    return null === $value;
}
function twig_test_divisibleby($value, $num)
{
    return 0 == $value % $num;
}
function twig_test_even($value)
{
    return $value % 2 == 0;
}
function twig_test_odd($value)
{
    return $value % 2 == 1;
}
function twig_test_constant($value, $constant)
{
    return constant($constant) === $value;
}
function twig_test_defined($name, $context)
{
    return array_key_exists($name, $context);
}
function twig_test_empty($value)
{
    return null === $value || false === $value || '' === (string) $value;
}
}
namespace
{
class Twig_Extension_Escaper extends Twig_Extension
{
    protected $autoescape;
    public function __construct($autoescape = true)
    {
        $this->autoescape = $autoescape;
    }
    public function getTokenParsers()
    {
        return array(new Twig_TokenParser_AutoEscape());
    }
    public function getNodeVisitors()
    {
        return array(new Twig_NodeVisitor_Escaper());
    }
    public function getFilters()
    {
        return array(
            'raw' => new Twig_Filter_Function('twig_raw_filter', array('is_safe' => array('all'))),
        );
    }
    public function isGlobal()
    {
        return $this->autoescape;
    }
    public function getName()
    {
        return 'escaper';
    }
}
function twig_raw_filter($string)
{
    return $string;
}
}
namespace
{
class Twig_Extension_Optimizer extends Twig_Extension
{
    protected $optimizers;
    public function __construct($optimizers = -1)
    {
        $this->optimizers = $optimizers;
    }
    public function getNodeVisitors()
    {
        return array(new Twig_NodeVisitor_Optimizer($this->optimizers));
    }
    public function getName()
    {
        return 'optimizer';
    }
}
}
namespace
{
interface Twig_LoaderInterface
{
    function getSource($name);
    function getCacheKey($name);
    function isFresh($name, $time);
}
}
namespace
{
class Twig_Markup
{
    protected $content;
    public function __construct($content)
    {
        $this->content = (string) $content;
    }
    public function __toString()
    {
        return $this->content;
    }
}
}
namespace
{
interface Twig_TemplateInterface
{
    const ANY_CALL    = 'any';
    const ARRAY_CALL  = 'array';
    const METHOD_CALL = 'method';
    function render(array $context);
    function display(array $context);
    function getEnvironment();
}
}
namespace
{
abstract class Twig_Template implements Twig_TemplateInterface
{
    static protected $cache = array();
    protected $env;
    protected $blocks;
    public function __construct(Twig_Environment $env)
    {
        $this->env = $env;
        $this->blocks = array();
    }
    public function getTemplateName()
    {
        return null;
    }
    public function getEnvironment()
    {
        return $this->env;
    }
    public function getParent(array $context)
    {
        return false;
    }
    public function displayParentBlock($name, array $context, array $blocks = array())
    {
        if (false !== $parent = $this->getParent($context)) {
            $parent->displayBlock($name, $context, $blocks);
        } else {
            throw new Twig_Error_Runtime('This template has no parent', -1, $this->getTemplateName());
        }
    }
    public function displayBlock($name, array $context, array $blocks = array())
    {
        if (isset($blocks[$name])) {
            $b = $blocks;
            unset($b[$name]);
            call_user_func($blocks[$name], $context, $b);
        } elseif (isset($this->blocks[$name])) {
            call_user_func($this->blocks[$name], $context, $blocks);
        } elseif (false !== $parent = $this->getParent($context)) {
            $parent->displayBlock($name, $context, array_merge($this->blocks, $blocks));
        }
    }
    public function renderParentBlock($name, array $context, array $blocks = array())
    {
        ob_start();
        $this->displayParentBlock($name, $context, $blocks);
        return new Twig_Markup(ob_get_clean());
    }
    public function renderBlock($name, array $context, array $blocks = array())
    {
        ob_start();
        $this->displayBlock($name, $context, $blocks);
        return new Twig_Markup(ob_get_clean());
    }
    public function hasBlock($name)
    {
        return isset($this->blocks[$name]);
    }
    public function getBlockNames()
    {
        return array_keys($this->blocks);
    }
    public function render(array $context)
    {
        ob_start();
        try {
            $this->display($context);
        } catch (Exception $e) {
                                                $count = 100;
            while (ob_get_level() && --$count) {
                ob_end_clean();
            }
            throw $e;
        }
        return ob_get_clean();
    }
    protected function getContext($context, $item, $line = -1)
    {
        if (!array_key_exists($item, $context)) {
            throw new Twig_Error_Runtime(sprintf('Variable "%s" does not exist', $item), $line, $this->getTemplateName());
        }
        return $context[$item];
    }
    protected function getAttribute($object, $item, array $arguments = array(), $type = Twig_TemplateInterface::ANY_CALL, $noStrictCheck = false, $line = -1)
    {
                if (Twig_TemplateInterface::METHOD_CALL !== $type) {
            if ((is_array($object) || is_object($object) && $object instanceof ArrayAccess) && isset($object[$item])) {
                return $object[$item];
            }
            if (Twig_TemplateInterface::ARRAY_CALL === $type) {
                if (!$this->env->isStrictVariables() || $noStrictCheck) {
                    return null;
                }
                if (is_object($object)) {
                    throw new Twig_Error_Runtime(sprintf('Key "%s" in object (with ArrayAccess) of type "%s" does not exist', $item, get_class($object)), $line, $this->getTemplateName());
                                } else {
                    throw new Twig_Error_Runtime(sprintf('Key "%s" for array with keys "%s" does not exist', $item, implode(', ', array_keys($object))), $line, $this->getTemplateName());
                }
            }
        }
        if (!is_object($object)) {
            if (!$this->env->isStrictVariables() || $noStrictCheck) {
                return null;
            }
            throw new Twig_Error_Runtime(sprintf('Item "%s" for "%s" does not exist', $item, $object), $line, $this->getTemplateName());
        }
                $class = get_class($object);
        if (!isset(self::$cache[$class])) {
            $r = new ReflectionClass($class);
            self::$cache[$class] = array('methods' => array(), 'properties' => array());
            foreach ($r->getMethods(ReflectionMethod::IS_PUBLIC) as $method) {
                self::$cache[$class]['methods'][strtolower($method->getName())] = true;
            }
            foreach ($r->getProperties(ReflectionProperty::IS_PUBLIC) as $property) {
                self::$cache[$class]['properties'][$property->getName()] = true;
            }
        }
                if (Twig_TemplateInterface::METHOD_CALL !== $type) {
            if (isset(self::$cache[$class]['properties'][$item]) || isset($object->$item)) {
                if ($this->env->hasExtension('sandbox')) {
                    $this->env->getExtension('sandbox')->checkPropertyAllowed($object, $item);
                }
                return $object->$item;
            }
        }
                $lcItem = strtolower($item);
        if (isset(self::$cache[$class]['methods'][$lcItem])) {
            $method = $item;
        } elseif (isset(self::$cache[$class]['methods']['get'.$lcItem])) {
            $method = 'get'.$item;
        } elseif (isset(self::$cache[$class]['methods']['is'.$lcItem])) {
            $method = 'is'.$item;
        } elseif (isset(self::$cache[$class]['methods']['__call'])) {
            $method = $item;
        } else {
            if (!$this->env->isStrictVariables() || $noStrictCheck) {
                return null;
            }
            throw new Twig_Error_Runtime(sprintf('Method "%s" for object "%s" does not exist', $item, get_class($object)), $line, $this->getTemplateName());
        }
        if ($this->env->hasExtension('sandbox')) {
            $this->env->getExtension('sandbox')->checkMethodAllowed($object, $method);
        }
        $ret = call_user_func_array(array($object, $method), $arguments);
        if ($object instanceof Twig_TemplateInterface) {
            return new Twig_Markup($ret);
        }
        return $ret;
    }
}
}
namespace Zend\Log
{
interface Factory
{
    static public function factory($config = array());
}
}
namespace Zend\Log
{
interface Filter
{
    public function accept($event);
}
}
namespace Zend\Log\Filter
{
use Zend\Log\Factory,
    Zend\Log\Filter;
abstract class AbstractFilter implements Filter, Factory
{
    static protected function _parseConfig($config)
    {
        if ($config instanceof \Zend\Config\Config) {
            $config = $config->toArray();
        }
        if (!is_array($config)) {
            throw new \Zend\Log\Exception\InvalidArgumentException('Configuration must be an array or instance of Zend\Config\Config');
        }
        return $config;
    }
}
}
namespace Zend\Log\Filter
{
class Priority extends AbstractFilter
{
    protected $_priority;
    protected $_operator;
    public function __construct($priority, $operator = null)
    {
        if (! is_integer($priority)) {
            throw new \Zend\Log\Exception\InvalidArgumentException('Priority must be an integer');
        }
        $this->_priority = $priority;
        $this->_operator = $operator === null ? '<=' : $operator;
    }
    static public function factory($config = array())
    {
        $config = self::_parseConfig($config);
        $config = array_merge(array(
            'priority' => null,
            'operator' => null,
        ), $config);
                if (!is_numeric($config['priority']) && isset($config['priority']) && defined($config['priority'])) {
            $config['priority'] = constant($config['priority']);
        }
        if (!is_numeric($config['priority'])) {
        	throw new \Zend\Log\Exception\InvalidArgumentException('Priority must be an integer.');
        }
        return new self(
            (int) $config['priority'],
            $config['operator']
        );
    }
    public function accept($event)
    {
        return version_compare($event['priority'], $this->_priority, $this->_operator);
    }
}
}
namespace Zend\Log
{
interface Formatter
{
    public function format($event);
}
}
namespace Zend\Log\Formatter
{
use \Zend\Log\Formatter;
class Simple implements Formatter
{
    protected $_format;
    const DEFAULT_FORMAT = '%timestamp% %priorityName% (%priority%): %message%';
    public function __construct($format = null)
    {
        if ($format === null) {
            $format = self::DEFAULT_FORMAT . PHP_EOL;
        }
        if (! is_string($format)) {
            throw new \Zend\Log\Exception\InvalidArgumentException('Format must be a string');
        }
        $this->_format = $format;
    }
    public function format($event)
    {
        $output = $this->_format;
        foreach ($event as $name => $value) {
            if ((is_object($value) && !method_exists($value,'__toString'))
                || is_array($value)) {
                $value = gettype($value);
            }
            $output = str_replace("%$name%", $value, $output);
        }
        return $output;
    }
}
}
namespace Zend\Log
{
use Zend\Config\Config;
class Logger implements Factory
{
    const EMERG   = 0;      const ALERT   = 1;      const CRIT    = 2;      const ERR     = 3;      const WARN    = 4;      const NOTICE  = 5;      const INFO    = 6;      const DEBUG   = 7;
    protected $_priorities = array();
    protected $_writers = array();
    protected $_filters = array();
    protected $_extras = array();
    protected $_defaultWriterNamespace = 'Zend\Log\Writer';
    protected $_defaultFilterNamespace = 'Zend\Log\Filter';
    protected $_origErrorHandler       = null;
    protected $_registeredErrorHandler = false;
    protected $_errorHandlerMap        = false;
    protected $_timestampFormat        = 'c';
    public function __construct(Writer $writer = null)
    {
        $r = new \ReflectionClass($this);
        $this->_priorities = array_flip($r->getConstants());
        if ($writer !== null) {
            $this->addWriter($writer);
        }
    }
    static public function factory($config = array())
    {
        if ($config instanceof Config) {
            $config = $config->toArray();
        }
        if (!is_array($config) || empty($config)) {
            throw new Exception\InvalidArgumentException('Configuration must be an array or instance of Zend\\Config\\Config');
        }
        $log = new self;
        if (!is_array(current($config))) {
            $log->addWriter(current($config));
        } else {
            foreach($config as $writer) {
                $log->addWriter($writer);
            }
        }
        return $log;
    }
    protected function _constructWriterFromConfig($config)
    {
        $writer = $this->_constructFromConfig('writer', $config, $this->_defaultWriterNamespace);
        if (!$writer instanceof Writer) {
            $writerName = is_object($writer)
                        ? get_class($writer)
                        : 'The specified writer';
            throw new Exception\InvalidArgumentException("{$writerName} does not extend Zend\\Log\\Writer!");
        }
        if (isset($config['filterName'])) {
            $filter = $this->_constructFilterFromConfig($config);
            $writer->addFilter($filter);
        }
        return $writer;
    }
    protected function _constructFilterFromConfig($config)
    {
        $filter = $this->_constructFromConfig('filter', $config, $this->_defaultFilterNamespace);
        if (!$filter instanceof Filter) {
             $filterName = is_object($filter)
                         ? get_class($filter)
                         : 'The specified filter';
            throw new Exception\InvalidArgumentException("{$filterName} does not implement Zend\\Log\\Filter");
        }
        return $filter;
    }
    protected function _constructFromConfig($type, $config, $namespace)
    {
        if ($config instanceof Config) {
            $config = $config->toArray();
        }
        if (!is_array($config) || empty($config)) {
            throw new Exception\InvalidArgumentException(
                'Configuration must be an array or instance of Zend\Config\Config'
            );
        }
        $params    = isset($config[ $type .'Params' ]) ? $config[ $type .'Params' ] : array();
        $className = $this->getClassName($config, $type, $namespace);
        if (!class_exists($className)) {
            \Zend\Loader::loadClass($className);
        }
        $reflection = new \ReflectionClass($className);
        if (!$reflection->implementsInterface('Zend\Log\Factory')) {
            throw new Exception\InvalidArgumentException(
                'Driver does not implement Zend\Log\Factory and can not be constructed from config.'
            );
        }
        return $className::factory($params);
    }
    protected function getClassName($config, $type, $defaultNamespace)
    {
        if (!isset($config[ $type . 'Name' ])) {
            throw new Exception\InvalidArgumentException("Specify {$type}Name in the configuration array");
        }
        $className = $config[ $type . 'Name' ];
        $namespace = $defaultNamespace;
        if (isset($config[ $type . 'Namespace' ])) {
            $namespace = $config[ $type . 'Namespace' ];
        }
        $fullClassName = $namespace . '\\' . $className;
        return $fullClassName;
    }
    protected function _packEvent($message, $priority)
    {
        return array_merge(array(
            'timestamp'    => date($this->_timestampFormat),
            'message'      => $message,
            'priority'     => $priority,
            'priorityName' => $this->_priorities[$priority]
            ),
            $this->_extras
        );
    }
    public function __destruct()
    {
        foreach($this->_writers as $writer) {
            $writer->shutdown();
        }
    }
    public function __call($method, $params)
    {
        $priority = strtoupper($method);
        if (($priority = array_search($priority, $this->_priorities)) !== false) {
            switch (count($params)) {
                case 0:
                    throw new Exception\InvalidArgumentException('Missing log message');
                case 1:
                    $message = array_shift($params);
                    $extras = null;
                    break;
                default:
                    $message = array_shift($params);
                    $extras  = array_shift($params);
                    break;
            }
            $this->log($message, $priority, $extras);
        } else {
            throw new Exception\InvalidArgumentException('Bad log priority');
        }
    }
    public function log($message, $priority, $extras = null)
    {
                if (empty($this->_writers)) {
            throw new Exception\RuntimeException('No writers were added');
        }
        if (! isset($this->_priorities[$priority])) {
            throw new Exception\InvalidArgumentException('Bad log priority');
        }
                $event = $this->_packEvent($message, $priority);
                if (!empty($extras)) {
            $info = array();
            if (is_array($extras)) {
                foreach ($extras as $key => $value) {
                    if (is_string($key)) {
                        $event[$key] = $value;
                    } else {
                        $info[] = $value;
                    }
                }
            } else {
                $info = $extras;
            }
            if (!empty($info)) {
                $event['info'] = $info;
            }
        }
                foreach ($this->_filters as $filter) {
            if (! $filter->accept($event)) {
                return;
            }
        }
                foreach ($this->_writers as $writer) {
            $writer->write($event);
        }
    }
    public function addPriority($name, $priority)
    {
                $name = strtoupper($name);
        if (isset($this->_priorities[$priority])
            || false !== array_search($name, $this->_priorities)) {
            throw new Exception\InvalidArgumentException('Existing priorities cannot be overwritten');
        }
        $this->_priorities[$priority] = $name;
        return $this;
    }
    public function addFilter($filter)
    {
        if (is_integer($filter)) {
            $filter = new Filter\Priority($filter);
        } elseif ($filter instanceof Config || is_array($filter)) {
            $filter = $this->_constructFilterFromConfig($filter);
        } elseif(! $filter instanceof Filter) {
            throw new Exception\InvalidArgumentException('Invalid filter provided');
        }
        $this->_filters[] = $filter;
        return $this;
    }
    public function addWriter($writer)
    {
        if (is_array($writer) || $writer instanceof Config) {
            $writer = $this->_constructWriterFromConfig($writer);
        }
        if (!$writer instanceof Writer) {
            throw new Exception\InvalidArgumentException(
                'Writer must be an instance of Zend\Log\Writer'
                . ' or you should pass a configuration array'
            );
        }
        $this->_writers[] = $writer;
        return $this;
    }
    public function setEventItem($name, $value)
    {
        $this->_extras = array_merge($this->_extras, array($name => $value));
        return $this;
    }
    public function registerErrorHandler()
    {
                if ($this->_registeredErrorHandler) {
        	return $this;
        }
        $this->_origErrorHandler = set_error_handler(array($this, 'errorHandler'));
                        $this->_errorHandlerMap = array(
            E_NOTICE            => self::NOTICE,
            E_USER_NOTICE       => self::NOTICE,
            E_WARNING           => self::WARN,
            E_CORE_WARNING      => self::WARN,
            E_USER_WARNING      => self::WARN,
            E_ERROR             => self::ERR,
            E_USER_ERROR        => self::ERR,
            E_CORE_ERROR        => self::ERR,
            E_RECOVERABLE_ERROR => self::ERR,
            E_STRICT            => self::DEBUG,
        );
        $this->_errorHandlerMap['E_DEPRECATED'] = self::DEBUG;
        $this->_errorHandlerMap['E_USER_DEPRECATED'] = self::DEBUG;
        $this->_registeredErrorHandler = true;
        return $this;
    }
    public function errorHandler($errno, $errstr, $errfile, $errline, $errcontext)
    {
        $errorLevel = error_reporting();
        if ($errorLevel && $errno) {
            if (isset($this->_errorHandlerMap[$errno])) {
                $priority = $this->_errorHandlerMap[$errno];
            } else {
                $priority = self::INFO;
            }
            $this->log($errstr, $priority, array('errno'=>$errno, 'file'=>$errfile, 'line'=>$errline, 'context'=>$errcontext));
        }
        if ($this->_origErrorHandler !== null) {
            return call_user_func($this->_origErrorHandler, $errno, $errstr, $errfile, $errline, $errcontext);
        }
        return false;
    }
    public function setTimestampFormat($format)
    {
        $this->_timestampFormat = $format;
        return $this;
    }
    public function getTimestampFormat()
    {
        return $this->_timestampFormat;
    }
}
}
namespace Zend\Log
{
interface Writer
{
    public function addFilter($filter);
    public function setFormatter(Formatter $formatter);
    public function write($event);
    public function shutdown();
}
}
namespace Zend\Log\Writer
{
use \Zend\Log\Factory,
    \Zend\Log\Writer;
abstract class AbstractWriter implements Writer, Factory
{
    protected $_filters = array();
    protected $_formatter;
    public function addFilter($filter)
    {
        if (is_integer($filter)) {
            $filter = new \Zend\Log\Filter\Priority($filter);
        }
        if (!$filter instanceof \Zend\Log\Filter) {
            throw new \Zend\Log\Exception\InvalidArgumentException('Invalid filter provided');
        }
        $this->_filters[] = $filter;
        return $this;
    }
    public function write($event)
    {
        foreach ($this->_filters as $filter) {
            if (! $filter->accept($event)) {
                return;
            }
        }
                $this->_write($event);
    }
    public function setFormatter(\Zend\Log\Formatter $formatter)
    {
        $this->_formatter = $formatter;
        return $this;
    }
    public function shutdown()
    {}
    abstract protected function _write($event);
    static protected function _parseConfig($config)
    {
        if ($config instanceof \Zend\Config\Config) {
            $config = $config->toArray();
        }
        if (!is_array($config)) {
            throw new \Zend\Log\Exception\InvalidArgumentException(
				'Configuration must be an array or instance of Zend\Config\Config'
			);
        }
        return $config;
    }
}
}
namespace Zend\Log\Writer
{
use Zend\Log;
class Stream extends AbstractWriter
{
    protected $_stream = null;
    public function __construct($streamOrUrl, $mode = \NULL)
    {
                if ($mode === NULL) {
            $mode = 'a';
        }
        if (is_resource($streamOrUrl)) {
            if (get_resource_type($streamOrUrl) != 'stream') {
                throw new Log\Exception\InvalidArgumentException('Resource is not a stream');
            }
            if ($mode != 'a') {
                throw new Log\Exception\InvalidArgumentException('Mode cannot be changed on existing streams');
            }
            $this->_stream = $streamOrUrl;
        } else {
            if (is_array($streamOrUrl) && isset($streamOrUrl['stream'])) {
                $streamOrUrl = $streamOrUrl['stream'];
            }
            if (! $this->_stream = @fopen($streamOrUrl, $mode, false)) {
                $msg = "\"$streamOrUrl\" cannot be opened with mode \"$mode\"";
                throw new Log\Exception\RuntimeException($msg);
            }
        }
        $this->_formatter = new Log\Formatter\Simple();
    }
    public static function factory($config = array())
    {
        $config = self::_parseConfig($config);
        $config = array_merge(array(
            'stream' => null,
            'mode'   => null,
        ), $config);
        $streamOrUrl = isset($config['url']) ? $config['url'] : $config['stream'];
        return new self(
            $streamOrUrl,
            $config['mode']
        );
    }
    public function shutdown()
    {
        if (is_resource($this->_stream)) {
            fclose($this->_stream);
        }
    }
    protected function _write($event)
    {
        $line = $this->_formatter->format($event);
        if (false === @fwrite($this->_stream, $line)) {
            throw new Log\Exception\RuntimeException("Unable to write to stream");
        }
    }
}
}
namespace Symfony\Bundle\ZendBundle\Logger
{
use Zend\Log\Writer\AbstractWriter;
use Symfony\Component\HttpKernel\Log\DebugLoggerInterface;
class DebugLogger extends AbstractWriter implements DebugLoggerInterface
{
    protected $logs = array();
    public function getLogs()
    {
        return $this->logs;
    }
    public function countErrors()
    {
        $count = 0;
        foreach ($this->getLogs() as $log) {
            if ('ERR' === $log['priorityName']) {
                ++$count;
            }
        }
        return $count;
    }
    protected function _write($event)
    {
        $this->logs[] = $event;
    }
    static public function factory($config = array())
    {
    }
}
}
namespace Symfony\Bundle\ZendBundle\Logger
{
use Zend\Log\Logger as BaseLogger;
use Symfony\Component\HttpKernel\Log\LoggerInterface;
use Symfony\Component\HttpKernel\Log\DebugLoggerInterface;
class Logger extends BaseLogger implements LoggerInterface
{
    public function getDebugLogger()
    {
        foreach ($this->_writers as $writer) {
            if ($writer instanceof DebugLoggerInterface) {
                return $writer;
            }
        }
        return null;
    }
    public function emerg($message)
    {
        return parent::log($message, 0);
    }
    public function alert($message)
    {
        return parent::log($message, 1);
    }
    public function crit($message)
    {
        return parent::log($message, 2);
    }
    public function err($message)
    {
        return parent::log($message, 3);
    }
    public function warn($message)
    {
        return parent::log($message, 4);
    }
    public function notice($message)
    {
        return parent::log($message, 5);
    }
    public function info($message)
    {
        return parent::log($message, 6);
    }
    public function debug($message)
    {
        return parent::log($message, 7);
    }
}
}
