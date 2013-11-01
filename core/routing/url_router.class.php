<?php
/**
 * This class is responsible for the url routing of the application.
 *
 * This file is part of the Lalaz Framework
 * Lalaz it's a open source MVA (Model View Action) Framework writen in PHP
 *
 * @author Gregory Peres Serrão (gregory@wmdweb.com.br)
 * @copyright Copyright 2010, WMD (http://www.wmdweb.com.br)
 */
class UrlRouter extends Singleton {

    private $prefixes = array();
    private $routes = array();
    private $here = null;
    private $base = null;
    private $root = array();

    public function __construct() {
        if(is_null($this->base)) {
            $this->base = dirname($_SERVER["PHP_SELF"]);

            while(in_array(basename($this->base), array("app", "public"))) {
                $this->base = dirname($this->base);
            }

            if($this->base == DIRECTORY_SEPARATOR || $this->base == ".") {
                $this->base = "/";
            }
        }

        if(is_null($this->here)) {
            $start = strlen($this->base);
            $this->here = self::normalize(substr($_SERVER["REQUEST_URI"], $start));
        }
    }

    public static function here() {
        $self = self::getInstance("UrlRouter");
        return $self->here;
    }

    public static function base() {
        $self = self::getInstance("UrlRouter");
        return $self->base;
    }

    public static function normalize($url) {
        if(preg_match("/^[a-z]+:/", $url)) {
            return $url;
        }

        $url = "/" . $url;

        while(strpos($url, "//") !== false) {
            $url = str_replace("//", "/", $url);
        }

        $url = rtrim($url, "/");

        if(empty($url)) {
            $url = "/";
        }

        return $url;
    }

    public static function root($directory, $actionName, $prefix = null, $default = false) {
        $self = self::getInstance("UrlRouter");
        $self->root[] = array(
            "prefix" => $prefix,
            "directory" => $directory,
            "actionName" => $actionName,
            "default" => $default);

        return true;
    }

    public static function getRoot($prefix = null) {
        $self = self::getInstance("UrlRouter");
        $arr = $self->root;

        foreach ($arr as $key => $value) {
            if (!is_null($prefix)) {
                if ($value["prefix"] == $prefix) {
                    return $value;
                }
            } else {
                if ($value["default"]) {
                    return $value;
                }
            }
        }

        return false;
    }

    public static function prefix($prefix) {
        $self = self::getInstance("UrlRouter");

        if(is_array($prefix)) {
            $prefixes = $prefix;
        } else {
            $prefixes = func_get_args();
        }

        foreach($prefixes as $prefix) {
            $self->prefixes []= $prefix;
        }

        return true;
    }

    public static function unsetPrefix($prefix) {
        $self = self::getInstance("UrlRouter");
        unset($self->prefixes[$prefix]);
        return true;
    }

    public static function getPrefixes() {
        $self = self::getInstance("UrlRouter");
        return $self->prefixes;
    }

    public static function connect($url = null, $route = null) {
        if(is_array($url)) {
            foreach($url as $key => $value) {
                self::connect($key, $value);
            }
        } else if(!is_null($url)) {
            $self = self::getInstance("UrlRouter");
            $url = self::normalize($url);
            $self->routes[$url] = rtrim($route, "/");
        }

        return true;
    }

    public static function disconnect($url) {
        $self = self::getInstance("UrlRouter");
        $url = rtrim($url, "/");
        unset($self->routes[$url]);
        return true;
    }

    public static function match($check, $url = null) {
        if(is_null($url)) {
            $url = self::here();
        }

        $check = "%^" . str_replace(array(":any", ":fragment", ":num"), array("(.+)", "([^\/]+)", "([0-9]+)"), $check) . "/?$%";
        return preg_match($check, $url);
    }

    public static function getRoute($url) {
        $self = self::getInstance("UrlRouter");

        foreach($self->routes as $map => $route) {
            if(self::match($map, $url)) {
                $map = "%^" . str_replace(array(":any", ":fragment", ":num"), array("(.+)", "([^\/]+)", "([0-9]+)"), $map) . "/?$%";
                $url = preg_replace($map, $route, $url);
                break;
            }
        }

        return self::normalize($url);
    }

