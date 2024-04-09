<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use App\Facades\DumperServiceFacade as Dumper;
use App\Services\Css2XPathService;

/**
 * Controller for developers use only
 * To track execution time use $this-t()
 * To dump values use $this->d
 * Set up _authorize() to you needs
 * See example() as an example of usage
 *
 */
class DevController extends Controller
{
    use \App\Traits\ScrapePosts;

    private $d = [];
    private $timings = [];
    private $queryLog = false;
    private $user;
    private $fullStart;
    private $start;

    public function __construct()
    {
        $this->user = auth()->user();
        $this->fullStart = $this->start = microtime(true);
    }

    // base method to navigate and manage testing logic
    public function action($slug)
    {
        if (!method_exists($this, $slug)) {
            dd('ERROR: action not found'); //? 404 instead
        }

        if ($slug != 'public') {
            $this->_authorize();
        }

        $result = $this->{$slug}();

        // dump query log if is set
        if ($this->queryLog) {
            dump('QUERY LOG', \DB::getQueryLog());
        }

        // dump $timings only if we set some
        if ($this->timings) {
            $this->timings['timings-finish'] = microtime(true) - $this->fullStart;
            dump('TIMINGS', $this->timings);
        }

        // dump $d only if we set some
        if ($this->d) {
            dump('RESULT DUMP', $this->d);
        }

        return $result;
    }

