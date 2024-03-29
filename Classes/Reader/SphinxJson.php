<?php
/*
 * This file is part of the TYPO3 CMS project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with TYPO3 source code.
 *
 * The TYPO3 project - inspiring people to share!
 */

namespace Causal\Restdoc\Reader;

use Causal\Restdoc\Utility\RestHelper;
use TYPO3\CMS\Core\Core\Environment;
use TYPO3\CMS\Core\Resource\ResourceStorage;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Sphinx JSON reader.
 *
 * @category    Reader
 * @author      Xavier Perseguers <xavier@causal.ch>
 * @copyright   Causal Sàrl
 * @license     http://www.gnu.org/copyleft/gpl.html
 */
class SphinxJson
{

    /**
     * @var ResourceStorage
     */
    protected $storage;

    /**
     * @var string
     */
    protected $path;

    /**
     * @var string
     */
    protected $document;

    /**
     * @var string
     */
    protected $jsonFilename;

    /**
     * @var bool
     */
    protected $keepPermanentLinks = false;

    /**
     * @var bool
     */
    protected $fallbackToDefaultFile = false;

    /**
     * @var string
     */
    protected $defaultFile = 'index';

    /**
     * @var array
     */
    protected $data = [];

    /**
     * Sets the storage.
     *
     * @param ResourceStorage $storage
     * @return SphinxJson
     */
    public function setStorage(ResourceStorage $storage): SphinxJson
    {
        $this->storage = $storage;
        return $this;
    }

    /**
     * Returns the storage.
     *
     * @return ResourceStorage
     */
    public function getStorage(): ResourceStorage
    {
        return $this->storage;
    }

    /**
     * Sets the root path to the documentation.
     *
     * @param string $path
     * @return SphinxJson
     */
    public function setPath(string $path): SphinxJson
    {
        $this->path = rtrim($path, '/') . '/';
        return $this;
    }

    /**
     * Returns the root path to the documentation.
     *
     * @return string
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * Sets the current document.
     * Format is expected to be URI segments such as Path/To/Chapter/
     *
     * @param string $document
     * @return SphinxJson
     */
    public function setDocument(string $document): SphinxJson
    {
        $this->document = $document;
        return $this;
    }

    /**
     * Returns the current document.
     *
     * @return string
     */
    public function getDocument(): string
    {
        return $this->document;
    }

    /**
     * Returns the JSON file name relative to $this->path.
     *
     * @return string
     */
    public function getJsonFilename(): string
    {
        return $this->jsonFilename;
    }

    /**
     * Sets whether permanent links to sections in BODY should be kept.
     *
     * @param bool $active
     * @return SphinxJson
     */
    public function setKeepPermanentLinks(bool $active): SphinxJson
    {
        $this->keepPermanentLinks = $active;
        return $this;
    }

    /**
     * Returns whether permanent links to sections in BODY should be kept.
     *
     * @return bool
     */
    public function getKeepPermanentLinks(): bool
    {
        return $this->keepPermanentLinks;
    }

    /**
     * Silently falls back to default document instead of throwing an
     * error if an invalid document is specified.
     *
     * @return SphinxJson
     */
    public function enableDefaultDocumentFallback(): SphinxJson
    {
        $this->fallbackToDefaultFile = true;
        return $this;
    }

    /**
     * Sets the default file (e.g., 'index').
     *
     * @param string $defaultFile
     * @return SphinxJson
     */
    public function setDefaultFile(string $defaultFile): SphinxJson
    {
        $this->defaultFile = $defaultFile;
        return $this;
    }

    /**
     * Returns the default file.
     *
     * @return string
     */
    public function getDefaultFile(): string
    {
        return $this->defaultFile;
    }

