package {

	

	import flash.events.Event;

	import flash.events.EventDispatcher;

	import flash.events.ErrorEvent;

	import flash.net.*;

	import org.igniterealtime.xiff.bookmark.UrlBookmark;

	

	public class LoginManager extends EventDispatcher {

		

		private static const LOGIN_SOURCE:String = "http://localhost/mycontentsite/scripts/login.php"; // Your website's login script location

		private static const LOGOUT_SOURCE:String = "http://localhost/mycontentsite/scripts/logout.php";

		private static const INDEX:String = "http://localhost/mycontentsite/index.php";

		

		private var _data:*;

		private var _isLoggedIn:Boolean;

		private var _username:String;

		private var _password:String;

		

		public static const LOGIN:String = "login";

		

		

		public function LoginManager() {

			

			super();

		}

		

		public function login( username:String, password:String ):void {

			

			var loader:URLLoader = new URLLoader();

			var req:URLRequest = new URLRequest( LOGIN_SOURCE + "?cb=" + new Date().time );

			var vars:URLVariables = new URLVariables();

			

			_username = username;

			_password = password;

			vars.username = username;

			vars.password = password;

			req.data = vars;

			req.method = URLRequestMethod.POST;

			loader.addEventListener( Event.COMPLETE, onLoginComplete );

			loader.load( req );

		}

		

		public function logout():void {

			

			var loader:URLLoader = new URLLoader();

			var req:URLRequest = new URLRequest( LOGOUT_SOURCE );

			loader.addEventListener( Event.COMPLETE, onLogoutComplete );

			loader.load( req );

		}

		

		private function onLoginComplete( e:Event ):void {

			

			e.target.removeEventListener( Event.COMPLETE, onLoginComplete );

			_data = e.target.data;

			var results:URLVariables = new URLVariables( _data.toString() );

			

			if ( results.login == "1" ) {

				

				_isLoggedIn = true;

			}

			else {

				

				_isLoggedIn = false;

				dispatchEvent( new ErrorEvent( ErrorEvent.ERROR, false, false, results.error ) );

				return;

			}

			

			dispatchEvent( new Event( Event.COMPLETE ) );

		}

		

		private function onLogoutComplete( e:Event ):void {

			

			e.target.removeEventListener( Event.COMPLETE, onLogoutComplete );

			var req:URLRequest = new URLRequest();

			navigateToURL( new URLRequest( INDEX ), "_self" );

		}

		

		public function get data():* {

			

			return _data;

		}

		

		public function get isLoggedIn():Boolean {

			

			return _isLoggedIn;

		}

		

		public function get username():String {

			

			return _username;

		}

		

		public function get password():String {

			

			return _password;

		}



	}

}