    private function test()
    {
        $d = [];

        $table = '<table class="aliDataTable" style="box-sizing: content-box; margin: 0px; padding: 0px; font-variant-numeric: inherit; font-variant-east-asian: inherit; font-stretch: inherit; font-size: 12px; line-height: inherit; font-family: Arial, Helvetica, sans-senif; border-collapse: collapse; width: 426.1pt; background-image: initial; background-position: initial; background-size: initial; background-repeat: initial; background-attachment: initial; background-origin: initial; background-clip: initial; color: rgb(51, 51, 51);">
        <tbody style="box-sizing: content-box; margin: 0px; padding: 0px; font-style: inherit; font-variant: inherit; font-weight: inherit; font-stretch: inherit; line-height: inherit; font-family: inherit;">
            <tr align="left" style="box-sizing: content-box; margin: 0px; padding: 0px; font-style: inherit; font-variant: inherit; font-weight: inherit; font-stretch: inherit; line-height: inherit; font-family: inherit; height: 30.2pt;">
                <td valign="center" style="box-sizing: content-box; margin: 0px; padding: 1px; font-style: inherit; font-variant: inherit; font-stretch: inherit; line-height: inherit; font-family: inherit; float: none; color: rgb(0, 0, 0); border-style: solid; border-color: rgb(204, 204, 204); width: 115.2pt;">
                <p style="box-sizing: content-box; margin: 0px; padding: 0px; border: 0px; font-style: inherit; font-variant: inherit; font-weight: inherit; font-stretch: inherit; font-size: inherit; line-height: inherit; font-family: inherit; vertical-align: baseline;"><span style="box-sizing: content-box; margin: 0px; padding: 0px; border: 0px; font-style: inherit; font-variant: inherit; font-weight: inherit; font-stretch: inherit; font-size: 16px; line-height: 24px; font-family: verdana, geneva; vertical-align: baseline; color: rgb(51, 51, 51);">Size&nbsp;(inches)</span></p>
                </td>
                <td colspan="4" valign="center" style="box-sizing: content-box; margin: 0px; padding: 1px; font-style: inherit; font-variant: inherit; font-stretch: inherit; line-height: inherit; font-family: inherit; float: none; color: rgb(0, 0, 0); border-style: solid; border-color: rgb(204, 204, 204); width: 310.9pt;">
                <p style="box-sizing: content-box; margin: 0px; padding: 0px; border: 0px; font-style: inherit; font-variant: inherit; font-weight: inherit; font-stretch: inherit; font-size: inherit; line-height: inherit; font-family: inherit; vertical-align: baseline;"><span style="box-sizing: content-box; margin: 0px; padding: 0px; border: 0px; font-style: inherit; font-variant: inherit; font-weight: inherit; font-stretch: inherit; font-size: 16px; line-height: 24px; font-family: verdana, geneva; vertical-align: baseline; color: rgb(51, 51, 51);">Working&nbsp;Pressure</span></p>
                <p style="box-sizing: content-box; margin: 0px; padding: 0px; border: 0px; font-style: inherit; font-variant: inherit; font-weight: inherit; font-stretch: inherit; font-size: inherit; line-height: inherit; font-family: inherit; vertical-align: baseline;"><span style="box-sizing: content-box; margin: 0px; padding: 0px; border: 0px; font-style: inherit; font-variant: inherit; font-weight: inherit; font-stretch: inherit; font-size: 16px; line-height: 24px; font-family: verdana, geneva; vertical-align: baseline; color: rgb(51, 51, 51);">(PSI)</span></p>
                </td>
            </tr>
            <tr align="left" style="box-sizing: content-box; margin: 0px; padding: 0px; font-style: inherit; font-variant: inherit; font-weight: inherit; font-stretch: inherit; line-height: inherit; font-family: inherit;">
                <td valign="center" style="box-sizing: content-box; margin: 0px; padding: 1px; font-style: inherit; font-variant: inherit; font-stretch: inherit; line-height: inherit; font-family: inherit; float: none; color: rgb(0, 0, 0); border-style: solid; border-color: rgb(204, 204, 204);">
                <p style="box-sizing: content-box; margin: 0px; padding: 0px; border: 0px; font-style: inherit; font-variant: inherit; font-weight: inherit; font-stretch: inherit; font-size: inherit; line-height: inherit; font-family: inherit; vertical-align: baseline;"><span style="box-sizing: content-box; margin: 0px; padding: 0px; border: 0px; font-style: inherit; font-variant: inherit; font-weight: inherit; font-stretch: inherit; font-size: 16px; line-height: 24px; font-family: verdana, geneva; vertical-align: baseline; color: rgb(51, 51, 51);">1&nbsp;13/16</span></p>
                </td>
                <td valign="top" style="box-sizing: content-box; margin: 0px; padding: 1px; font-style: inherit; font-variant: inherit; font-stretch: inherit; line-height: inherit; font-family: inherit; float: none; color: rgb(0, 0, 0); border-style: solid; border-color: rgb(204, 204, 204); width: 55.2pt;">
                <p style="box-sizing: content-box; margin: 0px; padding: 0px; border: 0px; font-style: inherit; font-variant: inherit; font-weight: inherit; font-stretch: inherit; font-size: inherit; line-height: inherit; font-family: inherit; vertical-align: baseline;"><span style="box-sizing: content-box; margin: 0px; padding: 0px; border: 0px; font-variant: inherit; font-stretch: inherit; font-size: 16px; line-height: normal; font-family: verdana, geneva; vertical-align: baseline; color: rgb(51, 51, 51);">&nbsp;</span></p>
                </td>
                <td valign="top" style="box-sizing: content-box; margin: 0px; padding: 1px; font-style: inherit; font-variant: inherit; font-stretch: inherit; line-height: inherit; font-family: inherit; float: none; color: rgb(0, 0, 0); border-style: solid; border-color: rgb(204, 204, 204); width: 85.2pt;">
                <p style="box-sizing: content-box; margin: 0px; padding: 0px; border: 0px; font-style: inherit; font-variant: inherit; font-weight: inherit; font-stretch: inherit; font-size: inherit; line-height: inherit; font-family: inherit; vertical-align: baseline;"><span style="box-sizing: content-box; margin: 0px; padding: 0px; border: 0px; font-variant: inherit; font-stretch: inherit; font-size: 16px; line-height: normal; font-family: verdana, geneva; vertical-align: baseline; color: rgb(51, 51, 51);">&nbsp;</span></p>
                </td>
                <td valign="top" style="box-sizing: content-box; margin: 0px; padding: 1px; font-style: inherit; font-variant: inherit; font-stretch: inherit; line-height: inherit; font-family: inherit; float: none; color: rgb(0, 0, 0); border-style: solid; border-color: rgb(204, 204, 204); width: 85.25pt;">
                <p style="box-sizing: content-box; margin: 0px; padding: 0px; border: 0px; font-style: inherit; font-variant: inherit; font-weight: inherit; font-stretch: inherit; font-size: inherit; line-height: inherit; font-family: inherit; vertical-align: baseline;"><span style="box-sizing: content-box; margin: 0px; padding: 0px; border: 0px; font-style: inherit; font-variant: inherit; font-weight: inherit; font-stretch: inherit; font-size: 16px; line-height: 24px; font-family: verdana, geneva; vertical-align: baseline; color: rgb(51, 51, 51);">10000</span></p>
                </td>
                <td valign="top" style="box-sizing: content-box; margin: 0px; padding: 1px; font-style: inherit; font-variant: inherit; font-stretch: inherit; line-height: inherit; font-family: inherit; float: none; color: rgb(0, 0, 0); border-style: solid; border-color: rgb(204, 204, 204); width: 85.25pt;">
                <p style="box-sizing: content-box; margin: 0px; padding: 0px; border: 0px; font-style: inherit; font-variant: inherit; font-weight: inherit; font-stretch: inherit; font-size: inherit; line-height: inherit; font-family: inherit; vertical-align: baseline;"><span style="box-sizing: content-box; margin: 0px; padding: 0px; border: 0px; font-style: inherit; font-variant: inherit; font-weight: inherit; font-stretch: inherit; font-size: 16px; line-height: 24px; font-family: verdana, geneva; vertical-align: baseline; color: rgb(51, 51, 51);">15000</span></p>
                </td>
            </tr>
            <tr align="left" style="box-sizing: content-box; margin: 0px; padding: 0px; font-style: inherit; font-variant: inherit; font-weight: inherit; font-stretch: inherit; line-height: inherit; font-family: inherit;">
                <td valign="center" style="box-sizing: content-box; margin: 0px; padding: 1px; font-style: inherit; font-variant: inherit; font-stretch: inherit; line-height: inherit; font-family: inherit; float: none; color: rgb(0, 0, 0); border-style: solid; border-color: rgb(204, 204, 204);">
                <p style="box-sizing: content-box; margin: 0px; padding: 0px; border: 0px; font-style: inherit; font-variant: inherit; font-weight: inherit; font-stretch: inherit; font-size: inherit; line-height: inherit; font-family: inherit; vertical-align: baseline;"><span style="box-sizing: content-box; margin: 0px; padding: 0px; border: 0px; font-style: inherit; font-variant: inherit; font-weight: inherit; font-stretch: inherit; font-size: 16px; line-height: 24px; font-family: verdana, geneva; vertical-align: baseline; color: rgb(51, 51, 51);">2&nbsp;1/16</span></p>
                </td>
                <td valign="top" style="box-sizing: content-box; margin: 0px; padding: 1px; font-style: inherit; font-variant: inherit; font-stretch: inherit; line-height: inherit; font-family: inherit; float: none; color: rgb(0, 0, 0); border-style: solid; border-color: rgb(204, 204, 204);">
                <p style="box-sizing: content-box; margin: 0px; padding: 0px; border: 0px; font-style: inherit; font-variant: inherit; font-weight: inherit; font-stretch: inherit; font-size: inherit; line-height: inherit; font-family: inherit; vertical-align: baseline;"><span style="box-sizing: content-box; margin: 0px; padding: 0px; border: 0px; font-variant: inherit; font-stretch: inherit; font-size: 16px; line-height: normal; font-family: verdana, geneva; vertical-align: baseline; color: rgb(51, 51, 51);">&nbsp;</span></p>
                </td>
                <td valign="center" style="box-sizing: content-box; margin: 0px; padding: 1px; font-style: inherit; font-variant: inherit; font-stretch: inherit; line-height: inherit; font-family: inherit; float: none; color: rgb(0, 0, 0); border-style: solid; border-color: rgb(204, 204, 204);">
                <p style="box-sizing: content-box; margin: 0px; padding: 0px; border: 0px; font-style: inherit; font-variant: inherit; font-weight: inherit; font-stretch: inherit; font-size: inherit; line-height: inherit; font-family: inherit; vertical-align: baseline;"><span style="box-sizing: content-box; margin: 0px; padding: 0px; border: 0px; font-style: inherit; font-variant: inherit; font-weight: inherit; font-stretch: inherit; font-size: 16px; line-height: 24px; font-family: verdana, geneva; vertical-align: baseline; color: rgb(51, 51, 51);">5000</span></p>
                </td>
                <td valign="top" style="box-sizing: content-box; margin: 0px; padding: 1px; font-style: inherit; font-variant: inherit; font-stretch: inherit; line-height: inherit; font-family: inherit; float: none; color: rgb(0, 0, 0); border-style: solid; border-color: rgb(204, 204, 204);">
                <p style="box-sizing: content-box; margin: 0px; padding: 0px; border: 0px; font-style: inherit; font-variant: inherit; font-weight: inherit; font-stretch: inherit; font-size: inherit; line-height: inherit; font-family: inherit; vertical-align: baseline;"><span style="box-sizing: content-box; margin: 0px; padding: 0px; border: 0px; font-style: inherit; font-variant: inherit; font-weight: inherit; font-stretch: inherit; font-size: 16px; line-height: 24px; font-family: verdana, geneva; vertical-align: baseline; color: rgb(51, 51, 51);">10000</span></p>
                </td>
                <td valign="top" style="box-sizing: content-box; margin: 0px; padding: 1px; font-style: inherit; font-variant: inherit; font-stretch: inherit; line-height: inherit; font-family: inherit; float: none; color: rgb(0, 0, 0); border-style: solid; border-color: rgb(204, 204, 204);">
                <p style="box-sizing: content-box; margin: 0px; padding: 0px; border: 0px; font-style: inherit; font-variant: inherit; font-weight: inherit; font-stretch: inherit; font-size: inherit; line-height: inherit; font-family: inherit; vertical-align: baseline;"><span style="box-sizing: content-box; margin: 0px; padding: 0px; border: 0px; font-style: inherit; font-variant: inherit; font-weight: inherit; font-stretch: inherit; font-size: 16px; line-height: 24px; font-family: verdana, geneva; vertical-align: baseline; color: rgb(51, 51, 51);">15000</span></p>
                </td>
            </tr>
            <tr align="left" style="box-sizing: content-box; margin: 0px; padding: 0px; font-style: inherit; font-variant: inherit; font-weight: inherit; font-stretch: inherit; line-height: inherit; font-family: inherit;">
                <td valign="center" style="box-sizing: content-box; margin: 0px; padding: 1px; font-style: inherit; font-variant: inherit; font-stretch: inherit; line-height: inherit; font-family: inherit; float: none; color: rgb(0, 0, 0); border-style: solid; border-color: rgb(204, 204, 204);">
                <p style="box-sizing: content-box; margin: 0px; padding: 0px; border: 0px; font-style: inherit; font-variant: inherit; font-weight: inherit; font-stretch: inherit; font-size: inherit; line-height: inherit; font-family: inherit; vertical-align: baseline;"><span style="box-sizing: content-box; margin: 0px; padding: 0px; border: 0px; font-style: inherit; font-variant: inherit; font-weight: inherit; font-stretch: inherit; font-size: 16px; line-height: 24px; font-family: verdana, geneva; vertical-align: baseline; color: rgb(51, 51, 51);">2&nbsp;9/16</span></p>
                </td>
                <td valign="top" style="box-sizing: content-box; margin: 0px; padding: 1px; font-style: inherit; font-variant: inherit; font-stretch: inherit; line-height: inherit; font-family: inherit; float: none; color: rgb(0, 0, 0); border-style: solid; border-color: rgb(204, 204, 204);">
                <p style="box-sizing: content-box; margin: 0px; padding: 0px; border: 0px; font-style: inherit; font-variant: inherit; font-weight: inherit; font-stretch: inherit; font-size: inherit; line-height: inherit; font-family: inherit; vertical-align: baseline;"><span style="box-sizing: content-box; margin: 0px; padding: 0px; border: 0px; font-variant: inherit; font-stretch: inherit; font-size: 16px; line-height: normal; font-family: verdana, geneva; vertical-align: baseline; color: rgb(51, 51, 51);">&nbsp;</span></p>
                </td>
                <td valign="top" style="box-sizing: content-box; margin: 0px; padding: 1px; font-style: inherit; font-variant: inherit; font-stretch: inherit; line-height: inherit; font-family: inherit; float: none; color: rgb(0, 0, 0); border-style: solid; border-color: rgb(204, 204, 204);">
                <p style="box-sizing: content-box; margin: 0px; padding: 0px; border: 0px; font-style: inherit; font-variant: inherit; font-weight: inherit; font-stretch: inherit; font-size: inherit; line-height: inherit; font-family: inherit; vertical-align: baseline;"><span style="box-sizing: content-box; margin: 0px; padding: 0px; border: 0px; font-style: inherit; font-variant: inherit; font-weight: inherit; font-stretch: inherit; font-size: 16px; line-height: 24px; font-family: verdana, geneva; vertical-align: baseline; color: rgb(51, 51, 51);">5000</span></p>
                </td>
                <td valign="top" style="box-sizing: content-box; margin: 0px; padding: 1px; font-style: inherit; font-variant: inherit; font-stretch: inherit; line-height: inherit; font-family: inherit; float: none; color: rgb(0, 0, 0); border-style: solid; border-color: rgb(204, 204, 204);">
                <p style="box-sizing: content-box; margin: 0px; padding: 0px; border: 0px; font-style: inherit; font-variant: inherit; font-weight: inherit; font-stretch: inherit; font-size: inherit; line-height: inherit; font-family: inherit; vertical-align: baseline;"><span style="box-sizing: content-box; margin: 0px; padding: 0px; border: 0px; font-style: inherit; font-variant: inherit; font-weight: inherit; font-stretch: inherit; font-size: 16px; line-height: 24px; font-family: verdana, geneva; vertical-align: baseline; color: rgb(51, 51, 51);">10000</span></p>
                </td>
                <td valign="top" style="box-sizing: content-box; margin: 0px; padding: 1px; font-style: inherit; font-variant: inherit; font-stretch: inherit; line-height: inherit; font-family: inherit; float: none; color: rgb(0, 0, 0); border-style: solid; border-color: rgb(204, 204, 204);">
                <p style="box-sizing: content-box; margin: 0px; padding: 0px; border: 0px; font-style: inherit; font-variant: inherit; font-weight: inherit; font-stretch: inherit; font-size: inherit; line-height: inherit; font-family: inherit; vertical-align: baseline;"><span style="box-sizing: content-box; margin: 0px; padding: 0px; border: 0px; font-style: inherit; font-variant: inherit; font-weight: inherit; font-stretch: inherit; font-size: 16px; line-height: 24px; font-family: verdana, geneva; vertical-align: baseline; color: rgb(51, 51, 51);">15000</span></p>
                </td>
            </tr>
            <tr align="left" style="box-sizing: content-box; margin: 0px; padding: 0px; font-style: inherit; font-variant: inherit; font-weight: inherit; font-stretch: inherit; line-height: inherit; font-family: inherit;">
                <td valign="center" style="box-sizing: content-box; margin: 0px; padding: 1px; font-style: inherit; font-variant: inherit; font-stretch: inherit; line-height: inherit; font-family: inherit; float: none; color: rgb(0, 0, 0); border-style: solid; border-color: rgb(204, 204, 204);">
                <p style="box-sizing: content-box; margin: 0px; padding: 0px; border: 0px; font-style: inherit; font-variant: inherit; font-weight: inherit; font-stretch: inherit; font-size: inherit; line-height: inherit; font-family: inherit; vertical-align: baseline;"><span style="box-sizing: content-box; margin: 0px; padding: 0px; border: 0px; font-style: inherit; font-variant: inherit; font-weight: inherit; font-stretch: inherit; font-size: 16px; line-height: 24px; font-family: verdana, geneva; vertical-align: baseline; color: rgb(51, 51, 51);">3&nbsp;1/8</span></p>
                </td>
                <td valign="center" style="box-sizing: content-box; margin: 0px; padding: 1px; font-style: inherit; font-variant: inherit; font-stretch: inherit; line-height: inherit; font-family: inherit; float: none; color: rgb(0, 0, 0); border-style: solid; border-color: rgb(204, 204, 204);">
                <p style="box-sizing: content-box; margin: 0px; padding: 0px; border: 0px; font-style: inherit; font-variant: inherit; font-weight: inherit; font-stretch: inherit; font-size: inherit; line-height: inherit; font-family: inherit; vertical-align: baseline;"><span style="box-sizing: content-box; margin: 0px; padding: 0px; border: 0px; font-style: inherit; font-variant: inherit; font-weight: inherit; font-stretch: inherit; font-size: 16px; line-height: 24px; font-family: verdana, geneva; vertical-align: baseline; color: rgb(51, 51, 51);">3000</span></p>
                </td>
                <td valign="top" style="box-sizing: content-box; margin: 0px; padding: 1px; font-style: inherit; font-variant: inherit; font-stretch: inherit; line-height: inherit; font-family: inherit; float: none; color: rgb(0, 0, 0); border-style: solid; border-color: rgb(204, 204, 204);">
                <p style="box-sizing: content-box; margin: 0px; padding: 0px; border: 0px; font-style: inherit; font-variant: inherit; font-weight: inherit; font-stretch: inherit; font-size: inherit; line-height: inherit; font-family: inherit; vertical-align: baseline;"><span style="box-sizing: content-box; margin: 0px; padding: 0px; border: 0px; font-style: inherit; font-variant: inherit; font-weight: inherit; font-stretch: inherit; font-size: 16px; line-height: 24px; font-family: verdana, geneva; vertical-align: baseline; color: rgb(51, 51, 51);">5000</span></p>
                </td>
                <td valign="top" style="box-sizing: content-box; margin: 0px; padding: 1px; font-style: inherit; font-variant: inherit; font-stretch: inherit; line-height: inherit; font-family: inherit; float: none; color: rgb(0, 0, 0); border-style: solid; border-color: rgb(204, 204, 204);">
                <p style="box-sizing: content-box; margin: 0px; padding: 0px; border: 0px; font-style: inherit; font-variant: inherit; font-weight: inherit; font-stretch: inherit; font-size: inherit; line-height: inherit; font-family: inherit; vertical-align: baseline;"><span style="box-sizing: content-box; margin: 0px; padding: 0px; border: 0px; font-style: inherit; font-variant: inherit; font-weight: inherit; font-stretch: inherit; font-size: 16px; line-height: 24px; font-family: verdana, geneva; vertical-align: baseline; color: rgb(51, 51, 51);">&nbsp;</span></p>
                </td>
                <td valign="top" style="box-sizing: content-box; margin: 0px; padding: 1px; font-style: inherit; font-variant: inherit; font-stretch: inherit; line-height: inherit; font-family: inherit; float: none; color: rgb(0, 0, 0); border-style: solid; border-color: rgb(204, 204, 204);">
                <p style="box-sizing: content-box; margin: 0px; padding: 0px; border: 0px; font-style: inherit; font-variant: inherit; font-weight: inherit; font-stretch: inherit; font-size: inherit; line-height: inherit; font-family: inherit; vertical-align: baseline;"><span style="box-sizing: content-box; margin: 0px; padding: 0px; border: 0px; font-style: inherit; font-variant: inherit; font-weight: inherit; font-stretch: inherit; font-size: 16px; line-height: 24px; font-family: verdana, geneva; vertical-align: baseline; color: rgb(51, 51, 51);">&nbsp;</span></p>
                </td>
            </tr>
            <tr align="left" style="box-sizing: content-box; margin: 0px; padding: 0px; font-style: inherit; font-variant: inherit; font-weight: inherit; font-stretch: inherit; line-height: inherit; font-family: inherit;">
                <td valign="center" style="box-sizing: content-box; margin: 0px; padding: 1px; font-style: inherit; font-variant: inherit; font-stretch: inherit; line-height: inherit; font-family: inherit; float: none; color: rgb(0, 0, 0); border-style: solid; border-color: rgb(204, 204, 204);">
                <p style="box-sizing: content-box; margin: 0px; padding: 0px; border: 0px; font-style: inherit; font-variant: inherit; font-weight: inherit; font-stretch: inherit; font-size: inherit; line-height: inherit; font-family: inherit; vertical-align: baseline;"><span style="box-sizing: content-box; margin: 0px; padding: 0px; border: 0px; font-style: inherit; font-variant: inherit; font-weight: inherit; font-stretch: inherit; font-size: 16px; line-height: 24px; font-family: verdana, geneva; vertical-align: baseline; color: rgb(51, 51, 51);">3&nbsp;1/16</span></p>
                </td>
                <td valign="top" style="box-sizing: content-box; margin: 0px; padding: 1px; font-style: inherit; font-variant: inherit; font-stretch: inherit; line-height: inherit; font-family: inherit; float: none; color: rgb(0, 0, 0); border-style: solid; border-color: rgb(204, 204, 204);">
                <p style="box-sizing: content-box; margin: 0px; padding: 0px; border: 0px; font-style: inherit; font-variant: inherit; font-weight: inherit; font-stretch: inherit; font-size: inherit; line-height: inherit; font-family: inherit; vertical-align: baseline;"><span style="box-sizing: content-box; margin: 0px; padding: 0px; border: 0px; font-variant: inherit; font-stretch: inherit; font-size: 16px; line-height: normal; font-family: verdana, geneva; vertical-align: baseline; color: rgb(51, 51, 51);">&nbsp;</span></p>
                </td>
                <td valign="top" style="box-sizing: content-box; margin: 0px; padding: 1px; font-style: inherit; font-variant: inherit; font-stretch: inherit; line-height: inherit; font-family: inherit; float: none; color: rgb(0, 0, 0); border-style: solid; border-color: rgb(204, 204, 204);">
                <p style="box-sizing: content-box; margin: 0px; padding: 0px; border: 0px; font-style: inherit; font-variant: inherit; font-weight: inherit; font-stretch: inherit; font-size: inherit; line-height: inherit; font-family: inherit; vertical-align: baseline;"><span style="box-sizing: content-box; margin: 0px; padding: 0px; border: 0px; font-variant: inherit; font-stretch: inherit; font-size: 16px; line-height: normal; font-family: verdana, geneva; vertical-align: baseline; color: rgb(51, 51, 51);">&nbsp;</span></p>
                </td>
                <td valign="top" style="box-sizing: content-box; margin: 0px; padding: 1px; font-style: inherit; font-variant: inherit; font-stretch: inherit; line-height: inherit; font-family: inherit; float: none; color: rgb(0, 0, 0); border-style: solid; border-color: rgb(204, 204, 204);">
                <p style="box-sizing: content-box; margin: 0px; padding: 0px; border: 0px; font-style: inherit; font-variant: inherit; font-weight: inherit; font-stretch: inherit; font-size: inherit; line-height: inherit; font-family: inherit; vertical-align: baseline;"><span style="box-sizing: content-box; margin: 0px; padding: 0px; border: 0px; font-style: inherit; font-variant: inherit; font-weight: inherit; font-stretch: inherit; font-size: 16px; line-height: 24px; font-family: verdana, geneva; vertical-align: baseline; color: rgb(51, 51, 51);">10000</span></p>
                </td>
                <td valign="top" style="box-sizing: content-box; margin: 0px; padding: 1px; font-style: inherit; font-variant: inherit; font-stretch: inherit; line-height: inherit; font-family: inherit; float: none; color: rgb(0, 0, 0); border-style: solid; border-color: rgb(204, 204, 204);">
                <p style="box-sizing: content-box; margin: 0px; padding: 0px; border: 0px; font-style: inherit; font-variant: inherit; font-weight: inherit; font-stretch: inherit; font-size: inherit; line-height: inherit; font-family: inherit; vertical-align: baseline;"><span style="box-sizing: content-box; margin: 0px; padding: 0px; border: 0px; font-style: inherit; font-variant: inherit; font-weight: inherit; font-stretch: inherit; font-size: 16px; line-height: 24px; font-family: verdana, geneva; vertical-align: baseline; color: rgb(51, 51, 51);">15000</span></p>
                </td>
            </tr>
            <tr align="left" style="box-sizing: content-box; margin: 0px; padding: 0px; font-style: inherit; font-variant: inherit; font-weight: inherit; font-stretch: inherit; line-height: inherit; font-family: inherit;">
                <td valign="center" style="box-sizing: content-box; margin: 0px; padding: 1px; font-style: inherit; font-variant: inherit; font-stretch: inherit; line-height: inherit; font-family: inherit; float: none; color: rgb(0, 0, 0); border-style: solid; border-color: rgb(204, 204, 204);">
                <p style="box-sizing: content-box; margin: 0px; padding: 0px; border: 0px; font-style: inherit; font-variant: inherit; font-weight: inherit; font-stretch: inherit; font-size: inherit; line-height: inherit; font-family: inherit; vertical-align: baseline;"><span style="box-sizing: content-box; margin: 0px; padding: 0px; border: 0px; font-style: inherit; font-variant: inherit; font-weight: inherit; font-stretch: inherit; font-size: 16px; line-height: 24px; font-family: verdana, geneva; vertical-align: baseline; color: rgb(51, 51, 51);">4&nbsp;1/16</span></p>
                </td>
                <td valign="center" style="box-sizing: content-box; margin: 0px; padding: 1px; font-style: inherit; font-variant: inherit; font-stretch: inherit; line-height: inherit; font-family: inherit; float: none; color: rgb(0, 0, 0); border-style: solid; border-color: rgb(204, 204, 204);">
                <p style="box-sizing: content-box; margin: 0px; padding: 0px; border: 0px; font-style: inherit; font-variant: inherit; font-weight: inherit; font-stretch: inherit; font-size: inherit; line-height: inherit; font-family: inherit; vertical-align: baseline;"><span style="box-sizing: content-box; margin: 0px; padding: 0px; border: 0px; font-style: inherit; font-variant: inherit; font-weight: inherit; font-stretch: inherit; font-size: 16px; line-height: 24px; font-family: verdana, geneva; vertical-align: baseline; color: rgb(51, 51, 51);">3000</span></p>
                </td>
                <td valign="top" style="box-sizing: content-box; margin: 0px; padding: 1px; font-style: inherit; font-variant: inherit; font-stretch: inherit; line-height: inherit; font-family: inherit; float: none; color: rgb(0, 0, 0); border-style: solid; border-color: rgb(204, 204, 204);">
                <p style="box-sizing: content-box; margin: 0px; padding: 0px; border: 0px; font-style: inherit; font-variant: inherit; font-weight: inherit; font-stretch: inherit; font-size: inherit; line-height: inherit; font-family: inherit; vertical-align: baseline;"><span style="box-sizing: content-box; margin: 0px; padding: 0px; border: 0px; font-style: inherit; font-variant: inherit; font-weight: inherit; font-stretch: inherit; font-size: 16px; line-height: 24px; font-family: verdana, geneva; vertical-align: baseline; color: rgb(51, 51, 51);">5000</span></p>
                </td>
                <td valign="top" style="box-sizing: content-box; margin: 0px; padding: 1px; font-style: inherit; font-variant: inherit; font-stretch: inherit; line-height: inherit; font-family: inherit; float: none; color: rgb(0, 0, 0); border-style: solid; border-color: rgb(204, 204, 204);">
                <p style="box-sizing: content-box; margin: 0px; padding: 0px; border: 0px; font-style: inherit; font-variant: inherit; font-weight: inherit; font-stretch: inherit; font-size: inherit; line-height: inherit; font-family: inherit; vertical-align: baseline;"><span style="box-sizing: content-box; margin: 0px; padding: 0px; border: 0px; font-style: inherit; font-variant: inherit; font-weight: inherit; font-stretch: inherit; font-size: 16px; line-height: 24px; font-family: verdana, geneva; vertical-align: baseline; color: rgb(51, 51, 51);">10000</span></p>
                </td>
                <td valign="top" style="box-sizing: content-box; margin: 0px; padding: 1px; font-style: inherit; font-variant: inherit; font-stretch: inherit; line-height: inherit; font-family: inherit; float: none; color: rgb(0, 0, 0); border-style: solid; border-color: rgb(204, 204, 204);"><span style="box-sizing: content-box; margin: 0px; padding: 0px; border: 0px; font-style: inherit; font-variant: inherit; font-weight: inherit; font-stretch: inherit; font-size: 16px; line-height: 24px; font-family: verdana, geneva; vertical-align: baseline;">&nbsp;</span></td>
            </tr>
        </tbody>
    </table>';

        // dump($table);
        
        $t = \App\Sanitizer\Sanitizer::handle($table, false);

        dd($t);

        $posts = Post::query()
            ->where('user_id', 16)
            ->get();

        // $posts = [];

        foreach ($posts as $post) {
            // $post->forceDelete();
            // continue;

            // try {
            //     if (!str_contains($post->description, '<p>')) {
            //         $post->saveTranslations([
            //             'description' => [
            //                 'en' => '<p>' . $post->description . '</p>'
            //             ]
            //         ]);
            //     }
            // } catch (\Throwable $th) {
            //     //throw $th;
            // }

            // $post->saveTranslations([
            //     'meta_description' => [
            //         'en' => $post->generateMetaDescription()
            //     ]
            // ]);

            // dump('s');

            // continue;

            $t = \App\Models\Translation::query()
                ->where('translatable_type', 'App\Models\Post')
                ->where('translatable_id', $post->id)
                ->where('field', 'description')
                ->where('locale', 'en')
                ->first();

            $desc = $t->value;

            foreach (\App\Traits\ScrapePosts::getEscapedChars() as $esc) {
                if ($esc[2]) {
                    $desc = preg_replace($esc[0], $esc[1], $desc);
                } else {
                    $desc = str_replace($esc[0], $esc[1], $desc);
                }
            }

            $t->update([
                'value' => $desc
            ]);

            dump("Updated $t->id");
        }

        // dd('done');

        $post = Post::find(2157);
        $desc = $post->description;

        // find closes chart
        for ($i=0; $i < strlen($desc); $i++) {
            $char = $desc[$i];
            $code = ord($char);
            if ($code == 195) {
                dump("found '192' char at $i");
            }
        }

        $from = 1;
        $length = 1000;

        $d[] = substr($desc, $from, $length);

        if ($length < 21) {
            foreach (range($from,$from+$length) as $i) {
                $d[$i] = [
                    $desc[$i]??'',
                    ord($desc[$i]??'')
                ];
            }
        }

        // $d = ord(195);
        // $d = substr($d, 843, 10);

        // $d[] = $desc;



        dd($d);
    }