    /**
     * Loads the current document.
     *
     * @return bool true if operation succeeded, otherwise false
     * @throws \RuntimeException
     */
    public function load(): bool
    {
        if (empty($this->path) || !is_dir($this->path)) {
            throw new \RuntimeException('Invalid path: ' . $this->path, 1365165151);
        }
        if (empty($this->document) || substr($this->document, -1) !== '/') {
            throw new \RuntimeException('Invalid document: ' . $this->document, 1365165369);
        }

        $this->jsonFilename = substr($this->document, 0, -1) . '.fjson';
        $filename = $this->path . $this->jsonFilename;

        if (!is_file($filename)) {
            $this->jsonFilename = $this->document . $this->getDefaultFile() . '.fjson';
            $filename = $this->path . $this->jsonFilename;
        }

        // Security check
        $fileExists = is_file($filename);
        if ($fileExists && strpos(realpath($filename), realpath($this->path)) !== 0) {
            $fileExists = false;
        }
        if (!$fileExists && $this->fallbackToDefaultFile) {
            $defaultDocument = $this->getDefaultFile() . '/';
            $defaultJsonFilename = substr($defaultDocument, 0, -1) . '.fjson';
            $defaultFilename = $this->path . $defaultJsonFilename;
            if (is_file($defaultFilename)) {
                $this->document = $defaultDocument;
                $this->jsonFilename = $defaultJsonFilename;
                $filename = $defaultFilename;
                $fileExists = true;
            } else {
                throw new \RuntimeException(
                    'restdoc: file "' . $this->jsonFilename . '" not found.' .
                    ' Please check static TypoScript and configuration.',
                    1398071985
                );
            }
        }
        if (!$fileExists) {
            throw new \RuntimeException('File not found: ' . $this->jsonFilename, 1365165515);
        }

        $content = file_get_contents($filename);
        $this->data = json_decode($content, true);
        $this->data['last_modification'] = filemtime($filename);

        return $this->data !== null;
    }

    /**
     * Enforces that current document is loaded.
     *
     * @throws \RuntimeException
     */
    protected function enforceIsLoaded(): void
    {
        if (empty($this->data)) {
            throw new \RuntimeException('Document is not loaded: ' . $this->document, 1365170112);
        }
    }

    /**
     * Returns the BODY of the documentation.
     *
     * @param callback $callbackLinks Callback to generate Links in current context
     * @param callback $callbackImages function to process images in current context
     * @param callback $callbackTags function to process some well-known tags in current context
     * @return string
     * @throws \RuntimeException
     */
    public function getBody($callbackLinks, $callbackImages, $callbackTags): string
    {
        $this->enforceIsLoaded();
        $callableName = '';
        if (!is_callable($callbackLinks, false, $callableName)) {
            throw new \RuntimeException('Invalid callback for links: ' . $callableName, 1365172111);
        }
        if (!is_callable($callbackImages, false, $callableName)) {
            throw new \RuntimeException('Invalid callback for images: ' . $callableName, 1365630854);
        }
        if (!is_callable($callbackTags, false, $callableName)) {
            throw new \RuntimeException('Invalid callback for tags: ' . $callableName, 1616417936);
        }

        $body = $this->data['body'];
        if (!$this->keepPermanentLinks) {
            // Remove permanent links in body
            $body = preg_replace('#<a class="headerlink" [^>]+>[^<]+</a>#', '', $body);
        }

        // Replace links in body
        $body = $this->replaceLinks($body, $callbackLinks);

        // Replace images in body
        $body = $this->replaceImages($body, $callbackImages);

        // Possibly process some well-known tags
        foreach (['table'] as $tag) {
            $body = $this->processTags($body, $tag, $callbackTags);
        }

        $body = $this->invokePostProcessors('body', $body);

        return $body;
    }

    /**
     * Returns the title of the current document.
     *
     * @return string
     */
    public function getTitle(): string
    {
        $this->enforceIsLoaded();

        return $this->data['title'];
    }

    /**
     * Returns the source name of the current document.
     *
     * @return string
     */
    public function getSourceName(): string
    {
        $this->enforceIsLoaded();

        return $this->data['sourcename'];
    }

