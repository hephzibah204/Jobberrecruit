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
    protected $helpers = [];

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
}
