<?php


use Illuminate\Foundation\Testing\DatabaseMigrations;
use rohsyl\LaravelAcl\Test\Models\User;

class ContextTest extends \rohsyl\LaravelAcl\Test\TestCase
{
    use DatabaseMigrations;

    private $group1;
    private $group2;

    protected function setUp(): void
    {
        parent::setUp();
        $this->app['config']->set('acl.users.context', [
            [
                'handler' => \rohsyl\LaravelAcl\Test\Context\GroupContextHandler::class,
                'restrictive' => true,
            ]
        ]);

        $group = $this->testGroups->first();
        $group->grantPermission('user', ACL_CREATE);
        $group->save();

        $group2 = $this->testGroups->get(1);
        $group2->grantPermission('page', ACL_CREATE);
        $group2->grantPermission('group', ACL_CREATE);
        $group2->save();

        $this->testUser->groups()->attach($group->id);
        $this->testUser->groups()->attach($group2->id);

        $this->testUser->grantPermission('user', ACL_CREATE);
        $this->testUser->save();

        $this->group1 = $group;
        $this->group2 = $group2;
    }

    public function test_context_restrict_acl() {

        session()->put('group_id', $this->group1->id);

        $this->assertTrue($this->testUser->can('user', [ACL_CREATE]));
        $this->assertFalse($this->testUser->can('page', [ACL_CREATE]));
        $this->assertFalse($this->testUser->can('group', [ACL_CREATE]));

    }

    public function test_no_context_dont_restrict_acl() {

        session()->put('group_id', null);

        $this->assertTrue($this->testUser->can('user', [ACL_CREATE]));
        $this->assertTrue($this->testUser->can('page', [ACL_CREATE]));
        $this->assertTrue($this->testUser->can('group', [ACL_CREATE]));
    }
}