    public static function parse($url = null) {
        $here = self::normalize(is_null($url) ? self::here() : $url);
        $url = self::getRoute($here);
        $prefixes = join("|", self::getPrefixes());

        $path = array();
        $parts = array("url", "prefix", "directory", "action", "id", "extension", "params", "queryString");

        preg_match("/^\/(?:({$prefixes})(?:\/|(?!\w)))?(?:([a-z_-]*)\/?)?(?:([a-z_-]*)\/?)?(?:(\d*))?(?:\.([\w]+))?(?:\/?([^?]+))?(?:\?(.*))?/i", $url, $reg);

        foreach($parts as $k => $key) {
            $path[$key] = isset($reg[$k]) ? $reg[$k] : null;
        }

        $path["named"] = $path["params"] = array();

        if(isset($reg[6])) {
            foreach(explode("/", $reg[6]) as $param) {
                if(preg_match("/([^:]*):([^:]*)/", $param, $reg)) {
                    $path["named"][$reg[1]] = urldecode($reg[2]);
                } else if($param != "") {
                    $path["params"] []= urldecode($param);
                }
            }
        }

        $path["url"] = $here;

        if(empty($path["directory"]) && empty($path["action"])) {
            if (empty($path["prefix"])) {
                $root = self::getRoot();
                $path["prefix"] = $root["prefix"];
            } else {
                $root = self::getRoot($path["prefix"]);
            }

            $path["directory"] = $root["directory"];
            $path["action"] = $root["actionName"];
        }

        if(($filtered = self::filterAction($path["action"]))) {
            $path["prefix"] = $filtered["prefix"];
            $path["action"] = $filtered["action"];
        }

        if(!empty($path["prefix"])) {
            $path["action"] = $path['action'];
        }

        if(empty($path["id"])) {
            $path["id"] = null;
        }

        if(!empty($path["queryString"])) {
            parse_str($path["queryString"], $queryString);
            $path["named"] = array_merge($path["named"], $queryString);
        }

        return $path;
    }

    public static function url($path, $root = false) {
        if(is_array($path)) {
            $here = self::parse();
            $params = $here["named"];

            $path = array_merge(array(
                "prefix" => $here["prefix"],
                "directory" => $here["directory"],
                "action" => $here["action"],
                "id" => $here["id"]
            ), $params, $path);

            $nonParams = array("prefix", "directory", "action", "id");
            $url = "";

            foreach($path as $key => $value) {
                if(!in_array($key, $nonParams)) {
                    $url .= "/" . "{$key}:{$value}";
                } else if(!is_null($value)) {
                    if($key == "action" && $filtered = self::filterAction($value)) {
                        $value = $filtered["action"];
                    }

                    $url .= "/" . $value;
                }
            }

            $url = self::normalize(self::base() . $url);
        } else {
            if(preg_match("/^[a-z]+:/", $path)) {
                return $path;
            } else if(substr($path, 0, 1) == "/") {
                $url = self::base() . $path;
            } elseif(substr($path, 0, 1) != "#") {
                $url = self::base() . self::here() . $path;
            } else {
                $url = self::base() . self::here() . "/" . $path;
            }

            $url = self::normalize($url);
        }

        return $root ? $url : $url;
    }

    public static function filterAction($action) {
        if(strpos($action, "_") !== false) {
            foreach(self::getPrefixes() as $prefix) {
                if(strpos($action, $prefix) === 0) {
                    return array(
                        "action" => substr($action, strlen($prefix) + 1),
                        "prefix" => $prefix
                    );
                }
            }
        }

        return false;
    }

    public static function redirectToAction($directory, $actionName, $prefix = null, $params = array()) {
        $url = self::action($directory, $actionName, $prefix, $params);
        header("Location: $url");
    }

    public static function action($directory, $actionName, $prefix = null, $params = array()) {
        $url = is_null($prefix) ? "/$directory/$actionName" : "/$prefix/$directory/$actionName";

        if (is_array($params)) {
            foreach ($params as $value) {
                $url .= "/$value";
            }
        }

        $url = self::url($url);
        return $url;
    }
}
?>