    private function querySelector($html, $selector, $context=null)
    {
        if ($html instanceof \DOMElement) {
            $innerHTML = "";

            foreach ($html->childNodes as $child) {
                $innerHTML .= $html->ownerDocument->saveHTML($child);
            }

            $html = $innerHTML;
        } else {
            // remove inner html tag to prevent query selectors error
            if (substr_count($html, '</html>') > 1) {
                $start = strposX($html, '<html', 2);
                $end = strpos($html, '</html>') + 7;
                $html = substr($html, 0, $start) . substr($html, $end);
            }
        }

        libxml_use_internal_errors(true);
        $dom = new \DomDocument;
        $dom->loadHTML($html);
        $xpath = new \DOMXPath($dom);
        $tr = new Css2XPathService($selector);
        $tr = $tr->asXPath();
        $nodeList = $xpath->query($tr);

        return $nodeList;
    }

    private function scrapeTest()
    {
        $d = [];

        // $d = \App\Services\PostScraperService::make('https://www.heavyoilfieldtrucks.com/listings/')
        //     ->post('.auto-listings-items .auto-listing')
        //     ->postLink('.summary .title a')
        //     ->value('title', '.listing .title')
        //     ->value('images', '#image-gallery img', 'src', true)
        //     ->value('price', '.price h4')
        //     ->value('condition', '.price .condition')
        //     ->value('short_specs', '.at-a-glance li', null, true)
        //     ->value('description', '.description', 'html')
        //     ->shot('details_tables', '.auto-listings-Tabs-panel--details')
        //     ->shot('specifications_tables', '.auto-listings-Tabs-panel--specifications')
        //     ->limit(2)
        //     ->sleep(0)
        //     ->debug(true)
        //     ->scrape();


        dd($d);
    }

