package {

	

	import flash.events.Event;

	import flash.events.EventDispatcher;

	import flash.net.URLLoader;

	import flash.net.URLRequest;

	import flash.net.URLVariables;

	import flash.net.URLRequestMethod;

	

	public class LoginCredentialsGrabber extends EventDispatcher {

		

		private static const PASSCODE:String = "letmein123";

		private static const SOURCE:String = "http://localhost/mycontentsite/scripts/check_login.php";

		

		private var _data:*;

		private var _username:String;

		private var _password:String;

		private var _isLoggedIn:Boolean;

		

		public function LoginCredentialsGrabber() {

			

			super();

		}

		

		public function grab():void {

			

			var loader:URLLoader = new URLLoader();

			var req:URLRequest = new URLRequest( SOURCE + "?cb=" + new Date().time );

			

			loader.addEventListener( Event.COMPLETE, onComplete );

			loader.load( req );

		}

		

		private function onComplete( e:Event ):void {

			

			e.target.removeEventListener( Event.COMPLETE, onComplete );

			_data = e.target.data;

			var results:URLVariables = new URLVariables( _data.toString() );

			

			if ( results.login == "1" ) {

				

				_isLoggedIn = true;

				_username = results.username;

				_password = results.password;

			}

			else {

				

				_isLoggedIn = false;

			}

			

			dispatchEvent( new Event( Event.COMPLETE ) );

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

