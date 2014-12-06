package {

	

	import flash.display.MovieClip;

	import flash.events.Event;

	import fl.data.DataProvider;

	import org.igniterealtime.xiff.core.XMPPConnection;

	import org.igniterealtime.xiff.core.EscapedJID;

	import fl.controls.List;

	import org.igniterealtime.xiff.events.RoomEvent;

	import org.igniterealtime.xiff.conference.Room;

	import org.igniterealtime.xiff.core.UnescapedJID;

	import flash.events.MouseEvent;

	import org.igniterealtime.xiff.data.Message;

	import org.igniterealtime.xiff.data.Presence;

	import flash.events.KeyboardEvent;

	import flash.ui.Keyboard;

	

	public class UserInterface extends MovieClip {

		

		private var dp:DataProvider;

		private var profileData:Vector.<String>;

		private var names:Vector.<String>;

		private var usernames:Vector.<String>;

		private var items:Vector.<Object>;

		private var darkBox:DarkBox;

		private var room:Room;

		

		public var connection:XMPPConnection;

		

		public static const SERVER_NAME:String = "tutorial-f5d57edaa";

		

		public function UserInterface() {

			

			super();

			dp = new DataProvider();

			profileData = new Vector.<String>();

			names = new Vector.<String>();

			usernames = new Vector.<String>();

			items = new Vector.<Object>();

			darkBox = new DarkBox();

			init();

		}

		

		private function init():void {

			

			UserDataExtensionManager.onUserData = onUserDataExtension;

			disable();

			list.dataProvider = dp;

			list.addEventListener( Event.CHANGE, onGuestSelected );

			displayTxt.editable = false;

			sendBtn.label = "Send";

			logoutBtn.label = "Logout";

			darkBox.visible = false;

			addChild( darkBox );

			positionContents();

			stage.addEventListener( Event.RESIZE, onStageResize );

			logoutBtn.addEventListener( MouseEvent.CLICK, logout );

		}

		

		private function positionContents():void {

			

			displayTxt.width = stage.stageWidth - list.width - displayTxt.x - 10 - 10 - 10;

			list.x = displayTxt.y + displayTxt.width + 10;

			inputTxt.width = displayTxt.width;

			sendBtn.x = list.x;

			logoutBtn.x = list.x;

			

			displayTxt.height = stage.stageHeight - inputTxt.height - 10 - 10 - 10;

			list.height = displayTxt.height - logoutBtn.height - 10;

			inputTxt.y = displayTxt.height + displayTxt.y + 10;

			sendBtn.y = inputTxt.y;

			logoutBtn.y = displayTxt.y;

			

			darkBox.width = stage.stageWidth;

			darkBox.height = stage.stageHeight;

		}

		

		private function onStageResize( e:Event ):void {

			

			positionContents();

		}

		

		public function joinRoom( connection:XMPPConnection, userData:UserDataGrabber, roomName:String ):void {

			

			if ( connection.isLoggedIn() ) {

				

				trace( "joining room..." );

				var id:String = roomName.toLowerCase().replace( " ", "" );

				var ext:UserDataExtension = new UserDataExtension( null, userData );

				room = new Room( connection );

				room.roomJID = new UnescapedJID( id + "@conference." + SERVER_NAME );

				room.nickname = userData.username;

				room.addEventListener( RoomEvent.ROOM_JOIN, onRoomJoin );

				room.addEventListener( RoomEvent.USER_JOIN, onUserJoined );

				room.addEventListener( RoomEvent.GROUP_MESSAGE, onGroupMessage );

				room.addEventListener( RoomEvent.USER_DEPARTURE, onUserLeave );

				room.join( false , [ ext ] );

			}

			else {

					

				trace ( "Must be logged in to enter a chat room." );

			}

		}

		

		private function enable():void {

			

			list.enabled = true;

			sendBtn.enabled = true;

			inputTxt.enabled = true;

			displayTxt.enabled = true;

			list.dataProvider = dp;

		}

		

		private function disable():void {

			

			list.enabled = false;

			sendBtn.enabled = false;

			inputTxt.enabled = false;

			displayTxt.enabled = false;

		}

		

		private function addMessage( msg:String, from:String ):void {

			

			var now:Date = new Date();

			var nHours:Number = now.hours;

			var sMin:String = now.minutes.toString();

			if ( sMin.length == 1 ) sMin = "0" + sMin;

			var ampm:String = "AM";

			

			if ( nHours > 12 ) {

				

				nHours -= 12;

				ampm = "PM";

			}

			var time:String = String( nHours ) + ":" + sMin + " " + ampm;

			var txt:String = "[ " + from + " ] " + time + " ==> " + msg + "\n";

			displayTxt.appendText( txt );

		}

		

		private function sendMessage( e:Event ):void {

			

			if ( !visible ) return;

			if ( inputTxt.length > 0 ) {

				

				if ( e is KeyboardEvent ) {

					

					var ke:KeyboardEvent = e as KeyboardEvent;

					

					if ( ke.keyCode != Keyboard.ENTER ) {

						

						return;

					}

				}

				

				addMessage( inputTxt.text , room.nickname );

				room.sendMessage( inputTxt.text );

				inputTxt.text = "";

			}

		}

		

		private function onRoomJoin( e:RoomEvent ):void {

			

			trace( "joined room" );

			enable();

			sendBtn.addEventListener( MouseEvent.CLICK, sendMessage );

			stage.addEventListener( KeyboardEvent.KEY_DOWN, sendMessage );

		}

		

		private function onUserJoined( e:RoomEvent ):void {

			

			var p:Presence = e.data as Presence;

			trace( "user joined" );

			trace( "Presence: " + p.getNode().toString() );

			trace( e.nickname );

		}

		

		private function onGroupMessage( e:RoomEvent ):void {

			

			var msg:Message = e.data as Message;

			

			for each( var user:String in usernames ) {

				

				if ( e.nickname == user ) {

					

					addMessage( msg.body, e.nickname );

					return;

				}

			}

		}

		

		private function addToList( item:Object ):void {

			

			dp.addItem( item );

			trace( "adding " + name + " to list" );

		}

		

		private function removeFromList( username:String ):void {

			

			var index:int = usernames.indexOf( username );

			

			if ( index > -1 ) {

				

				trace( "removing " + names[ index ] + " from the list" );

				dp.removeItem( items[ index ] );

				profileData[ index ] = null;

				names[ index ] = null;

				usernames[ index ] = null;

				items[ index ] = null;

			}

		}

		

		private function onGuestSelected( e:Event ):void {

			

			var user:String = list.selectedItem.value.toString();

			var index:int = usernames.indexOf( user );

			trace( "Selected: " + user );

			

			if ( index > -1 ) {

				

				// Display Member Information

				darkBox.visible = true;

				var data:String = profileData[ index ];

				var window:ProfileWindow = new ProfileWindow();

				window.text = data;

				window.addEventListener( ProfileWindow.DESTROYED, onDestroyed );

				addChild( window );

			}

			

			function onDestroyed( e:Event ):void {

				

				window.removeEventListener( ProfileWindow.DESTROYED, onDestroyed );

				window = null;

				darkBox.visible = false;

			}

		}

		

		private function onUserLeave( e:RoomEvent ):void {

			

			var p:Presence = e.data as Presence;

			var username:String = p.from.toString().replace( room.roomName + "@" + room.conferenceServer + "/", "" );

			removeFromList( username );

		}



		private function onUserDataExtension( ext:UserDataExtension ) {

			

			if ( usernames.indexOf( ext.username ) > -1 || ext.username == connection.username ) return;

			

			var name:String = ext.firstName + " " + ext.lastName;

			var profileText:String = name + "\n\n";

			profileText += "Username: " + ext.username + "\n";

			profileText += "Country: " + ext.country + "\n";

			profileText += "Status: " + ext.statusMessage + "\n";

			

			var item:Object = {};

			item.label = name;

			item.value = ext.username;

			profileData.push( profileText );

			names.push( name );

			usernames.push( ext.username );

			items.push( item );

			addToList( item );

		}

		

		private function logout( e:MouseEvent ):void {

			

			var manager:LoginManager = new LoginManager();

			manager.logout();

		}

	}

}