    private function showScrapedCashedFiles()
    {
        $files = [
            // 'lakepetro' => storage_path('scraper_jsons/lakepetro.json'),
            // 'oilmanchina' => storage_path('scraper_jsons/oilmanchina.json'),
            // 'goldenman' => storage_path('scraper_jsons/goldenman.json'),
            // 'rsdst' => storage_path('scraper_jsons/rsdst.json'),
            // 'peddler' => storage_path('scraper_jsons/peddler.json'),
            // 'blackdiamond' => storage_path('scraper_jsons/blackdiamond.json'),
            'dtosupply' => storage_path('scraper_jsons/dtosupply.json'),
        ];

        foreach ($files as $author => $file) {
            $json = file_get_contents($file);
            $scrapedPosts = json_decode($json, true);

            foreach ($scrapedPosts as $url => $p) {

            }

            $this->d($scrapedPosts);
        }
    }

    private function testScrapedShotImage()
    {
        $name = 'test-shot.jpeg';
        $path = storage_path('browsershot') . '/' . $name;
        $path = '/var/www/rigmanager/storage/browsershot/specifications_tables-1707565873.jpeg';
        $url = 'https://www.heavyoilfieldtrucks.com/listing/2006-sterling-model-lt-8500-picker-truck/';

        $browserhot = \Spatie\Browsershot\Browsershot::url($url)
            ->select('.auto-listings-Tabs-panel--specifications')
            ->setScreenshotType('jpeg', 100)
            ->newHeadless();

        $browserhot->setOption('addStyleTag', json_encode([
            'content' => '.specifications_tables{display:block !important;}'
        ]));

        $browserhot->save($path);

        dd('shot done.', $path);
    }

