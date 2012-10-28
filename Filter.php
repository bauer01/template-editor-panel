<?php

namespace TemplateEditor;

use Nette\Utils\Finder;

/**
 * @todo add caching
 */
class Filter
{

    const HIGHLIGHTER = '<span ßßstyleßß="%s">%s</span>';

    /** @var array */
    private $filters;

    /** @var string */
    private $filterDir;

    public function __construct()
    {
        $this->filterDir = __DIR__ . '/filters';
        $this->filters = $this->loadFilters();
    }

    /**
     * Load filters
     * @return array
     */
    public function loadFilters()
    {
        $filters = array();
        foreach (Finder::findFiles('*.php')->in($this->filterDir) as $file) {
            require_once $file->getRealPath();
        }

        return $filters;
    }

    /**
     * Run filters
     * @param string $code
     * @return string
     */
    public function applyFilters($code)
    {
        $code = htmlspecialchars($code);

        $filters = $this->filters;
        foreach ($filters as $filter) {
            $code = $this->highlight($code, $filter);
        }

        $code = str_replace('ßßstyleßß', 'style', $code);

        return $code;
    }

    /**
     * Highlight code
     * @param string $code
     * @param array $filter
     * @return string
     */
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

            foreach ($matches[0] as $match) {

                if (isset($style["MACROS"][$macroKey])) {
                    $code = $this->styleMacro($code, $match, $macro, $style, $macroKey);
                }
            }
        }

        return $code;
    }

    /**
     * Colorize macro properties
     *
     * @param string $code
     * @param array  $properties
     * @param string $style
     *
     * @return string
     */
    private function styleMacroProperties($code, $properties, $style)
    {
        if (!empty($style)) {

            foreach ($properties as $property) {

                $replace = sprintf(self::HIGHLIGHTER, $style, $property);
                $code = str_replace(" $property", " $replace", $code);
            }
        }

        return $code;
    }

    /**
     * Colorize macro quotemarks
     *
     * @param string $code
     * @param array  $quotemarks
     * @param string $style
     *
     * @return string
     */
    private function styleMacroQuotemarks($code, $quotemarks, $style)
    {
        if (!empty($style)) {

            foreach ($quotemarks as $quotemark) {

                $quotemark = htmlspecialchars($quotemark);
                $key = "\\$quotemark";
                $expression = "/$key.*?$key/";
                preg_match_all($expression, $code, $matches);

                foreach ($matches[0] as $match) {

                    $quotemarkContent = substr($match, strlen($quotemark), strlen($match) - 2 * strlen($quotemark));
                    $replace = sprintf(self::HIGHLIGHTER, $style, $quotemarkContent);

                    $quotemark_styled = $quotemark . substr_replace($match, $replace, 0, strlen($match)) . $quotemark;
                    $code = str_replace($match, $quotemark_styled, $code);
                }
            }
        }

        return $code;
    }

    /**
     * Colorize macro variables
     *
     * @param string $code
     * @param array  $variables
     * @param string $style
     *
     * @return string
     */
    private function styleMacroVariables($code, $variables, $style)
    {
        if (!empty($style)) {

            foreach ($variables as $varKey => $variable) {

                $start = htmlspecialchars($variable["START"]);
                $expression = "/\\$start.*?" . $variable['END'] . "/";
                preg_match_all($expression, $code, $matches);

                foreach ($matches[0] as $match) {
                    $end = substr($match, -1);
                    $match = substr($match, 0, strlen($match) - 1);
                    $styled = sprintf(self::HIGHLIGHTER, $style[$varKey], $match);
                    $code = str_replace($match . $end, $styled . $end, $code);
                }
            }
        }

        return $code;
    }

    /**
     * Colorize macro
     *
     * @param string $code
     * @param string $tag
     * @param array  $macro
     * @param string $style
     * @param string $macroKey
     *
     * @return string
     */
    private function styleMacro($code, $tag, $macro, $style, $macroKey)
    {
        $start = htmlspecialchars($macro["START"]);
        $end = htmlspecialchars($macro["END"]);

        if (isset($macro["STRICT"]) && $macro["STRICT"] === true) {

            $replace = sprintf(self::HIGHLIGHTER, $style["MACROS"][$macroKey], $tag);
            $macro_styled = substr_replace($tag, $replace, 0, strlen($tag));
        } else {

            foreach ($macro["KEYWORDS"] as $keyword) {

                $defined = strtolower($start . "$keyword ");
                $first = substr($tag, 0, strlen($defined));
                if ($defined === strtolower($first)) {
                    $start = rtrim($first);
                } else {

                    $defined = strtolower($keyword . $end);
                    $last = substr($tag, 0 - strlen($defined), strlen($defined));
                    if ($defined === strtolower($last)) {
                        $end = $last;
                    }
                }
            }


            $replace = sprintf(self::HIGHLIGHTER, $style["MACROS"][$macroKey], $start);
            $macro_styled = substr_replace($tag, $replace, 0, strlen($start));

            $replace = sprintf(self::HIGHLIGHTER, $style["MACROS"][$macroKey], $end);
            $macro_styled = substr_replace($macro_styled, $replace, strlen($macro_styled) - strlen($end), strlen($end));

            if (isset($style["PROPERTIES"])) {
                $macro_styled = $this->styleMacroProperties($macro_styled, $macro["PROPERTIES"], $style["PROPERTIES"]);
            }

            if (isset($style["VARIABLES"])) {
                $macro_styled = $this->styleMacroVariables($macro_styled, $macro["VARIABLES"], $style["VARIABLES"]);
            }

            if (isset($style["QUOTEMARKS"])) {
                $macro_styled = $this->styleMacroQuotemarks($macro_styled, $macro["QUOTEMARKS"], $style["QUOTEMARKS"]);
            }
        }

        if (isset($macro_styled)) {
            return str_replace($tag, $macro_styled, $code);
        }

        return $code;
    }

}