    /**
     * Returns the current page name.
     *
     * @return string
     */
    public function getCurrentPageName(): string
    {
        $this->enforceIsLoaded();

        return $this->data['current_page_name'];
    }

    /**
     * Returns previous document's information.
     *
     * @return array|null
     */
    public function getPreviousDocument(): ?array
    {
        $this->enforceIsLoaded();

        return $this->data['prev'] ?? null;
    }

    /**
     * Returns next document's information.
     *
     * @return array|null
     */
    public function getNextDocument(): ?array
    {
        $this->enforceIsLoaded();

        return $this->data['next'] ?? null;
    }

    /**
     * Returns parents of current document.
     *
     * @param bool $includeRoot
     * @return array
     */
    public function getParentDocuments(bool $includeRoot = false): array
    {
        $this->enforceIsLoaded();

        $parents = $this->data['parents'];

        if ($includeRoot && $this->getDocument() !== $this->getDefaultFile() . '/') {
            static $rootDocument;
            if ($rootDocument === null) {
                $rootDocument = [];
                $sphinxReader = (new self())
                    ->setStorage($this->getStorage())
                    ->setPath($this->getPath())
                    ->setDocument($this->getDefaultFile() . '/');
                if ($sphinxReader->load()) {
                    $rootDocument = [
                        'link' => '/',
                        'title' => $sphinxReader->data['title'],
                    ];
                }
                unset($sphinxReader);
            }
            if (!empty($rootDocument)) {
                array_unshift($parents, $rootDocument);
            }
        }

        return $parents;
    }

    /**
     * Returns relative links of current document.
     *
     * @return array
     */
    public function getRelativeLinks(): array
    {
        $this->enforceIsLoaded();

        return $this->data['rellinks'];
    }

    /**
     * Returns the index entries.
     *
     * @return array|null
     */
    public function getIndexEntries(): ?array
    {
        $this->enforceIsLoaded();

        return $this->data['genindexentries'] ?? null;
    }

    /**
     * Returns the master Table of Contents (TOC) of the documentation. That is,
     * the general overview of chapters as found on the master document, relative
     * to the current document.
     * BEWARE: links are kept as this and are not generated for current context
     *
     * @param bool $firstLevelIsMasterDocument
     * @return string
     * @throws \RuntimeException
     */
    public function getMasterTableOfContents(bool $firstLevelIsMasterDocument = true): string
    {
        if ($this->document === $this->defaultFile . '/' && !empty($this->data)) {
            $data = $this->data;
        } else {
            // Temporarily load the master document
            if (empty($this->path) || !is_dir($this->path)) {
                throw new \RuntimeException('Invalid path: ' . $this->path, 1369907635);
            }
            $filename = $this->path . $this->defaultFile . '.fjson';
            $content = file_get_contents($filename);
            $data = json_decode($content, true);
        }

        $toc = '';
        if (preg_match('#<div class="toctree-wrapper compound">(.*?)</div>#s', $data['body'], $matches)) {
            if ($firstLevelIsMasterDocument) {
                // Put the master document as first level
                $toc .= '<ul>' . LF;
                $toc .= '<li class="toctree-l0"><a class="reference internal" href="../' . $this->getDefaultFile() . '/">' . htmlspecialchars($data['title']) . '</a>' . trim($matches[1]) . LF;
                $toc .= '</li>' . LF;
                $toc .= '</ul>';
            } else {
                $toc .= trim($matches[1]);
            }
        }

        // Remove empty sublevels
        $toc = preg_replace('#<ul(\s+[^>]+)>\s*</ul>#s', '', $toc);
        // Fix TOC to make it XML compliant
        $toc = preg_replace_callback('# href="([^"]+)"#', static function ($matches) {
            $url = str_replace('&amp;', '&', $matches[1]);
            $url = str_replace('&', '&amp;', $url);

            return ' href="' . $url . '"';
        }, $toc);

        // Mark current document as "active"
        $needle = '<a class="reference internal" href="../' . $this->document . '"';
        $position = strpos($toc, $needle);
        if ($position === false) {
            // Strange, current page is not found!
            return $toc;
        }
        $toc = str_replace($needle, str_replace('"reference internal"', '"current reference internal"', $needle), $toc);

        // Mark parent pages in the breadcrumb as "active" by extracting
        // the depth, found in the outer element <li class="toctree-l<depth>"...
        $haystack = substr($toc, 0, $position);
        $needle = '<li class="toctree-l';
        $position = strrpos($haystack, $needle);
        $depth = (int)substr(
            $toc,
            $position + strlen($needle),
            strpos(
                $haystack,
                '"',
                $position + strlen($needle)
            ) - ($position + strlen($needle))
        );

        // ... and going up to root
        while (--$depth > 0) {
            // Search for closest parent <li>
            $liNeedle = $needle . $depth . '"';
            $haystack = substr($toc, 0, $position);
            $position = strrpos($haystack, $liNeedle);
            // Search for first <a> after the <li>
            $aNeedle = '<a class="';
            $position = strpos($haystack, $aNeedle, $position);

            // Parent page is found, mark it as "active"
            $newToc = substr($toc, 0, $position + strlen($aNeedle));
            $newToc .= 'active ';
            $newToc .= substr($toc, $position + strlen($aNeedle));
            $toc = $newToc;
        }

        return $toc;
    }