    private function example()
    {
        $this->enableQueryLog();

        $this->d('creating 1000 els array and collection...');

        $array = range(-500, 500);
        shuffle($array);

        $colleciton = collect($array);

        $this->d('starting sorting...');
        $this->setFullStart();

        sort($array);

        $this->t('array_sort');

        $colleciton->sort();

        $this->t('collection_sort');
        $this->d('sorting done.');

        return $array;
    }

    // dummy public method.
    // can be used to showcase some functionality to external user.
    private function public()
    {
        return "Hello from devs!";
    }

    // test emails
    private function emails()
    {
        $t = request()->type;
        $email = request()->email;

        if ($t == 'welcome') {
            $user = User::find(1);
            $mail = new \App\Mail\WelcomeMail($user);
        }
        if ($t == 'password-reset') {
            $url = url('');
            $mail = new \App\Mail\PasswordReset($url);
        }
        if ($t == 'verify') {
            $url = url('');
            $mail = new \App\Mail\TmpMail($url);
        };
        if ($t == 'mailer') {
            $posts = Post::inRandomOrder()->limit(4)->get();
            $mailer = \App\Models\Mailer::first();
            $mail = new \App\Mail\MailerPostFound($mailer, $posts);
        };
        if ($t == 'tba-non-reg') {
            $user = User::find(12);
            $post = Post::find(371);
            $mail = new \App\Mail\PostTbaForNonReg($post, $user, 'test message');
        };
        if ($t == 'sub-created') {
            $cycle = \App\Models\SubscriptionCycle::find(12);
            $mail = new \App\Mail\Subscriptions\Created($cycle);
        };
        if ($t == 'sub-canceled-cause-new') {
            $sub = \App\Models\Subscription::find(13);
            $group = \App\Enums\NotificationGroup::SUB_CANCELED_TERMINATED_CAUSE_NEW;
            $group = \App\Enums\NotificationGroup::SUB_TERMINATED_CAUSE_NEW;
            $mail = new \App\Mail\Subscriptions\CanceledCauseNew($sub, $group);
        };
        if ($t == 'sub-extended') {
            $cycle = \App\Models\SubscriptionCycle::find(12);
            $group = \App\Enums\NotificationGroup::SUB_EXTENDED;
            $group = \App\Enums\NotificationGroup::SUB_EXTENDED_INCOMPLETE;
            $mail = new \App\Mail\Subscriptions\Extended($cycle, $group);
        };
        if ($t == 'sub-extend-failed') {
            $sub = \App\Models\Subscription::find(13);
            $mail = new \App\Mail\Subscriptions\ExtentionFailed($sub);
        };
        if ($t == 'sub-canceled-expired') {
            $cycle = \App\Models\SubscriptionCycle::find(12);
            $mail = new \App\Mail\Subscriptions\CanceledExpired($cycle);
        };
        if ($t == 'sub-incompleted-expired') {
            $sub = \App\Models\Subscription::find(13);
            $mail = new \App\Mail\Subscriptions\IncompletedExpired($sub);
        };
        if ($t == 'sub-incompleted-paid') {
            $cycle = \App\Models\SubscriptionCycle::find(12);
            $mail = new \App\Mail\Subscriptions\IncompletedPaid($cycle);
        };
        if ($t == 'sub-canceled') {
            $sub = \App\Models\Subscription::find(13);
            $mail = new \App\Mail\Subscriptions\Canceled($sub);
        };
        if ($t == 'sub-end-in-7-days') {
            $cycle = \App\Models\SubscriptionCycle::find(12);
            $group = \App\Enums\NotificationGroup::SUB_RENEW_NEXT_WEEK;
            $group = \App\Enums\NotificationGroup::SUB_END_NEXT_WEEK;
            $mail = new \App\Mail\Subscriptions\EndNextWeek($cycle, $group);
        };
        if ($t == 'sub-end-tomorrow') {
            $cycle = \App\Models\SubscriptionCycle::find(12);
            $group = \App\Enums\NotificationGroup::SUB_END_TOMORROW;
            $group = \App\Enums\NotificationGroup::SUB_RENEW_TOMORROW;
            $mail = new \App\Mail\Subscriptions\EndTomorrow($cycle, $group);
        };
        if ($t == 'daily-posts-views-for-non-reg') {
            $user = User::find(14);
            $count = 17;
            $posts = Post::whereIn('id', [527, 526])->get();
            $mail = new \App\Mail\DailyPostViewsForNonReg($user, $count, $posts);
        };
        if ($t == 'daily-contact-views-for-non-reg') {
            $user = User::find(14);
            $count = 17;
            $mail = new \App\Mail\DailyContactViewsForNonReg($user, $count);
        };
        if ($t == 'daily-profile-views-for-non-reg') {
            $user = User::find(14);
            $count = 17;
            $mail = new \App\Mail\DailyProfileViewsForNonReg($user, $count);
        };
        if ($t == 'weekly-posts-views-for-non-reg') {
            $user = User::find(14);
            $count = 42;
            $posts = Post::whereIn('id', [527, 526])->get();
            $mail = new \App\Mail\WeeklyPostViewsForNonReg($user, $count, $posts);
        };

        // other emails test here...

        if (!isset($mail)) {
            dd('ERROR: mail not found');
        }

        if ($email) {
            Mail::to($email)->send($mail);
        }

        return $mail;
    }

