package  {

	

	import flash.events.Event;

	import flash.events.EventDispatcher;

	import flash.net.URLLoader;

	import flash.net.URLRequest;

	import flash.net.URLVariables;

	

	public class UserDataGrabber extends EventDispatcher {

		

		private static const SOURCE:String = "http://localhost/mycontentsite/scripts/grab_user_data.php";

		

		private var _data:*;

		private var _uid:String;

		private var _firstName:String;

		private var _lastName:String;

		private var _username:String;

		private var _country:String;

		private var _statusMessage:String;

		

		public function UserDataGrabber() {

			

			super();

		}

		

		public function grab( username:String ):void {

			

			var loader:URLLoader = new URLLoader();

			var req:URLRequest = new URLRequest( SOURCE + "?cb=" + new Date().time );

			var vars:URLVariables = new URLVariables();

			

			_username = username;

			vars.username = username;

			req.data = vars;

			req.method = "POST";

			loader.addEventListener( Event.COMPLETE, onComplete );

			loader.load( req );

		}

		

		private function onComplete( e:Event ):void {

			

			e.target.removeEventListener( Event.COMPLETE, onComplete );

			_data = e.target.data;

			trace( "User Data:\n" + data );

			var user:XML = new XML( _data );

			_uid = user.@id.toString();

			_firstName = user.firstName.toString();

			_lastName = user.lastName.toString();

			_country = user.country.toString();

			_statusMessage = user.statusMessage.toString();

			dispatchEvent( new Event( Event.COMPLETE ) );

		}

		

		public function get data():* {

			

			return _data;

		}

		

		public function get uid():String {

			

			return _uid;

		}

		

		public function get firstName():String {

			

			return _firstName;

		}

		

		public function get lastName():String {

			

			return _lastName;

		}

		

		public function get username():String {

			

			return _username;

		}

		

		public function get country():String {

			

			return _country;

		}

		

		public function get statusMessage():String {

			

			return _statusMessage;

		}

		

	}

}

