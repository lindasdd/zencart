<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\Browser\Traits\ConfigureFileConcerns;
use Tests\Browser\Traits\DatabaseConcerns;

class MultipleAdminPagesTest extends AdminDuskTestCase
{
    use DatabaseConcerns;
    use ConfigureFileConcerns;

    protected $pageMap = [
        ['page' => 'home', 'see' => 'Statistics'],
        ['page' => 'admin_account', 'see' => 'Reset Password'],
        ['page' => 'admin_activity', 'see' => 'Admin Activity Log'],
        ['page' => 'admin_page_registration', 'see' => 'Admin Page Registration'],
        ['page' => 'plugin_manager', 'see' => 'Plugin Manager'],
    ];

    /** @test */
    public function visit_multiple_admin_pages()
    {
        $this->browse(function (Browser $browser) {
            $browser->resize(1920, 1080);
            $browser->visit('/admin/index.php')
                ->waitFor('#admin_pass')
                ->type('admin_name', 'Admin')
                ->type('#admin_pass', 'develop1')
                ->press('Submit');
        });

        foreach ($this->pageMap as $page) {
            $this->browse(function (Browser $browser) use ($page) {
                $browser->resize(1920, 1080);
                $browser->visit('/admin/index.php?cmd=' . $page['page'])
                    ->screenshot('admin_' . $page['page']);
                if (isset($page['see'])) {
                    $browser->assertSee($page['see']);
                }
            });
        }
    }

}
