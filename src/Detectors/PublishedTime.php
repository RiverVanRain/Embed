<?php
declare(strict_types = 1);

namespace Embed\Detectors;

class PublishedTime extends Detector
{
    public function detect(): ?string
    {
        $oembed = $this->extractor->getOEmbed();
        $document = $this->extractor->getDocument();
        $ld = $this->extractor->getLinkedData();

        return $oembed->str('pubdate')
            ?: $document->meta('article:published_time')
            ?: $document->meta('created')
            ?: $document->meta('date')
            ?: $document->meta('datepublished')
            ?: $document->meta('music:release_date')
            ?: $document->meta('video:release_date')
            ?: $document->meta('newsrepublic:publish_date')
            ?: $ld->str('pagePublished')
            ?: $this->detectFromPath()
            ?: $document->meta('pagerender')
            ?: $document->meta('pub_date')
            ?: $document->meta('publication-date')
            ?: $document->meta('lp.article:published_time')
            ?: $document->meta('lp.article:modified_time')
            ?: $document->meta('publish-date')
            ?: $document->meta('rc.datecreation')
            ?: $document->meta('timestamp')
            ?: $document->meta('sailthru.date')
            ?: $document->meta('article:modified_time');
    }

    /**
     * Some sites using WordPress have the published time in the url
     * For example: mysite.com/2020/05/19/post-title
     */
    private function detectFromPath(): ?string
    {
        $path = $this->extractor->getUri()->getPath();

        if (preg_match('#/(19|20)\d{2}/[0-1]?\d/[0-3]?\d/#', $path, $matches)) {
            $date = date_create_from_format('/Y/m/d/', $matches[0]) ?: null;
            return $date ? $date->format('Y/m/d') : null;
        }

        return null;
    }
}
