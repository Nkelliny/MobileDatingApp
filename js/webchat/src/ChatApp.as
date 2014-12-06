package {

	

	import flash.display.Sprite;

	import flash.events.Event;

	import flash.events.ErrorEvent;

	import flash.display.StageScaleMode;

	import flash.display.StageAlign;

	import flash.events.TimerEvent;

	import flash.utils.Timer;

	import flash.system.Security;

	import flash.external.ExternalInterface;

	import org.igniterealtime.xiff.core.XMPPConnection;

	import org.igniterealtime.xiff.events.ConnectionSuccessEvent;

	import org.igniterealtime.xiff.events.LoginEvent;

	import org.igniterealtime.xiff.events.DisconnectionEvent;

	import org.igniterealtime.xiff.events.XIFFErrorEvent;

	import org.igniterealtime.xiff.events.RoomEvent;

	import org.igniterealtime.xiff.events.IncomingDataEvent;

	import org.igniterealtime.xiff.events.OutgoingDataEvent;

	

	public class ChatApp extends Sprite {

		

		/*

		 *

		 * @SERVER	IMPORTANT: This can be found in the Server Information page of Openfire's Admin Console

		 * 			under 'Server Information >> Environment >> Host Name'. Do not use 'Server Name'

		 * 			under the 'Server Information >> Server Properties >> Server Name' unless it is

		 *			the same as the 'Host Name'.

		 *

		 */

		private static const SERVER:String = "frank-f5d57edaa"; // Replace with our server's host name here

		private static const PORT:Number = 5222; // Your servers public port here -- 5222 is usually the default

		private static const RESOURCE:String = "My Content Site" // Resource name	ex.: ==> MyJabberApp

		private static const DEFAULT_ROOM:String = "Main Lobby";

		

		private var grabber:LoginCredentialsGrabber;

		private var userData:UserDataGrabber;

		private var connection:XMPPConnection;

		private var requireLogin:Boolean;

		private var roomName:String;

		

		public function ChatApp() {

			

			super();

			if (stage) init()

			else addEventListener(Event.ADDED_TO_STAGE, onAdded);

		}

		

		private function init():void {

			

			stage.align = StageAlign.TOP_LEFT;

			stage.scaleMode = StageScaleMode.NO_SCALE;

			loginScreen.visible = false;

			ui.visible = false;

			

			UserDataExtension.enable();

			grabber = new LoginCredentialsGrabber();

			userData = new UserDataGrabber();

			

			var flashVars:Object = this.loaderInfo.parameters;

			

			if ( flashVars.hasOwnProperty( "room" ) ) {

				

				roomName = flashVars.room;

			}

			

			checkLogin();

		}

		

		private function onAdded( e:Event ):void {

			

			removeEventListener(Event.ADDED_TO_STAGE, onAdded);

			init();

		}

		

		private function checkLogin():void {

			

			grabber.addEventListener( Event.COMPLETE, onLoginCredentials );

			grabber.grab();

		}

		

		private function grabUserData():void {

			

			userData.addEventListener( Event.COMPLETE, joinRoom );

			userData.grab( connection.username );

		}

		

		private function joinRoom( e:Event ):void {

			

			userData.removeEventListener( Event.COMPLETE, joinRoom );

			if ( !roomName ) roomName = DEFAULT_ROOM;

			ui.joinRoom( connection, userData, roomName );

		}

		

		private function connect( username:String, password:String ):void {

			

			if ( !connection ) connection = new XMPPConnection();

			connection.username = username;

			connection.password = password;

			connection.server = SERVER;

			connection.port = PORT;

			connection.resource = RESOURCE;

			connection.addEventListener( ConnectionSuccessEvent.CONNECT_SUCCESS, onConnected );

			connection.addEventListener( LoginEvent.LOGIN, onLogin );

			connection.addEventListener( DisconnectionEvent.DISCONNECT, onDisconnected );

			connection.addEventListener( XIFFErrorEvent.XIFF_ERROR, onXiffError );

			connection.addEventListener( IncomingDataEvent.INCOMING_DATA, onIncomingData );

			connection.addEventListener( OutgoingDataEvent.OUTGOING_DATA, onOutgoingData );

			connection.connect( XMPPConnection.STREAM_TYPE_FLASH );

		}

		

		private function startTimer():void {

			

			var aliveTimer:Timer = new Timer( 1000 * 60, 0 );

			aliveTimer.addEventListener( TimerEvent.TIMER, keepAlive );

			aliveTimer.start();

		}

		

		private function displayLogin():void {

			

			// Displays the login screen

			loginScreen.visible = true;

			loginScreen.addEventListener( LoginManager.LOGIN, onLoggingIn );

		}

		

		private function onLoggingIn( e:Event ):void {

			

			ui.visible = true;

			connect( loginScreen.manager.username, loginScreen.manager.password );

		}

		

		private function onLoginCredentials( e:Event ):void {

			

			grabber.removeEventListener( Event.COMPLETE, onLoginCredentials );

			

			if ( grabber.isLoggedIn ) {

				

				// Connect to Openfire

				ui.visible = true;

				connect( grabber.username, grabber.password );

			}

			else {

				

				// Display login

				displayLogin();

			}

		}

		

		private function onConnected( e:ConnectionSuccessEvent ):void {

			

			trace( "connected" );

		}

		

		private function onLogin( e:LoginEvent ):void {

			

			trace( "logged in" );

			ui.connection = connection;

			grabUserData();

			startTimer();

		}

		

		private function onDisconnected( e:DisconnectionEvent ):void {

			

			trace( "disconnected" );

			loginScreen.visible = true;

			ui.visible = false;

			loginScreen.displayError( "disconnected" );

		}

		

		private function onXiffError( e:XIFFErrorEvent ):void {

			

			trace( "Error: " + e.errorMessage );

			if ( loginScreen.visible ) loginScreen.displayError( e.errorMessage );

		}

		

		private function onIncomingData( e:IncomingDataEvent ):void {

			

			trace( e.data.toString() );

		}

		

		private function onOutgoingData( e:OutgoingDataEvent ):void {

			

			trace( e.data.toString() );

		}

		

		private function keepAlive( e:TimerEvent ):void {

			

			connection.sendKeepAlive();

			trace( "sending ping" );

		}

	}

}

