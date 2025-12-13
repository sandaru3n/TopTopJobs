<?php

namespace App\Controllers;

use App\Models\JobModel;
use App\Models\CollectionModel;

class SitemapController extends BaseController
{
    protected $jobModel;
    protected $collectionModel;

    public function __construct()
    {
        $this->jobModel = new JobModel();
        $this->collectionModel = new CollectionModel();
    }

    /**
     * Generate XML sitemap
     */
    public function index()
    {
        // Set content type to XML
        $this->response->setContentType('application/xml');

        // Get base URL
        $baseUrl = rtrim(base_url(), '/');

        // Get all active jobs
        $jobs = $this->jobModel
            ->where('status', 'active')
            ->select('slug, updated_at')
            ->findAll();

        // Get all active collections
        $collections = $this->collectionModel
            ->where('status', 'active')
            ->select('slug, updated_at')
            ->findAll();

        // Static pages with their priorities and change frequencies
        $staticPages = [
            ['url' => '', 'priority' => '1.0', 'changefreq' => 'daily'],
            ['url' => 'jobs', 'priority' => '0.9', 'changefreq' => 'daily'],
            ['url' => 'post-job', 'priority' => '0.8', 'changefreq' => 'weekly'],
            ['url' => 'about', 'priority' => '0.7', 'changefreq' => 'monthly'],
            ['url' => 'contact', 'priority' => '0.7', 'changefreq' => 'monthly'],
            ['url' => 'terms', 'priority' => '0.5', 'changefreq' => 'yearly'],
            ['url' => 'privacy', 'priority' => '0.5', 'changefreq' => 'yearly'],
        ];

        // Generate XML
        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";

        // Add static pages
        foreach ($staticPages as $page) {
            $url = $baseUrl . '/' . ($page['url'] ? $page['url'] : '');
            $xml .= "  <url>\n";
            $xml .= "    <loc>" . htmlspecialchars($url, ENT_XML1, 'UTF-8') . "</loc>\n";
            $xml .= "    <priority>" . $page['priority'] . "</priority>\n";
            $xml .= "    <changefreq>" . $page['changefreq'] . "</changefreq>\n";
            $xml .= "    <lastmod>" . date('Y-m-d') . "</lastmod>\n";
            $xml .= "  </url>\n";
        }

        // Add job pages
        foreach ($jobs as $job) {
            $url = $baseUrl . '/job/' . htmlspecialchars($job['slug'], ENT_XML1, 'UTF-8');
            $lastmod = !empty($job['updated_at']) ? date('Y-m-d', strtotime($job['updated_at'])) : date('Y-m-d');
            
            $xml .= "  <url>\n";
            $xml .= "    <loc>" . $url . "</loc>\n";
            $xml .= "    <priority>0.8</priority>\n";
            $xml .= "    <changefreq>weekly</changefreq>\n";
            $xml .= "    <lastmod>" . $lastmod . "</lastmod>\n";
            $xml .= "  </url>\n";
        }

        // Add collection pages
        foreach ($collections as $collection) {
            $url = $baseUrl . '/collection/' . htmlspecialchars($collection['slug'], ENT_XML1, 'UTF-8');
            $lastmod = !empty($collection['updated_at']) ? date('Y-m-d', strtotime($collection['updated_at'])) : date('Y-m-d');
            
            $xml .= "  <url>\n";
            $xml .= "    <loc>" . $url . "</loc>\n";
            $xml .= "    <priority>0.7</priority>\n";
            $xml .= "    <changefreq>weekly</changefreq>\n";
            $xml .= "    <lastmod>" . $lastmod . "</lastmod>\n";
            $xml .= "  </url>\n";
        }

        $xml .= '</urlset>';

        return $this->response->setBody($xml);
    }
}

