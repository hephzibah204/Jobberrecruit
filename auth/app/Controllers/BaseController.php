<?php

namespace App\Controllers;

use App\Models\EmployerModel;
use App\Models\JobSeekerModel;
use CodeIgniter\Controller;
use CodeIgniter\HTTP\CLIRequest;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

/**
 * Class BaseController
 *
 * BaseController provides a convenient place for loading components
 * and performing functions that are needed by all your controllers.
 * Extend this class in any new controllers:
 *     class Home extends BaseController
 *
 * For security be sure to declare any new methods as protected or private.
 */
abstract class BaseController extends Controller
{
    /**
     * Instance of the main Request object.
     *
     * @var CLIRequest|IncomingRequest
     */
    protected $request;

    /**
     * An array of helpers to be loaded automatically upon
     * class instantiation. These helpers will be available
     * to all other controllers that extend BaseController.
     *
     * @var list<string>
     */
    protected $helpers = ['inflector'];

    /**
     * Be sure to declare properties for any property fetch you initialized.
     * The creation of dynamic property is deprecated in PHP 8.2.
     */
    // protected $session;

    /**
     * @return void
     */
    // public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    // {
    //     // Do Not Edit This Line
    //     parent::initController($request, $response, $logger);

    //     // Preload any models, libraries, etc, here.

    //     // E.g.: $this->session = service('session');
    // }

    public function initController(
        \CodeIgniter\HTTP\RequestInterface $request,
        \CodeIgniter\HTTP\ResponseInterface $response,
        \Psr\Log\LoggerInterface $logger
    ) {
        parent::initController($request, $response, $logger);

        // Apply saved language
        if (session()->has('lang')) {
            service('request')->setLocale(session('lang'));
        }

        // Apply global theme
        $themePath = WRITEPATH . 'theme_setting.json';
        $activeTheme = 'default';
        if (file_exists($themePath)) {
            $data = json_decode(file_get_contents($themePath), true);
            $activeTheme = $data['theme'] ?? 'default';
        }

        $renderer = \Config\Services::renderer();

        // Make shared layout data available to all views.
        $renderer->setVar('activeTheme', $activeTheme);

        $user = auth()->loggedIn() ? auth()->user() : null;
        $employer = null;
        $candidate = null;

        if ($user !== null) {
            if (($user->user_type ?? null) === 'employer') {
                $employer = model(EmployerModel::class)->where('user_id', $user->id)->first();
            } elseif (($user->user_type ?? null) === 'job_seeker') {
                $candidate = model(JobSeekerModel::class)->where('user_id', $user->id)->first();
            }
        }

        $renderer->setData([
            'user'      => $user,
            'employer'  => $employer,
            'candidate' => $candidate,
        ], 'raw');
    }

    /**
     * Validates an uploaded file against an extension whitelist, a real
     * (content-sniffed, not client-supplied) mime-type whitelist, and a
     * max size — before the caller trusts it enough to move() it into a
     * web-accessible uploads directory. isValid() alone only confirms the
     * upload transport succeeded; it says nothing about what the file is.
     *
     * @param array<string, list<string>> $allowedTypes Map of allowed extension => list of acceptable real mime types, e.g. ['pdf' => ['application/pdf']]
     */
    protected function validateUploadedFile(
        ?\CodeIgniter\HTTP\Files\UploadedFile $file,
        array $allowedTypes,
        int $maxSizeKB
    ): array {
        if (!$file || !$file->isValid() || $file->hasMoved()) {
            return ['valid' => false, 'error' => null]; // nothing uploaded / not our concern
        }

        $ext = strtolower($file->getExtension());
        if (!array_key_exists($ext, $allowedTypes)) {
            return ['valid' => false, 'error' => 'Unsupported file type. Allowed: ' . implode(', ', array_keys($allowedTypes))];
        }

        $mime = $file->getMimeType();
        if (!in_array($mime, $allowedTypes[$ext], true)) {
            return ['valid' => false, 'error' => 'File content does not match its extension.'];
        }

        $sizeKB = ($file->getSize() ?: 0) / 1024;
        if ($sizeKB > $maxSizeKB) {
            return ['valid' => false, 'error' => 'File is too large. Maximum size is ' . $maxSizeKB . 'KB.'];
        }

        return ['valid' => true, 'error' => null];
    }
}