    /**
     * Returns the Table Of Contents (TOC) of the documentation
     * for the current document.
     *
     * @param callback $callbackLinks Callback to generate Links in current context
     * @return string
     * @throws \RuntimeException
     */
    public function getTableOfContents($callbackLinks): string
    {
        $this->enforceIsLoaded();
        $callableName = '';
        if (!is_callable($callbackLinks, false, $callableName)) {
            throw new \RuntimeException('Invalid callback for links: ' . $callableName, 1365172117);
        }

        // Replace links in table of contents
        $toc = $this->replaceLinks($this->data['toc'], $callbackLinks);
        // Remove empty sublevels
        $toc = preg_replace('#<ul>\s*</ul>#', '', $toc);
        // Fix TOC to make it XML compliant
        $toc = preg_replace_callback('# href="([^"]+)"#', static function ($matches) {
            $url = str_replace('&amp;', '&', $matches[1]);
            $url = str_replace('&', '&amp;', $url);

            return ' href="' . $url . '"';
        }, $toc);

        return $toc;
    }

    /**
     * Returns the raw data.
     *
     * @return array
     */
    public function getData(): array
    {
        return $this->data;
    }

    /**
     * Returns references from the documentation.
     *
     * @return array
     * @throws \RuntimeException
     */
    public function getReferences(): array
    {
        if (empty($this->path) || !is_dir($this->path)) {
            throw new \RuntimeException('Invalid path: ' . $this->path, 1365165151);
        }
        $filename = $this->path . 'objects.inv';
        if (!is_file($filename)) {
            throw new \RuntimeException('File not found: ' . $filename, 1367562970);
        }

        $content = file_get_contents($filename);
        // Remove ASCII comments at the beginning of the file
        while (strpos($content, '#') === 0) {
            $content = substr($content, strpos($content, LF) + 1);
        }
        // Uncompress the references
        if (function_exists('zlib_decode')) {
            $content = zlib_decode($content);
        } else {
            $content = gzuncompress($content);
        }

        $lines = explode(LF, $content);

        $references = [];
        foreach ($lines as $line) {
            $data = explode(' ', $line, 5);
            if (!count($data) === 5) {
                // Should not happen but something went wrong!
                continue;
            }

            $chapter = substr($data[3], 0, strpos($data[3], '#') - 1);
            $references[$chapter][$data[0]] = [
                'name' => $data[0],
                'type' => $data[1],
                'index' => $data[2],
                'link' => $data[3],
                'title' => $data[4],
            ];
        }

        // Sort references by chapter, then by name
        ksort($references);
        foreach ($references as $chapter => $_) {
            ksort($references[$chapter]);
        }

        return $references;
    }

