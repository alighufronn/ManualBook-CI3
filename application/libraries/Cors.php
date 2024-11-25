<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cors
{
    protected $ci;
    private $allowedOrigins = [];
    private $allowedOriginsPatterns = [];
    private $allowedMethods = [];
    private $allowedHeaders = [];
    private $exposedHeaders = [];
    private $supportsCredentials = false;
    private $maxAge = 0;
    private $allowAllOrigins = false;
    private $allowAllMethods = false;
    private $allowAllHeaders = false;

    public function __construct()
    {
        $this->ci = &get_instance();
        $this->ci->load->config('cors', true);
        $this->setOptions($this->ci->config->item('cors'));
    }

    public function handle()
    {
        if ($this->isPreflightRequest($this->ci->input)) {
            $this->handlePreflightRequest();
        } else {
            $this->handleActualRequest();
        }
    }

    private function setOptions($options)
    {
        $this->allowedOrigins = $options['allowed_origins'];
        $this->allowedOriginsPatterns = $options['allowed_origins_patterns'];
        $this->allowedMethods = $options['allowed_methods'];
        $this->allowedHeaders = $options['allowed_headers'];
        $this->exposedHeaders = $options['exposed_headers'];
        $this->supportsCredentials = $options['supports_credentials'];
        $this->maxAge = $options['max_age'];
    }

    private function handlePreflightRequest()
    {
        $this->ci->output->set_status_header(200);
        $this->ci->output->set_header('Access-Control-Allow-Methods: ' . implode(', ', $this->allowedMethods));
        $this->ci->output->set_header('Access-Control-Allow-Headers: ' . implode(', ', $this->allowedHeaders));
        $this->ci->output->set_header('Access-Control-Allow-Credentials: ' . ($this->supportsCredentials ? 'true' : 'false'));
        $this->ci->output->set_header('Access-Control-Max-Age: ' . $this->maxAge);
        $this->ci->output->set_header('Access-Control-Allow-Origin: ' . $this->getAllowedOrigin());
        $this->ci->output->set_header('Access-Control-Allow-Expose-Headers: ' . implode(', ', $this->exposedHeaders));
        $this->ci->output->set_header('Content-Length: 0');
        $this->ci->output->set_output(null);
        $this->ci->output->_display();
    }

    private function handleActualRequest()
    {
        $this->ci->output->set_header('Access-Control-Allow-Origin: ' . $this->getAllowedOrigin());
        $this->ci->output->set_header('Access-Control-Allow-Methods: ' . implode(', ', $this->allowedMethods));
        $this->ci->output->set_header('Access-Control-Allow-Headers: ' . implode(', ', $this->allowedHeaders));
        $this->ci->output->set_header('Access-Control-Allow-Credentials: ' . ($this->supportsCredentials ? 'true' : 'false'));
        $this->ci->output->set_header('Access-Control-Allow-Expose-Headers: ' . implode(', ', $this->exposedHeaders));
        $this->ci->output->set_header('Access-Control-Max-Age: ' . $this->maxAge);
    }

    private function getAllowedOrigin()
    {
        if ($this->allowAllOrigins) {
            return '*';
        }

        $origin = $this->ci->input->server('HTTP_ORIGIN');
        if (in_array($origin, $this->allowedOrigins) || $this->isAllowedOriginPattern($origin)) {
            return $origin;
        }

        return '';
    }

    private function isAllowedOriginPattern($origin)
    {
        foreach ($this->allowedOriginsPatterns as $pattern) {
            if (preg_match($pattern, $origin)) {
                return true;
            }
        }
        return false;
    }

    private function isPreflightRequest($input)
    {
        return $input->server('REQUEST_METHOD') === 'OPTIONS';
    }
}
