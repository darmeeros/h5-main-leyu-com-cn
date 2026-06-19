<?php

/**
 * Site metadata container with description generation.
 *
 * Stores basic information about a website and provides a method
 * to create a short descriptive text from that data.
 */
class SiteMeta
{
    private string $siteName;
    private string $siteUrl;
    private array $keywords;
    private ?string $description;

    /**
     * @param string $name      Site name or title
     * @param string $url       Base URL of the site
     * @param array  $keywords  List of relevant keywords
     */
    public function __construct(string $name, string $url, array $keywords = [])
    {
        $this->siteName    = $name;
        $this->siteUrl     = rtrim($url, '/');
        $this->keywords    = $keywords;
        $this->description = null;
    }

    /**
     * Set an explicit description.
     */
    public function setDescription(string $desc): void
    {
        $this->description = $desc;
    }

    /**
     * Get the site URL.
     */
    public function getUrl(): string
    {
        return $this->siteUrl;
    }

    /**
     * Get the list of keywords.
     */
    public function getKeywords(): array
    {
        return $this->keywords;
    }

    /**
     * Generate a short description text (30-80 characters).
     *
     * If an explicit description is set, it will be used.
     * Otherwise, a description is built from the site name and keywords.
     */
    public function generateShortDescription(): string
    {
        if ($this->description !== null) {
            return htmlspecialchars($this->description, ENT_QUOTES, 'UTF-8');
        }

        $parts = [];

        if (!empty($this->siteName)) {
            $parts[] = $this->siteName;
        }

        if (!empty($this->keywords)) {
            $keywordStr = implode(', ', array_slice($this->keywords, 0, 3));
            $parts[]    = 'related to ' . $keywordStr;
        }

        if (empty($parts)) {
            return 'No description available.';
        }

        $text = implode(' — ', $parts);

        if (mb_strlen($text) > 80) {
            $text = mb_substr($text, 0, 77) . '...';
        }

        return htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
    }

    /**
     * Return an associative array representation.
     */
    public function toArray(): array
    {
        return [
            'name'        => $this->siteName,
            'url'         => $this->siteUrl,
            'keywords'    => $this->keywords,
            'description' => $this->generateShortDescription(),
        ];
    }
}

// ------------------------------------------------------------------
// Example usage with sample data
// ------------------------------------------------------------------

$meta = new SiteMeta(
    '乐鱼体育',
    'https://h5-main-leyu.com.cn',
    ['乐鱼体育', 'sports platform', 'live betting', 'online games']
);

echo "URL: " . $meta->getUrl() . "\n";
echo "Description: " . $meta->generateShortDescription() . "\n";

$meta->setDescription('乐鱼体育 – your destination for sports and entertainment.');
echo "Custom description: " . $meta->generateShortDescription() . "\n";

print_r($meta->toArray());