    // login to user by ID (login to admin by default)
    private function login()
    {
        $user = request()->user;

        if (!$user) {
            $user = User::whereIn('email', ['admin@mail.com', 'admin@admin.com'])->first();
            if (!$user) {
                // todo add belongsTo relation check
                $user = User::whereHas('roles', function ($q) {
                    $q->where('name', 'admin');
                })->first();
            }
            if (!$user) {
                dump('Admin user not found. Please provide user_id manualy');
                dd(User::all());
            }
        } else {
            $user = User::find($user);
        }

        auth()->login($user);

        return redirect('/');
    }

    // get phpinfo
    private function phpinfo()
    {
        phpinfo();
    }

    // get client IP
    private function ip()
    {
        $ip = request()->ip();

        return "Your ip is: $ip";
    }

    // helper to store execution time
    private function t($key=null)
    {
        $t = microtime(true) - $this->start;

        if ($key) {
            $this->timings[$key] = $t;
        } else {
            $this->timings[] = $t;
        }

        $this->start = microtime(true);
    }

    private function d($value, $key=null)
    {
        if ($key) {
            $this->d[$key] = $value;
        } else {
            $this->d[] = $value;
        }
    }

    // reset start time
    private function setFullStart($key=null)
    {
        $this->fullStart = $this->start = microtime(true);
    }

    // authorize access to methods
    private function _authorize()
    {
        $ok = true || isdev() || $this->user?->isAdmin();

        abort_if(!$ok, 403);
    }

    // enable query log
    private function enableQueryLog()
    {
        $this->queryLog = true;
        \DB::connection()->enableQueryLog();
    }
}
