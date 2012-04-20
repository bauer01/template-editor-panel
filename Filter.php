<?php

namespace TemplateEditor;

use Nette\Utils\Finder;

/**
 * TODO add caching
 */
class Filter
{
        const HIGHLIGHTER = '<span style="%s">%s</span>';

        /** @var array */
        private $filters;

        /** @var string */
        private $filterDir;


        public function __construct()
        {
                $this->filterDir = __DIR__ . '/filters';
                $this->filters = $this->loadFilters();
        }


        public function loadFilters()
        {
                $filters = array();
                foreach (Finder::findFiles('*.php')->in($this->filterDir) as $file) {
                        require_once $file->getRealPath();
                }

                return $filters;
        }


        public function applyFilters($code)
        {
                $code = htmlspecialchars($code);

                $filters = $this->filters;
                foreach ($filters as $filter) {
                            $code = $this->highlight($code, $filter);
                }

                return $code;
        }


        private function highlight($code, $filter)
        {
                $style = $filter["STYLE"];

                foreach ($filter["MACROS"] as $macroKey => $macro) {

                        $start = htmlspecialchars($macro["START"]);
                        $end = htmlspecialchars($macro["END"]);

                        $keys = "";
                        foreach ($macro["KEYWORDS"] as $key) {
                                $keys .= "\{$key}|";
                        }

                        $expression = "/$start($keys).*?$end/";
                        preg_match_all($expression, $code, $matches);

                        foreach ($matches[0] as $tag) {

                                if (isset($style["MACROS"][$macroKey])) {
                                        $code = $this->styleMacro($code, $tag, $macro, $style["MACROS"][$macroKey]);
                                }
                                if (isset($style["PROPERTIES"])) {
                                        $code = $this->styleProperties($code, $tag, $macro, $style["PROPERTIES"]);
                                }
                                if (isset($style["QUOTEMARKS"])) {
                                        $code = $this->styleQuotemarks($code, $tag, $macro, $style["QUOTEMARKS"]);
                                }
                        }
                }

                return $code;
        }


        private function styleProperties($code, $tag, $macro, $style)
        {
                if ($macro["STRICT"] === false) {

                        if (!empty($style)) {

                                $properties = $macro["PROPERTIES"];
                                foreach ($properties as $property) {

                                        $property = htmlspecialchars($property);

                                        $key = "\\$property";
                                        $expression = "/[^\>] $property/";
                                        preg_match_all($expression, $tag, $matches);


                                        foreach ($matches[0] as $match) {

                                                $content = substr($match, 1);
                                                $replace = sprintf(self::HIGHLIGHTER, $style, $content);
                                                $styled = substr_replace($match, $replace, 1);
                                                $code = str_replace($match, $styled, $code);
                                        }
                                }
                        }
                }

                return $code;
        }


        private function styleQuotemarks($code, $tag, $macro, $style)
        {
                if ($macro["STRICT"] === false) {

                        if (!empty($style)) {

                                $quotemarks = $macro["QUOTEMARKS"];
                                foreach ($quotemarks as $quotemark) {

                                        $quotemark = htmlspecialchars($quotemark);

                                        $key = "\\$quotemark";
                                        $expression = "/$key.*?$key/";
                                        preg_match_all($expression, $tag, $matches);

                                        foreach ($matches[0] as $match) {

                                                $quotemarkContent = substr($match, strlen($quotemark), strlen($match) - 2 * strlen($quotemark));
                                                $replace = sprintf(self::HIGHLIGHTER, $style, $quotemarkContent);

                                                $quotemark_styled = $quotemark . substr_replace($match, $replace, 0, strlen($match)) . $quotemark;
                                                $code = str_replace($match, $quotemark_styled, $code);
                                        }

                                }
                        }

                }

                return $code;
        }


        private function styleMacro($code, $tag, $macro, $style)
        {
                $start = htmlspecialchars($macro["START"]);
                $end = htmlspecialchars($macro["END"]);

                if (isset($macro["STRICT"]) && $macro["STRICT"] === true) {

                        $replace = sprintf(self::HIGHLIGHTER, $style, $tag);
                        $macro_styled = substr_replace($tag, $replace, 0, strlen($tag));
                } else {

                        foreach ($macro["KEYWORDS"] as $keyword) {

                                $defined = strtolower($start."$keyword ");
                                $first = substr($tag, 0, strlen($defined));
                                if ($defined === strtolower($first)) {
                                        $start = $first;
                                } else {

                                        $defined = strtolower($keyword.$end);
                                        $last = substr($tag, 0 - strlen($defined), strlen($defined));
                                        if ($defined === strtolower($last)) {
                                                $end = $last;
                                        }
                                }

                        }

                        $replace = sprintf(self::HIGHLIGHTER, $style, $start);
                        $macro_styled = substr_replace($tag, $replace, 0, strlen($start));

                        $replace = sprintf(self::HIGHLIGHTER, $style, $end);
                        $macro_styled = substr_replace($macro_styled, $replace, strlen($macro_styled) - strlen($end), strlen($end));
                }

                if (isset($macro_styled)) {
                        return str_replace($tag, $macro_styled, $code);
                }
                return $code;
        }
}