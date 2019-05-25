<?php

class ControllerCommonFooter extends PT_Controller
{
    public function index()
    {
        /*$bots = $this->bots();

        foreach ($bots as $bot) {
            $bot = '#' . $bot . '#i';

            if (preg_match($bot, $this->request->server['HTTP_USER_AGENT'])) {
                die('YOU ARE A BOT');
            } else {
                return false;
            }
        }*/

        $this->load->model('tool/online');

        if (isset($this->request->server['HTTP_X_REAL_IP'])) {
            $ip = $this->request->server['HTTP_X_REAL_IP'];
        } else if (isset($this->request->server['REMOTE_ADDR'])) {
            $ip = $this->request->server['REMOTE_ADDR'];
        } else {
            $ip = '';
        }

        if (isset($this->request->server['HTTP_HOST']) && isset($this->request->server['REQUEST_URI'])) {
            $url = ($this->request->server['HTTPS'] ? 'https://' : 'http://') . $this->request->server['HTTP_HOST'] . $this->request->server['REQUEST_URI'];
        } else {
            $url = '';
        }

        if (isset($this->request->server['HTTP_REFERER'])) {
            $referer = $this->request->server['HTTP_REFERER'];
        } else {
            $referer = '';
        }

        $this->model_tool_online->addOnline($ip, $url, $referer);

        return $this->load->view('common/footer');
    }

    protected function bots()
    {
        static $bots = array(
            'crawler',
            'spider',
            'robot',
            'slurp',
            'Atomz',
            'googlebot',
            'VoilaBot',
            'msnbot',
            'Gaisbot',
            'Gigabot',
            'SBIder',
            'Zyborg',
            'FunWebProducts',
            'findlinks',
            'ia_archiver',
            'MJ12bot',
            'Ask Jeeves',
            'NG/2.0',
            'voyager',
            'Exabot',
            'Nutch',
            'Hercules',
            'psbot',
            'LocalcomBot'
        );

        return $bots;
    }
}
