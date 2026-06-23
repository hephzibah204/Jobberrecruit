<?php
/**
 * ============================================================================
 *  LIVE JOB TICKER — /api/latest-jobs  (CodeIgniter 4)
 * ----------------------------------------------------------------------------
 *  Feeds the homepage "Just posted" scrolling marquee.
 *
 *  The front-end (jobberrecruit-homepage.html) already does:
 *      fetch('/api/latest-jobs')  →  expects JSON array of:
 *          [{ role, company, location, url, isNew }, ...]
 *
 *  This file shows BOTH pieces your dev needs:
 *    1) the route,
 *    2) the controller (returns JSON in exactly that shape),
 *    3) the model query.
 *
 *  Drop the pieces into your existing app structure (don't paste this whole
 *  file verbatim — it's three files' worth of code shown together).
 * ============================================================================
 */


/* ────────────────────────────────────────────────────────────────────────
 * 1) ROUTE  →  app/Config/Routes.php
 * ──────────────────────────────────────────────────────────────────────── */
/*
$routes->get('api/latest-jobs', 'Api\JobTicker::latest');
*/


/* ────────────────────────────────────────────────────────────────────────
 * 2) CONTROLLER  →  app/Controllers/Api/JobTicker.php
 * ──────────────────────────────────────────────────────────────────────── */

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Models\JobModel;
use CodeIgniter\HTTP\ResponseInterface;

class JobTicker extends BaseController
{
    public function latest(): ResponseInterface
    {
        $model = new JobModel();
        $jobs  = $model->latestForTicker(12);   // newest 12 open jobs

        $payload = [];
        foreach ($jobs as $job) {
            $payload[] = [
                // Keys MUST match what the front-end expects: role/company/location/url/isNew
                'role'     => $job['title'],
                'company'  => $job['is_confidential']
                                ? 'Confidential'
                                : $job['company_name'],
                'location' => $job['location'] ?: 'Nigeria',
                'url'      => '/jobs/' . $job['slug'],
                // "NEW" tag if posted within the last 3 days
                'isNew'    => (strtotime($job['posted_at']) >= strtotime('-3 days')),
            ];
        }

        // Cache 60s at the edge/browser — the ticker doesn't need to be real-time,
        // and this protects the DB from being hit on every homepage load.
        return $this->response
            ->setHeader('Cache-Control', 'public, max-age=60')
            ->setJSON($payload);
    }
}


/* ────────────────────────────────────────────────────────────────────────
 * 3) MODEL METHOD  →  add to app/Models/JobModel.php
 * ──────────────────────────────────────────────────────────────────────── */
/*
namespace App\Models;

use CodeIgniter\Model;

class JobModel extends Model
{
    protected $table      = 'jobs';
    protected $primaryKey = 'id';

    // ... your existing model config ...

    /**
     * Newest OPEN jobs for the homepage ticker.
     * Returns only the columns the ticker needs (keep the query lean).
     */
    public function latestForTicker(int $limit = 12): array
    {
        return $this->select('title, slug, company_name, is_confidential, location, posted_at')
                    ->where('status', 'open')
                    ->orderBy('posted_at', 'DESC')
                    ->limit($limit)
                    ->find();
    }
}
*/


/* ────────────────────────────────────────────────────────────────────────
 *  NOTES FOR THE DEV
 * ----------------------------------------------------------------------------
 *  • SHAPE IS A CONTRACT. The JSON keys (role, company, location, url, isNew)
 *    must match exactly — the homepage JS reads those names. Don't rename them.
 *
 *  • CONFIDENTIAL EMPLOYERS. When is_confidential is set, the ticker shows
 *    "Confidential" instead of the real company name (handled above), matching
 *    how confidential jobs render elsewhere on the site.
 *
 *  • ESCAPING. setJSON() encodes safely, so titles with & or quotes are fine.
 *    Do NOT build the JSON by hand with string concatenation.
 *
 *  • CACHING. The 60s Cache-Control keeps the homepage fast and the DB calm.
 *    If you run a CDN (Cloudflare), this also lets it cache the endpoint.
 *    Bump or lower max-age to taste; real-time is unnecessary for a ticker.
 *
 *  • EMPTY RESULT. If there are no open jobs, this returns []. The front-end
 *    keeps showing its built-in seed list on an empty/failed response, so the
 *    ticker never looks broken. (See initTicker() in the homepage.)
 *
 *  • SAME SOURCE OF TRUTH. This pulls from the same `jobs` table as /jobs and
 *    the homepage "Recent jobs". Don't maintain a separate ticker list — that's
 *    how the demo's hardcoded SEED_JOBS got out of sync originally.
 *
 *  • OPTIONAL: prioritise featured. If you'd rather the ticker lead with paid
 *    featured jobs, change the model orderBy to:
 *        ->orderBy('featured', 'DESC')->orderBy('posted_at', 'DESC')
 *    (Most boards keep the ticker purely newest-first; your call.)
 * ──────────────────────────────────────────────────────────────────────── */
