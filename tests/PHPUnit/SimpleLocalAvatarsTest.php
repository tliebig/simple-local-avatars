<?php
/**
 * Tests the main Simple_Local_Avatars class.
 *
 * @package Simple_Local_Avatars
 * @author  10up
 */

namespace Tenup\SimpleLocalAvatars;

use WP_Mock as M;
use Mockery;
use ReflectionMethod;

class SimpleLocalAvatarsTest extends TestCase {

	protected $testFiles = array(
		'class-simple-local-avatars.php',
	);

	public function test_add_hooks() {
		$instance = Mockery::mock( '\Simple_Local_Avatars' )->makePartial();
		$instance->shouldReceive( 'get_setting' )
			->once()
			->with( 'only', false )
			->andReturn( false );
		$method   = new ReflectionMethod( $instance, 'add_hooks' );
		$method->setAccessible( true );

		M::expectActionAdded( 'admin_init', array( $instance, 'admin_init' ) );
		M::expectActionAdded( 'admin_enqueue_scripts', array( $instance, 'admin_enqueue_scripts' ) );
		M::expectActionAdded( 'show_user_profile', array( $instance, 'edit_user_profile' ) );
		M::expectActionAdded( 'edit_user_profile', array( $instance, 'edit_user_profile' ) );
		M::expectActionAdded( 'personal_options_update', array( $instance, 'edit_user_profile_update' ) );
		M::expectActionAdded( 'edit_user_profile_update', array( $instance, 'edit_user_profile_update' ) );
		M::expectActionAdded( 'admin_action_remove-simple-local-avatar', array( $instance, 'action_remove_simple_local_avatar' ) );
		M::expectActionAdded( 'wp_ajax_assign_simple_local_avatar_media', array( $instance, 'ajax_assign_simple_local_avatar_media' ) );
		M::expectActionAdded( 'wp_ajax_remove_simple_local_avatar', array( $instance, 'action_remove_simple_local_avatar' ) );
		M::expectActionAdded( 'user_edit_form_tag', array( $instance, 'user_edit_form_tag' ) );
		M::expectFilterAdded( 'avatar_defaults', array( $instance, 'avatar_defaults' ) );

		$method->invoke( $instance );
	}

	public function test_add_hooks_in_local_only_mode() {
		$instance = Mockery::mock( '\Simple_Local_Avatars' )->makePartial();
		$instance->shouldReceive( 'get_setting' )
			->once()
			->with( 'only', false )
			->andReturn( true );
		$method   = new ReflectionMethod( $instance, 'add_hooks' );
		$method->setAccessible( true );

		M::expectFilterAdded( 'get_avatar', array( $instance, 'get_avatar' ), 10, 5 );

		$method->invoke( $instance );
	}

}
