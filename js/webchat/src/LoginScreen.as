package {

	

	import flash.display.MovieClip;

	import flash.events.MouseEvent;

	import flash.events.Event;

	import flash.events.ErrorEvent;

	import flash.events.KeyboardEvent;

	import flash.ui.Keyboard;

	

	public class LoginScreen extends MovieClip {



		public var manager:LoginManager;

		

		public function LoginScreen() {

			

			super();

			manager = new LoginManager();

			init();

		}

		

		public function init():void {

			

			userLabel.text = "username:";

			passLabel.text = "password:";

			

			errorTxt.selectable = false;

			passwordTxt.displayAsPassword = true;

			

			loginBtn.label = "Login";

			loginBtn.addEventListener( MouseEvent.CLICK, login );

			stage.addEventListener( KeyboardEvent.KEY_DOWN, login );

			stage.addEventListener( Event.RESIZE, onStageResize );

		}

		

		private function login( e:Event ):void {

			

			loginBtn.removeEventListener( MouseEvent.CLICK, login );

			stage.removeEventListener( KeyboardEvent.KEY_DOWN, login );

			

			if ( e is KeyboardEvent ) {

				

				var ke:KeyboardEvent = e as KeyboardEvent;

					

				if ( ke.keyCode != Keyboard.ENTER ) {

						

					loginBtn.addEventListener( MouseEvent.CLICK, login );

					stage.addEventListener( KeyboardEvent.KEY_DOWN, login );

					return;

				}

			}

			

			if ( usernameTxt.length > 0 && passwordTxt.length > 0 ) {

				

				if (!manager) manager = new LoginManager();

				manager.addEventListener( Event.COMPLETE , onLogin );

				manager.addEventListener( ErrorEvent.ERROR , onLoginError );

				manager.login( usernameTxt.text, passwordTxt.text );

			}

			else if ( usernameTxt.length == 0 ) {

				

				// Display error

				errorTxt.text = "Please enter your username";

			}

			else {

				

				// Display error

				errorTxt.text = "Please enter your password";

			}

			

			loginBtn.addEventListener( MouseEvent.CLICK, login );

			stage.addEventListener( KeyboardEvent.KEY_DOWN, login );

		}

		

		private function onLogin( e:Event ):void {

			

			manager.removeEventListener( Event.COMPLETE , onLogin );

			manager.removeEventListener( ErrorEvent.ERROR , onLoginError );

			stage.removeEventListener( KeyboardEvent.KEY_DOWN, login );

			visible = false;

			dispatchEvent( new Event( LoginManager.LOGIN ) );

		}

		

		private function onLoginError( e:ErrorEvent ):void {

			

			manager.removeEventListener( Event.COMPLETE , onLogin );

			manager.removeEventListener( ErrorEvent.ERROR , onLoginError );

			errorTxt.text = e.text;

			loginBtn.addEventListener( MouseEvent.CLICK, login );

			stage.addEventListener( KeyboardEvent.KEY_DOWN, login );

		}

		

		private function onStageResize( e:Event ):void {

			

			darkBox.width = stage.stageWidth;

			darkBox.height = stage.stageHeight;

		}

		

		public function displayError( error:String ):void {

			

			errorTxt.text = error;

		}

	}

}