    /**
     * Replaces links in a ReStructuredText document.
     *
     * @param string $content
     * @param callback $callbackLinks function to generate Links in current context
     * @param bool $relativeToDefaultDocument
     * @return string
     */
    protected function replaceLinks(string $content, $callbackLinks, bool $relativeToDefaultDocument = false): string
    {
        $self = $this;
        $ret = preg_replace_callback(
            '#(<a .*? href=")([^"]+)#',
            static function (array $matches) use ($self, $callbackLinks, $relativeToDefaultDocument) {
                $document = $self->getDocument();
                $anchor = '';
                if (preg_match('#^[a-zA-Z]+://#', $matches[2])) {
                    // External URL
                    return $matches[0];
                }
                if (GeneralUtility::isFirstPartOfStr($matches[2], 'mailto:')) {
                    // Email address
                    $email = preg_replace_callback('/(&#(\d{2});)/', static function ($m) {
                        return chr($m[2]);
                    }, $matches[2]);
                    $link = $callbackLinks(urldecode($email));

                    return $matches[1] . $link;
                }
                if (strpos($matches[2], '#') === 0) {
                    $anchor = $matches[2];
                }

                if ($anchor !== '') {
                    $document .= $anchor;
                } elseif (GeneralUtility::isFirstPartOfStr($matches[2], '_sources/')) {
                    $document = $matches[2];
                } else {
                    if ($relativeToDefaultDocument) {
                        $currentDocumentDepth = count(explode('/', $document)) - 1;
                        if (GeneralUtility::isFirstPartOfStr($matches[2], '../')) {
                            $currentDocumentDepth--;
                        }
                        // Pretend the link was generated relative to current document
                        $matches[2] = str_repeat('../', $currentDocumentDepth) . $matches[2];
                    }
                    $segments = explode('/', substr($document, 0, -1));
                    if (count($segments) === 1 && !GeneralUtility::isFirstPartOfStr($matches[2], '../')) {
                        // $document's last part is a document, not a directory
                        $matches[2] = '../' . $matches[2];
                    }
                    if (GeneralUtility::isFirstPartOfStr($matches[2], '../')) {
                        $document = substr($document, 0, strrpos(rtrim($document, '/'), '/'));
                    }
                    $absolute = RestHelper::relativeToAbsolute($self->getPath() . $document, $matches[2]);
                    $document = substr($absolute, strlen($self->getPath()));
                }
                $url = $callbackLinks($document);
                $url = str_replace('&amp;', '&', $url);
                $url = str_replace('&', '&amp;', $url);

                return $matches[1] . $url;
            },
            $content
        );

        return $ret;
    }

    /**
     * Replaces images in a reST document.
     *
     * @param string $content
     * @param callback $callbackImages function to process images in current context
     * @return string
     * @link http://w-shadow.com/blog/2009/10/20/how-to-extract-html-tags-and-their-attributes-with-php/
     */
    protected function replaceImages(string $content, $callbackImages): string
    {
        $self = $this;
        $root = $this->path . $this->document;
        // $root's last part is a document, not a directory
        $root = substr($root, 0, strrpos(rtrim($root, '/'), '/'));
        $tagPattern =
            '@<img                      # <img
            (?P<attributes>\s[^>]+)?    # attributes, if any
            \s*/>                       # />
            @xsi';
        $attributePattern =
            '@
                (?P<name>\w+)                                           # attribute name
                \s*=\s*
                (
                    (?P<quote>[\"\'])(?P<value_quoted>.*?)(?P=quote)    # a quoted value
                    |                                                   # or
                    (?P<value_unquoted>[^\s"\']+?)(?:\s+|$)             # an unquoted value (terminated by whitespace or EOF)
                )
            @xsi';

        $ret = preg_replace_callback($tagPattern, static function (array $matches) use ($self, $root, $attributePattern, $callbackImages) {
            // Parse tag attributes, if any
            $attributes = [];
            if (!empty($matches['attributes'][0])) {
                if (preg_match_all($attributePattern, $matches['attributes'], $attributeData, PREG_SET_ORDER)) {
                    // Turn the attribute data into a name->value array
                    foreach ($attributeData as $attr) {
                        if (!empty($attr['value_quoted'])) {
                            $value = $attr['value_quoted'];
                        } elseif (!empty($attr['value_unquoted'])) {
                            $value = $attr['value_unquoted'];
                        } else {
                            $value = '';
                        }

                        $value = html_entity_decode($value, ENT_QUOTES, 'utf-8');
                        $attributes[$attr['name']] = $value;
                    }
                }
            }
            $src = RestHelper::relativeToAbsolute($root, $attributes['src']);
            $storage = $self->getStorage();
            if ($storage !== null) {
                $storageConfiguration = $storage->getConfiguration();
                $basePath = rtrim($storageConfiguration['basePath'], '/') . '/';
                $fileIdentifier = substr($src, strlen($basePath) - 1);
                $attributes['src'] = $storage->getUid() . ':' . $fileIdentifier;
            } else {
                // FAL is not used
                trigger_error('FAL is not used, please upgrade your plugin configuration.', E_USER_DEPRECATED);
                $pathSite = Environment::getPublicPath() . '/';
                $attributes['src'] = substr($src, strlen($pathSite));
            }

            return $callbackImages($attributes);
        }, $content);

        return $ret;
    }

    /**
     * Replaces well-known tags in a reST document.
     *
     * @param string $content
     * @param string $tagName
     * @param callback $callbackTags function to process matched tags in current context
     * @return string
     */
    protected function processTags(string $content, string $tagName, callable $callbackTags): string
    {
        $tagPattern =
            '@<' . $tagName . '         # <tagName
            (?P<attributes>\s[^>]+)?    # attributes, if any
            \s*>                        # >
            @xsi';
        $attributePattern =
            '@
                (?P<name>\w+)                                           # attribute name
                \s*=\s*
                (
                    (?P<quote>[\"\'])(?P<value_quoted>.*?)(?P=quote)    # a quoted value
                    |                                                   # or
                    (?P<value_unquoted>[^\s"\']+?)(?:\s+|$)             # an unquoted value (terminated by whitespace or EOF)
                )
            @xsi';

        $ret = preg_replace_callback($tagPattern, static function (array $matches) use ($attributePattern, $tagName, $callbackTags) {
            // Parse tag attributes, if any
            $attributes = [];
            if (!empty($matches['attributes'][0])) {
                if (preg_match_all($attributePattern, $matches['attributes'], $attributeData, PREG_SET_ORDER)) {
                    // Turn the attribute data into a name->value array
                    foreach ($attributeData as $attr) {
                        if (!empty($attr['value_quoted'])) {
                            $value = $attr['value_quoted'];
                        } elseif (!empty($attr['value_unquoted'])) {
                            $value = $attr['value_unquoted'];
                        } else {
                            $value = '';
                        }

                        $value = html_entity_decode($value, ENT_QUOTES, 'utf-8');
                        $attributes[$attr['name']] = $value;
                    }
                }
            }

            return $callbackTags($tagName, $matches[0], $attributes);
        }, $content);

        return $ret;
    }

    /**
     * Invokes registered hooks to post-process the content.
     *
     * @param string $name
     * @param string $content
     * @return string $content
     */
    protected function invokePostProcessors(string $name, string $content): string
    {
        $key = 'postProcess' . ucfirst($name);
        foreach (($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['restdoc'][$key] ?? []) as $funcRef) {
            $params = [
                'content' => &$content,
            ];
            GeneralUtility::callUserFunction($funcRef, $params, $this);
        }

        return $content;
    }
}
